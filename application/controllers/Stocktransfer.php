<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Stocktransfer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Commeninfo');
        $this->load->model('Locationinfo');
        $this->load->model('Stocktransferinfo');
    }

    public function index()
    {
        $result['locations'] = $this->Locationinfo->Getlocations();
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->view('stocktransfer', $result);
    }

    public function GetMaterialsByLocation()
    {
        $location = $this->input->post('location');
        $materials = $this->Stocktransferinfo->GetMaterialsByLocation($location);
        echo json_encode($materials);
    }

    public function GetBatchesByMaterial()
    {
        $material_id = $this->input->post('material_id');
        $location = $this->input->post('location');
        $batches = $this->Stocktransferinfo->GetBatchesByMaterial($material_id, $location);
        echo json_encode($batches);
    }

    public function ProcessTransfer()
    {
        $result = $this->Stocktransferinfo->ProcessTransfer();
        echo json_encode($result);
    }

    public function GetPendingTransfers() {
        $transfers = $this->Stocktransferinfo->GetPendingTransfers();
        echo json_encode($transfers);
    }
    
    public function ApproveTransfer() {
        $transfer_id = $this->input->post('transfer_id');
        $result = $this->Stocktransferinfo->ApproveTransfer($transfer_id);
        echo json_encode($result);
    }
    
    public function RejectTransfer() {
        $transfer_id = $this->input->post('transfer_id');
        $result = $this->Stocktransferinfo->RejectTransfer($transfer_id);
        echo json_encode($result);
    }
    
    public function GetTransferDetails() {
        $transfer_id = $this->input->post('transfer_id');
        $details = $this->Stocktransferinfo->GetTransferDetails($transfer_id);
        echo json_encode($details);
    }
    
    public function UpdateTransfer() {
        $result = $this->Stocktransferinfo->UpdateTransfer();
        echo json_encode($result);
    }
}