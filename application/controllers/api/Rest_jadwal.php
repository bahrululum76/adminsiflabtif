<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_jadwal extends CI_Controller {

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
        
        $pembayaran = $this->M_database_API->cekStatusBayar($npm);
        $ujian = $this->M_database_API->cekModeUjian();        
        $pesan = $this->M_database_API->cekPesan($npm);        
        $jadwal = $this->M_database_API->getJadwalAktif($npm);

        foreach ($jadwal as $key => $value) {
            $asisten1 = $this->M_database_API->getNamaAsisten($value['asisten_1']);
            $asisten2 = $this->M_database_API->getNamaAsisten($value['asisten_2']);
            $kuisioner = $this->db->where(array('npm' => $npm, 'kode_praktikum' => $value['jadwal_kode']))->get('status_penilaian_mahasiswa')->row();

            $kehadiran = $this->M_database_API->hitungKehadiran($npm, $value['jadwal_kode']) * 10;           

            $jadwal[$key]['pengajar1'] = $asisten1->asisten_nama;
            $jadwal[$key]['foto1'] = base_url().'assets/images/profil/'.$asisten1->foto;
            $jadwal[$key]['pengajar2'] = $asisten2->asisten_nama;
            $jadwal[$key]['foto2'] = base_url().'assets/images/profil/'.$asisten2->foto;
            $jadwal[$key]['kehadiran'] = $kehadiran;
            
            if($kuisioner) {
                $jadwal[$key]['kuisioner'] = 'sudah';                
            } else {
                $jadwal[$key]['kuisioner'] = 'belum'; 
            }

        }        

        $this->response([
            'status' => true,
            'pembayaran' => $pembayaran,
            'ujian' => $ujian,
            'jadwal' => $jadwal,
            'jmlPesanMhs' => $pesan
        ], 200);
    }

    public function jadwal_get()
    {
    
        $periodeId = $this->get('periode_id');
    	$matkumId = $this->get('matkum_id');

        $jadwal = $this->M_database_API->getJadwalTicketing($periodeId, $matkumId);

        foreach ($jadwal as $key => $value) {            
            $reg = $this->M_database_API->jumlahMahasiswa($value['jadwal_kode']);
            $jadwal[$key]['jumlah_mhs'] = $reg;
        }      
    	
        $this->response([
            'status' => true,
            'jadwal' => $jadwal                   
        ], 200);    	
    }

}

?>