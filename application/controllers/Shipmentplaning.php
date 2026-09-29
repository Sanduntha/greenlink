<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Shipmentplaning extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Commeninfo');
        $this->load->model('Shipmentplaninginfo');
        $this->load->model('Locationinfo');
    }

    public function index()
    {
        $result['supervisors'] = $this->Shipmentplaninginfo->GetSupervisors();
                $result['customers'] = $this->Shipmentplaninginfo->GetCustomers();

        $result['locations'] = $this->Locationinfo->Getlocations();
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        $this->load->view('shipmentplaning', $result);
    }

    public function GetNextShipmentId()
    {
        $next_shipment_id = $this->Shipmentplaninginfo->GetNextShipmentId();
        echo json_encode(array('shipment_id' => $next_shipment_id));
    }
    public function GetNextInvoiceNo()
{
    $next_invoice_no = $this->Shipmentplaninginfo->GetNextInvoiceNo();
    echo json_encode(array('invoice_number' => $next_invoice_no));
}

    public function GetAvailableStock()
    {
        $stock = $this->Shipmentplaninginfo->GetAvailableStock();
        echo json_encode($stock);
    }

    public function ProcessShipment()
    {
        $result = $this->Shipmentplaninginfo->ProcessShipment();
        echo json_encode($result);
    }

    public function GetShipmentRecords()
    {
        $shipments = $this->Shipmentplaninginfo->GetShipmentRecords();
        echo json_encode($shipments);
    }

    public function GetShipmentDetails()
    {
        $shipment_id = $this->input->post('shipment_id');
        $details = $this->Shipmentplaninginfo->GetShipmentDetails($shipment_id);
        echo json_encode($details);
    }

    public function GetShipmentForEdit()
    {
        $shipment_id = $this->input->post('shipment_id');
        $result = $this->Shipmentplaninginfo->GetShipmentForEdit($shipment_id);
        echo json_encode($result);
    }

    public function ApproveShipment()
    {
        $shipment_id = $this->input->post('shipment_id');
        $result = $this->Shipmentplaninginfo->ApproveShipment($shipment_id);
        echo json_encode($result);
    }

    public function RejectShipment()
    {
        $shipment_id = $this->input->post('shipment_id');
        $result = $this->Shipmentplaninginfo->RejectShipment($shipment_id);
        echo json_encode($result);
    }

    public function UpdateShipment()
    {
        $result = $this->Shipmentplaninginfo->UpdateShipment();
        echo json_encode($result);
    }

    public function DeleteShipment()
    {
        $shipment_id = $this->input->post('shipment_id');
        $result = $this->Shipmentplaninginfo->DeleteShipment($shipment_id);
        echo json_encode($result);
    }
    public function UpdateShipmentStatus()
{
    $result = $this->Shipmentplaninginfo->UpdateShipmentStatus();
    echo json_encode($result);
}
}