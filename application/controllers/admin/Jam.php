<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jam extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$data['jam'] = $this->M_database->getAllData('t_jam', null, null, 'jam ASC');
        $data['halaman_title'] = "Master Jam";
		$this->template->load('admin/template', 'admin/v-jam', $data);
	}

	function tambahJam()
	{
		$jam_mulai = $this->input->post('jam_mulai');
		$jam_selesai = $this->input->post('jam_selesai');

		$data = array(
					'jam' => $jam_mulai.' - '.$jam_selesai
				);

		$this->session->set_flashdata('tambah', "Data jam berhasil ditambahkan");
		$this->M_database->insertData('t_jam', $data);

		redirect('admin/jam', 'refresh');
	}

	function editJam()
	{	
		$jam_id = $this->input->post('jam_id');
		$jam_nama = $this->input->post('jam_nama');
		$jam_mulai = $this->input->post('jam_mulai');
		$jam_selesai = $this->input->post('jam_selesai');

		$data = array(
					'jam' => $jam_mulai.' - '.$jam_selesai
				);

		$this->session->set_flashdata('edit', "Data <b>$jam_nama</b> berhasil diedit");
		$this->M_database->updateData('t_jam', array('jam_id' => $jam_id), $data);

		redirect('admin/jam', 'refresh');
	}

	function hapusJam()
	{
		$jam_id = $this->input->post('jam_id');
		$jam_lengkap = $this->input->post('jam_lengkap');

		$this->session->set_flashdata('hapus', "Data jam <b>$jam_lengkap</b> berhasil dihapus");
		$this->M_database->deleteData('t_jam', array('jam_id' => $jam_id));

		redirect('admin/jam', 'refresh');
	}


}
