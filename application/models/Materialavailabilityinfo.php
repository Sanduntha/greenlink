<?php
class Materialavailabilityinfo extends CI_Model
{
    public function getAllCategories()
    {
        $this->db->select('idtbl_material_main_cat, categoryname');
        $this->db->from('tbl_material_main_cat');
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
}