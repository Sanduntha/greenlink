<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Customer extends CI_Controller
{
	public function index()
	{
		$this->load->model('Customerinfo');
		$this->load->model('Commeninfo');
		$result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
		$this->load->view('customer', $result);
	}

	public function Customerinsertupdate()
	{
		$this->load->model('Customerinfo');
		$result = $this->Customerinfo->Customerinsertupdate();
	}
	public function Customeredit()
	{
		$this->load->model('Customerinfo');
		$result = $this->Customerinfo->Customeredit();
	}
	public function Customerstatus($x, $y)
	{
		$this->load->model('Customerinfo');
		$result = $this->Customerinfo->Customerstatus($x, $y);
	}

	public function GetDeliveryAddress($addressID)
	{
		$this->load->model('Customerinfo');
		if (empty($addressID)) {
			echo json_encode(['status' => 'error', 'message' => 'Address ID required']);
			return;
		}

		$address = $this->Customerinfo->GetDeliveryAddress($addressID);
		echo json_encode($address);
	}

	public function AddDeliveryAddresses()
	{
		$this->load->model('Customerinfo');
		$customerID = $this->input->post('customerID');
		$addresses = json_decode($this->input->post('addresses'), true);

		if (empty($customerID) || empty($addresses)) {
			echo json_encode(['status' => 'error', 'message' => 'Customer ID and addresses required']);
			return;
		}

		$results = $this->Customerinfo->AddDeliveryAddresses($customerID, $addresses);
		echo json_encode($results);

	}

	public function DeleteDeliveryAddress($addressID)
	{
		$this->load->model('Customerinfo');
		if (empty($addressID)) {
			echo json_encode(['status' => 'error', 'message' => 'Address ID required']);
			return;
		}

		$result = $this->Customerinfo->DeleteDeliveryAddress($addressID);
		if ($result) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update address']);
		}
	}

	public function UpdateDeliveryAddress()
	{
		$this->load->model('Customerinfo');

		$addressID = $this->input->post('addressID');
		// if (empty($addressID)) {
		// 	echo json_encode(['status' => 'error', 'message' => 'Address ID required']);
		// 	return;
		// }

		$addressData = [
			'dline1' => $this->input->post('dline1'),
			'dline2' => $this->input->post('dline2'),
			'dcity' => $this->input->post('dcity'),
			'dstate' => $this->input->post('dstate'),
			'ddcountry' => $this->input->post('ddcountry'),
			'remark' => $this->input->post('remark')
		];

		$result = $this->Customerinfo->UpdateDeliveryAddress($addressID, $addressData);

		if ($result) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update address']);
		}
	}
	public function GetDeliveryAddresses($customerID)
	{
		$this->load->model('Customerinfo');

		$result = $this->Customerinfo->GetDeliveryAddresses($customerID);
		echo json_encode($result); 
	}

}
