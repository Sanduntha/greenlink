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
        $this->db->select('idtbl_employee, fullname, empno, status, designation');
        $this->db->from('tbl_employee');
        $this->db->where('status', 1);
        $this->db->group_start();
        $this->db->like('designation', 'supervisor');
        $this->db->or_like('designation', 'manager');
        $this->db->group_end();
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
		if (!$this->RequirePendingGrn($grn_id, 'edit')) {
    return;
}

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
		if (!$this->RequirePendingGrn($grn_id, 'remove')) {
    return;
}

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
		if (!$this->RequirePendingGrn($grn_id, 'statuschange')) {
    return;
}

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


// Check whether the logged-in user has permission to access or edit GRN.
	public function HasGrnPermission($permission = 'access_status')
{
    if (!$this->session->userdata('loggedin')
        || !$this->session->userdata('userid')
        || !in_array(
            $permission,
            array('access_status', 'edit', 'statuschange', 'remove'),
            true
        )) {
        return false;
    }
// Check the user's permission from the database.
    return $this->db->where(array(
        'tbl_user_idtbl_user' =>
            (int) $this->session->userdata('userid'),
        'tbl_menu_list_idtbl_menu_list' => 14,
        'status' => 1,
        'access_status' => 1,
        $permission => 1
    ))->count_all_results('tbl_user_privilege') > 0;
}
//update-- Allow existing-stock editing only when the feature is enabled, the GRN exists, the GRN is approved, its status is 1 or 2, and the user has edit permission.
public function CanEditExistingStock($grn)
{
    return $grn
        && $this->config->item('existing_stock_edit_enabled') === true
        && $grn['approval_status'] === 'approved'
        && in_array((int) $grn['status'], array(1, 2), true)
        && $this->HasGrnPermission('edit');
}
// Get previous stock changes made for this GRN.
public function GetExistingStockHistory($grnId)
{
    return $this->db
        ->select('l.*, u.name AS user_name, m.material_name')
        ->from('tbl_stock_adjustment_log l')
        ->join('tbl_user u', 'u.idtbl_user = l.user_id', 'left')
        ->join(
            'tbl_row_material m',
            'm.idtbl_row_material = l.material_id',
            'left'
        )
        ->where('l.grn_id', (int) $grnId)
        ->order_by('l.id', 'DESC')
        ->get()->result_array();
}

// Calculate quantity differences using integer hundredths.
private function ExistingStockUnits($value)
{
    if (!is_scalar($value)
        || !preg_match(
            '/^([0-9]{1,12})(?:\.([0-9]+))?$/D',
            (string) $value,
            $match
        )) {
        throw new RuntimeException(
            'Enter a valid non-negative quantity.'
        );
    }

    $fraction = isset($match[2]) ? rtrim($match[2], '0') : '';

    if (strlen($fraction) > 2) {
        throw new RuntimeException(
            'Quantities must use at most two decimal places.'
        );
    }

    return ((int) $match[1] * 100)
        + (int) str_pad($fraction, 2, '0', STR_PAD_RIGHT);
}

// Convert quantity into integer value to make calculations safely.// Example: 10.25 becomes 1025.
// Convert integer quantity back to normal decimal format.
private function ExistingStockDecimal($units)
{
    $absolute = abs($units);

    return ($units < 0 ? '-' : '')
        . intdiv($absolute, 100) . '.'
        . str_pad((string) ($absolute % 100), 2, '0', STR_PAD_LEFT);
}

private function ExistingStockQuery($sql, $bindings = array())
{
    $query = $this->db->query($sql, $bindings);

    if ($query === false) {
        throw new RuntimeException(
            'Database operation failed. Please try again.'
        );
    }

    return $query;
}

private function ExistingStockUpdate($table, $key, $id, $values)
{
    if (!$this->db->where($key, $id)->update($table, $values)) {
        throw new RuntimeException(
            'Database update failed. Please try again.'
        );
    }
}

