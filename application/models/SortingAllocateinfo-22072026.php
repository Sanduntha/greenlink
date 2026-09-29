<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SortingAllocateinfo extends CI_Model
{
    public function GetGrn()
    {
        $this->db->select('idtbl_grn, idtbl_grn as grn_number');
        $this->db->from('tbl_grn');
        $this->db->where('status', 1);
        $this->db->order_by('idtbl_grn', 'DESC');
        return $this->db->get();
    }

    public function GetMaterialsByGrn($grn_id)
    {
        $this->db->distinct();
        $this->db->select('m.idtbl_row_material, m.material_name');
        $this->db->from('tbl_grndetail gd');
        $this->db->join('tbl_row_material m', 'm.idtbl_row_material = gd.tbl_row_material_idtbl_row_material');
        $this->db->where('gd.tbl_grn_idtbl_grn', $grn_id);
        $this->db->where('gd.status', 1);
        $this->db->where('m.status', 1);
        return $this->db->get()->result_array();
    }

    // public function GetBatchLocations($material_id, $grn_id = null)
    // {
    //     $this->db->select('bs.idtbl_batchstock, bs.balanceqty, bs.batchnumber, bs.warehouse_location_name, l.location AS location_name');
    //     $this->db->from('tbl_batchstock bs');
    //     $this->db->join(
    //         'tbl_location l',
    //         'l.idtbl_location = bs.warehouse_location_name',
    //         'left'
    //     );
    //     $this->db->where('bs.tbl_row_material_idtbl_row_material', $material_id);
    //     $this->db->where('bs.balanceqty >', 0);
    //     $this->db->where('bs.status', 1);

    //     if ($grn_id) {

    //         $this->db->join('tbl_grndetail gd', 'gd.batch_number = bs.batchnumber', 'left');
    //         $this->db->where('gd.tbl_grn_idtbl_grn', $grn_id);
    //     }


    //     return $this->db->get()->result_array();
    // }
    public function GetBatchLocations($material_id, $grn_id = null)
    {
        $this->db->distinct();

        $this->db->select('
        bs.idtbl_batchstock, 
        bs.balanceqty, 
        bs.batchnumber,
        bs.site_location               AS site_location_id,
        l.idtbl_location,
        l.location                     AS location_name,
        bs.warehouse_location_name     AS rack_id,
        r.idtbl_rack,
        r.rack_number                  AS rack_number
    ');

        $this->db->from('tbl_batchstock bs');
        $this->db->join('tbl_location l', 'l.idtbl_location = bs.site_location', 'left');
        $this->db->join('tbl_rack r', 'r.idtbl_rack     = bs.warehouse_location_name', 'left');

        $this->db->where('bs.tbl_row_material_idtbl_row_material', $material_id);
        $this->db->where('bs.balanceqty >', 0);
        $this->db->where('bs.status', 1);

        if ($grn_id) {
            $this->db->where("EXISTS (
            SELECT 1 
            FROM tbl_grndetail gd 
            WHERE gd.batch_number = bs.batchnumber 
            AND gd.tbl_grn_idtbl_grn = " . $this->db->escape($grn_id) . "
        )", null, false);
        }

        return $this->db->get()->result_array();
    }


    public function GetBatchInfo($batch_id)
    {
        $this->db->select('
        bs.balanceqty,
        bs.batchnumber,
        bs.warehouse_location_name AS rack_id,
        r.rack_number,
        bs.site_location AS site_location_id,
        l.location AS site_location_name
    ');
        $this->db->from('tbl_batchstock bs');
        $this->db->join('tbl_rack r', 'r.idtbl_rack = bs.warehouse_location_name', 'left');
        $this->db->join('tbl_location l', 'l.idtbl_location = bs.site_location', 'left');
        $this->db->where('bs.idtbl_batchstock', $batch_id);
        return $this->db->get()->row_array();
    }

    public function SaveAllocation()
    {
        $this->db->trans_begin();

        $user_id = $_SESSION['userid'];
        $insert_datetime = date('Y-m-d H:i:s');


        $items = $this->input->post('items');

        if (!$items || !is_array($items)) {
            return [
                'success' => false,
                'message' => 'No items received in POST'
            ];
        }

        foreach ($items as $item) {


            if (empty($item['rack_id']) || empty($item['site_location_id'])) {
                $this->db->trans_rollback();
                return ['success' => false, 'message' => 'Missing location/rack reference'];
            }

            $data = [
                'grn_id' => $item['grn_id'],
                'material_id' => $item['material_id'],
                'batch_id' => $item['batch_id'],
                'batch_number' => $item['batch_number'],
                'rack_id' => $item['rack_id'],
                'site_location_id' => $item['site_location_id'],
                'qty' => $item['qty'],
                'status' => 1,
                'remarks' => $item['remarks'] ?? null,
                'insertdatetime' => $insert_datetime,
                'tbl_user_idtbl_user' => $user_id
            ];

            $this->db->insert('tbl_allocation', $data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['success' => false, 'message' => 'Failed to save allocation'];
        }

        $this->db->trans_commit();
        return ['success' => true, 'message' => 'Allocation saved successfully'];
    }


    public function GetAllocations($status = 1)
    {
        $this->db->select('a.*, g.idtbl_grn as grn_number, m.material_name, bs.batchnumber, bs.warehouse_location_name');
        $this->db->from('tbl_allocation a');
        $this->db->join('tbl_grn g', 'g.idtbl_grn = a.grn_id');
        $this->db->join('tbl_row_material m', 'm.idtbl_row_material = a.material_id');
        $this->db->join('tbl_batchstock bs', 'bs.idtbl_batchstock = a.batch_id', 'left');
        $this->db->where('a.status', $status);
        $this->db->order_by('a.insertdatetime', 'DESC');

        return $this->db->get()->result_array();
    }

    public function DeleteAllocation($allocation_id)
    {
        $this->db->trans_begin();

        $this->db->where('idtbl_allocation', $allocation_id);
        $this->db->where('status', 1);
        $this->db->delete('tbl_allocation');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => false, 'message' => 'Failed to delete allocation');
        } else {
            $this->db->trans_commit();
            return array('success' => true, 'message' => 'Allocation deleted successfully');
        }
    }


    public function UpdateAllocation($allocation_id)
    {
        $this->db->trans_begin();

        $user_id = $_SESSION['userid'];
        $update_datetime = date('Y-m-d H:i:s');

        $data = array(
            'qty' => $this->input->post('qty'),
            'remarks' => $this->input->post('remarks'),
            'updatedatetime' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        );

        $this->db->where('idtbl_allocation', $allocation_id);
        $this->db->where('status', 1);
        $this->db->update('tbl_allocation', $data);


        $history_data = array(
            'allocation_id' => $allocation_id,
            'action' => 'Edit',
            'remarks' => 'Allocation updated: Qty=' . $this->input->post('qty'),
            'action_date' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        );
        $this->db->insert('tbl_allocation_history', $history_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => false, 'message' => 'Failed to update allocation');
        } else {
            $this->db->trans_commit();
            return array('success' => true, 'message' => 'Allocation updated successfully');
        }
    }

    public function ApproveAllocation($allocation_id)
    {
        $this->db->trans_begin();

        $user_id = $_SESSION['userid'];
        $update_datetime = date('Y-m-d H:i:s');


        $this->db->select('a.*, bs.balanceqty as current_balance');
        $this->db->from('tbl_allocation a');
        $this->db->join('tbl_batchstock bs', 'bs.idtbl_batchstock = a.batch_id');
        $this->db->where('a.idtbl_allocation', $allocation_id);
        $allocation = $this->db->get()->row_array();

        $rack_id = $allocation['rack_id'];
        $site_id = $allocation['site_location_id'];

        if (!$allocation || $allocation['status'] != 1) {
            return array('success' => false, 'message' => 'Allocation not found or not in pending status');
        }


        if ($allocation['qty'] > $allocation['current_balance']) {
            return array('success' => false, 'message' => 'Insufficient quantity in batch');
        }


        $this->db->where('idtbl_allocation', $allocation_id);
        $this->db->update('tbl_allocation', array(
            'status' => 2,
            'updatedatetime' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        ));


        $new_balance = $allocation['current_balance'] - $allocation['qty'];
        $this->db->where('idtbl_batchstock', $allocation['batch_id']);
        $this->db->update('tbl_batchstock', array(
            'balanceqty' => $new_balance,
            'updatedatetime' => $update_datetime
        ));


        $this->db->set('qty', 'qty - ' . $allocation['qty'], FALSE);
        $this->db->where('tbl_row_material_idtbl_row_material', $allocation['material_id']);
        $this->db->where('warehouse_location_name', $rack_id);
        $this->db->where('site_location', $site_id);
        $this->db->update('tbl_stock');


        $history_data = array(
            'allocation_id' => $allocation_id,
            'action' => 'Approve',
            'remarks' => 'Allocation approved, batch updated',
            'action_date' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        );
        $this->db->insert('tbl_allocation_history', $history_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => false, 'message' => 'Failed to approve allocation');
        } else {
            $this->db->trans_commit();
            return array('success' => true, 'message' => 'Allocation approved successfully');
        }
    }

    public function RejectAllocation($allocation_id)
    {
        $this->db->trans_begin();

        $user_id = $_SESSION['userid'];
        $update_datetime = date('Y-m-d H:i:s');


        $this->db->where('idtbl_allocation', $allocation_id);
        $this->db->update('tbl_allocation', array(
            'status' => 3,
            'updatedatetime' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        ));


        $history_data = array(
            'allocation_id' => $allocation_id,
            'action' => 'Reject',
            'remarks' => $this->input->post('reject_reason') ?: 'Allocation rejected',
            'action_date' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        );
        $this->db->insert('tbl_allocation_history', $history_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => false, 'message' => 'Failed to reject allocation');
        } else {
            $this->db->trans_commit();
            return array('success' => true, 'message' => 'Allocation rejected successfully');
        }
    }


    // public function GetAllocationDetails($allocation_id)
    // {
    //     $this->db->select('a.*, m.material_name');
    //     $this->db->from('tbl_allocation a');
    //     $this->db->join('tbl_row_material m', 'm.idtbl_row_material = a.material_id', 'left');
    //     $this->db->where('a.idtbl_allocation', $allocation_id);
    //     $this->db->where('a.status', 1);
    //     $result = $this->db->get()->row_array();

    //     return $result;
    // }
    public function GetAllocationDetails($allocation_id)
    {
        $this->db->select('
        a.*,
        m.material_name,
        r.rack_number,
        l.location AS site_location_name
    ');
        $this->db->from('tbl_allocation a');
        $this->db->join('tbl_row_material m', 'm.idtbl_row_material = a.material_id', 'left');
        $this->db->join('tbl_rack r', 'r.idtbl_rack = a.rack_id', 'left');
        $this->db->join('tbl_location l', 'l.idtbl_location = a.site_location_id', 'left');
        $this->db->where('a.idtbl_allocation', $allocation_id);
        $this->db->where('a.status', 1);

        $result = $this->db->get()->row_array();
        return $result;
    }


    public function UpdateSortingComplete($allocation_id)
    {
        $this->db->trans_begin();

        $user_id = $_SESSION['userid'];
        $update_datetime = date('Y-m-d H:i:s');


        $this->db->select('sorting_complete');
        $this->db->from('tbl_allocation');
        $this->db->where('idtbl_allocation', $allocation_id);
        $current = $this->db->get()->row_array();

        $new_status = ($current['sorting_complete'] == 0) ? 1 : 0;


        $data = array(
            'sorting_complete' => $new_status,
            'updatedatetime' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        );

        $this->db->where('idtbl_allocation', $allocation_id);
        $this->db->update('tbl_allocation', $data);


        $action = ($new_status == 1) ? 'Sorting Complete' : 'Sorting Incomplete';
        $history_data = array(
            'allocation_id' => $allocation_id,
            'action' => 'Sorting Status',
            'remarks' => $action . ' updated',
            'action_date' => $update_datetime,
            'tbl_user_idtbl_user' => $user_id
        );
        $this->db->insert('tbl_allocation_history', $history_data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('success' => false, 'message' => 'Failed to update sorting status');
        } else {
            $this->db->trans_commit();
            return array('success' => true, 'message' => 'Sorting status updated successfully', 'new_status' => $new_status);
        }
    }
}