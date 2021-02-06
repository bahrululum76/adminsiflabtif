<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$data['jabatan'] = $this->M_database->getAllData('t_jabatan', null, null, 'jabatan_nama ASC');
		$data['halaman_title'] = "Master Jabatan";
		$data['tabTitle'] = 'Master Jabatan';
		$this->template->load('admin/template', 'admin/v-jabatan', $data);
	}

	function tambahJabatan()
	{
		$jabatan_nama = ucwords($this->input->post('jabatan_nama'));
		$honor_pertemuan = $this->input->post('honor_pertemuan');
		$honor_perbulan = $this->input->post('honor_perbulan');

		$data = array(
					'jabatan_nama' => $jabatan_nama,
					'honor_pertemuan' => $honor_pertemuan,
					'honor_perbulan' => $honor_perbulan
				);

		$this->session->set_flashdata('tambah', "Jabatan <b>$jabatan_nama</b> berhasil disimpan");
		$this->M_database->insertData('t_jabatan', $data);

		redirect('admin/jabatan', 'refresh');
	}

	function editjabatan()
	{
		$jabatan_id = $this->input->post('jabatan_id');
		$jabatan_nama = ucwords($this->input->post('jabatan_nama'));
		$honor_pertemuan = $this->input->post('honor_pertemuan');
		$honor_perbulan = $this->input->post('honor_perbulan');

		$data = array(
					'jabatan_nama' => $jabatan_nama,
					'honor_pertemuan' => $honor_pertemuan,
					'honor_perbulan' => $honor_perbulan
				);

		$this->session->set_flashdata('edit', "Data jabatan <b>$jabatan_nama</b> berhasil diedit");
		$this->M_database->updateData('t_jabatan', array('jabatan_id' => $jabatan_id), $data);

		redirect('admin/jabatan', 'refresh');
	}

	function hapusJabatan()
	{
		$jabatan_id = $this->input->post('jabatan_id');
		$jabatan_nama = ucwords($this->input->post('jabatan_nama'));

		$this->session->set_flashdata('hapus', "Data jabatan <b>$jabatan_nama</b> berhasil dihapus");
		$this->M_database->deleteData('t_jabatan', array('jabatan_id' => $jabatan_id));

		redirect('admin/jabatan', 'refresh');
	}


}
