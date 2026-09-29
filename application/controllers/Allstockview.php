<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Allstockview extends CI_Controller
{
   

    public function index()
    {
         $this->load->model('Allstockviewinfo');
        $this->load->model('Commeninfo');
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        $this->load->view('allstockview', $result);
    }

   
}