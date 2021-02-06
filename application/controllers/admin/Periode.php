<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$data['halaman_title'] = "Penjadwalan";
		$data['tabTitle'] = 'Penjadwalan';
		$data['periode'] = $this->M_database->getAllData('t_periode', null, null, 'periode_id DESC');
		$this->template->load('admin/template', 'admin/v-periode', $data);
	}

	function tambahPeriode()
	{
		$periode_nama = $this->input->post('periode_nama');

		$data = array(
					'periode_nama' => $periode_nama,
					'status' => 1
				);

		$this->M_database->insertData('t_periode', $data);
		$this->session->set_flashdata('tambah', "Data <b>$periode_nama</b> berhasil ditambahkan");

		redirect('admin/periode', 'refresh');
	}

	function editPeriode()
	{
		$periode_id = $this->input->post('periode_id');
		$periode_nama = $this->input->post('periode_nama');

		$data = array(
					'periode_nama' => $periode_nama
				);

		$this->M_database->updateData('t_periode', array('periode_id' => $periode_id), $data);
		$this->session->set_flashdata('edit', "Data periode berhasil diedit");

		redirect('admin/periode', 'refresh');
	}

	function hapusPeriode()
	{
		$periode_id = $this->input->post('periode_id');
		$periode_nama = $this->input->post('periode_nama');

		$this->M_database->deleteData('t_periode', array('periode_id' => $periode_id));
		$this->session->set_flashdata('hapus', "Data <b>$periode_nama</b> berhasil dihapus");
		redirect('admin/periode', 'refresh');
	}

	function statusPeriode()
	{
		$periode_id = $this->input->post('periode_id');
		$status = $this->input->post('status');	
		if ($status == 'Aktif') {
			$data = array('status' => 0);
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
			$data = array('status' => 1);
			$badge = '<span class="badge-status badge-ok">Aktif</span>';
		}		
		$jadwal = $this->db->where('periode_id', $periode_id)->get('t_jadwal')->result();
		$jadwals = "";

		foreach ($jadwal as $key => $value) {
			$jadwals .= "jadwal_kode='$value->jadwal_kode' OR ";
		}

		$this->M_database->updateData('t_periode', array('periode_id' => $periode_id), $data);
		$this->M_database->updateData('t_jadwal', array('periode_id' => $periode_id), $data);
		$this->M_database->updateData('t_absensi_asisten', rtrim($jadwals, ' OR '), $data);	

		echo $badge;
	}	

}
