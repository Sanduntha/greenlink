<?php
class Dashboardinfo extends CI_Model
{

    public function getTotalStock()
    {
        $this->db->select('SUM(qty) as total_stock');
        $this->db->from('tbl_stock');
        $this->db->where('status', 1);

        $query = $this->db->get();
        return $query->row();
    }


    public function getStockByCategory()
    {
        $this->db->select('tbl_material_main_cat.categoryname as category_name, SUM(tbl_stock.qty) as total_qty');
        $this->db->from('tbl_stock');

        $this->db->join('tbl_row_material', 'tbl_row_material.idtbl_row_material = tbl_stock.tbl_row_material_idtbl_row_material');
        $this->db->join('tbl_material_main_cat', 'tbl_material_main_cat.idtbl_material_main_cat = tbl_row_material.tbl_material_main_cat_idtbl_material_main_cat');

        $this->db->where('tbl_stock.status', 1);

        $this->db->group_by('tbl_material_main_cat.categoryname');

        $query = $this->db->get();

        return $query->result();
    }

    public function getWarehouseCapacity()
    {
        // Total rack capacity
        $this->db->select('SUM(max_weight) as total_capacity');
        $this->db->from('tbl_rack');
        $this->db->where('status', 1);
        $rack = $this->db->get()->row();

        // Current stock
        $this->db->select('SUM(qty) as current_stock');
        $this->db->from('tbl_stock');
        $this->db->where('status', 1);
        $stock = $this->db->get()->row();

        $data['capacity'] = $rack->total_capacity;
        $data['stock'] = $stock->current_stock;

        if ($rack->total_capacity > 0) {
            $data['percentage'] = ($stock->current_stock / $rack->total_capacity) * 100;
        } else {
            $data['percentage'] = 0;
        }

        return $data;
    }

    public function getWarehouseZoneCapacity()
    {
        $sql = "
        SELECT 
            loc.location          AS site_name,
            loc.code              AS site_code,           
            r.rack_number           AS rack_number,
            r.max_weight          AS capacity_kg,
            COALESCE(SUM(s.qty), 0) AS used_kg
        FROM tbl_location loc
        INNER JOIN tbl_rack r 
            ON r.tbl_location_idtbl_location = loc.idtbl_location
        LEFT JOIN tbl_stock s 
            ON s.warehouse_location_name = r.idtbl_rack
            AND s.site_location          = loc.idtbl_location
            AND s.status = 1
        WHERE loc.status = 1
          AND r.status   = 1
        GROUP BY 
            loc.idtbl_location,
            r.idtbl_rack
        ORDER BY 
            loc.location ASC,
            r.zone_name ASC
    ";

        return $this->db->query($sql)->result();
    }



    public function getPendingGRNCount()
    {
        $this->db->select('COUNT(*) as pending_count');
        $this->db->from('tbl_grn');
        $this->db->where('status', 1);
        $this->db->where('approval_status', 'pending');


        $query = $this->db->get();
        return $query->row()->pending_count ?? 0;
    }