public function UpdateExistingStockQuantities($grnId, $items, $reason)
{
    if (!is_array($items) || !$items
        || strlen($reason) > 255 || $reason === '') {
        return array(
            'status' => 0,
            'message' => 'Provide items and a reason of at most 255 characters.'
        );
    }

    $oldDebug = $this->db->db_debug;
    $this->db->db_debug = false;

    try {
        if (!$this->db->trans_begin()) {
            throw new RuntimeException(
                'Could not start the stock update.'
            );
        }

        $grn = $this->ExistingStockQuery(
            'SELECT * FROM tbl_grn
             WHERE idtbl_grn = ? FOR UPDATE',
            array($grnId)
        )->row_array();

        if (!$this->CanEditExistingStock($grn)) {
            throw new RuntimeException(
                'This approved GRN cannot be edited.'
            );
        }
		// If this GRN came from a PO, make sure the linked PO exists. If it does,
		// load all details of that PO and lock them while the current operation is running
		// does not check a specific GRN ID ; it uses the PO number stored inside the current $grn.

        $poDetails = array();
        if ($grn['grn_source'] === 'po') {
            if (empty($grn['ponumber']) || !$this->ExistingStockQuery(
                'SELECT idtbl_porder FROM tbl_porder
                 WHERE idtbl_porder = ? FOR UPDATE',
                array($grn['ponumber'])
            )->row_array()) {
                throw new RuntimeException('The linked purchase order is missing.');
            }

            $poDetails = $this->ExistingStockQuery(
                'SELECT * FROM tbl_porder_detail
                 WHERE tbl_porder_idtbl_porder = ?
                 ORDER BY idtbl_porder_detail FOR UPDATE',
                array($grn['ponumber'])
            )->result_array();
        }

        $details = $this->ExistingStockQuery(
            'SELECT * FROM tbl_grndetail
             WHERE tbl_grn_idtbl_grn = ?
             ORDER BY idtbl_grndetail FOR UPDATE',
            array($grnId)
        )->result_array();

        $submitted = array();

        foreach ($items as $item) {
            if (!is_array($item)
                || !isset(
                    $item['detail_id'],
                    $item['old_qty'],
                    $item['qty']
                )
                || !ctype_digit((string) $item['detail_id'])
                || (int) $item['detail_id'] < 1) {
                throw new RuntimeException('Invalid item data.');
            }

            $id = (int) $item['detail_id'];

            if (isset($submitted[$id])) {
                throw new RuntimeException('Duplicate item submitted.');
            }

            $submitted[$id] = $item;
        }

        if (count($submitted) !== count($details)) {
            throw new RuntimeException(
                'The item list changed. Reload the GRN.'
            );
        }

        $now = date('Y-m-d H:i:s');
        $userId = (int) $this->session->userdata('userid');
        $changes = 0;

        foreach ($details as $detail) {
            $detailId = (int) $detail['idtbl_grndetail'];

            if (!isset($submitted[$detailId])) {
                throw new RuntimeException(
                    'An item does not belong to this GRN.'
                );
            }

            $old = $this->ExistingStockUnits($detail['accepted_qty']);
            $expected = $this->ExistingStockUnits(
                $submitted[$detailId]['old_qty']
            );
            $new = $this->ExistingStockUnits(
                $submitted[$detailId]['qty']
            );

            if ($old !== $expected) {
                throw new RuntimeException(
                    'Another user changed this GRN. Reload before saving.'
                );
            }

            $delta = $new - $old;

            if ($delta === 0) {
                continue;
            }

            $stockKey = array(
                $detail['tbl_row_material_idtbl_row_material'],
                $detail['warehouse_location_name'],
                $grn['site_location']
            );

            $stocks = $this->ExistingStockQuery(
                'SELECT * FROM tbl_stock
                 WHERE tbl_row_material_idtbl_row_material = ?
                   AND warehouse_location_name = ?
                   AND site_location = ?
                 FOR UPDATE',
                $stockKey
            )->result_array();

            $batchKey = $stockKey;
            $batchKey[] = $detail['batch_number'];

            $batches = $this->ExistingStockQuery(
                'SELECT * FROM tbl_batchstock
                 WHERE tbl_row_material_idtbl_row_material = ?
                   AND warehouse_location_name = ?
                   AND site_location = ?
                   AND batchnumber = ?
                 FOR UPDATE',
                $batchKey
            )->result_array();

            if (count($stocks) !== 1 || count($batches) !== 1) {
                throw new RuntimeException(
                    'Stock or batch record is missing or ambiguous. Check the stock data.'
                );
            }

            $stock = $stocks[0];
            $batch = $batches[0];

            $before = $this->ExistingStockUnits($stock['qty']);
            $after = $before + $delta;

            $batchQty =
                $this->ExistingStockUnits($batch['qty']) + $delta;

            $batchBalance =
                $this->ExistingStockUnits($batch['balanceqty']) + $delta;

            if ($after < 0 || $batchQty < 0 || $batchBalance < 0) {
                throw new RuntimeException(
                    'This reduction exceeds the available stock or batch balance.'
                );
            }

            $quantity = $this->ExistingStockDecimal($new);
// Updates GRN item quantities, validates purchase order limits, adjusts PO received quantities,
//  and keeps stock receipt data consistent during corrections.
            $detailValues = array(
                'accepted_qty' => $quantity,
                'total' => round(
                    ($new / 100) * (float) $detail['unitprice'],
                    2
                ),
                'updatedatetime' => $now
            );

            if ($grn['grn_source'] === 'po') {
                $matches = array();
                foreach ($poDetails as $index => $poDetail) {
                    if ((int) $poDetail['tbl_row_material_idtbl_row_material']
                        === (int) $detail['tbl_row_material_idtbl_row_material']) {
                        $matches[] = $index;
                    }
                }

                if (count($matches) !== 1) {
                    throw new RuntimeException(
                        'Purchase order item is missing or ambiguous.'
                    );
                }

                $index = $matches[0];
                $poReceived = $this->ExistingStockUnits(
                    $poDetails[$index]['received_qty'] ?? '0'
                ) + $delta;

                if ($poReceived < 0
                    || $poReceived > $this->ExistingStockUnits($poDetails[$index]['qty'])) {
                    throw new RuntimeException(
                        'The correction exceeds the purchase order quantity or received balance.'
                    );
                }

                $poDetails[$index]['received_qty'] = $this->ExistingStockDecimal($poReceived);
                $this->ExistingStockUpdate(
                    'tbl_porder_detail',
                    'idtbl_porder_detail',
                    $poDetails[$index]['idtbl_porder_detail'],
                    array('received_qty' => $poDetails[$index]['received_qty'])
                );

                // Preserve ordered and rejected quantities on PO receipts.
                $detailValues['received_qty'] = $this->ExistingStockDecimal(
                    $new + $this->ExistingStockUnits($detail['rejected_qty'])
                );
            } else {
                $detailValues['qty'] = $quantity;
                $detailValues['po_qty'] = $quantity;
                $detailValues['received_qty'] = $quantity;
                $detailValues['rejected_qty'] = 0;
            }

            $this->ExistingStockUpdate(
                'tbl_grndetail',
                'idtbl_grndetail',
                $detailId,
                $detailValues
            );

            $this->ExistingStockUpdate(
                'tbl_stock',
                'idtbl_stock',
                $stock['idtbl_stock'],
                array(
                    'qty' => $this->ExistingStockDecimal($after),
                    'status' => $after > 0 ? 1 : 0,
                    'updatedatetime' => $now
                )
            );

            $this->ExistingStockUpdate(
                'tbl_batchstock',
                'idtbl_batchstock',
                $batch['idtbl_batchstock'],
                array(
                    'qty' => $this->ExistingStockDecimal($batchQty),
                    'balanceqty' =>
                        $this->ExistingStockDecimal($batchBalance),
                    'status' => $batchBalance > 0 ? 1 : 0,
                    'updatedatetime' => $now
                )
            );

            if (!$this->db->insert(
                'tbl_stock_adjustment_log',
                array(
                    'grn_id' => $grnId,
                    'grn_detail_id' => $detailId,
                    'material_id' =>
                        $detail['tbl_row_material_idtbl_row_material'],
                    'site_location' => $grn['site_location'],
                    'warehouse' => $detail['warehouse_location_name'],
                    'batch_number' => $detail['batch_number'],
                    'old_qty' => $this->ExistingStockDecimal($old),
                    'new_qty' => $quantity,
                    'adjustment_qty' =>
                        $this->ExistingStockDecimal($delta),
                    'stock_before' =>
                        $this->ExistingStockDecimal($before),
                    'stock_after' =>
                        $this->ExistingStockDecimal($after),
                    'action' => $delta > 0
                        ? 'Manually Added' : 'Manually Reduced',
                    'reason' => $reason,
                    'user_id' => $userId,
                    'created_at' => $now
                )
            )) {
                throw new RuntimeException(
                    'Could not save the adjustment log.'
                );
            }

            $changes++;
        }

		// Checks whether all purchase order items are fully received,
		// then updates the PO completed status to complete or incomplete.

        if ($changes > 0) {
            if ($grn['grn_source'] === 'po') {
                $completed = count($poDetails) > 0;
                foreach ($poDetails as $poDetail) {
                    if ($this->ExistingStockUnits($poDetail['received_qty'] ?? '0')
                        < $this->ExistingStockUnits($poDetail['qty'])) {
                        $completed = false;
                        break;
                    }
                }
                $this->ExistingStockUpdate(
                    'tbl_porder',
                    'idtbl_porder',
                    $grn['ponumber'],
                    array('completedstatus' => $completed ? 1 : 0)
                );
            }

            $total = $this->ExistingStockQuery(
                'SELECT COALESCE(SUM(total), 0) AS total
                 FROM tbl_grndetail
                 WHERE tbl_grn_idtbl_grn = ?',
                array($grnId)
            )->row()->total;

            $this->ExistingStockUpdate(
                'tbl_grn',
                'idtbl_grn',
                $grnId,
                array(
                    'total' => $total,
                    'updatedatetime' => $now
                )
            );
        }

        if (!$this->db->trans_status()
            || !$this->db->trans_commit()) {
            throw new RuntimeException(
                'Could not complete the stock update.'
            );
        }

        return array(
            'status' => 1,
            'message' => $changes
                ? 'Quantities, stock and adjustment history updated.'
                : 'No quantity changes.'
        );
    } catch (Exception $exception) {
        $this->db->trans_rollback();

        return array(
            'status' => 0,
            'message' => $exception->getMessage()
        );
    } finally {
        $this->db->db_debug = $oldDebug;
    }
}

private function RequirePendingGrn($grnId, $permission)
{
    $query = $this->db->query(
        'SELECT approval_status FROM tbl_grn
         WHERE idtbl_grn = ? FOR UPDATE',
        array($grnId)
    );

    $grn = $query ? $query->row_array() : null;

    if (!$grn || $grn['approval_status'] !== 'pending'
        || !$this->HasGrnPermission($permission)) {
        $this->db->trans_rollback();

        echo json_encode(array(
            'status' => 0,
            'type' => 'danger',
            'message' =>
                'This operation is allowed only for a pending GRN with the required permission.'
        ));

        return false;
    }

    return true;
}





}
