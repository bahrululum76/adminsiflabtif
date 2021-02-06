<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		$this->load->helper('cookie');
	}


	public function index()
	{		
		if (isset($_COOKIE['siflabtif_uid']) && isset($_COOKIE['siflabtif_user']) && isset($_COOKIE['siflabtif_role'])) {		
			$uid = $_COOKIE['siflabtif_uid'];
			$user_is = $_COOKIE['siflabtif_user'];
			$role = $_COOKIE['siflabtif_role'];
			if ($role != 'dosen') {
				$user = $this->db->get_where('t_asisten', array('asisten_id' => $uid))->row_array();
				if ($user_is === hash('sha256', $user['username'])) {

					$namaLengkap = explode(' ', $user['asisten_nama']);
					if (sizeof($namaLengkap) > 3) {
						$nama = $namaLengkap[0]." ".$namaLengkap[1]." ".$namaLengkap[2];
					} else {
						$nama = $user['asisten_nama'];
					}

					if (sizeof($namaLengkap) > 1) {
						if ($namaLengkap[0] == 'M' || $namaLengkap[0] == 'M.' || $namaLengkap[0] == 'Muhamad' || $namaLengkap[0] == 'Muhammad' || $namaLengkap[0] == 'Mohamed' || $namaLengkap[0] == 'Mohammed' || $namaLengkap[0] == 'Moch' || $namaLengkap[0] == 'Moch.' || $namaLengkap[0] == 'Mochamad' || $namaLengkap[0] == 'Mochammad' || $namaLengkap[0] == 'Much' || $namaLengkap[0] == 'Much.' || $namaLengkap[0] == 'Muchamad' || $namaLengkap[0] == 'Muchammad') {
							$panggilan = $namaLengkap[1];
						} else {
							$panggilan = $namaLengkap[0];
						}
					} else {
						$panggilan = $user['asisten_nama'];
					}
	
					$data_user = array(
						'uid' => $user['asisten_id'],								
						'u_token' => $user['username'],								
						'asisten_id' => $user['asisten_id'],								
						'nama' => $nama,								
						'panggilan' => $panggilan,								
						'jabatan_id' => $user['jabatan_id'],								
						'jabatan' => 'asisten_'								
					);

					$this->session->set_userdata($data_user);

					if ($role == 'admin') {
						redirect('admin/dashboard');
					} else if ($role == 'bak') {
						redirect('bak/dashboard');
					} else {
						redirect('asisten/dashboard');
					}
				} else {
					$this->load->view('v-login');
				}			
			} else {
				$dosen = $this->db->get_where('t_dosen', array('dosen_id' => $uid))->row_array();
				if ($user_is === hash('sha256', $dosen['dosen_nidn'])) {
					$data_user = array(
						'uid' => $dosen['dosen_id'],								
						'u_token' => $dosen['dosen_nidn'],								
						'dosen_id' => $dosen['dosen_id'],								
						'nama' => $dosen['dosen_nama'],								
						'jabatan_id' => $dosen['jabatan_id'],
						'jabatan' => 'dosen_'								
					);

					$this->session->set_userdata($data_user);
					redirect('dosen/dashboard');
				} else {
					$this->load->view('v-login');
				}
			}			
		} else {
			$this->load->view('v-login');
		}	
	}

	function login()
	{
		$role = $this->input->post('role');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if ($role == 'asisten') {
			$user = $this->db->get_where('t_asisten', array('username' => $username))->row_array();
			if ($user) {
				// status aktif
				if ($user['status'] == 1) 
				{
					// password benar
					if (password_verify($password, $user['password'])) {

						$namaLengkap = explode(' ', $user['asisten_nama']);

						if (sizeof($namaLengkap) > 3) {
							$nama = $namaLengkap[0]." ".$namaLengkap[1]." ".$namaLengkap[2];
						} else {
							$nama = $user['asisten_nama'];
						}

						// nama panggilan
						if (sizeof($namaLengkap) > 1) {
							if ($namaLengkap[0] == 'M' || $namaLengkap[0] == 'M.' || $namaLengkap[0] == 'Muhamad' || $namaLengkap[0] == 'Muhammad' || $namaLengkap[0] == 'Mohamed' || $namaLengkap[0] == 'Mohammed' || $namaLengkap[0] == 'Moch' || $namaLengkap[0] == 'Moch.' || $namaLengkap[0] == 'Mochamad' || $namaLengkap[0] == 'Mochammad' || $namaLengkap[0] == 'Much' || $namaLengkap[0] == 'Much.' || $namaLengkap[0] == 'Muchamad' || $namaLengkap[0] == 'Muchammad') {
								$panggilan = $namaLengkap[1];
							} else {
								$panggilan = $namaLengkap[0];
							}
						} else {
							$panggilan = $user['asisten_nama'];
						}

						$data = array(
									'uid' => $user['asisten_id'],								
									'u_token' => $user['username'],								
									'asisten_id' => $user['asisten_id'],								
									'nama' => $nama,								
									'panggilan' => $panggilan,								
									'jabatan_id' => $user['jabatan_id'],								
									'jabatan' => 'asisten_'								
								);
						
						setcookie('siflabtif_uid', $user['asisten_id'], time()+(86400 * 180), "/");
						setcookie('siflabtif_user', hash('sha256', $user['username']), time()+(86400 * 180), "/");
						$this->session->set_userdata($data);						
						// admin
						if ($user['jabatan_id'] == 6) {
							setcookie('siflabtif_role', 'admin', time()+(86400 * 180), "/");
							echo 'sukses|admin/dashboard';
						} 
						else if ($user['jabatan_id'] == 12) {
							setcookie('siflabtif_role', 'bak', time()+(86400 * 180), "/");
							// BAK
							echo 'sukses|bak/dashboard';
						}					
						else {
							setcookie('siflabtif_role', 'asisten', time()+(86400 * 180), "/");
							// asisten
							echo 'sukses|asisten/dashboard';
						}

					} else {
						// password salah
						echo 'gagal|Password yang anda masukan salah';
					}
				} else {
					// status tidak aktif	
					echo 'status|Maaf, status mengajar anda sudah tidak aktif';
				}			
			} else {
				// user tidak ada			
				echo 'gagal|Username tidak terdaftar';
			}
		} else {
			$dosen = $this->db->get_where('t_dosen', array('dosen_nidn' => $username))->row_array();	
			if ($dosen) {
				// status aktif
				if ($dosen['dosen_status'] == 1) {
					// password benar
					if (password_verify($password, $dosen['password'])) {
						$data = array(
									'uid' => $dosen['dosen_id'],								
									'u_token' => $dosen['dosen_nidn'],								
									'dosen_id' => $dosen['dosen_id'],								
									'nama' => $dosen['dosen_nama'],								
									'jabatan_id' => $dosen['jabatan_id'],
									'jabatan' => 'dosen_'								
								);
						
						setcookie('siflabtif_role', 'dosen', time()+(86400 * 180), "/");
						setcookie('siflabtif_uid', $dosen['dosen_id'], time()+(86400 * 180), "/");
						setcookie('siflabtif_user', hash('sha256', $dosen['dosen_nidn']), time()+(86400 * 180), "/");
						$this->session->set_userdata($data);
						echo 'sukses|dosen/dashboard';
					} else {
						// password salah					
						echo 'gagal|Password yang anda masukan salah';
					}
				} else {
					// status tidak aktif					
					echo 'status|Maaf, status mengajar anda sudah tidak aktif';
				}
			} else {
				echo 'gagal|NIDN tidak terdaftar';
			}
		}
				
	}

	function logout()
	{
		delete_cookie("siflabtif_role");
		delete_cookie("siflabtif_uid");
		delete_cookie("siflabtif_user");
		$this->session->sess_destroy();
		redirect('auth');
	}

	public function subscribe()
	{
		$this->load->library('user_agent');

		$agent = $this->agent->browser();
		$u_token = $this->session->userdata('u_token');
		$jabatan_id = $this->session->userdata('jabatan_id');
		$token = $this->input->post('token');

		if ($jabatan_id == 1) {
			$u_role = 'koorlab';
		} else if ($jabatan_id == 13) {
			$u_role = 'dosen';		
		} else if ($jabatan_id == 12) {
			$u_role = 'bak';
		} else if ($jabatan_id == 6) {
			$u_role = 'admin';
		} else {
			$u_role = 'asisten';
		}

		$obj = json_decode($token);
        $endpoint = $obj->endpoint;
        $auth = $obj->keys->auth;
		$p256dh = $obj->keys->p256dh;
		
		$data = array('endpoint' => $endpoint, 'auth' => $auth, 'p256dh' => $p256dh, 'user_id' => $u_token, 'user_role' => $u_role, 'agent' => $agent, 'last_active' => date('Y-m-d H:i:s'));            
		$token = $this->db->order_by('id ASC')->where('endpoint', $endpoint)->where('user_id', $u_token)->get('t_device_token_admin')->row();
		
		if (!$token) {                       
            $this->M_database->insertData('t_device_token_admin', $data);                
        }
	}

}
