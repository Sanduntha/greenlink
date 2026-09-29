<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class SortingAllocate extends CI_Controller
{
    public function index()
    {
        $this->load->model('Commeninfo');
        $this->load->model('SortingAllocateinfo');
        $result['grnlist'] = $this->SortingAllocateinfo->GetGrn();
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->view('sortingallocate', $result);
    }

    public function get_materials_by_grn($grn_id)
    {
        $this->load->model('SortingAllocateinfo');
        $materials = $this->SortingAllocateinfo->GetMaterialsByGrn($grn_id);
        echo json_encode($materials);
    }

    public function get_batch_locations($material_id, $grn_id = null)
    {
        $this->load->model('SortingAllocateinfo');
        $batch_locations = $this->SortingAllocateinfo->GetBatchLocations($material_id, $grn_id);
        echo json_encode($batch_locations);
    }

    public function get_batch_qty($batch_id)
    {
        $this->load->model('SortingAllocateinfo');
        $batch_info = $this->SortingAllocateinfo->GetBatchInfo($batch_id);
        echo json_encode($batch_info);
    }

    public function save_allocation()
    {
        $this->load->model('SortingAllocateinfo');
        $result = $this->SortingAllocateinfo->SaveAllocation();
        echo json_encode($result);
    }

    public function get_allocations($status = 1)
    {
        $this->load->model('SortingAllocateinfo');
        $allocations = $this->SortingAllocateinfo->GetAllocations($status);
        echo json_encode($allocations);
    }

    public function delete_allocation($allocation_id)
    {
        $this->load->model('SortingAllocateinfo');
        $result = $this->SortingAllocateinfo->DeleteAllocation($allocation_id);
        echo json_encode($result);
    }

    public function update_allocation($allocation_id)
    {
        $this->load->model('SortingAllocateinfo');
        $result = $this->SortingAllocateinfo->UpdateAllocation($allocation_id);
        echo json_encode($result);
    }

    public function approve_allocation($allocation_id)
    {
        $this->load->model('SortingAllocateinfo');
        $result = $this->SortingAllocateinfo->ApproveAllocation($allocation_id);
        echo json_encode($result);
    }

    public function reject_allocation($allocation_id)
    {
        $this->load->model('SortingAllocateinfo');
        $result = $this->SortingAllocateinfo->RejectAllocation($allocation_id);
        echo json_encode($result);
    }

    public function get_allocation_details($allocation_id)
    {
        $this->load->model('SortingAllocateinfo');
        $allocation = $this->SortingAllocateinfo->GetAllocationDetails($allocation_id);

        if (isset($allocation['material_id']) && (!isset($allocation['material_name']) || empty($allocation['material_name']))) {
            $this->db->select('material_name');
            $this->db->from('tbl_row_material');
            $this->db->where('idtbl_row_material', $allocation['material_id']);
            $material = $this->db->get()->row();
            if ($material) {
                $allocation['material_name'] = $material->material_name;
            }
        }

        echo json_encode($allocation);
    }

    public function get_batch_info($batch_id)
    {
        $this->load->model('SortingAllocateinfo');
        $batch_info = $this->SortingAllocateinfo->GetBatchInfo($batch_id);
        echo json_encode($batch_info);
    }

    public function update_sorting_complete($allocation_id)
    {
        $this->load->model('SortingAllocateinfo');
        $result = $this->SortingAllocateinfo->UpdateSortingComplete($allocation_id);
        echo json_encode($result);
    }
}