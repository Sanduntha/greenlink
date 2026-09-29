<?php
class Purchaseorderinfo extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function GetSupplierAddress($supplier_id)
    {
        $this->db->select('address_line1, address_line2, city, state');
        $this->db->from('tbl_supplier');
        $this->db->where('idtbl_supplier', $supplier_id);
        $this->db->where('status', 1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return null;
    }

    public function GetMaterialsBySupplier($supplier_id)
    {
        $this->db->select('idtbl_row_material, material_name, material_code, unitprice');
        $this->db->from('tbl_row_material');
        $this->db->where('tbl_supplier_idtbl_supplier', $supplier_id);
        $this->db->where('status', 1);
        $query = $this->db->get();

        return $query->result();
    }

    public function GetMaterialDetails($material_id)
    {
        $this->db->select('m.*, ms.measure_type as unit_of_measure');
        $this->db->from('tbl_row_material m');
        $this->db->join('tbl_measurements ms', 'm.tbl_measurements_idtbl_measurements = ms.idtbl_mesurements', 'left');
        $this->db->where('m.idtbl_row_material', $material_id);
        $this->db->where('m.status', 1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return null;
    }

    public function GetUnitpriceAccoMaterial()
    {
        $recordID = $this->input->post('recordID');

        $this->db->select('unitprice');
        $this->db->from('tbl_row_material');
        $this->db->where('status', 1);
        $this->db->where('idtbl_row_material', $recordID);
        $respond = $this->db->get();

        echo json_encode($respond->result());
    }

    public function GeneratePONumber()
    {
        $this->db->select('MAX(CAST(SUBSTRING(ponumber, 4) AS UNSIGNED)) as last_number');
        $this->db->from('tbl_porder');
        $this->db->like('ponumber', 'PO-', 'after');
        $query = $this->db->get();
        $result = $query->row();

        $new_number = (isset($result->last_number) && $result->last_number) ? $result->last_number + 1 : 1001;

        return 'PO-' . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    public function Porderinsert()
    {
        $this->db->trans_begin();
        $userID = $_SESSION['userid'];

        $tableData = $this->input->post('tableData');
        $ponumber = $this->input->post('ponumber');
        $orderdate = $this->input->post('orderdate');
        $total = floatval($this->input->post('total'));
        $remark = $this->input->post('remark');
        $supplier = $this->input->post('supplier');
        $supplieraddress = $this->input->post('supplieraddress');
        $deliverylocationid = $this->input->post('deliverylocationid');
        $deliverylocation = $this->input->post('deliverylocation');
        $expecteddeliverydate = $this->input->post('expecteddeliverydate');
        $paymentterms = $this->input->post('paymentterms');
        $updatedatetime = date('Y-m-d H:i:s');

        // Check if PO number already exists
        $this->db->where('ponumber', $ponumber);
        $existing = $this->db->get('tbl_porder')->num_rows();

        if ($existing > 0) {
            return array(
                'status' => 0,
                'action' => array(
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => 'Error',
                    'message' => 'PO Number already exists',
                    'type' => 'danger'
                )
            );
        }

        if (empty($tableData)) {
            return array(
                'status' => 0,
                'action' => array(
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => 'Error',
                    'message' => 'Please add at least one item',
                    'type' => 'danger'
                )
            );
        }

        $data = array(
            'ponumber' => $ponumber,
            'podate' => $orderdate,
            'total' => $total,
            'status' => 1,
            'confirmedstatus' => 0,
            'insertdatetime' => $updatedatetime,
            'tbl_user_idtbl_user' => $userID,
            'remarks' => $remark,
            'tbl_supplier_idtbl_supplier' => $supplier,
            'supplier_address' => $supplieraddress,
            'delivery_location_id' => $deliverylocationid,
            'delivery_location_text' => $deliverylocation,
            'expected_delivery_date' => $expecteddeliverydate,
            'payment_terms' => $paymentterms
        );

        $this->db->insert('tbl_porder', $data);
        $porderid = $this->db->insert_id();

        foreach ($tableData as $row) {
            $dataone = array(
                'qty' => floatval($row['qty']),
                'unitprice' => floatval($row['unitprice']),
                'totalvalue' => floatval($row['total']),
                'discount' => floatval($row['discount'] ?? 0),
                'tax_amount' => floatval($row['taxamount'] ?? 0),
                'material_code' => $row['materialcode'] ?? '',
                'unit_of_measure' => $row['unitofmeasure'] ?? '',
                'tbl_row_material_idtbl_row_material' => $row['materialid'],
                'tbl_porder_idtbl_porder' => $porderid,
                'status' => 1
            );
            $this->db->insert('tbl_porder_detail', $dataone);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'status' => 0,
                'action' => array(
                    'icon' => 'fas fa-exclamation-triangle',
                    'title' => 'Error',
                    'message' => 'Error creating purchase order',
                    'type' => 'danger'
                )
            );
        } else {
            $this->db->trans_commit();
            return array(
                'status' => 1,
                'action' => array(
                    'icon' => 'fas fa-check',
                    'title' => 'Success',
                    'message' => 'Purchase Order Created Successfully',
                    'type' => 'success'
                )
            );
        }
    }

  
    public function GetPorderDetails($id)
    {
        $this->db->select('tbl_porder.*, 
                         tbl_supplier.name as supplier_name,
                         tbl_location.location');
        $this->db->from('tbl_porder');
        $this->db->join('tbl_supplier', 'tbl_porder.tbl_supplier_idtbl_supplier = tbl_supplier.idtbl_supplier', 'left');
        $this->db->join('tbl_location', 'tbl_porder.delivery_location_id = tbl_location.idtbl_location', 'left');
        $this->db->where('tbl_porder.idtbl_porder', $id);
        $this->db->where('tbl_porder.status', 1);

        $order = $this->db->get()->row_array();

        if (!$order) {
            return array(
                'order' => null,
                'items' => array()
            );
        }

        // Clean up the delivery location text
        if (isset($order['delivery_location_text'])) {
            $order['delivery_location_text'] = trim($order['delivery_location_text']);
        }

        $this->db->select('tbl_porder_detail.*, tbl_row_material.material_name, tbl_row_material.material_code');
        $this->db->from('tbl_porder_detail');
        $this->db->join('tbl_row_material', 'tbl_porder_detail.tbl_row_material_idtbl_row_material = tbl_row_material.idtbl_row_material', 'left');
        $this->db->where('tbl_porder_detail.tbl_porder_idtbl_porder', $id);
        $this->db->where('tbl_porder_detail.status', 1);

        $items = $this->db->get()->result_array();

        return array(
            'order' => $order,
            'items' => $items
        );
    }

    public function GetPorderDetailsForEdit($order_id)
    {
        return $this->GetPorderDetails($order_id);
    }

    public function PorderdetailsView($recordID)
    {
        
        $this->db->select('tbl_porder.*, 
        tbl_supplier.name AS supplier_name,
        tbl_supplier_type.type AS supplier_category,
        tbl_location.location AS warehouse_name');

        $this->db->from('tbl_porder');
        $this->db->join('tbl_supplier', 'tbl_porder.tbl_supplier_idtbl_supplier = tbl_supplier.idtbl_supplier', 'left');
        $this->db->join('tbl_location', 'tbl_porder.delivery_location_id = tbl_location.idtbl_location', 'left');
        $this->db->join(
            'tbl_supplier_type',
            'tbl_supplier.tbl_supplier_type_idtbl_supplier_type = tbl_supplier_type.idtbl_supplier_type',
            'left'
        );

        $this->db->where('tbl_porder.idtbl_porder', $recordID);
        $this->db->where('tbl_porder.status', 1);
        $order = $this->db->get()->row_array();

        if (!$order) {
            return '<div class="alert alert-danger">Purchase Order not found</div>';
        }

       
        $this->db->select('tbl_porder_detail.*, 
        tbl_row_material.material_name,
        tbl_row_material.material_code,
        tbl_material_main_cat.categoryname AS material_category');

        $this->db->from('tbl_porder_detail');
        $this->db->join(
            'tbl_row_material',
            'tbl_porder_detail.tbl_row_material_idtbl_row_material = tbl_row_material.idtbl_row_material',
            'left'
        );
        $this->db->join(
            'tbl_material_main_cat',
            'tbl_row_material.tbl_material_main_cat_idtbl_material_main_cat = tbl_material_main_cat.idtbl_material_main_cat',
            'left'
        );

        $this->db->where('tbl_porder_detail.tbl_porder_idtbl_porder', $recordID);
        $this->db->where('tbl_porder_detail.status', 1);
        $items = $this->db->get()->result_array();

        
        $html = '<div class="container-fluid">';

     
        $html .= '<h5 class="mb-3">PO HEADER</h5>';
        $html .= '<div class="row mb-2">
        <div class="col-md-3"><strong>PO No:</strong> ' . htmlspecialchars($order['ponumber']) . '</div>
        <div class="col-md-3"><strong>PO Date:</strong> ' . date('Y-m-d', strtotime($order['podate'])) . '</div>
        <div class="col-md-3"><strong>Status:</strong> ' . ($order['confirmedstatus'] ? 'Confirmed' : 'Draft') . '</div>
        <div class="col-md-3"><strong>Payment:</strong> ' . ($order['paymentmethod'] ?? 'N/A') . '</div>
    </div>';

        
        $html .= '<div class="row mb-2">
        <div class="col-md-3"><strong>Supplier:</strong> ' . $order['supplier_name'] . '</div>
        <div class="col-md-3"><strong>Category:</strong> ' . ($order['supplier_category'] ?? 'N/A') . '</div>
        <div class="col-md-3"><strong>Warehouse:</strong> ' . ($order['warehouse_name'] ?? $order['delivery_location_text']) . '</div>
        <div class="col-md-3"><strong>Expected Delivery:</strong> ' . date('Y-m-d', strtotime($order['expected_delivery_date'])) . '</div>
    </div>';

       
        $html .= '<div class="row mb-3">
        <div class="col-md-6"><strong>Supplier Address:</strong><br>' . nl2br($order['supplier_address']) . '</div>
        <div class="col-md-3"><strong>Payment Terms:</strong> ' . $order['payment_terms'] . '</div>
        <div class="col-md-3"><strong>Remarks:</strong> ' . ($order['remarks'] ?? 'N/A') . '</div>
    </div>';

        /* ===== ITEMS TABLE ===== */
        $html .= '<hr><h5 class="mb-3">PO ITEM DETAILS</h5>';

        $html .= '<table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Material</th>
                <th>Code</th>
                <th>UOM</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>';

        $subtotal = $total_discount = $total_tax = 0;
        $i = 1;

        if ($items) {
            foreach ($items as $item) {
                $subtotal += $item['totalvalue'];
                $total_discount += $item['discount'] ?? 0;
                $total_tax += $item['tax_amount'] ?? 0;

                $html .= '<tr>
                <td>' . $i++ . '</td>
                <td>' . ($item['material_category'] ?? 'N/A') . '</td>
                <td>' . $item['material_name'] . '</td>
                <td>' . $item['material_code'] . '</td>
                <td>' . $item['unit_of_measure'] . '</td>
                <td class="text-right">' . number_format($item['qty'], 2) . '</td>
                <td class="text-right">' . number_format($item['unitprice'], 2) . '</td>
                <td class="text-right">' . number_format($item['discount'] ?? 0, 2) . '</td>
                <td class="text-right">' . number_format($item['tax_amount'] ?? 0, 2) . '</td>
                <td class="text-right">' . number_format($item['totalvalue'], 2) . '</td>
            </tr>';
            }
        } else {
            $html .= '<tr><td colspan="10" class="text-center">No items found</td></tr>';
        }

        $html .= '</tbody></table>';

        /* ===== TOTALS ===== */
        $html .= '<div class="row mt-3">
        <div class="col-md-4 offset-md-8">
            <p><strong>Sub Total:</strong> ' . number_format($subtotal, 2) . '</p>
            
            <p><strong>Total Value:</strong> ' . number_format($order['total'], 2) . '</p>
        </div>
    </div>';

       
        $html .= '<hr><div class="row">
        <div class="col-md-3"><strong>Approval:</strong> ' . ($order['confirmedstatus'] ? 'Approved' : 'Pending') . '</div>
        <div class="col-md-3"><strong>Created:</strong> ' . date('Y-m-d H:i:s', strtotime($order['insertdatetime'])) . '</div>';

        if ($order['updatedatetime']) {
            $html .= '<div class="col-md-3"><strong>Updated:</strong> ' . date('Y-m-d H:i:s', strtotime($order['updatedatetime'])) . '</div>';
        }

        $html .= '</div></div>';

        return $html;
    }



    public function Porderupdate()
    {
        $this->db->trans_begin();
        $userID = $_SESSION['userid'];

        $order_id = $this->input->post('order_id');
        $tableData = $this->input->post('tableData');
        $orderdate = $this->input->post('orderdate');
        $total = floatval($this->input->post('total'));
        $remark = $this->input->post('remark');
        $supplier = $this->input->post('supplier');
        $supplieraddress = $this->input->post('supplieraddress');
        $deliverylocationid = $this->input->post('deliverylocationid');
        $deliverylocation = $this->input->post('deliverylocation');
        $expecteddeliverydate = $this->input->post('expecteddeliverydate');
        $paymentterms = $this->input->post('paymentterms');
        $updatedatetime = date('Y-m-d H:i:s');

        if (empty($tableData)) {
            return array(
                'status' => 0,
                'message' => 'Please add at least one item'
            );
        }

        $data = array(
            'podate' => $orderdate,
            'total' => $total,
            'updatedatetime' => $updatedatetime,
            'remarks' => $remark,
            'tbl_supplier_idtbl_supplier' => $supplier,
            'supplier_address' => $supplieraddress,
            'delivery_location_id' => $deliverylocationid,
            'delivery_location_text' => $deliverylocation,
            'expected_delivery_date' => $expecteddeliverydate,
            'payment_terms' => $paymentterms
        );

        $this->db->where('idtbl_porder', $order_id);
        $this->db->update('tbl_porder', $data);

        // Delete existing items
        $this->db->where('tbl_porder_idtbl_porder', $order_id);
        $this->db->delete('tbl_porder_detail');

        // Insert new items
        foreach ($tableData as $row) {
            $dataone = array(
                'qty' => floatval($row['qty']),
                'unitprice' => floatval($row['unitprice']),
                'totalvalue' => floatval($row['total']),
                'discount' => floatval($row['discount'] ?? 0),
                'tax_amount' => floatval($row['taxamount'] ?? 0),
                'material_code' => $row['materialcode'] ?? '',
                'unit_of_measure' => $row['unitofmeasure'] ?? '',
                'tbl_row_material_idtbl_row_material' => $row['materialid'],
                'tbl_porder_idtbl_porder' => $order_id,
                'status' => 1
            );
            $this->db->insert('tbl_porder_detail', $dataone);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'status' => 0,
                'message' => 'Error updating purchase order'
            );
        } else {
            $this->db->trans_commit();
            return array(
                'status' => 1,
                'message' => 'Purchase Order Updated Successfully'
            );
        }
    }

    public function Porderdelete($id)
    {
        $this->db->trans_begin();

        
        $this->db->select('confirmedstatus');
        $this->db->from('tbl_porder');
        $this->db->where('idtbl_porder', $id);
        $order = $this->db->get()->row();

        if (!$order) {
            $this->db->trans_rollback();
            return array('status' => 0, 'message' => 'Purchase order not found');
        }

        if ($order->confirmedstatus == 1) {
            $this->db->trans_rollback();
            return array('status' => 0, 'message' => 'Cannot delete a confirmed purchase order');
        }

        
        $this->db->where('tbl_porder_idtbl_porder', $id);
        $this->db->update('tbl_porder_detail', ['status' => 0]);

        
        $this->db->where('idtbl_porder', $id);
        $this->db->update('tbl_porder', ['status' => 0]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => 0, 'message' => 'Error deleting purchase order');
        } else {
            $this->db->trans_commit();
            return array('status' => 1, 'message' => 'Purchase Order Deleted Successfully');
        }
    }

    public function ConfirmPorder($id, $status)
    {
        $this->db->trans_begin();

        $data = array(
            'confirmedstatus' => $status,
            'updatedatetime' => date('Y-m-d H:i:s')
        );

        $this->db->where('idtbl_porder', $id);
        $this->db->update('tbl_porder', $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}