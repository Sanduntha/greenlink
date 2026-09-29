<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockReport extends CI_Controller
{
    public function index()
    {
        $this->load->model('Commeninfo');
        $data['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->model('PdfStock');
        $data['Mainmaterials'] = $this->PdfStock->getMainMaterials();
        $this->load->view('stock_report', $data);
    }

    public function getRowMaterials($mainId)
    {
        $this->load->model('PdfStock');
        $data = $this->PdfStock->getRowMaterials($mainId);
        echo json_encode($data);
    }

    public function generate()
    {
        $this->load->model('PdfStock');
        $material_id = $this->input->post('material_id');
        $month_year = $this->input->post('month_year');
        list($year, $month) = explode('-', $month_year);

        $this->PdfStock->generatePdf($material_id, $month, $year);
    }

    public function GetBalanceStock()
    {
        $this->load->model('PdfStock');
        $month = $this->input->get('month');
        $year = $this->input->get('year');

        $this->PdfStock->GetBalanceStock($month, $year);
    }

    public function generateSummary()
    {
        $this->load->model('PdfStock');
        $material_ids = $this->input->post('material_ids');

        if (empty($material_ids)) {
            echo json_encode(['success' => false, 'message' => 'Please select at least one material']);
            return;
        }

        $this->PdfStock->generateSummaryReport($material_ids);
    }

    // Add this method to your StockReport controller
    public function getAllMaterials()
    {
        $this->load->model('PdfStock');
        $this->db->select('idtbl_row_material, material_name');
        $this->db->from('tbl_row_material');
        $this->db->order_by('material_name', 'ASC');
        $query = $this->db->get();
        $data = $query->result();
        echo json_encode($data);
    }
}
