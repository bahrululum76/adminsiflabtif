<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matkum extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{	
		$data['matkum'] = $this->M_database->getAllData('t_mata_praktikum', null, null, 'matkum_status DESC', 'matkum ASC');		
		$data['halaman_title'] = "Master Mata Praktikum";
		$data['tabTitle'] = 'Mata Praktikum';
		$this->template->load('admin/template', 'admin/v-matkum', $data);
	}

	function tambahMatkum()
	{
		$matkum = ucwords($this->input->post('matkum'));

		$data = array(
					'matkum' => $matkum,
					'matkum_status' => 1
				);

		$this->session->set_flashdata('tambah', "Mata praktikum <b>$matkum</b> berhasil disimpan");
		$this->M_database->insertData('t_mata_praktikum', $data);

		redirect('admin/matkum', 'refresh');
	}

	function editMatkum()
	{
		$matkum_id = $this->input->post('matkum_id');
		$matkum = $this->input->post('matkum');

		$data = array(
					'matkum' => $matkum
				);

		$this->session->set_flashdata('edit', "Mata praktikum <b>$matkum</b> berhasil diedit");
		$this->M_database->updateData('t_mata_praktikum', array('matkum_id' => $matkum_id), $data);

		redirect('admin/matkum', 'refresh');
	}

	function hapusMatkum()
	{
		$matkum_id = $this->input->post('matkum_id');
		$matkum = $this->input->post('matkum');

		$this->session->set_flashdata('hapus', "Mata praktikum <b>$matkum</b> berhasil dihapus");
		$this->M_database->deleteData('t_mata_praktikum', array('matkum_id' => $matkum_id));

		redirect('admin/matkum', 'refresh');
	}

	function statusMatkum()
	{
		$matkum_id = $this->input->post('matkum_id');
		$status = $this->input->post('status');	

		if ($status == 'Aktif') {
			$data = array('matkum_status' => 0);
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
			$data = array('matkum_status' => 1);
			$badge = '<span class="badge-status badge-ok">Aktif</span>';
		}		

		$this->M_database->updateData('t_mata_praktikum', array('matkum_id' => $matkum_id), $data);

		echo $badge;
	}


}
