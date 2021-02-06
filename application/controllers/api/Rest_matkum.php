<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_matkum extends CI_Controller {

    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {       
        $this->__resTraitConstruct();
        $this->load->model('M_database_API');
    }

    public function index_get()
    {
    	$npm = $this->get('npm');

        $matkum = $this->M_database_API->getMatkum(array('npm' => $npm));        
        $jadwal = $this->M_database_API->getAllJadwal($npm);
    	
    	if ($matkum) {
    		$this->response([
                'status' => true,
                'id_akhir' => $matkum['jadwal_id'],                   
                'kode_akhir' => $matkum['jadwal_kode'],                   
                'matkum_akhir' => $matkum['matkum'],
                'jadwal' => $jadwal   
            ], 200);
    	} else {
    		$this->response([
                'status' => false,
                'message' => "Data tidak ditemukan"
            ], 204);
    	}
    }

}

?>