<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai_praktikum extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_dosen();
	}


	public function index()
	{
		$dosen_id = $this->session->userdata('dosen_id');
		$matkum_id = $this->input->get('mp');
		$kelas_kode = $this->input->get('kelas');

		$data['tabTitle'] = 'Nilai Praktikum';
		$data['kelas'] = $this->M_database->getNilaiPraktikum(array('matkum_id' => $matkum_id, 'kelas_kode' => $kelas_kode));
		$data['jadwal'] = $this->M_database->getMatkumDosen("t_jadwal.dosen_id = $dosen_id");
		$data['kls'] = $this->M_database->getKelasDosen("t_jadwal.dosen_id = $dosen_id");
    
        $this->template->load('dosen/template-dosen', 'dosen/v-penilaian', $data);

	}	

}
