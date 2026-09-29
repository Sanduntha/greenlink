<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shipmentplaninginfo extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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
    public function GetCustomers()
    {
        $this->db->select('idtbl_customer, name');
        $this->db->from('tbl_customer');
        $this->db->where('status', 1);

        return $this->db->get()->result_array();
    }

    public function GetNextShipmentId()
    {
        $this->db->select('shipment_id');
        $this->db->from('tbl_shipmentplaning');
        $this->db->order_by('idtbl_shipmentplaning', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_record = $query->row();
            $last_shipment = $last_record->shipment_id;

            if (preg_match('/SHP-(\d+)/', $last_shipment, $matches)) {
                $number = (int) $matches[1];
                $next_number = $number + 1;
                return 'SHP-' . str_pad($next_number, 7, '0', STR_PAD_LEFT);
            }
        }

        return 'SHP-0000001';
    }

    public function GetNextInvoiceNo()
    {
        $this->db->select('invoice_number');
        $this->db->from('tbl_shipmentplaning');
        $this->db->where('invoice_number IS NOT NULL');
        $this->db->where('invoice_number !=', '');
        $this->db->order_by('idtbl_shipmentplaning', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_record = $query->row();
            $last_invoice = $last_record->invoice_number;

            // Check if invoice number matches pattern INV-YYYY-XXXXX
            if (preg_match('/INV-(\d{4})-(\d+)/', $last_invoice, $matches)) {
                $year = $matches[1];
                $number = (int) $matches[2];
                $current_year = date('Y');

                // If same year, increment number
                if ($year == $current_year) {
                    $next_number = $number + 1;
                    return 'INV-' . $current_year . '-' . str_pad($next_number, 5, '0', STR_PAD_LEFT);
                } else {
                    // New year, start from 1
                    return 'INV-' . $current_year . '-00001';
                }
            }
        }

        // First invoice
        return 'INV-' . date('Y') . '-00001';
    }


    public function GetAvailableStock()
    {
        $this->db->select('s.idtbl_stock, rm.material_name, s.qty as available_qty, s.warehouse_location_name, 
                          bs.batchnumber, bs.balanceqty');
        $this->db->from('tbl_stock s');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = s.tbl_row_material_idtbl_row_material');
        $this->db->join('tbl_batchstock bs', 'bs.tbl_row_material_idtbl_row_material = rm.idtbl_row_material AND bs.warehouse_location_name = s.warehouse_location_name', 'left');
        $this->db->where('s.status', 1);
        $this->db->where('s.qty >', 0);
        $this->db->order_by('rm.material_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function ProcessShipment()
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $current_date = date('Y-m-d H:i:s');
        $invoice_number = $this->GetNextInvoiceNo();

        $header_data = array(
            'shipment_id' => $this->input->post('shipment_id'),
            'shipment_date' => $this->input->post('shipment_date'),
            'shipment_time' => $this->input->post('shipment_time'),
            'shipment_status' => 'Draft',
            'supervisor' => $this->input->post('supervisor'),
            'loading_location' => $this->input->post('loading_location'),
            'exporter' => $this->input->post('exporter'),
            'buyer' => $this->input->post('buyer'),
            'consignee' => $this->input->post('consignee'),
            'country' => $this->input->post('country'),
            'handling_company' => $this->input->post('handling_company'),
            'freight_company' => $this->input->post('freight_company'),
            'vehicle_no' => $this->input->post('vehicle_no'),
            'transporter' => $this->input->post('transporter'),
            'driver_name' => $this->input->post('driver_name'),
            'driver_nic' => $this->input->post('driver_nic'),
            'driver_tp' => $this->input->post('driver_tp'),
            'container_id' => $this->input->post('container_id'),
            'container_number' => $this->input->post('container_number'),
            'seal_no' => $this->input->post('seal_no'),
            'container_size' => $this->input->post('container_size'),
            'cusdec_number' => $this->input->post('cusdec_number'),
            'cusdec_date' => $this->input->post('cusdec_date'),
            'cusdec_net_weight' => $this->input->post('cusdec_net_weight'),
            'invoice_number' => $invoice_number,
            'status' => 2,
            'created_by' => $userID,
            'created_at' => $current_date
        );

        $this->db->insert('tbl_shipmentplaning', $header_data);
        $shipment_id = $this->db->insert_id();

        $items = $this->input->post('items');
        $total_weight = 0;
        $total_items = 0;

        foreach ($items as $item) {
            $detail_data = array(
                'shipment_id' => $shipment_id,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'quantity_unit' => $item['quantity_unit'],
                'material_type' => $item['material_type'],
                'weight' => $item['weight'],
                'batch_no' => $item['batch_no'] ?? null,
                'stock_id' => $item['stock_id'] ?? null,
                'status' => 1
            );
            $this->db->insert('tbl_shipmentplaning_details', $detail_data);

            $total_weight += $item['weight'];
            $total_items++;
        }

        $this->db->set('total_weight', $total_weight);
        $this->db->set('total_items', $total_items);
        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $this->db->update('tbl_shipmentplaning');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error saving shipment', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Shipment saved successfully', 'type' => 'success', 'shipment_id' => $shipment_id);
        }
    }

    public function GetShipmentRecords()
    {
        $this->db->select('sp.*, u.username as created_by_name');
        $this->db->from('tbl_shipmentplaning sp');
        $this->db->join('tbl_user u', 'u.idtbl_user = sp.created_by', 'left');
        $this->db->where('sp.status !=', 0);
        $this->db->order_by('sp.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }
    public function UpdateShipmentStatus()
    {
        $shipment_id = $this->input->post('shipment_id');
        $shipment_status = $this->input->post('shipment_status');
        $userID = $_SESSION['userid'];
        $update_date = date('Y-m-d H:i:s');

        $this->db->set('shipment_status', $shipment_status);
        $this->db->set('updated_by', $userID);
        $this->db->set('updated_at', $update_date);

        if ($shipment_status == 'Dispatched') {
            $this->db->set('dispatched_by', $userID);
            $this->db->set('dispatched_at', $update_date);
        }

        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $this->db->update('tbl_shipmentplaning');

        if ($this->db->affected_rows() > 0) {
            return array('status' => true, 'message' => 'Shipment status updated successfully', 'type' => 'success');
        } else {
            return array('status' => false, 'message' => 'Error updating shipment status', 'type' => 'danger');
        }
    }

    public function GetShipmentDetails($shipment_id)
    {
        // Get header with related data
        $this->db->select('sp.*, 
        u.username as created_by_name,
        e.fullname as supervisor_name,
        c.name as buyer_name,
        l.location as loading_location_name');

        $this->db->from('tbl_shipmentplaning sp');
        $this->db->join('tbl_user u', 'u.idtbl_user = sp.created_by', 'left');
        $this->db->join('tbl_employee e', 'e.idtbl_employee = sp.supervisor', 'left');
        $this->db->join('tbl_customer c', 'c.idtbl_customer = sp.buyer', 'left');
        $this->db->join('tbl_location l', 'l.idtbl_location = sp.loading_location', 'left');
        $this->db->where('sp.idtbl_shipmentplaning', $shipment_id);
        $header_query = $this->db->get();
        $header = $header_query->row_array();

        // Get items
        $this->db->select('*');
        $this->db->from('tbl_shipmentplaning_details');
        $this->db->where('shipment_id', $shipment_id);
        $items_query = $this->db->get();
        $items = $items_query->result_array();

        return array(
            'header' => $header,
            'items' => $items
        );
    }

    public function GetShipmentForEdit($shipment_id)
    {
        $this->db->select('sp.*');
        $this->db->from('tbl_shipmentplaning sp');
        $this->db->where('sp.idtbl_shipmentplaning', $shipment_id);
        $this->db->where('sp.status', 2);
        $header_query = $this->db->get();
        $header = $header_query->row_array();

        if (!$header) {
            return array('status' => false, 'message' => 'Shipment not found or cannot be edited');
        }

        $this->db->select('*');
        $this->db->from('tbl_shipmentplaning_details');
        $this->db->where('shipment_id', $shipment_id);
        $items_query = $this->db->get();
        $items = $items_query->result_array();

        return array(
            'status' => true,
            'header' => $header,
            'items' => $items
        );
    }

    public function UpdateShipment()
    {
        $this->db->trans_begin();

        $shipment_id = $this->input->post('shipment_id');
        $userID = $_SESSION['userid'];

        $existing_invoice = '';
        $this->db->select('invoice_number');
        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $query = $this->db->get('tbl_shipmentplaning');
        if ($query->num_rows() > 0) {
            $existing_invoice = $query->row()->invoice_number;
        }


        $header_data = array(
            'shipment_date' => $this->input->post('shipment_date'),
            'shipment_time' => $this->input->post('shipment_time'),
            'shipment_status' => 'Draft',
            'supervisor' => $this->input->post('supervisor'),
            'loading_location' => $this->input->post('loading_location'),
            'exporter' => $this->input->post('exporter'),
            'buyer' => $this->input->post('buyer'),
            'consignee' => $this->input->post('consignee'),
            'country' => $this->input->post('country'),
            'handling_company' => $this->input->post('handling_company'),
            'freight_company' => $this->input->post('freight_company'),
            'vehicle_no' => $this->input->post('vehicle_no'),
            'transporter' => $this->input->post('transporter'),
            'driver_name' => $this->input->post('driver_name'),
            'driver_nic' => $this->input->post('driver_nic'),
            'driver_tp' => $this->input->post('driver_tp'),
            'container_id' => $this->input->post('container_id'),
            'container_number' => $this->input->post('container_number'),
            'seal_no' => $this->input->post('seal_no'),
            'container_size' => $this->input->post('container_size'),
            'cusdec_number' => $this->input->post('cusdec_number'),
            'cusdec_date' => $this->input->post('cusdec_date'),
            'cusdec_net_weight' => $this->input->post('cusdec_net_weight'),
            'invoice_number' => $existing_invoice,
        );

        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $this->db->update('tbl_shipmentplaning', $header_data);

        $this->db->where('shipment_id', $shipment_id);
        $this->db->delete('tbl_shipmentplaning_details');

        $items = $this->input->post('items');
        $total_weight = 0;
        $total_items = 0;

        foreach ($items as $item) {
            $detail_data = array(
                'shipment_id' => $shipment_id,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'quantity_unit' => $item['quantity_unit'],
                'material_type' => $item['material_type'],
                'weight' => $item['weight'],
                'batch_no' => $item['batch_no'] ?? null,
                'stock_id' => $item['stock_id'] ?? null,
                'status' => 1
            );
            $this->db->insert('tbl_shipmentplaning_details', $detail_data);

            $total_weight += $item['weight'];
            $total_items++;
        }

        $this->db->set('total_weight', $total_weight);
        $this->db->set('total_items', $total_items);
        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $this->db->update('tbl_shipmentplaning');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error updating shipment', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Shipment updated successfully', 'type' => 'success');
        }
    }

    public function ApproveShipment($shipment_id)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $approval_date = date('Y-m-d H:i:s');

        $this->db->set('status', 1);
        $this->db->set('shipment_status', 'Ready');
        $this->db->set('approved_by', $userID);
        $this->db->set('approved_at', $approval_date);
        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $this->db->update('tbl_shipmentplaning');

        $this->db->select('sd.*, s.tbl_row_material_idtbl_row_material, s.warehouse_location_name');
        $this->db->from('tbl_shipmentplaning_details sd');
        $this->db->join('tbl_stock s', 's.idtbl_stock = sd.stock_id', 'left');
        $this->db->where('sd.shipment_id', $shipment_id);
        $query = $this->db->get();
        $shipment_items = $query->result_array();

        foreach ($shipment_items as $item) {
            if ($item['stock_id'] && $item['tbl_row_material_idtbl_row_material']) {
                $this->db->set('qty', 'qty - ' . $item['quantity'], FALSE);
                $this->db->set('updatedatetime', $approval_date);
                $this->db->set('tbl_user_idtbl_user', $userID);
                $this->db->where('idtbl_stock', $item['stock_id']);
                $this->db->update('tbl_stock');

                if ($item['batch_no']) {
                    $this->db->set('balanceqty', 'balanceqty - ' . $item['quantity'], FALSE);
                    $this->db->set('updatedatetime', $approval_date);
                    $this->db->set('tbl_user_idtbl_user', $userID);
                    $this->db->where('tbl_row_material_idtbl_row_material', $item['tbl_row_material_idtbl_row_material']);
                    $this->db->where('warehouse_location_name', $item['warehouse_location_name']);
                    $this->db->where('batchnumber', $item['batch_no']);
                    $this->db->update('tbl_batchstock');
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error approving shipment', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Shipment approved successfully', 'type' => 'success');
        }
    }

    public function RejectShipment($shipment_id)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $rejection_date = date('Y-m-d H:i:s');

        $this->db->set('status', 3);
        $this->db->set('rejected_by', $userID);
        $this->db->set('rejected_at', $rejection_date);
        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $this->db->update('tbl_shipmentplaning');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error rejecting shipment', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Shipment rejected successfully', 'type' => 'success');
        }
    }

    public function DeleteShipment($shipment_id)
    {
        $this->db->trans_begin();

        $this->db->set('status', 0);
        $this->db->where('idtbl_shipmentplaning', $shipment_id);
        $this->db->update('tbl_shipmentplaning');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error deleting shipment', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Shipment deleted successfully', 'type' => 'success');
        }
    }
}