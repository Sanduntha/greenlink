<?php
class Stockmovementinfo extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function GetWarehousesByLocation($location_id)
    {
        $this->db->select('idtbl_rack, rack_number as warehouse_code');
        $this->db->from('tbl_rack');
        $this->db->where('tbl_location_idtbl_location', $location_id);
        $this->db->where('status', 1);
        $this->db->order_by('warehouse_code', 'asc');
        return $this->db->get()->result_array();
    }

    public function GetAvailableMaterials($site_id, $zone_id)
    {
        $this->db->select('DISTINCT(m.idtbl_row_material), m.material_name');
        $this->db->from('tbl_batchstock bs');
        $this->db->join('tbl_row_material m', 'm.idtbl_row_material = bs.tbl_row_material_idtbl_row_material');
        $this->db->where('bs.site_location', $site_id);
        $this->db->where('bs.warehouse_location_name', $zone_id);
        $this->db->where('bs.status', 1);
        $this->db->where('bs.balanceqty >', 0);
        $this->db->order_by('m.material_name', 'asc');

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

    public function GetWarehouses()
    {
        $this->db->select('idtbl_rack, rack_number as warehouse_code');
        $this->db->from('tbl_rack');
        $this->db->where('status', 1);
        $this->db->order_by('warehouse_code', 'asc');
        return $this->db->get()->result_array();
    }

    public function GetMaterials()
    {
        $this->db->select('idtbl_row_material, material_name');
        $this->db->from('tbl_row_material');
        $this->db->where('status', 1);
        $this->db->order_by('material_name', 'asc');
        return $this->db->get()->result_array();
    }

    public function GetNextMovementId()
    {
        $this->db->select('movement_id');
        $this->db->from('tbl_movement');
        $this->db->order_by('idtbl_movement', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_record = $query->row();
            $last_movement = $last_record->movement_id;
            $number = (int) substr($last_movement, 4);
            $next_number = $number + 1;
            return 'MOV-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
        } else {
            return 'MOV-001';
        }
    }

    public function GetAvailableStock($site_id, $zone_id)
    {
        $this->db->select('bs.*, m.material_name');
        $this->db->from('tbl_batchstock bs');
        $this->db->join('tbl_row_material m', 'm.idtbl_row_material = bs.tbl_row_material_idtbl_row_material');
        $this->db->where('bs.site_location', $site_id);
        $this->db->where('bs.warehouse_location_name', $zone_id);
        $this->db->where('bs.status', 1);
        $this->db->where('bs.balanceqty >', 0);
        $this->db->order_by('m.material_name', 'asc');

        return $this->db->get()->result_array();
    }

    public function GetZoneCapacity($site_id, $zone_id)
    {


        // Get zone capacity from rack table
        $this->db->select(' max_weight as capacity');
        $this->db->from('tbl_rack');
        $this->db->where('idtbl_rack', $zone_id);
        $this->db->where('status', 1);
        $capacity_query = $this->db->get();
        $max_capacity = $capacity_query->row() ? $capacity_query->row()->capacity : 0;

        // Calculate used capacity
        $this->db->select('SUM(balanceqty) as used_capacity');
        $this->db->from('tbl_batchstock');
        $this->db->where('site_location', $site_id);
        $this->db->where('warehouse_location_name', $zone_id);
        $this->db->where('status', 1);
        $used_query = $this->db->get();
        $used_capacity = $used_query->row() ? $used_query->row()->used_capacity : 0;

        return array(
            'max_capacity' => $max_capacity,
            'used_capacity' => $used_capacity ? $used_capacity : 0
        );
    }

    public function GetMaterialBatches($material_id, $site_id, $zone_id)
    {
        $this->db->select('batchnumber, qty, balanceqty');
        $this->db->from('tbl_batchstock');
        $this->db->where('tbl_row_material_idtbl_row_material', $material_id);
        $this->db->where('site_location', $site_id);
        $this->db->where('warehouse_location_name', $zone_id);
        $this->db->where('status', 1);
        $this->db->where('balanceqty >', 0);
        $this->db->order_by('batchnumber', 'asc');

        return $this->db->get()->result_array();
    }

    public function SaveMovement()
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $movement_type = $this->input->post('movement_type');
        $movement_datetime = $this->input->post('movement_datetime');
        $from_site_location = $this->input->post('from_site_location');
        $from_zone = $this->input->post('from_zone');
        $to_site_location = $this->input->post('to_site_location');
        $to_zone = $this->input->post('to_zone');
        $vehicle_no = $this->input->post('vehicle_no');
        $moved_by = $this->input->post('moved_by');
        $reason = $this->input->post('reason');
        $items = $this->input->post('items');

        // Get next movement ID
        $movement_id = $this->GetNextMovementId();



        // Insert movement header
        $header_data = array(
            'movement_id' => $movement_id,
            'movement_type' => $movement_type,
            'movement_date' => $movement_datetime,
            'from_site_location_id' => $from_site_location,
            'from_zone_id' => $from_zone,
            'to_site_location_id' => $to_site_location,
            'to_zone_id' => $to_zone,
            'vehicle_no' => $vehicle_no,
            'moved_by' => $moved_by,
            'reason' => $reason,
            'status' => 2, // Pending
            'created_by' => $userID,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('tbl_movement', $header_data);
        $movement_pkid = $this->db->insert_id();

        // Insert movement details
        foreach ($items as $item) {
            $detail_data = array(
                'movement_id' => $movement_pkid,
                'material_id' => $item['material_id'],
                'material_name' => $item['material_name'],
                'batch_no' => $item['batch_no'],
                'quantity' => $item['quantity'],
                'status' => 2, // Pending
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('tbl_movement_details', $detail_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error saving movement', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('type' => 'success', 'status' => true, 'message' => 'Movement saved successfully.', 'movement_id' => $movement_id);
        }
    }


    public function GetMovementDetails($movement_id)
    {
        $this->db->select('m.*,
        fl.location as from_location_name,
        tl.location as to_location_name,
        fr.rack_number as from_zone_name,
        tr.rack_number as to_zone_name,
        cu.name as created_by_name,
        au.name as approved_by_name');

        $this->db->from('tbl_movement m');

        // Locations
        $this->db->join('tbl_location fl', 'fl.idtbl_location = m.from_site_location_id', 'left');
        $this->db->join('tbl_location tl', 'tl.idtbl_location = m.to_site_location_id', 'left');

        // Racks / Zones
        $this->db->join('tbl_rack fr', 'fr.idtbl_rack = m.from_zone_id', 'left');
        $this->db->join('tbl_rack tr', 'tr.idtbl_rack = m.to_zone_id', 'left');

        // Users
        $this->db->join('tbl_user cu', 'cu.idtbl_user = m.created_by', 'left');   // Created by
        $this->db->join('tbl_user au', 'au.idtbl_user = m.approved_by', 'left');  // Approved by

        $this->db->where('m.idtbl_movement', $movement_id);

        $header_query = $this->db->get();
        $header = $header_query->row_array();

        // Movement details
        $this->db->select('md.*, mat.material_name');
        $this->db->from('tbl_movement_details md');
        $this->db->join('tbl_row_material mat', 'mat.idtbl_row_material = md.material_id');
        $this->db->where('md.movement_id', $movement_id);

        $details_query = $this->db->get();
        $details = $details_query->result_array();

        return array(
            'header' => $header,
            'details' => $details
        );
    }

    public function GetMovementForEdit($movement_id)
    {
        $this->db->select('m.*');
        $this->db->from('tbl_movement m');
        $this->db->where('m.idtbl_movement', $movement_id);
        $this->db->where('m.status', 2);
        $header_query = $this->db->get();
        $header = $header_query->row_array();

        if (!$header) {
            return array('status' => false, 'message' => 'Movement not found or cannot be edited');
        }

        $this->db->select('md.*, mat.material_name');
        $this->db->from('tbl_movement_details md');
        $this->db->join('tbl_row_material mat', 'mat.idtbl_row_material = md.material_id');
        $this->db->where('md.movement_id', $movement_id);
        $items_query = $this->db->get();
        $items = $items_query->result_array();

        return array(
            'status' => true,
            'header' => $header,
            'items' => $items
        );
    }

    public function UpdateMovement()
    {
        $this->db->trans_begin();

        $movement_pkid = $this->input->post('movement_id');
        $movement_type = $this->input->post('movement_type');
        $movement_datetime = $this->input->post('movement_datetime');
        $from_site_location = $this->input->post('from_site_location');
        $from_zone = $this->input->post('from_zone');
        $to_site_location = $this->input->post('to_site_location');
        $to_zone = $this->input->post('to_zone');
        $vehicle_no = $this->input->post('vehicle_no');
        $moved_by = $this->input->post('moved_by');
        $reason = $this->input->post('reason');
        $items = $this->input->post('items');

        $userID = $_SESSION['userid'];
        $update_date = date('Y-m-d H:i:s');



        // Update movement header
        $header_data = array(
            'movement_type' => $movement_type,
            'movement_date' => $movement_datetime,
            'from_site_location_id' => $from_site_location,
            'from_zone_id' => $from_zone,
            'to_site_location_id' => $to_site_location,
            'to_zone_id' => $to_zone,
            'vehicle_no' => $vehicle_no,
            'moved_by' => $moved_by,
            'reason' => $reason,
            'updated_at' => $update_date
        );
        $this->db->where('idtbl_movement', $movement_pkid);
        $this->db->update('tbl_movement', $header_data);

        // Delete existing details
        $this->db->where('movement_id', $movement_pkid);
        $this->db->delete('tbl_movement_details');

        // Insert new details
        foreach ($items as $item) {
            $detail_data = array(
                'movement_id' => $movement_pkid,
                'material_id' => $item['material_id'],
                'material_name' => $item['material_name'],
                'batch_no' => $item['batch_no'],
                'quantity' => $item['quantity'],
                'status' => 2, // Pending
                'created_at' => $update_date
            );
            $this->db->insert('tbl_movement_details', $detail_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error updating movement', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Movement updated successfully', 'type' => 'success');
        }
    }


    public function ApproveMovement($movement_id)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $approval_date = date('Y-m-d H:i:s');


        $movement = $this->db->get_where('tbl_movement', ['idtbl_movement' => $movement_id])->row_array();




        $this->db->set('status', 1);
        $this->db->set('approved_by', $userID);
        $this->db->set('approved_at', $approval_date);
        $this->db->where('idtbl_movement', $movement_id);
        $this->db->update('tbl_movement');


        $this->db->set('status', 1);
        $this->db->where('movement_id', $movement_id);
        $this->db->update('tbl_movement_details');


        $items = $this->db->get_where('tbl_movement_details', ['movement_id' => $movement_id])->result_array();


        foreach ($items as &$item) {
            $item['from_site_location_id'] = $movement['from_site_location_id'];
            $item['from_zone_id'] = $movement['from_zone_id'];
            $item['to_site_location_id'] = $movement['to_site_location_id'];
            $item['to_zone_id'] = $movement['to_zone_id'];
        }
        unset($item);


        foreach ($items as $item) {
            if (!empty($item['from_site_location_id']) && !empty($item['from_zone_id'])) {
                $qty_change = -$item['quantity'];

                $stock = $this->db->get_where('tbl_stock', [
                    'tbl_row_material_idtbl_row_material' => $item['material_id'],
                    'site_location' => $item['from_site_location_id'],
                    'warehouse_location_name' => $item['from_zone_id']
                ])->row();

                if ($stock) {
                    $this->db->set('qty', $stock->qty + $qty_change);
                    $this->db->set('updatedatetime', $approval_date);
                    $this->db->set('tbl_user_idtbl_user', $userID);
                    $this->db->where('idtbl_stock', $stock->idtbl_stock);
                    $this->db->update('tbl_stock');
                }

                // Update tbl_batchstock
                $batch = $this->db->get_where('tbl_batchstock', [
                    'tbl_row_material_idtbl_row_material' => $item['material_id'],
                    'site_location' => $item['from_site_location_id'],
                    'warehouse_location_name' => $item['from_zone_id'],
                    'batchnumber' => $item['batch_no']
                ])->row();

                if ($batch) {
                    $new_qty = $batch->qty + $qty_change;
                    $new_balance = $batch->balanceqty + $qty_change;
                    $new_status = $new_balance > 0 ? 1 : 0;

                    $this->db->set('balanceqty', $new_balance);
                    $this->db->set('status', $new_status);
                    $this->db->set('updatedatetime', $approval_date);
                    $this->db->set('tbl_user_idtbl_user', $userID);
                    $this->db->where('idtbl_batchstock', $batch->idtbl_batchstock);
                    $this->db->update('tbl_batchstock');
                }
            }

            // --- Update Destination Stock & Batch ---
            if (!empty($item['to_site_location_id']) && !empty($item['to_zone_id'])) {
                $qty_change = $item['quantity'];

                // Update tbl_stock
                $stock = $this->db->get_where('tbl_stock', [
                    'tbl_row_material_idtbl_row_material' => $item['material_id'],
                    'site_location' => $item['to_site_location_id'],
                    'warehouse_location_name' => $item['to_zone_id']
                ])->row();

                if ($stock) {
                    $this->db->set('qty', $stock->qty + $qty_change);
                    $this->db->set('updatedatetime', $approval_date);
                    $this->db->set('tbl_user_idtbl_user', $userID);
                    $this->db->where('idtbl_stock', $stock->idtbl_stock);
                    $this->db->update('tbl_stock');
                } else {
                    $this->db->insert('tbl_stock', [
                        'tbl_row_material_idtbl_row_material' => $item['material_id'],
                        'site_location' => $item['to_site_location_id'],
                        'warehouse_location_name' => $item['to_zone_id'],
                        'qty' => $qty_change,
                        'status' => 1,
                        'updatedatetime' => $approval_date,
                        'tbl_user_idtbl_user' => $userID
                    ]);
                }

                // Update tbl_batchstock
                $batch = $this->db->get_where('tbl_batchstock', [
                    'tbl_row_material_idtbl_row_material' => $item['material_id'],
                    'site_location' => $item['to_site_location_id'],
                    'warehouse_location_name' => $item['to_zone_id'],
                    'batchnumber' => $item['batch_no']
                ])->row();

                if ($batch) {
                    $new_qty = $batch->qty + $qty_change;
                    $new_balance = $batch->balanceqty + $qty_change;

                    $this->db->set('qty', $new_qty);
                    $this->db->set('balanceqty', $new_balance);
                    $this->db->set('status', 1);
                    $this->db->set('updatedatetime', $approval_date);
                    $this->db->set('tbl_user_idtbl_user', $userID);
                    $this->db->where('idtbl_batchstock', $batch->idtbl_batchstock);
                    $this->db->update('tbl_batchstock');
                } else {
                    $this->db->insert('tbl_batchstock', [
                        'tbl_row_material_idtbl_row_material' => $item['material_id'],
                        'site_location' => $item['to_site_location_id'],
                        'warehouse_location_name' => $item['to_zone_id'],
                        'batchnumber' => $item['batch_no'],
                        'qty' => $qty_change,
                        'balanceqty' => $qty_change,
                        'status' => 1,
                        'updatedatetime' => $approval_date,
                        'tbl_user_idtbl_user' => $userID
                    ]);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['status' => false, 'message' => 'Error approving movement', 'type' => 'danger'];
        } else {
            $this->db->trans_commit();
            return ['status' => true, 'message' => 'Movement approved and stock updated successfully', 'type' => 'success'];
        }
    }

    public function DeleteMovement($movement_id)
    {
        $this->db->trans_begin();

        // Soft delete movement header
        $this->db->set('status', 0);
        $this->db->where('idtbl_movement', $movement_id);
        $this->db->update('tbl_movement');

        // Soft delete movement details
        $this->db->set('status', 0);
        $this->db->where('movement_id', $movement_id);
        $this->db->update('tbl_movement_details');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error deleting movement', 'type' => 'danger');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Movement deleted successfully', 'type' => 'success');
        }
    }

    private function GetLocationName($location_id)
    {
        $this->db->select('location');
        $this->db->from('tbl_location');
        $this->db->where('idtbl_location', $location_id);
        $query = $this->db->get();
        return $query->row() ? $query->row()->location : '';
    }

    private function GetZoneName($zone_id)
    {
        $this->db->select('rack_number');
        $this->db->from('tbl_rack');
        $this->db->where('idtbl_rack', $zone_id);
        $query = $this->db->get();
        return $query->row() ? $query->row()->rack_number : '';
    }
}