<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Rowmaterials extends CI_Controller {
    public function index(){
		$this->load->model('Rowmaterialsinfo');
		$this->load->model('Supplierinfo');
		$this->load->model('Measurementsinfo');
		$this->load->model('Materialmaincategoryinfo');
		$this->load->model('Commeninfo');
		  $this->load->model('Rackinfo');
		$result['measurmentlist']=$this->Measurementsinfo->GetMeasurmentList();
		$result['supplierlist']=$this->Supplierinfo->GetSupplierList();
		$result['maincategorylist']=$this->Materialmaincategoryinfo->GetMainCatList();
		$result['racklist'] = $this->Rackinfo->Getracks();
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('rowmaterials',$result);
	}
   
	public function Rowmaterialsinsertupdate(){
		$this->load->model('Rowmaterialsinfo');
        $result=$this->Rowmaterialsinfo->Rowmaterialsinsertupdate();
	}
	public function Rowmaterialsedit(){
		$this->load->model('Rowmaterialsinfo');
        $result=$this->Rowmaterialsinfo->Rowmaterialsedit();
	}
	public function GetMaterialList(){
		$this->load->model('Rowmaterialsinfo');
        $result=$this->Rowmaterialsinfo->GetMaterialList();
	}
	public function Rowmaterialsstatus($x, $y){
		$this->load->model('Rowmaterialsinfo');
        $result=$this->Rowmaterialsinfo->Rowmaterialsstatus($x, $y);
	}
	
}
