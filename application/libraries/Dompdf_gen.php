<?php
use Dompdf\Dompdf;

class Dompdf_gen extends Dompdf
{
    public function __construct()
    {
        require_once FCPATH . 'vendor/autoload.php';
        parent::__construct();
    }
}
