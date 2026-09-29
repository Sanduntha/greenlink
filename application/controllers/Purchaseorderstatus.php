<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Purchaseorderstatus extends CI_Controller
{
	public function index()
	{
		$this->load->model('Commeninfo');
		$this->load->model('PurchaseOrderStatusinfo');
		$result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
		$this->load->view('purchaseorderstatus', $result);
	}
	public function Completeoder($x, $y)
	{
		$this->load->model('PurchaseOrderStatusinfo');
		$result = $this->PurchaseOrderStatusinfo->Completeoder($x, $y);
	}
	public function Notcompleteoder($x, $y)
	{
		$this->load->model('PurchaseOrderStatusinfo');
		$result = $this->PurchaseOrderStatusinfo->Notcompleteoder($x, $y);
	}
	public function GetPorderDetails($id) {
        $this->load->model('PurchaseOrderStatusinfo');
        $result = $this->PurchaseOrderStatusinfo->GetPorderDetails($id);
        echo json_encode($result);
    }


}