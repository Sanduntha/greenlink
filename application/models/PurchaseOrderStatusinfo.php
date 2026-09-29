<?php
class PurchaseOrderStatusinfo extends CI_Model
{


    public function Completeoder($x, $y)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $recordID = $x;
        $type = $y;
        $updatedatetime = date('Y-m-d H:i:s');

        if ($type == 1) {
            $data = array(
                'completedstatus' => '1',
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_porder', $recordID);
            $this->db->update('tbl_porder', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-check';
                $actionObj->title = '';
                $actionObj->message = 'Porder is Complete';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'success';

                $actionJSON = json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Purchaseorderstatus');
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
                redirect('Purchaseorderstatus');
            }
        }

    }
    public function Notcompleteoder($x, $y)
    {
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $recordID = $x;
        $type = $y;
        $updatedatetime = date('Y-m-d H:i:s');

        if ($type == 1) {
            $data = array(
                'completedstatus' => '0',
                'updatedatetime' => $updatedatetime
            );

            $this->db->where('idtbl_porder', $recordID);
            $this->db->update('tbl_porder', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-check';
                $actionObj->title = '';
                $actionObj->message = 'Porder is Not complete';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'warning';

                $actionJSON = json_encode($actionObj);

                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Purchaseorderstatus');
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
                redirect('Purchaseorderstatus');
            }
        }

    }

    public function getPorderDetails($id)
{
    $this->db->select('p.*, s.name');
    $this->db->from('tbl_porder p');
    $this->db->join('tbl_supplier s', 'p.tbl_supplier_idtbl_supplier = s.idtbl_supplier', 'left');
    $this->db->where('p.idtbl_porder', $id);
    $order = $this->db->get()->row_array();

    if (!$order) {
        echo json_encode([
            'success' => false,
            'message' => 'Purchase order not found'
        ]);
        exit; 
    }

    $this->db->select('pod.*, rm.material_name');
    $this->db->from('tbl_porder_detail pod');
    $this->db->join('tbl_row_material rm', 'pod.tbl_row_material_idtbl_row_material = rm.idtbl_row_material', 'left');
    $this->db->where('pod.tbl_porder_idtbl_porder', $id);
    $items = $this->db->get()->result_array();

    echo json_encode([
        'success' => true,
        'data' => [
            'idtbl_porder' => $order['idtbl_porder'],
            'podate' => $order['podate'],
            'total' => $order['total'],
            'completedstatus' => $order['completedstatus'],
            'remarks' => $order['remarks'],
            'suppliername' => $order['name'],
            'items' => $items
        ]
    ]);
    exit; 
}






}

