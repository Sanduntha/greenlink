<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Goodreceive extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Goodreceiveinfo');
        $this->load->model('Commeninfo');
        $this->load->model('Locationinfo');
    }

    public function index()
    {
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $result['supervisors'] = $this->Goodreceiveinfo->GetSupervisors();
        $result['suppliers'] = $this->Goodreceiveinfo->GetSuppliers();
        $result['site_locations'] = $this->Goodreceiveinfo->GetSiteLocations();
        $result['units'] = $this->Goodreceiveinfo->GetMaterialUnits();
        $result['materials'] = $this->Goodreceiveinfo->GetMaterials();
        $result['locations'] = $this->Locationinfo->Getlocations();
        $result['next_grn_number'] = $this->Goodreceiveinfo->GetNextGrnNumber();

        $result['addcheck'] = isset($result['menuaccess']['add']) ? $result['menuaccess']['add'] : 0;
        $result['editcheck'] = isset($result['menuaccess']['edit']) ? $result['menuaccess']['edit'] : 0;
        $result['statuscheck'] = isset($result['menuaccess']['status']) ? $result['menuaccess']['status'] : 0;
        $result['deletecheck'] = isset($result['menuaccess']['delete']) ? $result['menuaccess']['delete'] : 0;

        $this->load->view('goodreceive', $result);
    }

    public function GetSupplierContact()
    {
        $supplier_id = $this->input->post('supplier_id');

        $this->db->select('telephone_no');
        $this->db->from('tbl_supplier');
        $this->db->where('idtbl_supplier', $supplier_id);
        $result = $this->db->get()->row();

        echo json_encode(array(
            'contact_no' => $result ? $result->telephone_no : ''
        ));
    }

    public function GetGrnNumber()
    {
        if ($this->input->is_ajax_request()) {
            $grn_number = $this->Goodreceiveinfo->GetNextGrnNumber();

            echo json_encode(array(
                'success' => true,
                'grn_no' => $grn_number,
                'message' => 'GRN number generated successfully'
            ));
        } else {
            show_404();
        }
    }

    public function GetSupplierPos()
    {
        $supplier_id = $this->input->post('supplier_id');

        $this->load->model('Goodreceiveinfo');
        $pos = $this->Goodreceiveinfo->GetPorderList($supplier_id)->result_array();

        echo json_encode($pos);
    }

    public function GetPoDetailsForGrn()
    {
        $po_id = $this->input->post('po_id');

        $this->load->model('Goodreceiveinfo');
        $details = $this->Goodreceiveinfo->GetPoDetailsForGrn($po_id);

        echo json_encode($details);
    }

    public function SaveNewGrn()
    {
        $this->load->model('Goodreceiveinfo');
        $this->Goodreceiveinfo->SaveNewGrn();
    }

    public function UpdateGrn($grn_id)
    {
        $this->load->model('Goodreceiveinfo');
        $this->Goodreceiveinfo->UpdateGrn($grn_id);
    }

    public function DeleteGrn()
    {
        $grn_id = $this->input->post('grn_id');

        $this->load->model('Goodreceiveinfo');
        $this->Goodreceiveinfo->DeleteGrn($grn_id);
    }

    public function ApproveGrn()
    {
        $grn_id = $this->input->post('grn_id');

        $this->load->model('Goodreceiveinfo');
        $this->Goodreceiveinfo->ApproveGrn($grn_id);
    }

    public function GetGrnForEdit()
    {
        $grn_id = $this->input->post('grn_id');

        $this->load->model('Goodreceiveinfo');
        $grn = $this->Goodreceiveinfo->GetGrnForEdit($grn_id);

        echo json_encode($grn);
    }

    public function GetGrnDetails()
    {
        $grn_id = $this->input->post('grn_id');

        $this->load->model('Goodreceiveinfo');
        $grn = $this->Goodreceiveinfo->GetGrnForEdit($grn_id);

        echo json_encode(array(
            'status' => 'success',
            'data' => $grn
        ));
    }

    public function GrnTableData()
    {
        $this->load->model('Goodreceiveinfo');

        $params = array(
            'draw' => $this->input->post('draw'),
            'start' => $this->input->post('start'),
            'length' => $this->input->post('length'),
            'search' => $this->input->post('search'),
            'order' => $this->input->post('order')
        );

        $result = $this->Goodreceiveinfo->GetGrnDataTable($params);
        echo json_encode($result);
    }

    public function GetCapacityInfo()
    {
        $site_location = $this->input->post('site_location');
        $warehouse = $this->input->post('warehouse');

        if (!$site_location || !$warehouse) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
            return;
        }

        $data = $this->Goodreceiveinfo->GetCapacityInfo($site_location, $warehouse);
        echo json_encode(['status' => 'success', 'data' => $data]);
    }

    public function GetZonesBySite()
    {
        $site_id = $this->input->post('site_id');
        $zones = $this->Goodreceiveinfo->getZonesBySite($site_id);

        echo json_encode([
            'status' => 'success',
            'zones'  => $zones
        ]);
    }

	private function ExistingStockJson($data, $statusCode = 200)
{
    $this->output
        ->set_status_header($statusCode)
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
}

public function GetExistingStockAdjustmentData()
{
    if (!$this->Goodreceiveinfo->HasGrnPermission()) {
        $this->ExistingStockJson(
            array('status' => 0, 'message' => 'Access denied.'),
            403
        );
        return;
    }

    $grnId = (int) $this->input->post('grn_id');
    $grn = $this->Goodreceiveinfo->GetGrnForEdit($grnId);

	//update -- Continue only if the GRN exists, is approved, and its status is 1 or 2. Otherwise stop and show an error.
    if (!$grn || $grn['approval_status'] !== 'approved'
        || !in_array((int) $grn['status'], array(1, 2), true)) {
        $this->ExistingStockJson(
            array(
                'status' => 0,
                'message' => 'Approved stock GRN not found.'
            ),
            404
        );
        return;
    }

    $this->ExistingStockJson(array(
        'status' => 1,
        'grn' => $grn,
        'editable' =>
            $this->Goodreceiveinfo->CanEditExistingStock($grn),
        'history' =>
            $this->Goodreceiveinfo->GetExistingStockHistory($grnId)
    ));
}

public function UpdateExistingStockQuantities()
{
    if ($this->input->method(true) !== 'POST') {
        $this->ExistingStockJson(
            array('status' => 0, 'message' => 'POST required.'),
            405
        );
        return;
    }

    if (!$this->Goodreceiveinfo->HasGrnPermission('edit')) {
        $this->ExistingStockJson(
            array(
                'status' => 0,
                'message' => 'Edit permission required.'
            ),
            403
        );
        return;
    }

    $items = json_decode(
        (string) $this->input->post('items'),
        true
    );

    $reason = trim((string) $this->input->post('reason'));

    $this->ExistingStockJson(
        $this->Goodreceiveinfo->UpdateExistingStockQuantities(
            (int) $this->input->post('grn_id'),
            $items,
            $reason
        )
    );
}
}
