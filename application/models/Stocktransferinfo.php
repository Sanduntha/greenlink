<?php
class Stocktransferinfo extends CI_Model
{
    public function GetMaterialsByLocation($location)
    {
        $this->db->distinct();
        $this->db->select('rm.idtbl_row_material, rm.material_name');
        $this->db->from('tbl_batchstock bs');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = bs.tbl_row_material_idtbl_row_material');
        $this->db->where('bs.warehouse_location_name', $location);
        $this->db->where('bs.balanceqty >', 0); 
        $this->db->where('bs.status', 1);
        $this->db->order_by('rm.material_name', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetBatchesByMaterial($material_id, $location)
    {
        $this->db->select('batchnumber, balanceqty as qty');
        $this->db->from('tbl_batchstock');
        $this->db->where('tbl_row_material_idtbl_row_material', $material_id);
        $this->db->where('warehouse_location_name', $location);
        $this->db->where('status', 1);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return array(
                array(
                    'batch_number' => $row->batchnumber,
                    'qty' => $row->qty
                )
            );
        } else {
            return array(
                array(
                    'batch_number' => '',
                    'qty' => 0
                )
            );
        }
    }

    public function GetPendingTransfers()
    {
        $this->db->select('st.id, st.transfer_date, l1.locationname as from_location, l2.locationname as to_location, st.status');
        $this->db->from('tbl_stock_transfer st');
        $this->db->join('tbl_warehouse_location l1', 'l1.locationid = (SELECT from_location FROM tbl_stock_transfer_details WHERE transfer_id = st.id LIMIT 1)');
        $this->db->join('tbl_warehouse_location l2', 'l2.locationid = (SELECT to_location FROM tbl_stock_transfer_details WHERE transfer_id = st.id LIMIT 1)');
        $this->db->where('st.status', 2);
        $this->db->order_by('st.transfer_date', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetTransferDetails($transfer_id)
    {
        // Get regular transfer details with location names
        $this->db->select('std.*, rm.material_name, 
                       fl.location as from_location_name, 
                       tl.location as to_location_name');
        $this->db->from('tbl_stock_transfer_details std');
        $this->db->join('tbl_row_material rm', 'rm.idtbl_row_material = std.material_id');
        $this->db->join('tbl_location fl', 'fl.idtbl_location = std.from_location', 'left');
        $this->db->join('tbl_location tl', 'tl.idtbl_location = std.to_location', 'left');
        $this->db->where('std.transfer_id', $transfer_id);
        $regular_details = $this->db->get()->result_array();

        return array(
            'regular' => $regular_details,
        );
    }

    public function ProcessTransfer()
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $transfer_date = date('Y-m-d H:i:s');
        $items = $this->input->post('items');

        $header_data = array(
            'transfer_date' => $transfer_date,
            'status' => 2,
            'created_by' => $userID,
            'created_at' => $transfer_date
        );
        $this->db->insert('tbl_stock_transfer', $header_data);
        $transfer_id = $this->db->insert_id();

        foreach ($items as $item) {
            $detail_data = array(
                'transfer_id' => $transfer_id,
                'from_location' => $item['from_location'],
                'to_location' => $item['to_location'],
                'material_id' => $item['material_id'],
                'batch_no' => $item['batch_no'],
                'quantity' => $item['qty'],
                'status' => 2,
                'created_at' => $transfer_date
            );
            $this->db->insert('tbl_stock_transfer_details', $detail_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error saving transfer');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Transfer saved successfully. Waiting for approval.', 'transfer_id' => $transfer_id);
        }
    }

    public function ApproveTransfer($transfer_id)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $approval_date = date('Y-m-d H:i:s');

        $this->db->set('status', 1);
        $this->db->set('approved_by', $userID);
        $this->db->set('approved_at', $approval_date);
        $this->db->where('id', $transfer_id);
        $this->db->update('tbl_stock_transfer');

        $regular_items = $this->db->get_where('tbl_stock_transfer_details', array('transfer_id' => $transfer_id))->result_array();

        foreach ($regular_items as $item) {
            $this->db->set('status', 1);
            $this->db->where('id', $item['id']);
            $this->db->update('tbl_stock_transfer_details');

            // Update source stock
            $this->db->set('qty', 'qty - ' . $item['quantity'], FALSE);
            $this->db->where('tbl_row_material_idtbl_row_material', $item['material_id']);
            $this->db->where('warehouse_location_name', $item['from_location']);
            $this->db->update('tbl_stock');

            // Update destination stock
            $this->db->where('tbl_row_material_idtbl_row_material', $item['material_id']);
            $this->db->where('warehouse_location_name', $item['to_location']);
            $query = $this->db->get('tbl_stock');

            if ($query->num_rows() > 0) {
                $this->db->set('qty', 'qty + ' . $item['quantity'], FALSE);
                $this->db->where('tbl_row_material_idtbl_row_material', $item['material_id']);
                $this->db->where('warehouse_location_name', $item['to_location']);
                $this->db->update('tbl_stock');
            } else {
                $stock_data = array(
                    'tbl_row_material_idtbl_row_material' => $item['material_id'],
                    'warehouse_location_name' => $item['to_location'],
                    'qty' => $item['quantity'],
                    'status' => 1,
                    'updatedatetime' => $approval_date,
                    'tbl_user_idtbl_user' => $userID
                );
                $this->db->insert('tbl_stock', $stock_data);
            }

            // Get batch stocks from source location
            $this->db->where('tbl_row_material_idtbl_row_material', $item['material_id']);
            $this->db->where('warehouse_location_name', $item['from_location']);
            $this->db->where('status', 1);
            $this->db->order_by('updatedatetime', 'ASC');
            $source_batches = $this->db->get('tbl_batchstock')->result_array();

            $remaining_qty = $item['quantity'];

            foreach ($source_batches as $batch) {
                if ($remaining_qty <= 0)
                    break;

                $deduct_qty = min($remaining_qty, $batch['balanceqty']);

                if ($deduct_qty > 0) {
                    // Update source batch balance
                    $new_balance = $batch['balanceqty'] - $deduct_qty;
                    $new_total_qty = $batch['qty'] - $deduct_qty;
                    $batch_status = $new_balance > 0 ? 1 : 0;

                    $this->db->set('balanceqty', $new_balance);
                    $this->db->set('qty', $new_total_qty);
                    $this->db->set('status', $batch_status);
                    $this->db->set('updatedatetime', $approval_date);
                    $this->db->set('tbl_user_idtbl_user', $userID);
                    $this->db->where('idtbl_batchstock', $batch['idtbl_batchstock']);
                    $this->db->update('tbl_batchstock');

                    // Check if batch exists in destination location
                    $this->db->where('tbl_row_material_idtbl_row_material', $item['material_id']);
                    $this->db->where('warehouse_location_name', $item['to_location']);
                    $this->db->where('batchnumber', $batch['batchnumber']);
                    $dest_batch_query = $this->db->get('tbl_batchstock');

                    if ($dest_batch_query->num_rows() > 0) {
                        // Update existing batch in destination
                        $dest_batch = $dest_batch_query->row();

                        $new_dest_balance = $dest_batch->balanceqty + $deduct_qty;
                        $new_dest_qty = $dest_batch->qty + $deduct_qty; 

                        $this->db->set('balanceqty', $new_dest_balance);
                        $this->db->set('qty', $new_dest_qty); 
                        $this->db->set('status', 1);
                        $this->db->set('updatedatetime', $approval_date);
                        $this->db->set('tbl_user_idtbl_user', $userID);
                        $this->db->where('idtbl_batchstock', $dest_batch->idtbl_batchstock);
                        $this->db->update('tbl_batchstock');
                    } else {
                        // Create new batch record in destination
                        $batch_dest_data = array(
                            'tbl_row_material_idtbl_row_material' => $item['material_id'],
                            'warehouse_location_name' => $item['to_location'],
                            'batchnumber' => $batch['batchnumber'],
                            'qty' => $deduct_qty,
                            'balanceqty' => $deduct_qty,
                            'status' => 1,
                            'updatedatetime' => $approval_date,
                            'tbl_user_idtbl_user' => $userID
                        );
                        $this->db->insert('tbl_batchstock', $batch_dest_data);
                    }

                    $remaining_qty -= $deduct_qty;
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error approving transfer');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Transfer approved successfully');
        }
    }

    public function RejectTransfer($transfer_id)
    {
        $this->db->trans_begin();

        // Update transfer header status to rejected
        $this->db->set('status', 0);
        $this->db->where('id', $transfer_id);
        $this->db->update('tbl_stock_transfer');

        // Update regular items status to rejected
        $this->db->set('status', 0);
        $this->db->where('transfer_id', $transfer_id);
        $this->db->update('tbl_stock_transfer_details');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error rejecting transfer');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Transfer rejected successfully');
        }
    }

    public function UpdateTransfer()
    {
        $this->db->trans_begin();

        $transfer_id = $this->input->post('transfer_id');
        $items_json = $this->input->post('items');

        $items = json_decode($items_json, true);
        if (!is_array($items)) {
            return array('status' => false, 'message' => 'Invalid items data');
        }

        // Delete existing transfer details
        $this->db->where('transfer_id', $transfer_id);
        $this->db->delete('tbl_stock_transfer_details');

        $userID = $_SESSION['userid'];
        $update_date = date('Y-m-d H:i:s');

        foreach ($items as $item) {
            $detail_data = array(
                'transfer_id' => $transfer_id,
                'from_location' => $item['from_location'],
                'to_location' => $item['to_location'],
                'material_id' => $item['material_id'],
                'batch_no' => $item['batch_no'],
                'quantity' => $item['qty'],
                'status' => 2,
                'created_at' => $update_date
            );
            $this->db->insert('tbl_stock_transfer_details', $detail_data);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => false, 'message' => 'Error updating transfer');
        } else {
            $this->db->trans_commit();
            return array('status' => true, 'message' => 'Transfer updated successfully');
        }
    }
}