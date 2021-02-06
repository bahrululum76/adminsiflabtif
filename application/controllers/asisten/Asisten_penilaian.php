<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asisten_penilaian extends CI_Controller {

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
		$data['tabTitle'] = 'Penilaian Praktikum';
		$data['mahasiswa'] = $this->M_database->getDetailTugas(array('jadwal_kode' => $jadwal_kode));
		$data['jadwal'] = $this->M_database->getJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id");
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $this->template->load('asisten/template-asisten', 'asisten/v-penilaian', $data);

	}	

	function penilaian() {
		$npm = $this->input->post('npm');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$total_tugas = $this->input->post('total_tugas');	
		$nilai_tugas = $this->input->post('nilai_tugas');	
		$nilai_ujian = $this->input->post('nilai_ujian');
		if ($nilai_tugas == '') {
			$nilai_tugas = 0;
		}
		if ($nilai_ujian == '') {
			$nilai_ujian = 0;
		}		
		$total = $nilai_tugas + $nilai_ujian;	
		$this->M_database->updateData('registrasi_praktikum', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm), array('nilai_tugas' => $nilai_tugas, 'nilai_ujian' => $nilai_ujian));
		echo "$nilai_tugas|$nilai_ujian|$total";
	}	


}
