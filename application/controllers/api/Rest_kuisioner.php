<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_kuisioner extends CI_Controller {

    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {       
        $this->__resTraitConstruct();
        $this->load->model('M_database_API');
        $this->load->model('M_kuisioner');
    }

    public function index_get()
    {        
        $kategori = $this->M_kuisioner->getAllData('kategori_penilaian', array('tipe_penilaian' => '1'))->result_array();
        $uraian = $this->M_kuisioner->getUraian('1');
     
        $this->response([
            'status' => true,
            'kategori' => $kategori,
            'uraian' => $uraian
        ], 200);

    }

    function index_post() {
        $npm = $this->post('npm');
        $idp = $this->post('idp');
        $data = $this->post('data');
        
        $penilaian1 = $data[0];
        $penilaian2 = $data[1];
        $komentar1 = $data[2];
        $komentar2 = $data[3];
        $komentar3 = $data[4];
        
        $this->M_kuisioner->insertData('status_penilaian_mahasiswa', array('npm' => $npm, 'kode_praktikum' => $idp));        
        $this->M_kuisioner->insertData('penilaian_komentar', $komentar1);
        $this->M_kuisioner->insertData('penilaian_komentar', $komentar2);
        $this->M_kuisioner->insertData('penilaian_komentar', $komentar3);
        $this->M_kuisioner->insertAllData('penilaian_mahasiswa', $penilaian1);
        $this->M_kuisioner->insertAllData('penilaian_mahasiswa', $penilaian2);     
        
        $this->response([
            'status' => true            
        ], 200);
    }

    public function asisten_get()
    {
        $npm = $this->get('npm');        
        $idp = $this->get('idp');        
               
        $jadwal = $this->db->where('jadwal_kode', $idp)->get('t_jadwal')->row_array();
        $kuisioner = $this->db->where(array('npm' => $npm, 'kode_praktikum' => $idp))->get('status_penilaian_mahasiswa')->row();

        if($kuisioner) {
            $status = 'sudah';                
        } else {
            $status = 'belum'; 
        }

        $asisten1 = $this->M_database_API->getNamaAsisten($jadwal['asisten_1']);
        $asisten2 = $this->M_database_API->getNamaAsisten($jadwal['asisten_2']);
        
        $asisten = [];
        
        $asisten['username1'] = $asisten1->username;
        $asisten['pengajar1'] = $asisten1->asisten_nama;
        $asisten['foto1'] = base_url().'assets/images/profil/'.$asisten1->foto;
        $asisten['username2'] = $asisten2->username;
        $asisten['pengajar2'] = $asisten2->asisten_nama;
        $asisten['foto2'] = base_url().'assets/images/profil/'.$asisten2->foto;
        
        $this->response([
            'status' => true,
            'kuisioner' => $status, 
            'periode' => $jadwal['periode_id'],
            'asisten' => $asisten,
        ], 200);
    }
    
}


?>