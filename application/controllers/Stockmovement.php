<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Stockmovement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Commeninfo');
        $this->load->model('Stockmovementinfo');
    }

    public function index()
    {
        $result['site_locations'] = $this->Stockmovementinfo->GetSiteLocations();
        $result['warehouses'] = $this->Stockmovementinfo->GetWarehouses();
        $result['materials'] = $this->Stockmovementinfo->GetMaterials();
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->view('stockmovement', $result);
    }
    public function GetWarehousesByLocation()
{
    $location_id = $this->input->post('location_id');
    $warehouses = $this->Stockmovementinfo->GetWarehousesByLocation($location_id);
    echo json_encode($warehouses);
}

public function GetAvailableMaterials()
{
    $site_id = $this->input->post('site_id');
    $zone_id = $this->input->post('zone_id');
    $materials = $this->Stockmovementinfo->GetAvailableMaterials($site_id, $zone_id);
    echo json_encode($materials);
}

    public function GetNextMovementId()
    {
        $next_movement_id = $this->Stockmovementinfo->GetNextMovementId();
        echo json_encode(array('movement_id' => $next_movement_id));
    }

    public function GetAvailableStock()
    {
        $site_id = $this->input->post('site_id');
        $zone_id = $this->input->post('zone_id');
        $stock = $this->Stockmovementinfo->GetAvailableStock($site_id, $zone_id);
        echo json_encode($stock);
    }

    public function GetZoneCapacity()
    {
        $site_id = $this->input->post('site_id');
        $zone_id = $this->input->post('zone_id');
        $capacity = $this->Stockmovementinfo->GetZoneCapacity($site_id, $zone_id);
        echo json_encode($capacity);
    }

    public function GetMaterialBatches()
    {
        $material_id = $this->input->post('material_id');
        $site_id = $this->input->post('site_id');
        $zone_id = $this->input->post('zone_id');
        $batches = $this->Stockmovementinfo->GetMaterialBatches($material_id, $site_id, $zone_id);
        echo json_encode($batches);
    }

    public function SaveMovement()
    {
        $result = $this->Stockmovementinfo->SaveMovement();
        echo json_encode($result);
    }


    public function GetMovementDetails()
    {
        $movement_id = $this->input->post('movement_id');
        $details = $this->Stockmovementinfo->GetMovementDetails($movement_id);
        echo json_encode($details);
    }

    public function GetMovementForEdit()
    {
        $movement_id = $this->input->post('movement_id');
        $result = $this->Stockmovementinfo->GetMovementForEdit($movement_id);
        echo json_encode($result);
    }

    public function UpdateMovement()
    {
        $result = $this->Stockmovementinfo->UpdateMovement();
        echo json_encode($result);
    }

    public function ApproveMovement()
    {
        $movement_id = $this->input->post('movement_id');
        $result = $this->Stockmovementinfo->ApproveMovement($movement_id);
        echo json_encode($result);
    }

    public function DeleteMovement()
    {
        $movement_id = $this->input->post('movement_id');
        $result = $this->Stockmovementinfo->DeleteMovement($movement_id);
        echo json_encode($result);
    }
}