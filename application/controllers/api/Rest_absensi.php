<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Rest_absensi extends CI_Controller {

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

        $mhs_id = $this->get('mhs');        
        $npm = $this->get('npm');        
        $key = $this->get('key');
        $idp = $this->get('jadwal_kode');

        $mhs = $this->M_database_API->getMahasiswa("mhs_id = $mhs_id");

        if ($key === hash('sha256', $mhs['npm'])) {
            
            $absensi = $this->M_database_API->getAbsensi($idp);
            $matkum = $this->M_database_API->getMatkum(array('t_jadwal.jadwal_kode' => $idp));

            $arr = [];
            for ($i = 1; $i <= count($absensi); $i++) { 			
                array_push($arr, ['pertemuan' => $i, 'absen_tgl' => 'Tidak ada pertemuan', 'kehadiran' => 'tidak hadir']);
            }

            foreach ($absensi as $key => $value) {
                $cek_absen = $this->M_database_API->cekdataAbsenMhs('t_absensi', 'npm', 'jadwal_kode', 'pertemuan', $npm, $idp, $value['pertemuan']);
                if ($cek_absen) {
                    $arr[$key]['absen_tgl'] = $value['absen_tgl'];
                    $arr[$key]['kehadiran'] = 'hadir';
                } else {
                    $arr[$key]['absen_tgl'] = $value['absen_tgl'];
                    $arr[$key]['kehadiran'] = 'tidak hadir';				
                }             
            }              
                
            $this->response([
                'status' => true,
                'npm' => $npm,
                'jadwal_kode' => $matkum['jadwal_kode'],
                'matkum' => $matkum['matkum'],
                'absensi' => $arr        
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