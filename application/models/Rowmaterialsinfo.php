<?php
class Rowmaterialsinfo extends CI_Model
{

    public function Rowmaterialsinsertupdate()
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $insertdatetime = date('Y-m-d H:i:s');

        $materialcode = $this->input->post('materialcode');
        $materialmaincategory = $this->input->post('materialmaincategory');
        $materialname = $this->input->post('materialname');
        $measurment = $this->input->post('measurment');
        // ROL is removed from form, set to NULL
        $rol = NULL;
        $racknumber = $this->input->post('racknumber');
        $recordOption = $this->input->post('recordOption');
        $existing_attachment = $this->input->post('existing_attachment');

        if (!empty($this->input->post('recordID'))) {
            $recordID = $this->input->post('recordID');
        }

        $attachment = $existing_attachment;

        if (!empty($_FILES['attachment']['name'])) {
            $config['upload_path'] = './images/material_attachment/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png|gif';
            $config['max_size'] = 5120;
            $config['encrypt_name'] = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('attachment')) {
                $upload_data = $this->upload->data();
                $attachment = $upload_data['file_name'];

                if (!empty($existing_attachment) && file_exists('./images/material_attachment/' . $existing_attachment)) {
                    unlink('./images/material_attachment/' . $existing_attachment);
                }
            }
        }

        if ($recordOption == 1) {
            // INSERT new material
            $data = array(
                'material_code' => $materialcode,
                'material_name' => $materialname,
                'rol' => $rol, // Set to NULL
                'attachment' => $attachment,
                'status' => '1',
                'insertdatetime' => $insertdatetime,
                'tbl_user_idtbl_user' => $userID,
                'tbl_measurements_idtbl_measurements' => $measurment,
                'tbl_material_main_cat_idtbl_material_main_cat' => $materialmaincategory,
                'tbl_rack_idtbl_rack' => $racknumber,
            );

            $this->db->insert('tbl_row_material', $data);
            $material_id = $this->db->insert_id();

            // Insert supplier records
            $suppliers = $this->input->post('suppliers');
            $unitprices = $this->input->post('unitprices');
            $saleprices = $this->input->post('saleprices');

            if (!empty($suppliers) && is_array($suppliers)) {
                for ($i = 0; $i < count($suppliers); $i++) {
                    if (!empty($suppliers[$i]) && !empty($unitprices[$i]) && !empty($saleprices[$i])) {
                        $supplier_data = array(
                            'tbl_row_material_id' => $material_id,
                            'tbl_supplier_idtbl_supplier' => $suppliers[$i],
                            'unitprice' => $unitprices[$i],
                            'saleprice' => $saleprices[$i],
                            'is_primary' => ($i == 0) ? 1 : 0,
                            'status' => '1',
                            'insertdatetime' => $insertdatetime
                        );
                        $this->db->insert('tbl_material_supplier', $supplier_data);
                    }
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
                redirect('Rowmaterials');
            } else {
                $this->db->trans_rollback();
                if (!empty($attachment) && $attachment != $existing_attachment) {
                    unlink('./images/material_attachment/' . $attachment);
                }
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-warning';
                $actionObj->title = '';
                $actionObj->message = 'Record Error - ' . $this->db->error()['message'];
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';
                $actionJSON = json_encode($actionObj);
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Rowmaterials');
            }
        } else {
            // UPDATE existing material
            $data = array(
                'material_code' => $materialcode,
                'material_name' => $materialname,
                'rol' => $rol, // Set to NULL
                'attachment' => $attachment,
                'updatedatetime' => $insertdatetime,
                'tbl_user_idtbl_user' => $userID,
                'tbl_measurements_idtbl_measurements' => $measurment,
                'tbl_material_main_cat_idtbl_material_main_cat' => $materialmaincategory,
                'tbl_rack_idtbl_rack' => $racknumber,
            );

            $this->db->where('idtbl_row_material', $recordID);
            $this->db->update('tbl_row_material', $data);

            // Update supplier records - delete existing and insert new ones
            $this->db->where('tbl_row_material_id', $recordID);
            $this->db->delete('tbl_material_supplier');

            // Insert updated supplier records
            $suppliers = $this->input->post('suppliers');
            $unitprices = $this->input->post('unitprices');
            $saleprices = $this->input->post('saleprices');

            if (!empty($suppliers) && is_array($suppliers)) {
                for ($i = 0; $i < count($suppliers); $i++) {
                    if (!empty($suppliers[$i]) && !empty($unitprices[$i]) && !empty($saleprices[$i])) {
                        $supplier_data = array(
                            'tbl_row_material_id' => $recordID,
                            'tbl_supplier_idtbl_supplier' => $suppliers[$i],
                            'unitprice' => $unitprices[$i],
                            'saleprice' => $saleprices[$i],
                            'is_primary' => ($i == 0) ? 1 : 0,
                            'status' => '1',
                            'insertdatetime' => $insertdatetime
                        );
                        $this->db->insert('tbl_material_supplier', $supplier_data);
                    }
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-save';
                $actionObj->title = '';
                $actionObj->message = 'Record Updated Successfully';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'primary';
                $actionJSON = json_encode($actionObj);
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Rowmaterials');
            } else {
                $this->db->trans_rollback();
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-warning';
                $actionObj->title = '';
                $actionObj->message = 'Record Error - ' . $this->db->error()['message'];
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';
                $actionJSON = json_encode($actionObj);
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Rowmaterials');
            }
        }
    }
    
    // Rest of your existing methods remain the same...
    public function Rowmaterialsstatus($x, $y)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $recordID = $x;
        $type = $y;
        $updatedatetime = date('Y-m-d H:i:s');

        $current_attachment = '';
        if ($type == 3) {
            $this->db->select('attachment');
            $this->db->from('tbl_row_material');
            $this->db->where('idtbl_row_material', $recordID);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $current_attachment = $query->row()->attachment;
            }
        }

        if ($type == 1) {
            $data = array(
                'status' => '1',
                'tbl_user_idtbl_user' => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_row_material', $recordID);
            $this->db->update('tbl_row_material', $data);

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
                redirect('Rowmaterials');
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
                redirect('Rowmaterials');
            }
        } else if ($type == 2) {
            $data = array(
                'status' => '2',
                'tbl_user_idtbl_user' => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_row_material', $recordID);
            $this->db->update('tbl_row_material', $data);

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
                redirect('Rowmaterials');
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
                redirect('Rowmaterials');
            }
        } else if ($type == 3) {
            $data = array(
                'status' => '3',
                'tbl_user_idtbl_user' => $userID,
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_row_material', $recordID);
            $this->db->update('tbl_row_material', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                if (!empty($current_attachment) && file_exists('./images/material_attachment/' . $current_attachment)) {
                    unlink('./images/material_attachment/' . $current_attachment);
                }
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-trash-alt';
                $actionObj->title = '';
                $actionObj->message = 'Record Remove Successfully';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';
                $actionJSON = json_encode($actionObj);
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Rowmaterials');
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
                redirect('Rowmaterials');
            }
        }
    }

    public function Rowmaterialsedit()
    {
        $recordID = $this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_row_material');
        $this->db->where('idtbl_row_material', $recordID);
        $this->db->where('status !=', 3);

        $respond = $this->db->get();

        // Get supplier relationships with supplier names
        $this->db->select('ms.*, s.name as supplier_name');
        $this->db->from('tbl_material_supplier ms');
        $this->db->join('tbl_supplier s', 'ms.tbl_supplier_idtbl_supplier = s.idtbl_supplier');
        $this->db->where('ms.tbl_row_material_id', $recordID);
        $this->db->where('ms.status', 1);
        $suppliers_data = $this->db->get();

        $obj = new stdClass();
        $obj->id = $respond->row(0)->idtbl_row_material;
        $obj->materialcode = $respond->row(0)->material_code;
        $obj->materialname = $respond->row(0)->material_name;
        $obj->rol = $respond->row(0)->rol; // This will be NULL
        $obj->measurment = $respond->row(0)->tbl_measurements_idtbl_measurements;
        $obj->maincat = $respond->row(0)->tbl_material_main_cat_idtbl_material_main_cat;
        $obj->racknumber = $respond->row(0)->tbl_rack_idtbl_rack;
        $obj->attachment = $respond->row(0)->attachment;
        $obj->suppliers = $suppliers_data->result();

        echo json_encode($obj);
    }

    public function GetMaterialList()
    {
        $this->db->select('idtbl_row_material, material_name');
        $this->db->from('tbl_row_material');
        $this->db->where('status', 1);
        return $respond = $this->db->get();
    }
}