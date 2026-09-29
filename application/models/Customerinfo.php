<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customerinfo extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function Customerinsertupdate()
	{
		$this->db->trans_begin();

		$userID = $_SESSION['userid'];

		$customer_name = $this->input->post('customer_name');
		$customer_type = $this->input->post('customer_type');
		$business_regno = $this->input->post('business_regno');
		$nbtno = $this->input->post('nbtno');
		$svatno = $this->input->post('svatno');
		$telephoneno = $this->input->post('telephoneno');
		$faxno = $this->input->post('faxno');
		$customer_email = $this->input->post('customer_email');
		$website_url = $this->input->post('website_url');
		$vatno = $this->input->post('vatno');
		$vat_customer = $this->input->post('vat_customer');
		$line1 = $this->input->post('line1');
		$line2 = $this->input->post('line2');
		$city = $this->input->post('city');
		$state = $this->input->post('state');
		$country = $this->input->post('country');
		$dline1 = $this->input->post('dline1');
		$dline2 = $this->input->post('dline2');
		$dcity = $this->input->post('dcity');
		$dstate = $this->input->post('dstate');
		$dcountry = $this->input->post('dcountry');
		$business_status = $this->input->post('bstatus');
		
		// Handle payment methods as checkboxes (multiple selections)
		$payment_methods = array();
		if ($this->input->post('payementmethod_cash')) $payment_methods[] = 'Cash';
		if ($this->input->post('payementmethod_bank')) $payment_methods[] = 'Bank';
		if ($this->input->post('payementmethod_credit')) $payment_methods[] = 'Credit';
		if ($this->input->post('payementmethod_cheque')) $payment_methods[] = 'Cheque';
		$payment_method = !empty($payment_methods) ? implode(', ', $payment_methods) : '';
		
		$company_id = $this->input->post('f_company_id');
		$branch_id = $this->input->post('f_branch_id');

		$recordOption = $this->input->post('recordOption');

		if (!empty($this->input->post('recordID'))) {
			$recordID = $this->input->post('recordID');
		}

		$insertdatetime = date('Y-m-d H:i:s');

		if ($recordOption == 1) {
			// Build data array with only non-empty values for INSERT
			$data = array(
				'status' => '1',
				'insertdatetime' => $insertdatetime,
				'tbl_user_idtbl_user' => $userID,
			);
			
			// Add fields only if they have values
			if (!empty($customer_name)) $data['name'] = $customer_name;
			if (!empty($customer_type)) $data['customer_type'] = $customer_type;
			if (!empty($business_regno)) $data['bus_reg_no'] = $business_regno;
			if (!empty($nbtno)) $data['nbt_no'] = $nbtno;
			if (!empty($svatno)) $data['svat_no'] = $svatno;
			if (!empty($telephoneno)) $data['telephone_no'] = $telephoneno;
			if (!empty($faxno)) $data['fax_no'] = $faxno;
			if (!empty($customer_email)) $data['email'] = $customer_email;
			if (!empty($website_url)) $data['website_url'] = $website_url;
			if (!empty($vatno)) $data['vat_no'] = $vatno;
			if (!empty($vat_customer)) $data['vat_customer'] = $vat_customer;
			if (!empty($line1)) $data['address_line1'] = $line1;
			if (!empty($line2)) $data['address_line2'] = $line2;
			if (!empty($city)) $data['city'] = $city;
			if (!empty($state)) $data['state'] = $state;
			if (!empty($country)) $data['country'] = $country;
			if (!empty($dline1)) $data['delivery_address_line1'] = $dline1;
			if (!empty($dline2)) $data['delivery_address_line2'] = $dline2;
			if (!empty($dcity)) $data['delivery_city'] = $dcity;
			if (!empty($dstate)) $data['delivery_state'] = $dstate;
			if (!empty($dcountry)) $data['delivery_country'] = $dcountry;
			if (!empty($business_status)) $data['business_status'] = $business_status;
			if (!empty($payment_method)) $data['payment_method'] = $payment_method;
			if (!empty($company_id)) $data['company_id'] = $company_id;
			if (!empty($branch_id)) $data['company_branch_id'] = $branch_id;

			$this->db->insert('tbl_customer', $data);

			$insertId = $this->db->insert_id();

			if (!empty($_FILES['image']['name'])) {
				$config['upload_path'] = './images/cetificate';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size'] = 10000;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				
				if (!$this->upload->do_upload('image')) {
					$error = $this->upload->display_errors();
					error_log('File upload error: ' . $error);
				} else {
					$image1_data = $this->upload->data();
					$filedata = array('imagepath' => $image1_data['file_name']);
					$this->db->where('idtbl_customer', $insertId);
					$this->db->update('tbl_customer', $filedata);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-save';
				$actionObj->title = '';
				$actionObj->message = 'Record Added Successfully';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'success';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			} else {
				$this->db->trans_rollback();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-warning';
				$actionObj->title = '';
				$actionObj->message = 'Record Error';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'danger';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		} else {
			// For UPDATE - only update fields that have values
			$data = array(
				'updatedatetime' => $insertdatetime,
				'tbl_user_idtbl_user' => $userID,
			);
			
			// Add fields only if they have values
			if (!empty($customer_name)) $data['name'] = $customer_name;
			if (!empty($customer_type)) $data['customer_type'] = $customer_type;
			if (!empty($business_regno)) $data['bus_reg_no'] = $business_regno;
			if (!empty($nbtno)) $data['nbt_no'] = $nbtno;
			if (!empty($svatno)) $data['svat_no'] = $svatno;
			if (!empty($telephoneno)) $data['telephone_no'] = $telephoneno;
			if (!empty($faxno)) $data['fax_no'] = $faxno;
			if (!empty($customer_email)) $data['email'] = $customer_email;
			if (!empty($website_url)) $data['website_url'] = $website_url;
			if (!empty($vatno)) $data['vat_no'] = $vatno;
			if (!empty($vat_customer)) $data['vat_customer'] = $vat_customer;
			if (!empty($line1)) $data['address_line1'] = $line1;
			if (!empty($line2)) $data['address_line2'] = $line2;
			if (!empty($city)) $data['city'] = $city;
			if (!empty($state)) $data['state'] = $state;
			if (!empty($country)) $data['country'] = $country;
			if (!empty($dline1)) $data['delivery_address_line1'] = $dline1;
			if (!empty($dline2)) $data['delivery_address_line2'] = $dline2;
			if (!empty($dcity)) $data['delivery_city'] = $dcity;
			if (!empty($dstate)) $data['delivery_state'] = $dstate;
			if (!empty($dcountry)) $data['delivery_country'] = $dcountry;
			if (!empty($business_status)) $data['business_status'] = $business_status;
			if (!empty($payment_method)) $data['payment_method'] = $payment_method;
			if (!empty($company_id)) $data['company_id'] = $company_id;
			if (!empty($branch_id)) $data['company_branch_id'] = $branch_id;

			$this->db->where('idtbl_customer', $recordID);
			$this->db->update('tbl_customer', $data);
			
			if (!empty($_FILES['image']['name'])) {
				$config['upload_path'] = './images/cetificate/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = 10000;
				$config['file_name'] = 'br_certificate_' . $recordID . '_' . time();
				$this->load->library('upload', $config);

				if ($this->upload->do_upload('image')) {
					$image_data = $this->upload->data();
					$filedata = array('imagepath' => $image_data['file_name']);
					$this->db->where('idtbl_customer', $recordID);
					$this->db->update('tbl_customer', $filedata);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-save';
				$actionObj->title = '';
				$actionObj->message = 'Record Update Successfully';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'primary';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			} else {
				$this->db->trans_rollback();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-warning';
				$actionObj->title = '';
				$actionObj->message = 'Record Error';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'danger';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		}
	}

	public function Customerstatus($x, $y)
	{
		$this->db->trans_begin();

		$userID = $_SESSION['userid'];
		$recordID = $x;
		$type = $y;
		$updatedatetime = date('Y-m-d H:i:s');

		if ($type == 1) {
			$data = array(
				'status' => '1',
				'updatedatetime' => $updatedatetime
			);

			$this->db->where('idtbl_customer', $recordID);
			$this->db->update('tbl_customer', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-check';
				$actionObj->title = '';
				$actionObj->message = 'Record Activate Successfully';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'success';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			} else {
				$this->db->trans_rollback();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-warning';
				$actionObj->title = '';
				$actionObj->message = 'Record Error';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'danger';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		} else if ($type == 2) {
			$data = array(
				'status' => '2',
				'updatedatetime' => $updatedatetime
			);

			$this->db->where('idtbl_customer', $recordID);
			$this->db->update('tbl_customer', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-times';
				$actionObj->title = '';
				$actionObj->message = 'Record Deactivate Successfully';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'warning';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			} else {
				$this->db->trans_rollback();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-warning';
				$actionObj->title = '';
				$actionObj->message = 'Record Error';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'danger';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		} else if ($type == 3) {
			$data = array(
				'status' => '3',
				'updatedatetime' => $updatedatetime
			);

			$this->db->where('idtbl_customer', $recordID);
			$this->db->update('tbl_customer', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-trash-alt';
				$actionObj->title = '';
				$actionObj->message = 'Record Remove Successfully';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'danger';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			} else {
				$this->db->trans_rollback();

				$actionObj = new stdClass();
				$actionObj->icon = 'fas fa-warning';
				$actionObj->title = '';
				$actionObj->message = 'Record Error';
				$actionObj->url = '';
				$actionObj->target = '_blank';
				$actionObj->type = 'danger';

				$actionJSON = json_encode($actionObj);
				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Customer');
			}
		}
	}

	public function Customeredit()
	{
		$recordID = $this->input->post('recordID');

		$this->db->select('*');
		$this->db->from('tbl_customer');
		$this->db->where('idtbl_customer', $recordID);
		$this->db->where('status', 1);

		$respond = $this->db->get();

		$obj = new stdClass();
		$obj->id = $respond->row(0)->idtbl_customer;
		$obj->name = $respond->row(0)->name;
		$obj->customer_type = $respond->row(0)->customer_type;
		$obj->business_regno = $respond->row(0)->bus_reg_no;
		$obj->nbtno = $respond->row(0)->nbt_no;
		$obj->vat_customer = $respond->row(0)->vat_customer;
		$obj->svatno = $respond->row(0)->svat_no;
		$obj->telephoneno = $respond->row(0)->telephone_no;
		$obj->faxno = $respond->row(0)->fax_no;
		$obj->customer_email = $respond->row(0)->email;
		$obj->website_url = $respond->row(0)->website_url;
		$obj->line1 = $respond->row(0)->address_line1;
		$obj->line2 = $respond->row(0)->address_line2;
		$obj->city = $respond->row(0)->city;
		$obj->state = $respond->row(0)->state;
		$obj->country = $respond->row(0)->country;
		$obj->dline1 = $respond->row(0)->delivery_address_line1;
		$obj->dline2 = $respond->row(0)->delivery_address_line2;
		$obj->dcity = $respond->row(0)->delivery_city;
		$obj->dstate = $respond->row(0)->delivery_state;
		$obj->dcountry = $respond->row(0)->delivery_country;
		$obj->business_status = $respond->row(0)->business_status;
		$obj->payementmethod = $respond->row(0)->payment_method;
		$obj->vat_no = $respond->row(0)->vat_no;

		echo json_encode($obj);
	}

	public function GetCustomerList()
	{
		$this->db->select('idtbl_customer, name');
		$this->db->from('tbl_customer');
		$this->db->where('status', 1);

		return $respond = $this->db->get();
	}
	
	public function AddDeliveryAddresses($customerID, $addresses)
	{
		$this->db->trans_begin();
		$userID = $_SESSION['userid'];

		$savedAddresses = [];
		$results = [];
		
		// Decode JSON if it's a string
		if (is_string($addresses)) {
			$addresses = json_decode($addresses, true);
		}

		foreach ($addresses as $address) {
			$data = [
				'tbl_customer_idtbl_customer' => $customerID,
				'address_line1' => $address['dline1'],
				'address_line2' => $address['dline2'],
				'city' => $address['dcity'],
				'state' => $address['dstate'],
				'ddcountry' => $address['ddcountry'],
				'remark' => $address['remark'] ?? null,
				'status' => 1,
				'insertdatetime' => date('Y-m-d H:i:s'),
				'tbl_user_idtbl_user' => $userID
			];

			$this->db->insert('tbl_customer_delivery_address', $data);
			$insertId = $this->db->insert_id();

			if ($insertId) {
				$results[] = $insertId;
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return ['status' => 'error', 'message' => 'Failed to add addresses'];
		}

		$savedAddresses = [];
		foreach ($results as $id) {
			$savedAddresses[] = $this->GetDeliveryAddress($id);
		}

		return [
			'status' => 'success',
			'message' => 'Addresses added successfully',
			'addresses' => $savedAddresses,
			'insert_ids' => $results
		];
	}

	public function GetDeliveryAddress($addressID)
	{
		$this->db->select('*');
		$this->db->from('tbl_customer_delivery_address');
		$this->db->where('idtbl_customer_delivery_address', $addressID);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	public function GetDeliveryAddresses($customerID)
	{
		$this->db->select('*');
		$this->db->from('tbl_customer_delivery_address');
		$this->db->where('tbl_customer_idtbl_customer', $customerID);
		$this->db->where('status', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function DeleteDeliveryAddress($addressID)
	{
		$this->db->trans_begin();

		$data = [
			'status' => 0,
			'updatedatetime' => date('Y-m-d H:i:s')
		];

		$this->db->where('idtbl_customer_delivery_address', $addressID);
		$this->db->update('tbl_customer_delivery_address', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return ['status' => 'error', 'message' => 'Failed to delete address'];
		}

		return ['status' => 'success', 'message' => 'Address deleted successfully'];
	}

	public function UpdateDeliveryAddress($addressID, $addressData)
	{
		$this->db->trans_begin();
		$userID = $_SESSION['userid'];
		$updatedatetime = date('Y-m-d H:i:s');

		$data = [
			'address_line1' => $addressData['dline1'],
			'address_line2' => $addressData['dline2'],
			'city' => $addressData['dcity'],
			'state' => $addressData['dstate'],
			'ddcountry' => $addressData['ddcountry'],
			'remark' => $addressData['remark'] ?? null,
			'updatedatetime' => $updatedatetime,
			'tbl_user_idtbl_user' => $userID
		];

		$this->db->where('idtbl_customer_delivery_address', $addressID);
		$this->db->update('tbl_customer_delivery_address', $data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return ['status' => 'error', 'message' => 'Failed to update address'];
		}

		$updatedAddress = $this->GetDeliveryAddress($addressID);

		return [
			'status' => 'success',
			'message' => 'Address updated successfully',
			'address' => $updatedAddress
		];
	}
}
?>