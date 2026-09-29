<?php
class Goodreceiveinfo extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getZonesBySite($site_id)
    {
        $this->db->select('idtbl_rack, rack_number, max_weight');
        $this->db->from('tbl_rack');
        $this->db->where('tbl_location_idtbl_location', $site_id);
        $this->db->where('status', 1);
        $this->db->order_by('rack_number', 'ASC');

        return $this->db->get()->result_array();
    }

    public function GetPorderList($supplier_id = null)
    {
        $this->db->select('idtbl_porder, podate, tbl_supplier_idtbl_supplier as supplier_id');
        $this->db->from('tbl_porder');
        $this->db->where('status', 1);
        $this->db->where('confirmedstatus', 1);
        $this->db->where('completedstatus', 0);

        if ($supplier_id) {
            $this->db->where('tbl_supplier_idtbl_supplier', $supplier_id);
        }

        return $this->db->get();
    }

    public function GetSupervisors()
    {
        $this->db->select('idtbl_employee, fullname, empno');
        $this->db->from('tbl_employee');
        $this->db->where('status', 1);
        $this->db->where('designation LIKE "%supervisor%" OR designation LIKE "%manager%"', NULL, FALSE);
        $this->db->order_by('fullname', 'asc');

        return $this->db->get()->result_array();
    }

    public function GetSuppliers()
    {
        $this->db->select('idtbl_supplier, name, telephone_no');
        $this->db->from('tbl_supplier');
        $this->db->where('status', 1);
        $this->db->order_by('name', 'asc');

        return $this->db->get()->result_array();
    }

    public function GetSiteLocations()
    {
        $this->db->select('idtbl_location, location as location_name');
        $this->db->from('tbl_location');
        $this->db->where('status', 1);
        $this->db->order_by('location', 'asc');

        return $this->db->get()->result_array();
    }

    public function GetMaterialUnits()
    {
        $this->db->select('idtbl_mesurements, measure_type');
        $this->db->from('tbl_measurements');
        $this->db->where('status', 1);
        $this->db->order_by('measure_type', 'asc');

        return $this->db->get()->result_array();
    }

    public function GetMaterials()
    {
        $this->db->select('rm.idtbl_row_material, rm.material_code, rm.material_name, m.measure_type');
        $this->db->from('tbl_row_material rm');
        $this->db->join('tbl_measurements m', 'm.idtbl_mesurements = rm.tbl_measurements_idtbl_measurements', 'left');
        $this->db->where('rm.status', 1);
        $this->db->order_by('rm.material_name', 'asc');

        return $this->db->get()->result_array();
    }

    public function GenerateGrnNumber()
    {
        $prefix = 'GRN';
        $year = date('Y');
        $month = date('m');

        $this->db->select('MAX(grn_no) as last_grn');
        $this->db->from('tbl_grn');
        $this->db->like('grn_no', $prefix . '-' . $year . $month, 'after');
        $result = $this->db->get()->row();

        if ($result && $result->last_grn) {
            $last_number = (int) substr($result->last_grn, -4);
            $new_number = str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $new_number = '0001';
        }

        return $prefix . '-' . $year . $month . '-' . $new_number;
    }

    public function GetNextGrnNumber()
    {
        return $this->GenerateGrnNumber();
    }

    public function SaveNewGrn()
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $current_datetime = date('Y-m-d H:i:s');

        // Get form data
        $grn_type = $this->input->post('grn_type');
        $grn_date = $this->input->post('grn_date');
        $grn_time = $this->input->post('grn_time');
        $supervisor_id = $this->input->post('supervisor_id');
        $supplier_id = $this->input->post('supplier_id');
        $contact_no = $this->input->post('contact_no');
        $vehicle_no = $this->input->post('vehicle_no');
        $driver_name = $this->input->post('driver_name');
        $gatepass_no = $this->input->post('gatepass_no');
        $site_location = $this->input->post('site_location');
        $warehouse = $this->input->post('warehouse');
        $grn_source = $this->input->post('grn_source');
        $purchase_order = $this->input->post('purchase_order');
        $invoice_no = $this->input->post('invoice_no');
        $delivery_no = $this->input->post('delivery_no');
        $remarks = $this->input->post('remarks');
        $grn_total = $this->input->post('grn_total');

        // Generate GRN number
        $grn_no = $this->GenerateGrnNumber();

        // Generate batch number
        $batch_number = 'BATCH-' . date('YmdHis');

        $document_path = '';

        if (!empty($_FILES['document_file']['name'])) {
            $upload_dir = FCPATH . 'images/grn_documents/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
            $config['max_size'] = 2048;
            $config['file_name'] = 'GRN_' . time() . '_' . rand(1000, 9999);
            $config['overwrite'] = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('document_file')) {
                $upload_data = $this->upload->data();
                $document_path = 'images/grn_documents/' . $upload_data['file_name'];
            } else {
                echo $this->upload->display_errors();
                die;
            }
        }

        // Save GRN header - Invoice and Delivery no are optional
        $grn_data = array(
            'grn_no' => $grn_no,
            'date' => $grn_date,
            'grn_time' => $grn_time,
            'total' => $grn_total,
            'invoicenum' => $invoice_no ? $invoice_no : NULL,
            'dispatchnum' => $delivery_no ? $delivery_no : NULL,
            'grntype' => $grn_type,
            'status' => 1,
            'updatedatetime' => $current_datetime,
            'tbl_user_idtbl_user' => $userID,
            'ponumber' => ($grn_source == 'po') ? $purchase_order : NULL,
            'approval_status' => 'pending',
            'supervisor_id' => $supervisor_id,
            'supplier_id' => $supplier_id,
            'contact_no' => $contact_no,
            'vehicle_no' => $vehicle_no,
            'driver_name' => $driver_name,
            'gatepass_no' => $gatepass_no,
            'site_location' => $site_location,
            'warehouse' => $warehouse,
            'document_path' => $document_path,
            'grn_source' => $grn_source,
            'remarks' => $remarks,
            'batch_number' => $batch_number
        );

        $this->db->insert('tbl_grn', $grn_data);
        $grn_id = $this->db->insert_id();

        // Handle GRN details
        if ($grn_source == 'po') {
            // PO-based GRN
            $po_details = json_decode($this->input->post('po_details'), true);

            foreach ($po_details as $detail) {
                $item_data = array(
                    'date' => $grn_date,
                    'qty' => $detail['po_qty'],
                    'po_qty' => $detail['po_qty'],
                    'received_qty' => $detail['received_qty'],
                    'accepted_qty' => $detail['accepted_qty'],
                    'rejected_qty' => $detail['rejected_qty'],
                    'unitprice' => $detail['unitprice'],
                    'total' => $detail['total'],
                    'status' => 1,
                    'updatedatetime' => $current_datetime,
                    'tbl_user_idtbl_user' => $userID,
                    'tbl_grn_idtbl_grn' => $grn_id,
                    'tbl_row_material_idtbl_row_material' => $detail['material_id'],
                    'warehouse_location_name' => $warehouse,
                    'batch_number' => $batch_number,
                    'item_code' => $detail['item_code'],
                    'unit_of_measure' => $detail['unit_of_measure']
                );

                $this->db->insert('tbl_grndetail', $item_data);
            }
        } else {
            // No-PO GRN (Manual items)
            $manual_items = json_decode($this->input->post('manual_items'), true);

            foreach ($manual_items as $item) {
                $item_data = array(
                    'date' => $grn_date,
                    'qty' => $item['qty'],
                    'po_qty' => $item['qty'],
                    'received_qty' => $item['qty'],
                    'accepted_qty' => $item['qty'],
                    'rejected_qty' => 0,
                    'unitprice' => $item['unit_price'],
                    'total' => $item['total'],
                    'status' => 1,
                    'updatedatetime' => $current_datetime,
                    'tbl_user_idtbl_user' => $userID,
                    'tbl_grn_idtbl_grn' => $grn_id,
                    'tbl_row_material_idtbl_row_material' => $item['material_id'],
                    'warehouse_location_name' => $warehouse,
                    'batch_number' => $batch_number,
                    'item_code' => $item['item_code'],
                    'unit_of_measure' => $item['unit_of_measure']
                );

                $this->db->insert('tbl_grndetail', $item_data);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $response = array(
                'status' => 0,
                'message' => 'Failed to save GRN. Please try again.',
                'type' => 'danger'
            );
        } else {
            $this->db->trans_commit();

            $response = array(
                'status' => 1,
                'message' => 'GRN saved successfully. GRN Number: ' . $grn_no,
                'grn_no' => $grn_no,
                'grn_id' => $grn_id,
                'type' => 'success'
            );
        }

        echo json_encode($response);
    }

    public function GetGrnForEdit($grn_id)
    {
        $this->db->select('g.*, 
                          e.fullname as supervisor_name,
                          s.name as supplier_name,
                          sl.location as site_name,
                          w.rack_number as warehouse_name');
        $this->db->from('tbl_grn g');
        $this->db->join('tbl_employee e', 'e.idtbl_employee = g.supervisor_id', 'left');
        $this->db->join('tbl_supplier s', 's.idtbl_supplier = g.supplier_id', 'left');
        $this->db->join('tbl_location sl', 'sl.idtbl_location = g.site_location', 'left');
        $this->db->join('tbl_rack w', 'w.idtbl_rack = g.warehouse', 'left');
        $this->db->where('g.idtbl_grn', $grn_id);

        $grn = $this->db->get()->row_array();

        if ($grn) {
            // Get GRN details
            $this->db->select('gd.*, rm.material_name, rm.material_code, m.measure_type');
            $this->db->from('tbl_grndetail gd');
            $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = gd.tbl_row_material_idtbl_row_material', 'left');
            $this->db->join('tbl_measurements m', 'm.idtbl_mesurements = rm.tbl_measurements_idtbl_measurements', 'left');
            $this->db->where('gd.tbl_grn_idtbl_grn', $grn_id);
            $details = $this->db->get()->result_array();

            $grn['details'] = $details;
        }

        return $grn;
    }

    public function UpdateGrn($grn_id)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $current_datetime = date('Y-m-d H:i:s');

        // Get form data
        $grn_type = $this->input->post('grn_type');
        $grn_date = $this->input->post('grn_date');
        $grn_time = $this->input->post('grn_time');
        $supervisor_id = $this->input->post('supervisor_id');
        $supplier_id = $this->input->post('supplier_id');
        $contact_no = $this->input->post('contact_no');
        $vehicle_no = $this->input->post('vehicle_no');
        $driver_name = $this->input->post('driver_name');
        $gatepass_no = $this->input->post('gatepass_no');
        $site_location = $this->input->post('site_location');
        $warehouse = $this->input->post('warehouse');
        $grn_source = $this->input->post('grn_source');
        $purchase_order = $this->input->post('purchase_order');
        $invoice_no = $this->input->post('invoice_no');
        $delivery_no = $this->input->post('delivery_no');
        $remarks = $this->input->post('remarks');
        $grn_total = $this->input->post('grn_total');

        // Handle file upload
        $document_path = $this->input->post('existing_document');
        if (!empty($_FILES['document_file']['name'])) {
            $upload_dir = FCPATH . 'images/grn_documents/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $config['upload_path'] = $upload_dir;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
            $config['max_size'] = 2048;
            $config['file_name'] = 'GRN_' . time() . '_' . rand(1000, 9999);
            $config['overwrite'] = true;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('document_file')) {
                // Delete old file if exists
                if (!empty($document_path) && file_exists('./' . $document_path)) {
                    unlink('./' . $document_path);
                }

                $upload_data = $this->upload->data();
                $document_path = 'images/grn_documents/' . $upload_data['file_name'];
            } else {
                echo $this->upload->display_errors();
                die;
            }
        }

        // Update GRN header - Invoice and Delivery no are optional
        $grn_data = array(
            'date' => $grn_date,
            'grn_time' => $grn_time,
            'total' => $grn_total,
            'invoicenum' => $invoice_no ? $invoice_no : NULL,
            'dispatchnum' => $delivery_no ? $delivery_no : NULL,
            'grntype' => $grn_type,
            'updatedatetime' => $current_datetime,
            'ponumber' => ($grn_source == 'po') ? $purchase_order : NULL,
            'supervisor_id' => $supervisor_id,
            'supplier_id' => $supplier_id,
            'contact_no' => $contact_no,
            'vehicle_no' => $vehicle_no,
            'driver_name' => $driver_name,
            'gatepass_no' => $gatepass_no,
            'site_location' => $site_location,
            'warehouse' => $warehouse,
            'document_path' => $document_path,
            'grn_source' => $grn_source,
            'remarks' => $remarks
        );

        $this->db->where('idtbl_grn', $grn_id);
        $this->db->update('tbl_grn', $grn_data);

        // Delete existing details
        $this->db->where('tbl_grn_idtbl_grn', $grn_id);
        $this->db->delete('tbl_grndetail');

        // Re-add GRN details
        if ($grn_source == 'po') {
            $po_details = json_decode($this->input->post('po_details'), true);

            foreach ($po_details as $detail) {
                $item_data = array(
                    'date' => $grn_date,
                    'qty' => $detail['po_qty'],
                    'po_qty' => $detail['po_qty'],
                    'received_qty' => $detail['received_qty'],
                    'accepted_qty' => $detail['accepted_qty'],
                    'rejected_qty' => $detail['rejected_qty'],
                    'unitprice' => $detail['unitprice'],
                    'total' => $detail['total'],
                    'status' => 1,
                    'updatedatetime' => $current_datetime,
                    'tbl_user_idtbl_user' => $userID,
                    'tbl_grn_idtbl_grn' => $grn_id,
                    'tbl_row_material_idtbl_row_material' => $detail['material_id'],
                    'warehouse_location_name' => $warehouse,
                    'batch_number' => $detail['batch_number'],
                    'item_code' => $detail['item_code'],
                    'unit_of_measure' => $detail['unit_of_measure']
                );

                $this->db->insert('tbl_grndetail', $item_data);
            }
        } else {
            $manual_items = json_decode($this->input->post('manual_items'), true);

            foreach ($manual_items as $item) {
                $item_data = array(
                    'date' => $grn_date,
                    'qty' => $item['qty'],
                    'po_qty' => $item['qty'],
                    'received_qty' => $item['qty'],
                    'accepted_qty' => $item['qty'],
                    'rejected_qty' => 0,
                    'unitprice' => $item['unit_price'],
                    'total' => $item['total'],
                    'status' => 1,
                    'updatedatetime' => $current_datetime,
                    'tbl_user_idtbl_user' => $userID,
                    'tbl_grn_idtbl_grn' => $grn_id,
                    'tbl_row_material_idtbl_row_material' => $item['material_id'],
                    'warehouse_location_name' => $warehouse,
                    'batch_number' => $item['batch_number'],
                    'item_code' => $item['item_code'],
                    'unit_of_measure' => $item['unit_of_measure']
                );

                $this->db->insert('tbl_grndetail', $item_data);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $response = array(
                'status' => 0,
                'message' => 'Failed to update GRN. Please try again.',
                'type' => 'danger'
            );
        } else {
            $this->db->trans_commit();

            $response = array(
                'status' => 1,
                'message' => 'GRN updated successfully.',
                'type' => 'success'
            );
        }

        echo json_encode($response);
    }

    public function DeleteGrn($grn_id)
    {
        $this->db->trans_begin();

        $this->db->select('document_path');
        $this->db->from('tbl_grn');
        $this->db->where('idtbl_grn', $grn_id);
        $grn = $this->db->get()->row();

        if ($grn && $grn->document_path && file_exists('./' . $grn->document_path)) {
            unlink('./' . $grn->document_path);
        }

        $this->db->where('tbl_grn_idtbl_grn', $grn_id);
        $this->db->delete('tbl_grndetail');

        $this->db->where('idtbl_grn', $grn_id);
        $this->db->delete('tbl_grn');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $response = array(
                'status' => 0,
                'message' => 'Failed to delete GRN.',
                'type' => 'danger'
            );
        } else {
            $this->db->trans_commit();

            $response = array(
                'status' => 1,
                'message' => 'GRN deleted successfully.',
                'type' => 'success'
            );
        }

        echo json_encode($response);
    }

    public function ApproveGrn($grn_id)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $current_datetime = date('Y-m-d H:i:s');

        // Get GRN details
        $this->db->select('gd.*, g.grn_source, g.ponumber, g.site_location');
        $this->db->from('tbl_grndetail gd');
        $this->db->join('tbl_grn g', 'g.idtbl_grn = gd.tbl_grn_idtbl_grn');
        $this->db->where('gd.tbl_grn_idtbl_grn', $grn_id);
        $details = $this->db->get()->result_array();

        // Update stock for each item
        foreach ($details as $detail) {
            $material_id = $detail['tbl_row_material_idtbl_row_material'];
            $accepted_qty = $detail['accepted_qty'];
            $warehouse = $detail['warehouse_location_name'];
            $batch_number = $detail['batch_number'];
            $site_location = $detail['site_location'];

            // Update or insert stock
            $this->db->where('tbl_row_material_idtbl_row_material', $material_id);
            $this->db->where('warehouse_location_name', $warehouse);
            $this->db->where('site_location', $site_location);
            $existing_stock = $this->db->get('tbl_stock')->row();

            if ($existing_stock) {
                $this->db->set('qty', 'qty + ' . (float) $accepted_qty, FALSE);
                $this->db->where('tbl_row_material_idtbl_row_material', $material_id);
                $this->db->where('warehouse_location_name', $warehouse);
                $this->db->where('site_location', $site_location);
                $this->db->update('tbl_stock');
            } else {
                $stock_data = array(
                    'qty' => $accepted_qty,
                    'status' => 1,
                    'updatedatetime' => $current_datetime,
                    'tbl_user_idtbl_user' => $userID,
                    'tbl_row_material_idtbl_row_material' => $material_id,
                    'warehouse_location_name' => $warehouse,
                    'site_location' => $site_location
                );
                $this->db->insert('tbl_stock', $stock_data);
            }

            // Update batch stock
            $batchstock_data = array(
                'qty' => $accepted_qty,
                'balanceqty' => $accepted_qty,
                'batchnumber' => $batch_number,
                'status' => 1,
                'updatedatetime' => $current_datetime,
                'tbl_user_idtbl_user' => $userID,
                'tbl_row_material_idtbl_row_material' => $material_id,
                'warehouse_location_name' => $warehouse,
                'site_location' => $site_location
            );
            $this->db->insert('tbl_batchstock', $batchstock_data);

            // Update PO received quantity if it's a PO-based GRN
            if ($detail['grn_source'] == 'po' && $detail['ponumber']) {
                $this->db->set('received_qty', 'received_qty + ' . (float) $accepted_qty, FALSE);
                $this->db->where('tbl_porder_idtbl_porder', $detail['ponumber']);
                $this->db->where('tbl_row_material_idtbl_row_material', $material_id);
                $this->db->update('tbl_porder_detail');
            }
        }

        $this->db->where('idtbl_grn', $grn_id);
        $this->db->update('tbl_grn', array(
            'approval_status' => 'approved',
            'updatedatetime' => $current_datetime
        ));

        if (isset($details[0]['ponumber']) && $details[0]['ponumber']) {
            $this->checkPoCompletion($details[0]['ponumber']);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            $response = array(
                'status' => 0,
                'message' => 'Failed to approve GRN.',
                'type' => 'danger'
            );
        } else {
            $this->db->trans_commit();

            $response = array(
                'status' => 1,
                'message' => 'GRN approved successfully. Stock has been updated.',
                'type' => 'success'
            );
        }

        echo json_encode($response);
    }

    private function checkPoCompletion($po_id)
    {
        $this->db->select('COUNT(*) as total, 
                          SUM(CASE WHEN qty <= received_qty THEN 1 ELSE 0 END) as completed');
        $this->db->from('tbl_porder_detail');
        $this->db->where('tbl_porder_idtbl_porder', $po_id);
        $result = $this->db->get()->row();

        if ($result && $result->total == $result->completed) {
            // Update PO completion status
            $this->db->where('idtbl_porder', $po_id);
            $this->db->update('tbl_porder', array('completedstatus' => 1));
        }
    }

    public function GetPoDetailsForGrn($po_id)
    {
        $this->db->select('pd.tbl_row_material_idtbl_row_material as material_id,
                          rm.material_code as item_code,
                          rm.material_name,
                          m.measure_type as unit_of_measure,
                          pd.unitprice,
                          pd.qty as po_qty,
                          IFNULL(pd.received_qty, 0) as received_qty,
                          (pd.qty - IFNULL(pd.received_qty, 0)) as pending_qty,
                          pd.totalvalue');
        $this->db->from('tbl_porder_detail pd');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = pd.tbl_row_material_idtbl_row_material');
        $this->db->join('tbl_measurements m', 'm.idtbl_mesurements = rm.tbl_measurements_idtbl_measurements', 'left');
        $this->db->where('pd.tbl_porder_idtbl_porder', $po_id);

        return $this->db->get()->result_array();
    }

    public function GetCapacityInfo($site_location, $warehouse)
    {
        $this->db->select('r.idtbl_rack, r.rack_number, r.max_weight');
        $this->db->from('tbl_rack r');
        $this->db->where('r.idtbl_rack', $warehouse);
        $rack = $this->db->get()->row_array();

        $this->db->select('l.idtbl_location, l.location, l.capacity');
        $this->db->from('tbl_location l');
        $this->db->where('l.idtbl_location', $site_location);
        $location = $this->db->get()->row_array();

        $this->db->select('SUM(s.qty) as used_qty');
        $this->db->from('tbl_stock s');
        $this->db->where('s.warehouse_location_name', $warehouse);
        $this->db->where('s.site_location', $site_location);
        $stock = $this->db->get()->row_array();

        $used_qty = floatval($stock['used_qty'] ?? 0);
        $max_weight = floatval($rack['max_weight'] ?? 0);
        $available = $max_weight - $used_qty;
        $percent_used = $max_weight > 0 ? round(($used_qty / $max_weight) * 100, 1) : 0;

        return array(
            'location_name' => $location['location'] ?? 'N/A',
            'zone_name' => $rack['rack_number'] ?? 'N/A',
            'max_capacity' => $max_weight,
            'used_qty' => $used_qty,
            'available_qty' => $available,
            'percent_used' => $percent_used,
            'has_capacity' => $available > 0
        );
    }
}