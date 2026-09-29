<?php
class Sortinggoodsinfo extends CI_Model
{

    public function GetSiteLocations()
    {
        $this->db->select('idtbl_location,location as  location_name');
        $this->db->from('tbl_location');
        $this->db->where('status', 1);
        $this->db->order_by('location', 'asc');

        return $this->db->get()->result_array();
    }

    public function GetWarehouses()
    {
        $this->db->select('idtbl_rack, rack_number as  warehouse_code');
        $this->db->from('tbl_rack');
        $this->db->where('status', 1);
        $this->db->order_by('warehouse_code', 'asc');

        return $this->db->get()->result_array();
    }
    public function GetActiveAllocations()
    {
        $this->db->select('a.idtbl_allocation, a.material_id, rm.material_name, a.qty, 
                          a.batch_number, a.location_name,a.locationname as site_location, g.grn_no as grn_number');
        $this->db->from('tbl_allocation a');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = a.material_id');
        $this->db->join('tbl_grn g', 'g.idtbl_grn = a.grn_id', 'left');
        $this->db->where('a.status', 2);
        $this->db->where('a.sorting_complete', 0);
        $this->db->order_by('a.allocation_date', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetRawMaterials()
    {
        $this->db->select('idtbl_row_material, material_name');
        $this->db->from('tbl_row_material');
        $this->db->where('status', 1);
        $this->db->order_by('material_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetNextJobCardNo()
    {
        $this->db->select('job_card_no');
        $this->db->from('tbl_sorting_goods');
        $this->db->order_by('idtbl_sorting_goods', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_record = $query->row();
            $last_job_card = $last_record->job_card_no;


            $number = (int) substr($last_job_card, 5);
            $next_number = $number + 1;


            return 'SORT-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
        } else {

            return 'SORT-001';
        }
    }

    public function ProcessSorting()
    {
        $this->db->trans_begin();



        $userID = $_SESSION['userid'];
        $sorting_date = $this->input->post('sorting_date') . ' ' . date('H:i:s');
        $allocation_id = $this->input->post('allocation_id');
        $site_location_id = $this->input->post('site_location');
        $zone_id = $this->input->post('zone');
        $job_card_no = $this->input->post('job_card_no');
        $items = $this->input->post('items');

        // echo '<pre>';
        // print_r($_POST);
        // die();


        $allocation = $this->db->get_where(
            'tbl_allocation',
            array('idtbl_allocation' => $allocation_id)
        )->row_array();


        if (!$allocation) {
            return array('status' => false, 'message' => 'Invalid allocation selected');
        }


        $header_data = array(
            'sorting_date' => $sorting_date,
            'allocation_id' => $allocation_id,
            'site_location_id' => $site_location_id,
            'zone_id' => $zone_id,
            'job_card_no' => $job_card_no,
            'status' => 2, // 2 = Pending
            'created_by' => $userID,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('tbl_sorting_goods', $header_data);
        $sorting_id = $this->db->insert_id();


        foreach ($items as $item) {
            $detail_data = array(
                'sorting_id' => $sorting_id,
                'allocation_id' => $allocation_id,
                'site_location_id' => $site_location_id,
                'zone_id' => $zone_id,
                'allocation_material_id' => $allocation['material_id'],
                'sorted_material_id' => $item['material_id'],
                'quantity' => $item['quantity'],
                'batch_no' => $allocation['batch_number'],
                'remark' => $item['remark'],
                'status' => 2,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('tbl_sorting_details', $detail_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error saving sorting', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('type' => 'success', 'status' => true, 'message' => 'Sorting saved successfully.', 'sorting_id' => $sorting_id);
        }
    }

    public function GetSortingRecords()
    {
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search')['value'];
        $order_column = $this->input->post('order')[0]['column'];
        $order_dir = $this->input->post('order')[0]['dir'];

        $this->db->select('sg.idtbl_sorting_goods as id, 
                          sg.sorting_date, 
                          sg.job_card_no,
                          CONCAT("ALLOC-", sg.allocation_id) as allocation_text,
                          COUNT(sd.idtbl_sorting_details) as total_items,
                          sg.status,
                          u.username as created_by');
        $this->db->from('tbl_sorting_goods sg');
        $this->db->join('tbl_sorting_details sd', 'sd.sorting_id = sg.idtbl_sorting_goods', 'left');
        $this->db->join('tbl_user u', 'u.idtbl_user = sg.created_by', 'left');


        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('sg.job_card_no', $search);
            $this->db->or_like('u.username', $search);
            $this->db->or_like('sg.sorting_date', $search);
            $this->db->group_end();
        }

        $this->db->group_by('sg.idtbl_sorting_goods');


        $columns = array(
            'sg.idtbl_sorting_goods',
            'sg.sorting_date',
            'allocation_text',
            'total_items',
            'sg.status',
            'u.username'
        );
        $this->db->order_by($columns[$order_column], $order_dir);


        $total_query = $this->db->get_compiled_select();
        $total_result = $this->db->query($total_query);
        $total_records = $total_result->num_rows();


        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = $query->result_array();

        return array(
            'draw' => intval($this->input->post('draw')),
            'recordsTotal' => $total_records,
            'recordsFiltered' => $total_records,
            'data' => $data
        );
    }

    public function GetSortingDetails($sorting_id)
    {
        $this->db->select('sg.*, 
                      CONCAT("ALLOC-", sg.allocation_id) as allocation_text,
                      a.material_id as input_material_id,
                      rm.material_name as input_material,
                      a.qty as input_qty,
                      a.batch_number,
                      a.location_name as location,
                      g.grn_no as grn_number,
                      u.username as created_by,
                      l.location as site_location_name,
                      r.rack_number as zone_name');
        $this->db->from('tbl_sorting_goods sg');
        $this->db->join('tbl_allocation a', 'a.idtbl_allocation = sg.allocation_id');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = a.material_id');
        $this->db->join('tbl_grn g', 'g.idtbl_grn = a.grn_id', 'left');
        $this->db->join('tbl_user u', 'u.idtbl_user = sg.created_by', 'left');
        $this->db->join('tbl_location l', 'l.idtbl_location = sg.site_location_id', 'left');
        $this->db->join('tbl_rack r', 'r.idtbl_rack = sg.zone_id', 'left');
        $this->db->where('sg.idtbl_sorting_goods', $sorting_id);
        $header_query = $this->db->get();
        $header = $header_query->row_array();

        $this->db->select('sd.*, rm.material_name as sorted_material_name');
        $this->db->from('tbl_sorting_details sd');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = sd.sorted_material_id');
        $this->db->where('sd.sorting_id', $sorting_id);
        $details_query = $this->db->get();
        $details = $details_query->result_array();

        return array(
            'header' => $header,
            'details' => $details
        );
    }

    // public function GetSortingForEdit($sorting_id)
    // {



    //     $this->db->select('sg.*, 
    //                       a.material_id as input_material_id,
    //                       rm.material_name as input_material,
    //                       a.qty as input_qty,
    //                       a.batch_number,
    //                       a.location_name as location,
    //                       g.grn_no as grn_number');
    //     $this->db->from('tbl_sorting_goods sg');
    //     $this->db->join('tbl_allocation a', 'a.idtbl_allocation = sg.allocation_id', 'left');
    //     $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = a.material_id', 'left');
    //     $this->db->join('tbl_grn g', 'g.idtbl_grn = a.grn_id', 'left');

    //     $this->db->where('sg.idtbl_sorting_goods', $sorting_id);
    //     $this->db->where('sg.status', 2);
    //     $header_query = $this->db->get();
    //     $header = $header_query->row_array();


    //     if (!$header) {
    //         return array('status' => false, 'message' => 'Sorting not found or cannot be edited');
    //     }


    //     $this->db->select('sd.*, rm.material_name as sorted_material_name');
    //     $this->db->from('tbl_sorting_details sd');
    //     $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = sd.sorted_material_id');
    //     $this->db->where('sd.sorting_id', $sorting_id);
    //     $items_query = $this->db->get();
    //     $items = $items_query->result_array();

    //     return array(
    //         'status' => true,
    //         'header' => $header,
    //         'items' => $items
    //     );
    // }

    public function GetSortingForEdit($sorting_id)
    {
        $this->db->select('sg.*, 
                      a.material_id as input_material_id,
                      rm.material_name as input_material,
                      a.qty as input_qty,
                      a.batch_number,
                      a.location_name as location,
                      g.grn_no as grn_number,
                      l.idtbl_location as site_location_id,
                      l.location as site_location_name,
                      r.idtbl_rack as zone_id,
                      r.rack_number as zone_name');
        $this->db->from('tbl_sorting_goods sg');
        $this->db->join('tbl_allocation a', 'a.idtbl_allocation = sg.allocation_id', 'left');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = a.material_id', 'left');
        $this->db->join('tbl_grn g', 'g.idtbl_grn = a.grn_id', 'left');
        $this->db->join('tbl_location l', 'l.idtbl_location = sg.site_location_id', 'left');
        $this->db->join('tbl_rack r', 'r.idtbl_rack = sg.zone_id', 'left');
        $this->db->where('sg.idtbl_sorting_goods', $sorting_id);
        $this->db->where('sg.status', 2);
        $header_query = $this->db->get();
        $header = $header_query->row_array();

        if (!$header) {
            return array('status' => false, 'message' => 'Sorting not found or cannot be edited');
        }

        $this->db->select('sd.*, rm.material_name as sorted_material_name');
        $this->db->from('tbl_sorting_details sd');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = sd.sorted_material_id');
        $this->db->where('sd.sorting_id', $sorting_id);
        $items_query = $this->db->get();
        $items = $items_query->result_array();

        return array(
            'status' => true,
            'header' => $header,
            'items' => $items
        );
    }

    public function ApproveSorting($sorting_id)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $approval_date = date('Y-m-d H:i:s');


        $this->db->set('status', 1);
        $this->db->set('approved_by', $userID);
        $this->db->set('approved_at', $approval_date);
        $this->db->where('idtbl_sorting_goods', $sorting_id);
        $this->db->update('tbl_sorting_goods');

        $this->db->set('status', 1);
        $this->db->where('sorting_id', $sorting_id);
        $this->db->update('tbl_sorting_details');

        $this->db->select('sd.*, a.location_name, a.batch_number');
        $this->db->from('tbl_sorting_details sd');
        $this->db->join('tbl_allocation a', 'a.idtbl_allocation = sd.allocation_id');
        $this->db->where('sd.sorting_id', $sorting_id);
        $query = $this->db->get();
        $sorting_items = $query->result_array();

        foreach ($sorting_items as $item) {
            $material_id = $item['sorted_material_id'];
            $quantity = $item['quantity'];
            $site_location = $item['site_location_id'];
            $zone_id = $item['zone_id'];
            $batch_no = $item['batch_no'];

            $this->db->where('tbl_row_material_idtbl_row_material', $material_id);
            $this->db->where('site_location', $site_location);
            $this->db->where('warehouse_location_name', $zone_id);
            $stock_query = $this->db->get('tbl_stock');

            if ($stock_query->num_rows() > 0) {
                $existing_stock = $stock_query->row();
                $new_qty = $existing_stock->qty + $quantity;

                $this->db->set('qty', $new_qty);
                $this->db->set('updatedatetime', $approval_date);
                $this->db->set('tbl_user_idtbl_user', $userID);
                $this->db->where('idtbl_stock', $existing_stock->idtbl_stock);
                $this->db->update('tbl_stock');
            } else {
                $stock_data = array(
                    'tbl_row_material_idtbl_row_material' => $material_id,
                    'warehouse_location_name' => $zone_id,
                    'site_location' => $site_location,
                    'qty' => $quantity,
                    'status' => 1,
                    'updatedatetime' => $approval_date,
                    'tbl_user_idtbl_user' => $userID
                );
                $this->db->insert('tbl_stock', $stock_data);
            }

            $this->db->where('tbl_row_material_idtbl_row_material', $material_id);
            $this->db->where('warehouse_location_name', $zone_id);
            $this->db->where('site_location', $site_location);
            $this->db->where('batchnumber', $batch_no);
            $batch_query = $this->db->get('tbl_batchstock');

            if ($batch_query->num_rows() > 0) {
                $existing_batch = $batch_query->row();
                $new_qty = $existing_batch->qty + $quantity;
                $new_balance = $existing_batch->balanceqty + $quantity;

                $this->db->set('qty', $new_qty);
                $this->db->set('balanceqty', $new_balance);
                $this->db->set('updatedatetime', $approval_date);
                $this->db->set('tbl_user_idtbl_user', $userID);
                $this->db->where('idtbl_batchstock', $existing_batch->idtbl_batchstock);
                $this->db->update('tbl_batchstock');
            } else {
                $batch_data = array(
                    'tbl_row_material_idtbl_row_material' => $material_id,
                    'warehouse_location_name' => $zone_id,
                    'site_location' => $site_location,
                    'batchnumber' => $batch_no,
                    'qty' => $quantity,
                    'balanceqty' => $quantity,
                    'status' => 1,
                    'updatedatetime' => $approval_date,
                    'tbl_user_idtbl_user' => $userID
                );
                $this->db->insert('tbl_batchstock', $batch_data);
            }
        }

        $sorting_details = $this->db->get_where(
            'tbl_sorting_goods',
            array('idtbl_sorting_goods' => $sorting_id)
        )->row_array();

        if ($sorting_details) {
            $this->db->set('sorting_complete', 1);
            $this->db->where('idtbl_allocation', $sorting_details['allocation_id']);
            $this->db->update('tbl_allocation');
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error approving sorting', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Sorting approved and inventory updated successfully', 'type' => 'success');
        }
    }

    public function RejectSorting($sorting_id)
    {
        $this->db->trans_begin();

        $this->db->set('status', 3);
        $this->db->where('idtbl_sorting_goods', $sorting_id);
        $this->db->update('tbl_sorting_goods');

        $this->db->set('status', 3);
        $this->db->where('sorting_id', $sorting_id);
        $this->db->update('tbl_sorting_details');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error rejecting sorting');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Sorting rejected successfully');
        }
    }

    public function UpdateSorting()
    {
        $this->db->trans_begin();

        $sorting_id = $this->input->post('sorting_id');
        $sorting_date = $this->input->post('sorting_date') . ' ' . date('H:i:s');
        $allocation_id = $this->input->post('allocation_id');
        $site_location_id = $this->input->post('site_location');
        $zone_id = $this->input->post('zone');
        $items = $this->input->post('items');

        $userID = $_SESSION['userid'];
        $update_date = date('Y-m-d H:i:s');

        $this->db->set('sorting_date', $sorting_date);
        $this->db->set('allocation_id', $allocation_id);
        $this->db->set('site_location_id', $site_location_id);
        $this->db->set('zone_id', $zone_id);
        $this->db->where('idtbl_sorting_goods', $sorting_id);
        $this->db->update('tbl_sorting_goods');

        $this->db->where('sorting_id', $sorting_id);
        $this->db->delete('tbl_sorting_details');

        $allocation = $this->db->get_where(
            'tbl_allocation',
            array('idtbl_allocation' => $allocation_id)
        )->row_array();


        foreach ($items as $item) {
            $detail_data = array(
                'sorting_id' => $sorting_id,
                'allocation_id' => $allocation_id,
                'site_location_id' => $site_location_id,
                'zone_id' => $zone_id,
                'allocation_material_id' => $allocation['material_id'],
                'sorted_material_id' => $item['material_id'],
                'quantity' => $item['quantity'],
                'batch_no' => $allocation['batch_number'],
                'remark' => $item['remark'],
                'status' => 2,
                'created_at' => $update_date
            );
            $this->db->insert('tbl_sorting_details', $detail_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error updating sorting', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Sorting updated successfully', 'type' => 'success');
        }
    }

    public function DeleteSorting($sorting_id)
    {
        $this->db->trans_begin();

        $this->db->set('status', 0);
        $this->db->where('idtbl_sorting_goods', $sorting_id);
        $this->db->update('tbl_sorting_goods');

        $this->db->set('status', 0);
        $this->db->where('sorting_id', $sorting_id);
        $this->db->update('tbl_sorting_details');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error deleting sorting', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Sorting deleted successfully', 'type' => 'success');
        }
    }
}