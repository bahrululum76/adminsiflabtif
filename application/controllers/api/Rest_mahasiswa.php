<?php 
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_mahasiswa extends CI_Controller {

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
        $mhs_id = $this->get('mhs_id');
        $key = $this->get('key');
        
        $mhs = $this->M_database_API->getMahasiswa("mhs_id = $mhs_id");
        $namaPendek = namaPendek($mhs['nama']);
        $namaPanggilan = namaPanggilan($mhs['nama']);    

        if ($key === hash('sha256', $mhs['npm'])) {
            
            if ($mhs) {            
                $this->response([
                    'status' => true,
                    'mahasiswa' => [
                        'mhs_id' => $mhs['mhs_id'],
                        'npm' => $mhs['npm'],
                        'nama' => $mhs['nama'],
                        'nama_pendek' => $namaPendek,
                        'panggilan' => $namaPanggilan,
                        'kelas' => $mhs['kelas_nama']  
                    ]      
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'mahasiswa' => ''
                ], 204);
            } 
        } else {
            $this->response([
                    'status' => false,
                    'error' => 'invalid API key'                                     
                ], 403);
        }
    }

    public function login_post()
    {
    	$npm = $this->post('npm');
    	$password = $this->post('password');
        
        $mhs = $this->M_database_API->getMahasiswa("npm = $npm");
        $namaPendek = namaPendek($mhs['nama']);
		$namaPanggilan = namaPanggilan($mhs['nama']);

        if ($mhs) {
        	if (password_verify($password, $mhs['password'])) {
        		$this->response([
                    'login' => true,
                    'kode' => 1,
                    'message' => "login berhasil",
                    'mahasiswa' => [
                        'mhs_id' => $mhs['mhs_id'],
                        'npm' => $mhs['npm'],
                        'nama' => $mhs['nama'],
                        'nama_pendek' => $namaPendek,
                        'panggilan' => $namaPanggilan,
                        'kelas' => $mhs['kelas_nama']                     
                    ]
                ], 200);
        	} else {
        		$this->response([
                    'login' => false,
                    'kode' => 2,
                    'message' => "password salah"
                ], 200);
        	}
        } else {
            $this->response([
                'login' => false,
                'kode' => 3,
                'message' => "npm tidak diketahui"
            ], 200);
        } 
    }

    public function pass_post()
    {
        $mhs_id = $this->post('mhs_id');
        $old_pass = $this->post('old_pass');
        $new_pass = password_hash($this->post('new_pass'), PASSWORD_DEFAULT);

        $mhs = $this->M_database_API->getMahasiswa("mhs_id = $mhs_id");

        $data = array('password' => $new_pass);

        if (password_verify($old_pass, $mhs['password'])) {
            $this->M_database_API->updateData('t_mahasiswa', array('mhs_id' => $mhs_id), $data);
            $this->response([
                'changed' => true,
                'message' => "Password diganti"
            ], 200);
        } else {
            $this->response([
                'changed' => false,
                'message' => "Password gagal"
            ], 200);
        }

    }

    public function token_post()    
    {
        $npm = $this->post('npm');
        $token = $this->post('token');
        $agent = $this->post('agent');

        $obj = json_decode($token);
        $endpoint = $obj->endpoint;
        $auth = $obj->keys->auth;
        $p256dh = $obj->keys->p256dh;

        $data = array(
            'endpoint' => $endpoint, 
            'auth' => $auth, 
            'p256dh' => $p256dh, 
            'npm' => $npm, 
            'agent' => $agent
        );

        $checkToken = $this->M_database_API->checkDeviceToken($endpoint);

        if ($checkToken) {                       
            $this->response([
                'token' => false,
                'message' => 'token exist'
            ], 204);
        } else {
            $this->M_database_API->insertData('t_device_token', $data);                
            $this->response([
                'token' => true,
                'message' => 'token saved'
            ], 201);
        }           
    }

}

 ?>