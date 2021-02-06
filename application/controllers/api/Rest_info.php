<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_info extends CI_Controller {

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
    
        $kelas = $this->get('kelas');
        $info = $this->M_database_API->getInfo($kelas);
        $img = [];
        
    	foreach ($info as $key => $value) {
            $infoImage = $this->M_database_API->getInfoImage($value['info_id']);
            foreach ($infoImage as $image) {
                array_push($img, base_url()."assets/images/info/".$image['info_image']);
            }
            $info[$key]['info_image'] = $img;
    	}

        $this->response([
            'status' => true,
            'info' => $info
        ], 200);

    }

    public function tentang_get()
    {
    
        $lab = $this->db->get('t_lab')->row();
        $fb = $lab->lab_fb;
        $ig = $lab->lab_ig;
        $yt = $lab->lab_yt;
        $prosedural = $lab->lab_prosedural;
        $tata_tertib = $lab->lab_tata_tertib;
        $sanksi = $lab->lab_sanksi;
        
        $this->response([
            'status' => true,
            'facebook' => $fb,
            'instagram' => $ig,
            'youtube' => $yt,
            'prosedural' => $prosedural,
            'tata_tertib' => $tata_tertib,
            'sanksi' => $sanksi
        ], 200);                    
    }
}


?>