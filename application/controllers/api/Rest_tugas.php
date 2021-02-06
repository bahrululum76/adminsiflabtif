<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_tugas extends CI_Controller {

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
        $jadwalKode = $this->get('jadwal_kode');

        echo $jadwalKode;die;
        
        $jadwalAktif = $this->M_database_API->getJadwalAktif($npm);

        if ($jadwalAktif) {

            $jadwalMhs = "";
            foreach ($jadwalAktif as $jadwal) {
                $jadwalMhs .= "t_tugas.jadwal_kode = '$jadwal[jadwal_kode]' OR ";
            }
            
            $tugas = $this->M_database_API->getTugas(rtrim($jadwalMhs, ' OR'));
            
            foreach ($tugas as $key => $value) {
    
                $file_tugas = $this->M_database_API->countWhere('t_tugas_file', array('tugas_id' => $value['tugas_id'], 'npm' => $npm));
                $nilai_tugas = $this->M_database_API->countWhere('t_tugas_nilai', array('tugas_id' => $value['tugas_id'], 'npm' => $npm));
                $tgl_file = $this->db->select('tgl_upload')->where(array('npm' => $npm, 'tugas_id' => $value['tugas_id']))->get('t_tugas_file')->row_array();
                $tgl_nilai = $this->db->select('tgl_upload')->where(array('npm' => $npm, 'tugas_id' => $value['tugas_id']))->get('t_tugas_nilai')->row_array();
    
                if ($file_tugas || $nilai_tugas) {                                                    
                    $tugas[$key]['mengumpulkan'] = 'sudah';
                } else {                                                    
                    $tugas[$key]['mengumpulkan'] = 'belum';
                }
    
                if ($tgl_file || $tgl_nilai) {                
                    if ($file_tugas) {                                                    
                        $tugas[$key]['tgl_upload'] = $tgl_file['tgl_upload'];
                    } else {                                                    
                        $tugas[$key]['tgl_upload'] = $tgl_nilai['tgl_upload'];
                    }
                } else {
                    $tugas[$key]['tgl_upload'] = '-';
                }
    
                $waktu = strtotime($value['batas_waktu']) - strtotime('now');
                $menit = floor($waktu /(60));
                
    
                if ($menit < 1) {
                    $menit = 0;
                }
    
                $hours = floor($menit / 60);
                $minutes = ($menit % 60);
    
                $sisa_waktu = sprintf('%02d:%02d', $hours, $minutes);
                $tugas[$key]['sisa_waktu'] = $menit;
    
            }
    
            $this->response([
                'status' => true,
                'npm' => $npm,
                'tugas' => $tugas        
            ], 200);
        } else {
            $this->response([
                'status' => true,
                'npm' => $npm,
                'tugas' => []        
            ], 200);
        }

    }

    public function detail_get()
    {
    
    	$tugasId = $this->get('tugas_id');
    	$tugasKode = $this->get('tugas_kode');

        $tugas = $this->M_database_API->getTugasDetail($tugasId, $tugasKode);
        $image = $this->M_database_API->getTugasImage($tugasKode);	
        
    	foreach ($image as $key => $img) {
    		$image[$key]['tugas_image'] = base_url()."assets/images/tugas/$img[tugas_image]";
    	}
    	
    	if ($tugas) {
    		$this->response([
                    'status' => true,
                    'id_tugas' => $tugas['tugas_kode'],
                    'tugas' => $tugas,        
                    't_image' => $image        
                ], 200);
    	} else {
    		$this->response([
                    'status' => false,           
                    'tugas' => $tugas,
                    't_image' => $image                    
                ], 200);
    	}
    }

    public function upload_post()
    {

        $npm = $this->post('npm');
        $tugasId = $this->post('tugas_id');
        $fileName = $this->post('file_nama');

        
        $config['upload_path']          = './assets/tugas_file/';
        $config['allowed_types']        = 'pdf|zip|rar|7z|7zip|doc|docx';
        $config['file_name']            = $fileName;
        $config['overwrite']            = true;
        $config['max_size']             = 0;
        
        $this->load->library('upload', $config);
        
        // CEK FILE
        $cekFile = $this->db->where(array('tugas_id' => $tugasId, 'npm' => $npm))->get('t_tugas_file')->row();

        // FILE ADA
        if ($cekFile) {
            if ($this->upload->do_upload('file')) {

                $file = $this->upload->data();          
                $data = array(
                    'tugas_id' => $tugasId,
                    'npm' => $npm,
                    'file_nama' => $file['file_name'],
                    'tgl_upload' => date("d-m-Y H:i")
                );            
                
                $this->M_database_API->updateData('t_tugas_file', array('npm' => $npm, 'tugas_id' => $tugasId), $data);                
                
                $this->response([
                    'upload' => true,
                    'tgl_upload' => date('d-m-Y H:i'),
                    'message' => "Upload berhasil"
                ], 200);

            } else {
                $this->response([
                    'upload' => false,
                    'tgl_upload' => date('d-m-Y H:i'),
                    'message' => $this->upload->display_errors()
                ], 200);
            }

        } else {
        // FILE KOSONG
            if ($this->upload->do_upload('file')) {

                $file = $this->upload->data();          
                $data = array(
                    'tugas_id' => $tugasId,
                    'npm' => $npm,
                    'file_nama' => $file['file_name'],
                    'tgl_upload' => date("d-m-Y H:i")
                );            
                
                $this->M_database_API->insertData('t_tugas_file', $data);                               
                
                $this->response([
                        'upload' => true,
                        'tgl_upload' => date('d-m-Y H:i'),
                        'message' => "Upload berhasil"
                    ], 200);

            } else {
                $this->response([
                        'upload' => false,
                        'tgl_upload' => date('d-m-Y H:i'),
                        'message' => $this->upload->display_errors()
                    ], 200);
            }
        }   
        
    }

}

?>