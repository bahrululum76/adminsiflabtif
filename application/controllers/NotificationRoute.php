<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotificationRoute extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
	}
	
    public function index()
    {
        $user_id = $this->input->get('user');
        $url = $this->input->get('url');

        $this->route($user_id, $url);
    }

    public function route($user_id, $url)
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

                    if ($url == 'chat') {                        
                        if ($role == 'bak') {
                            redirect('bak/chat?'.$user_id);
                        } else {
                            redirect('asisten/chat?'.$user_id);
                        }
                    } else if ($url == 'ticketing') {
                        redirect('admin/ticketing');
                    }
				}				
			} else {
				$dosen = $this->db->get_where('t_dosen', array('dosen_nidn' => $username))->row_array();
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
                    redirect('dosen/chat?'.$user_id);
				}	
			}			
		} else {
			$this->load->view('v-login');
		}	
	}
}