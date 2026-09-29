<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Commeninfo');
        $this->load->model('Dashboardinfo');
    }

    public function index()
    {
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $result['total_stock'] = $this->Dashboardinfo->getTotalStock();
        $result['stock_category'] = $this->Dashboardinfo->getStockByCategory();
        $result['warehouse'] = $this->Dashboardinfo->getWarehouseCapacity();
        $result['warehouse_zones'] = $this->Dashboardinfo->getWarehouseZoneCapacity();
        $result['pending_grn_count'] = $this->Dashboardinfo->getPendingGRNCount();
        $result['recent_pending_grns'] = $this->Dashboardinfo->getRecentPendingGRNs(5);
        $result['pending_processing_count'] = $this->Dashboardinfo->getPendingProcessingCount();
        $result['recent_pending_processing'] = $this->Dashboardinfo->getRecentPendingProcessing(6);
        $result['exports_this_month'] = $this->Dashboardinfo->getExportsThisMonth();
        $result['exports_this_year'] = $this->Dashboardinfo->getExportsThisYear();
        $result['exports_by_country'] = $this->Dashboardinfo->getExportsByDestination(); 
        $result['revenue_growth'] = $this->Dashboardinfo->getRevenueGrowth();
        $result['low_stock_materials'] = $this->Dashboardinfo->getLowStockMaterials(8);
        $this->load->view('dashboard', $result);
    }

    public function get_finish_goods_stock()
    {
        $finishGoods = $this->Finishgoodsinfo->GetFinishedGoodsSummary();

        $locationSummary = [];
        foreach ($finishGoods as $item) {
            $location = $item['location_name'];
            if (!isset($locationSummary[$location])) {
                $locationSummary[$location] = [
                    'location_name' => $location,
                    'location_code' => $item['location_code'],
                    'total_qty' => 0,
                    'total_items' => 0
                ];
            }
            $locationSummary[$location]['total_qty'] += floatval($item['total_quantity']);
            $locationSummary[$location]['total_items']++;
        }

        $locationSummary = array_values($locationSummary);

        echo json_encode([
            'data' => $finishGoods,
            'location_summary' => $locationSummary
        ]);
    }

    public function get_pending_finish_goods()
    {
        $pendingData = $this->Finishgoodsinfo->GetPendingTransactionsForDataTable(
            0,
            10,
            '',
            'transfer_date',
            'desc'
        );

        echo json_encode([
            'data' => $pendingData['data'],
            'total' => $pendingData['totalRecords']
        ]);
    }

    public function get_low_stock_materials()
    {
        $this->load->model('Allstockviewinfo');

        $lowStock = $this->db->query("
            SELECT 
                tbl_row_material.material_name,
                tbl_location.location,
                tbl_stock.qty
            FROM tbl_stock 
            LEFT JOIN tbl_row_material ON tbl_row_material.idtbl_row_material = tbl_stock.tbl_row_material_idtbl_row_material
            LEFT JOIN tbl_location ON tbl_location.idtbl_location = tbl_stock.warehouse_location_name
            WHERE tbl_stock.status = 1 
                AND tbl_stock.qty > 0 
                AND tbl_stock.qty < 2000
            ORDER BY tbl_stock.qty ASC, tbl_row_material.material_name ASC
            LIMIT 100
        ")->result_array();

        echo json_encode(['data' => $lowStock]);
    }
}
?>