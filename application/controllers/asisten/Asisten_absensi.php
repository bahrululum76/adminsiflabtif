<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asisten_absensi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}


	public function index()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		$jadwal_kode = $this->input->get('idp');
		
		if (isset($jadwal_kode)) {			
			$jadwal = $this->db->where('jadwal_kode', $jadwal_kode)->get('t_jadwal')->row();
			if ($jadwal->asisten_1 != $asisten_id && $jadwal->asisten_2 != $asisten_id) {
				redirect('asisten/akses_ditolak');
			}
		}
		$data['tabTitle'] = 'Absensi Praktikum';
		$data['mahasiswa'] = $this->M_database->getRegistrasiMahasiswa(array('jadwal_kode' => $jadwal_kode));
		$data['jadwal'] = $this->M_database->getJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id");
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
		
        $this->template->load('asisten/template-asisten', 'asisten/v-absensi', $data);

	}	

	function do_absen()
	{
		$jadwal_kode = $this->input->post('jadwal_kode');
		$npm = $this->input->post('npm');
		$kehadiran = $this->input->post('kehadiran');
		$pertemuan = explode(',',$this->input->post('pertemuan'));
		rsort($pertemuan);
		$absen_tgl = $this->input->post('absen_tgl');
				
		$cek = $this->M_database->cekAbsen('t_absensi', $jadwal_kode, $npm, end($pertemuan));
				
		if($kehadiran == 'Tidak Hadir' && $cek->hadir == 0) {
			foreach ($pertemuan as $pertemuan) {			
				$data = array(		
						'jadwal_kode'=> $jadwal_kode,
						'pertemuan'=> $pertemuan,
						'npm'=> $npm,
						'absen_tgl'=> $absen_tgl
					);
				$this->M_database->insertData('t_absensi', $data);
			}
			$badge = '<span class="badge-status badge-ok">Hadir</span>';

		} else if($kehadiran == 'Hadir' && $cek->hadir == 1) {
			foreach ($pertemuan as $pertemuan) {											
				$this->M_database->hapusAbsen('t_absensi', 'jadwal_kode', 'npm', 'pertemuan', $jadwal_kode, $npm, $pertemuan);
			}
			$badge = '<span class="badge-status badge-not">Tidak Hadir</span>'; 
		} else {
			$badge = '<span class="badge-status badge-not">Tidak Hadir</span>'; 			
		}

		echo $badge;
	}

	function do_absen_asisten($absen = NULL)
	{
		$jadwal_kode = $this->input->post('jadwal_kode');
		$asisten_id = $this->session->userdata('asisten_id');
		$password = $this->input->post('password');
		$kehadiran = $this->input->post('kehadiran');
		$pertemuan = explode(',',$this->input->post('pertemuan'));
		rsort($pertemuan);
		$absen_tgl = $this->input->post('absen_tgl');	
	
		$cek = $this->M_database->cekAbsenAsisten('t_absensi_asisten', $jadwal_kode, $asisten_id, end($pertemuan));
		$asisten = $this->db->get_where('t_asisten', array('asisten_id' => $asisten_id))->row();

		if (password_verify($password, $asisten->password)) {
			$pass = 'benar';
			if($kehadiran == 'Tidak Hadir' && $cek->hadir == 0) {
				foreach ($pertemuan as $pertemuan) {			
					$data = array(		
							'jadwal_kode'=> $jadwal_kode,
							'pertemuan'=> $pertemuan,
							'asisten_id'=> $asisten_id,
							'absen_tgl'=> $absen_tgl,
							'status'=> 1,
						);
					$this->M_database->insertData('t_absensi_asisten', $data);
				}
				$badge = '<span class="badge-status badge-ok">Hadir</span>';
			}
			else if($kehadiran == 'Hadir' && $cek->hadir == 1) {
				foreach ($pertemuan as $pertemuan) {																
					$this->M_database->hapusAbsen('t_absensi_asisten', 'jadwal_kode', 'asisten_id', 'pertemuan', $jadwal_kode, $asisten_id, $pertemuan);
				}
				$badge = '<span class="badge-status badge-not">Tidak Hadir</span>';
			}
			else{
				$badge = '<span class="badge-status badge-not">Tidak Hadir</span>';
			}
		} else {
			$pass = 'salah';
			$badge = '<span class="badge-status badge-not">Tidak Hadir</span>';
		}		
		echo "$pass|$badge";
	}	



}
