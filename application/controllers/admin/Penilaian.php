<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penilaian extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$jadwal_kode = $this->input->get('idp');
		$data['tabTitle'] = 'Penilaian Praktikum';
		$data['mahasiswa'] = $this->M_database->getDetailTugas(array('jadwal_kode' => $jadwal_kode));
		$data['jadwal'] = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC');
        $data['halaman_title'] = "Penilaian Praktikum";

        $this->template->load('admin/template', 'admin/v-penilaian', $data);

	}

	public function laporan_pdf(){

		$jadwal_kode = $this->input->get('idp');

		$data['mahasiswa'] = $this->M_database->getDetailTugas(array('jadwal_kode' => $jadwal_kode));
		$data['jadwal'] = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC');

		$this->load->library('pdf');

		$this->pdf->set_option('isRemoteEnabled', TRUE);
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-petanikode.pdf";
		$this->pdf->load_view('pdf/penilaian-pdf', $data);


	}

	function editPersentase() {
		$jadwal_kode = $this->input->post('jadwal_kode');
		$p_kehadiran = $this->input->post('p_kehadiran');
		$p_tugas = $this->input->post('p_tugas');
		$p_ujian = $this->input->post('p_ujian');

		$data = array(
					'p_kehadiran' => $p_kehadiran,
					'p_tugas' => $p_tugas,
					'p_ujian' => $p_ujian
				);

		$this->M_database->updateData('t_jadwal', array('jadwal_kode' => $jadwal_kode), $data);
		
		$this->session->set_flashdata('edit', "Persentase penilaian <b>$jadwal_kode</b> berhasil diedit");
		redirect("admin/penilaian?idp=$jadwal_kode", 'refresh');

	}

	function penilaian() {
		$npm = $this->input->post('npm');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$nilai_tugas = $this->input->post('nilai_tugas');	
		$nilai_ujian = $this->input->post('nilai_ujian');
		$total = $nilai_tugas + $nilai_ujian;	
		$this->M_database->updateData('registrasi_praktikum', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm), array('nilai_tugas' => $nilai_tugas, 'nilai_ujian' => $nilai_ujian));
		echo "$nilai_tugas|$nilai_ujian|$total";
	}	


}
