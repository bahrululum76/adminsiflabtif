<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_praktikum extends CI_Controller {

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
		$data['tabTitle'] = 'Modul Praktikum';
		$data['mahasiswa'] = $this->M_database->getWhere('registrasi_praktikum', 't_mahasiswa', 'registrasi_praktikum.npm = t_mahasiswa.npm', array('jadwal_kode' => $jadwal_kode), 'registrasi_praktikum.npm DESC');
		$data['jadwal'] = $this->M_database->getJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id");
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));        
        $this->template->load('asisten/template-asisten', 'asisten/v-modul', $data);
    }
    
    public function statusModul()
	{
		$registrasi_id = $this->input->post('registrasi_id');
		$status = $this->input->post('status');

		if ($status == 'Sudah') {
            $data = array('status_modul' => 0, 'tgl_modul' => '-');
			$tgl = '-';
			$badge = '<span class="badge-status badge-not">Belum</span>';
		} else  {
			$tgl = date('d/m/Y');
            $data = array('status_modul' => 1, 'tgl_modul' => $tgl);
			$badge= '<span class="badge-status badge-ok">Sudah</span>';
		}

		$this->M_database->updateData('registrasi_praktikum', array('registrasi_id' => $registrasi_id), $data);

		echo "$badge|$tgl";
	}

}
