<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplierinfo extends CI_Model {

	public function GetSuppliercategory() {
		$this->db->select('idtbl_supplier_type, type');
		$this->db->from('tbl_supplier_type');
		$this->db->where('status', 1);

		return $respond=$this->db->get();
	}

	public function Supplierinsertupdate() {
		$this->db->trans_begin();

		$userID=$_SESSION['userid'];

		$supplier_name=$this->input->post('supplier_name');
		$suppliertype=$this->input->post('suppliertype');
		$business_regno=$this->input->post('business_regno');
		$nbtno=$this->input->post('nbtno');
		$svatno=$this->input->post('svatno');
		$telephoneno=$this->input->post('telephoneno');
		$faxno=$this->input->post('faxno');
		$vatno=$this->input->post('vatno');
		$line1=$this->input->post('line1');
		$line2=$this->input->post('line2');
		$city=$this->input->post('city');
		$state=$this->input->post('state');
		$dline1=$this->input->post('dline1');
		$dline2=$this->input->post('dline2');
		$dcity=$this->input->post('dcity');
		$dstate=$this->input->post('dstate');
		$business_status=$this->input->post('bstatus');
		$credit_days=$this->input->post('credit_days');
		$company_id=$this->input->post('f_company_id');
		$branch_id=$this->input->post('f_branch_id');
		
		// Handle payment methods as checkboxes (multiple selections)
		$payment_methods = array();
		if ($this->input->post('payementmethod_cash')) $payment_methods[] = 'Cash';
		if ($this->input->post('payementmethod_bank')) $payment_methods[] = 'Bank';
		if ($this->input->post('payementmethod_credit')) $payment_methods[] = 'Credit';
		if ($this->input->post('payementmethod_cheque')) $payment_methods[] = 'Cheque';
		$payment_method = !empty($payment_methods) ? implode(', ', $payment_methods) : '';

		$recordOption=$this->input->post('recordOption');

		if( !empty($this->input->post('recordID'))) {
			$recordID=$this->input->post('recordID');
		}

		$insertdatetime=date('Y-m-d H:i:s');

		if($recordOption==1) {
			// Build data array with only non-empty values for INSERT
			$data = array(
				'status' => '1',
				'insertdatetime' => $insertdatetime,
				'tbl_user_idtbl_user' => $userID,
			);
			
			// Add fields only if they have values
			if (!empty($supplier_name)) $data['name'] = $supplier_name;
			if (!empty($suppliertype)) $data['tbl_supplier_type_idtbl_supplier_type'] = $suppliertype;
			if (!empty($business_regno)) $data['bus_reg_no'] = $business_regno;
			if (!empty($nbtno)) $data['nbt_no'] = $nbtno;
			if (!empty($svatno)) $data['svat_no'] = $svatno;
			if (!empty($telephoneno)) $data['telephone_no'] = $telephoneno;
			if (!empty($faxno)) $data['fax_no'] = $faxno;
			if (!empty($vatno)) $data['vat_no'] = $vatno;
			if (!empty($line1)) $data['address_line1'] = $line1;
			if (!empty($line2)) $data['address_line2'] = $line2;
			if (!empty($city)) $data['city'] = $city;
			if (!empty($state)) $data['state'] = $state;
			if (!empty($dline1)) $data['delivery_address_line1'] = $dline1;
			if (!empty($dline2)) $data['delivery_address_line2'] = $dline2;
			if (!empty($dcity)) $data['delivery_city'] = $dcity;
			if (!empty($dstate)) $data['delivery_state'] = $dstate;
			if (!empty($business_status)) $data['business_status'] = $business_status;
			if (!empty($payment_method)) $data['payment_method'] = $payment_method;
			if (!empty($credit_days)) $data['credit_days'] = $credit_days;
			if (!empty($company_id)) $data['company_id'] = $company_id;
			if (!empty($branch_id)) $data['company_branch_id'] = $branch_id;

			$this->db->insert('tbl_supplier', $data);

			$insertId=$this->db->insert_id();

			// Check if images are uploaded
			if ( !empty($_FILES['image']['name'])) {
				// Configure upload settings for image1
				$config1['upload_path']='./images/supplier_br_cetificate';
				$config1['allowed_types']='gif|jpg|png|jpeg';
				$config1['max_size']=10000;
				$this->load->library('upload', $config1);

				// Upload image1
				$this->upload->initialize($config1);

				if (!$this->upload->do_upload('image')) {
					$error = $this->upload->display_errors();
					// Log error but continue - don't break the transaction
					error_log('File upload error: ' . $error);
				} else {
					$image1_data=$this->upload->data();
					$filedata=array('imagepath'=> $image1_data['file_name']);
					$this->db->where('idtbl_supplier', $insertId);
					$this->db->update('tbl_supplier', $filedata);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-save';
				$actionObj->title='';
				$actionObj->message='Record Added Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='success';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			} else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			}
		} else {
			// For UPDATE - only update fields that have values
			$data = array(
				'updatedatetime' => $insertdatetime,
				'tbl_user_idtbl_user' => $userID,
			);
			
			// Add fields only if they have values
			if (!empty($supplier_name)) $data['name'] = $supplier_name;
			if (!empty($suppliertype)) $data['tbl_supplier_type_idtbl_supplier_type'] = $suppliertype;
			if (!empty($business_regno)) $data['bus_reg_no'] = $business_regno;
			if (!empty($nbtno)) $data['nbt_no'] = $nbtno;
			if (!empty($svatno)) $data['svat_no'] = $svatno;
			if (!empty($telephoneno)) $data['telephone_no'] = $telephoneno;
			if (!empty($faxno)) $data['fax_no'] = $faxno;
			if (!empty($vatno)) $data['vat_no'] = $vatno;
			if (!empty($line1)) $data['address_line1'] = $line1;
			if (!empty($line2)) $data['address_line2'] = $line2;
			if (!empty($city)) $data['city'] = $city;
			if (!empty($state)) $data['state'] = $state;
			if (!empty($dline1)) $data['delivery_address_line1'] = $dline1;
			if (!empty($dline2)) $data['delivery_address_line2'] = $dline2;
			if (!empty($dcity)) $data['delivery_city'] = $dcity;
			if (!empty($dstate)) $data['delivery_state'] = $dstate;
			if (!empty($business_status)) $data['business_status'] = $business_status;
			if (!empty($payment_method)) $data['payment_method'] = $payment_method;
			if (!empty($credit_days)) $data['credit_days'] = $credit_days;
			if (!empty($company_id)) $data['company_id'] = $company_id;
			if (!empty($branch_id)) $data['company_branch_id'] = $branch_id;

			$this->db->where('idtbl_supplier', $recordID);
			$this->db->update('tbl_supplier', $data);

			// Handle file upload for update if provided
			if ( !empty($_FILES['image']['name'])) {
				$config1['upload_path']='./images/supplier_br_cetificate';
				$config1['allowed_types']='gif|jpg|png|jpeg';
				$config1['max_size']=10000;
				$this->load->library('upload', $config1);
				$this->upload->initialize($config1);

				if ($this->upload->do_upload('image')) {
					$image1_data=$this->upload->data();
					$filedata=array('imagepath'=> $image1_data['file_name']);
					$this->db->where('idtbl_supplier', $recordID);
					$this->db->update('tbl_supplier', $filedata);
				}
			}

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-save';
				$actionObj->title='';
				$actionObj->message='Record Update Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='primary';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			} else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			}
		}
	}

	public function Supplierstatus($x, $y) {
		$this->db->trans_begin();

		$userID=$_SESSION['userid'];
		$recordID=$x;
		$type=$y;
		$updatedatetime=date('Y-m-d H:i:s');

		if($type==1) {
			$data=array('status'=> '1',
				'tbl_user_idtbl_user'=> $userID,
				'updatedatetime'=> $updatedatetime);

			$this->db->where('idtbl_supplier', $recordID);
			$this->db->update('tbl_supplier', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-check';
				$actionObj->title='';
				$actionObj->message='Record Activate Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='success';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			} else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			}
		} else if($type==2) {
			$data=array('status'=> '2',
				'tbl_user_idtbl_user'=> $userID,
				'updatedatetime'=> $updatedatetime);

			$this->db->where('idtbl_supplier', $recordID);
			$this->db->update('tbl_supplier', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-times';
				$actionObj->title='';
				$actionObj->message='Record Deactivate Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='warning';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			} else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			}
		} else if($type==3) {
			$data=array('status'=> '3',
				'tbl_user_idtbl_user'=> $userID,
				'updatedatetime'=> $updatedatetime);

			$this->db->where('idtbl_supplier', $recordID);
			$this->db->update('tbl_supplier', $data);

			$this->db->trans_complete();

			if ($this->db->trans_status()===TRUE) {
				$this->db->trans_commit();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-trash-alt';
				$actionObj->title='';
				$actionObj->message='Record Remove Successfully';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			} else {
				$this->db->trans_rollback();

				$actionObj=new stdClass();
				$actionObj->icon='fas fa-warning';
				$actionObj->title='';
				$actionObj->message='Record Error';
				$actionObj->url='';
				$actionObj->target='_blank';
				$actionObj->type='danger';

				$actionJSON=json_encode($actionObj);

				$this->session->set_flashdata('msg', $actionJSON);
				redirect('Supplier');
			}
		}
	}

	public function Supplieredit() {
		$recordID=$this->input->post('recordID');

		$this->db->select('*');
		$this->db->from('tbl_supplier');
		$this->db->where('idtbl_supplier', $recordID);
		$this->db->where('status', 1);

		$respond=$this->db->get();

		$obj=new stdClass();
		$obj->id=$respond->row(0)->idtbl_supplier;
		$obj->name=$respond->row(0)->name;
		$obj->business_regno=$respond->row(0)->bus_reg_no;
		$obj->nbtno=$respond->row(0)->nbt_no;
		$obj->svatno=$respond->row(0)->svat_no;
		$obj->telephoneno=$respond->row(0)->telephone_no;
		$obj->faxno=$respond->row(0)->fax_no;
		$obj->line1=$respond->row(0)->address_line1;
		$obj->line2=$respond->row(0)->address_line2;
		$obj->city=$respond->row(0)->city;
		$obj->state=$respond->row(0)->state;
		$obj->dline1=$respond->row(0)->delivery_address_line1;
		$obj->dline2=$respond->row(0)->delivery_address_line2;
		$obj->dcity=$respond->row(0)->delivery_city;
		$obj->dstate=$respond->row(0)->delivery_state;
		$obj->business_status=$respond->row(0)->business_status;
		$obj->payementmethod=$respond->row(0)->payment_method;
		$obj->credit_days=$respond->row(0)->credit_days;
		$obj->vat_no=$respond->row(0)->vat_no;
		$obj->type=$respond->row(0)->tbl_supplier_type_idtbl_supplier_type;
		echo json_encode($obj);
	}

	public function GetSupplierList() {
		$this->db->select('idtbl_supplier, name');
		$this->db->from('tbl_supplier');
		$this->db->where('status', 1);

		return $respond=$this->db->get();
	}

	public function get_suppliers_for_select($term = '') {
	    $this->db->select('idtbl_supplier as id, name as text');
	    $this->db->from('tbl_supplier');
	    $this->db->where('status', 1);
	    
	    if (!empty($term)) {
	        $this->db->like('name', $term);
	    }
	    
	    $this->db->order_by('name', 'ASC');
	    $query = $this->db->get();
	    
	    return array('results' => $query->result());
	}
}
?>