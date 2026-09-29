<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Materialavailability extends CI_Controller
{
    public function index()
    {
        $this->load->model('Commeninfo');
        $this->load->model('Materialavailabilityinfo');

        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $result['categories'] = $this->Materialavailabilityinfo->getAllCategories();
        $this->load->view('materialavailability', $result);
    }

    public function getMaterialData()
    {
        $this->load->model('Materialavailabilityinfo');

        $categoryId = $this->input->post('categoryId');
        $searchTerm = $this->input->post('searchTerm');

        $data = $this->Materialavailabilityinfo->getMaterialAvailabilityData($categoryId, $searchTerm);
        echo json_encode(['data' => $data]);
    }
}