<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_nilai extends CI_Controller {

    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {    
        $this->__resTraitConstruct();
        $this->load->model('M_database_API');
        $this->load->model('M_database');
    }

    public function index_get()
    {    
    	$mhs_id = $this->get('mhs');        
        $npm = $this->get('npm');
        $key = $this->get('key');
        $idp = $this->get('jadwal_kode');

        $mhs = $this->M_database_API->getMahasiswa("mhs_id = $mhs_id");

        if ($key === hash('sha256', $mhs['npm'])) {

            $matkum = $this->M_database_API->getMatkum(array('t_jadwal.jadwal_kode' => $idp));
            $tugas = $this->M_database_API->getNilaiTugas($npm, $idp);
            $hasilAkhir = $this->M_database_API->getHasilAkhir($npm, $idp);
            
            $kehadiran = $this->M_database_API->hitungKehadiran($npm, $idp) * 10;
            $nilaiKehadiran = $kehadiran * $hasilAkhir['p_kehadiran'] / 100;
            $nilaiTugas = $hasilAkhir['nilai_tugas'] * $hasilAkhir['p_tugas'] / 100;
            $nilaiUjian = $hasilAkhir['nilai_ujian'] * $hasilAkhir['p_ujian'] / 100;

		    $nilaiAkhir = $nilaiKehadiran + $nilaiTugas + $nilaiUjian;
            
            $this->response([
                'npm' => $npm,
                'jadwal_kode' => $matkum['jadwal_kode'],
                'matkum' => $matkum['matkum'],
                'p_kehadiran' => $hasilAkhir['p_kehadiran'],
                'p_tugas' => $hasilAkhir['p_tugas'],
                'p_ujian' => $hasilAkhir['p_ujian'],
                'total_kehadiran' => $kehadiran,
                'total_tugas' => $hasilAkhir['nilai_tugas'],
                'total_ujian' => $hasilAkhir['nilai_ujian'],
                'kehadiran' => $nilaiKehadiran,
                'tugas' => $nilaiTugas,
                'ujian' => $nilaiUjian,
                'hasil' => $nilaiAkhir,
                'nilai' => $tugas     
            ], 200);      
        } else {
            $this->response([
                'status' => false,
                'error' => 'invalid API key'                                     
            ], 403);
        }
    }    
}


 ?>