<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuration extends CI_Controller {

    public function index(){
		// $this->load->model('Commeninfo');
		$this->load->view('configuration_table');
	}

    
}