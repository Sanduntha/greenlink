<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Colombo');

class Purchaseorder extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Purchaseorderinfo');
        $this->load->model('Supplierinfo');
        $this->load->model('Locationinfo');
        $this->load->model('Rowmaterialsinfo');
        $this->load->model('Commeninfo');
    }
    
    public function index() {
        $data['supplierlist'] = $this->Supplierinfo->GetSupplierList();
        $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $data['materiallist'] = $this->Rowmaterialsinfo->GetMaterialList();
        $data['locations'] = $this->Locationinfo->Getlocations();
        
        $data['addcheck'] = isset($data['menuaccess']['add']) ? $data['menuaccess']['add'] : 0;
        $data['editcheck'] = isset($data['menuaccess']['edit']) ? $data['menuaccess']['edit'] : 0;
        $data['statuscheck'] = isset($data['menuaccess']['status']) ? $data['menuaccess']['status'] : 0;
        $data['deletecheck'] = isset($data['menuaccess']['delete']) ? $data['menuaccess']['delete'] : 0;
        
        $this->load->view('purchaseorder', $data);
    }
    
    public function GetSupplierAddress() {
        $supplier_id = $this->input->post('supplier_id');
        $address = $this->Purchaseorderinfo->GetSupplierAddress($supplier_id);
        echo json_encode($address);
    }
    
    public function GetMaterialsBySupplier() {
        $supplier_id = $this->input->post('supplier_id');
        $materials = $this->Purchaseorderinfo->GetMaterialsBySupplier($supplier_id);
        echo json_encode($materials);
    }
    
    public function GetMaterialDetails() {
        $material_id = $this->input->post('material_id');
        $supplier_id = $this->input->post('supplier_id');
        $details = $this->Purchaseorderinfo->GetMaterialDetails($material_id, $supplier_id);
        echo json_encode($details);
    }
    
    public function GetUnitpriceAccoMaterial() {
        $this->Purchaseorderinfo->GetUnitpriceAccoMaterial();
    }
    
    public function GeneratePONumber() {
        $po_number = $this->Purchaseorderinfo->GeneratePONumber();
        echo json_encode(['ponumber' => $po_number]);
    }
    
    public function Porderinsert() {
        $result = $this->Purchaseorderinfo->Porderinsert();
        echo json_encode($result);
    }
    
    public function GetPorderDetails($id = null) {
        if ($id === null) {
            $id = $this->input->post('order_id');
        }
        $result = $this->Purchaseorderinfo->GetPorderDetails($id);
        echo json_encode($result);
    }
    
    public function PorderdetailsView() {
        $recordID = $this->input->post('recordID');
        $result = $this->Purchaseorderinfo->PorderdetailsView($recordID);
        echo $result;
    }
    
    public function Porderupdate() {
        $result = $this->Purchaseorderinfo->Porderupdate();
        echo json_encode($result);
    }
    
    public function Porderdelete($id) {
        $result = $this->Purchaseorderinfo->Porderdelete($id);
        echo json_encode($result);
    }
    
    public function ConfirmPorder($id) {
        $result = $this->Purchaseorderinfo->ConfirmPorder($id, 1);
        if ($result) {
            $this->session->set_flashdata('success', 'Purchase Order Confirmed Successfully');
        } else {
            $this->session->set_flashdata('error', 'Error confirming purchase order');
        }
        redirect('Purchaseorder');
    }
}
?>