    public function getRecentPendingGRNs($limit = 5)
    {
        $this->db->select('
        g.idtbl_grn,
        g.grn_no,
        g.date,
        g.supplier_id,
        g.total,
        g.approval_status,
        s.name as supplier_name         
    ');
        $this->db->from('tbl_grn g');
        $this->db->join('tbl_supplier s', 's.idtbl_supplier = g.supplier_id', 'left');
        $this->db->where('g.status', 1);
        $this->db->where('g.approval_status', 'pending');
        $this->db->order_by('g.date', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }


    public function getPendingProcessingCount()
    {
        $this->db->select('COUNT(*) as pending_count');
        $this->db->from('tbl_allocation');
        $this->db->where('status', 2);
        $this->db->where('sorting_complete', 0);



        $query = $this->db->get();
        return $query->row()->pending_count ?? 0;
    }


    public function getRecentPendingProcessing($limit = 6)
    {
        $this->db->select('
        a.idtbl_allocation,
        a.allocation_date,
        a.batch_number,
        a.qty,
        rm.material_name,          
        loc.location AS site_name, 
        r.rack_number,
        a.sorting_complete,
        a.remarks
    ');
        $this->db->from('tbl_allocation a');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = a.material_id', 'left');
        $this->db->join('tbl_rack r', 'r.idtbl_rack = a.rack_id', 'left');
        $this->db->join('tbl_location loc', 'loc.idtbl_location = a.site_location_id', 'left');

        $this->db->where('a.status', 2);
        $this->db->where('a.sorting_complete', 0);

        $this->db->order_by('a.allocation_date', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }

    public function getExportsThisMonth()
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('tbl_shipmentplaning');
        $this->db->where('status', 1);
        $this->db->where('shipment_status', 'Dispatched'); // ← CHANGE to your "completed/dispatched" status
        $this->db->where('YEAR(shipment_date)', date('Y'));
        $this->db->where('MONTH(shipment_date)', date('m'));
        $query = $this->db->get();
        return $query->row()->count ?? 0;
    }

    /**
     * Number of completed exports this year
     */
    public function getExportsThisYear()
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from('tbl_shipmentplaning');
        $this->db->where('status', 1);
        $this->db->where('shipment_status', 'Dispatched');
        $this->db->where('YEAR(shipment_date)', date('Y'));
        $query = $this->db->get();
        return $query->row()->count ?? 0;
    }

    /**
     * Exports grouped by destination country (this year or all-time)
     * Returns array of objects: country, export_count, total_weight
     */
    public function getExportsByDestination($year = null)
    {
        if ($year === null)
            $year = date('Y');

        $this->db->select('
            country,
            COUNT(*) as export_count,
            SUM(total_weight) as total_weight_kg
        ');
        $this->db->from('tbl_shipmentplaning');
        $this->db->where('status', 1);
        $this->db->where('shipment_status', 'Dispatched');
        $this->db->where('YEAR(shipment_date)', $year);
        $this->db->group_by('country');
        $this->db->order_by('export_count', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Simple revenue growth (using total_weight as proxy)
     * Returns: current_month_kg, prev_month_kg, growth_percent
     */
    public function getRevenueGrowth()
    {
        $current_year = date('Y');
        $current_month = date('m');

        // Current month
        $this->db->select('COALESCE(SUM(total_weight), 0) as current_kg');
        $this->db->from('tbl_shipmentplaning');
        $this->db->where('status', 1);
        $this->db->where('shipment_status', 'Dispatched');
        $this->db->where('YEAR(shipment_date)', $current_year);
        $this->db->where('MONTH(shipment_date)', $current_month);
        $current = $this->db->get()->row()->current_kg ?? 0;

        // Previous month
        $prev_month = $current_month - 1;
        $prev_year = $current_year;
        if ($prev_month == 0) {
            $prev_month = 12;
            $prev_year--;
        }

        $this->db->select('COALESCE(SUM(total_weight), 0) as prev_kg');
        $this->db->from('tbl_shipmentplaning');
        $this->db->where('status', 1);
        $this->db->where('shipment_status', 'Dispatched');
        $this->db->where('YEAR(shipment_date)', $prev_year);
        $this->db->where('MONTH(shipment_date)', $prev_month);
        $prev = $this->db->get()->row()->prev_kg ?? 0;

        $growth = ($prev > 0) ? (($current - $prev) / $prev) * 100 : 0;

        return [
            'current_month_kg' => round($current, 1),
            'prev_month_kg' => round($prev, 1),
            'growth_percent' => round($growth, 1),
            'growth_class' => $growth >= 0 ? 'text-success' : 'text-danger',
            'growth_icon' => $growth >= 0 ? '↑' : '↓'
        ];
    }
    public function getLowStockMaterials($limit = 8)
    {
        $sql = "
        SELECT 
            rm.material_code,
            rm.material_name,
            rm.rol,
            COALESCE(SUM(s.qty), 0) AS current_stock
        FROM tbl_row_material rm
        LEFT JOIN tbl_stock s 
            ON s.tbl_row_material_idtbl_row_material = rm.idtbl_row_material
            AND s.status = 1
        WHERE rm.status = 1
          AND rm.rol > 0
        GROUP BY rm.idtbl_row_material
        HAVING COALESCE(SUM(s.qty), 0) < rm.rol
        ORDER BY (rm.rol - COALESCE(SUM(s.qty), 0)) DESC, rm.material_name ASC
        LIMIT ?
    ";

        return $this->db->query($sql, [$limit])->result();
    }
}