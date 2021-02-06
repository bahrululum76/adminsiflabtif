<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$data['ruangan'] = $this->M_database->getAllData('t_ruangan', 't_jabatan', 't_ruangan.ruangan_pj=t_jabatan.jabatan_id');
		$data['jabatan'] = $this->M_database->getWhere('t_jabatan', null, null, array('jabatan_id != ' => 1, 'jabatan_id !=' => 13, 'jabatan_id !=' => 3));
		$data['jabatan'] = $this->db->order_by('jabatan_nama ASC')->where('jabatan_id !=', 1)->where('jabatan_id !=', 12)->where('jabatan_id !=', 13)->get('t_jabatan');
		$data['halaman_title'] = "Master Ruangan";
		$data['tabTitle'] = 'Master Ruangan';
		$this->template->load('admin/template', 'admin/v-ruangan', $data);
	}

	function tambahRuangan()
	{
		$ruangan_nama = $this->input->post('ruangan_nama');
		$kapasitas = $this->input->post('kapasitas');
		$ruangan_pj = $this->input->post('ruangan_pj');

		$data = array(
					'ruangan_nama' => $ruangan_nama,
					'ruangan_kapasitas' => $kapasitas,
					'ruangan_pj' => $ruangan_pj
				);

		$this->session->set_flashdata('tambah', "Data ruangan <b>$ruangan_nama</b> berhasil disimpan");
		$this->M_database->insertData('t_ruangan', $data);

		redirect('admin/ruangan', 'refresh');
	}

	function editRuangan()
	{
		$ruangan_id = $this->input->post('ruangan_id');
		$ruangan_nama = $this->input->post('ruangan_nama');
		$kapasitas = $this->input->post('kapasitas');
		$ruangan_pj = $this->input->post('ruangan_pj');

		$data = array(
					'ruangan_nama' => $ruangan_nama,
					'ruangan_kapasitas' => $kapasitas,
					'ruangan_pj' => $ruangan_pj		
				);

		$this->session->set_flashdata('edit', "Data ruangan <b>$ruangan_nama</b> berhasil diedit");
		$this->M_database->updateData('t_ruangan', array('ruangan_id' => $ruangan_id), $data);

		redirect('admin/ruangan', 'refresh');
	}

	function hapusRuangan()
	{
		$ruangan_id = $this->input->post('ruangan_id');
		$ruangan_nama = $this->input->post('ruangan_nama');

		$this->session->set_flashdata('hapus', "Data ruangan <b>$ruangan_nama</b> berhasil dihapus");
		$this->M_database->deleteData('t_ruangan', array('ruangan_id' => $ruangan_id ));

		redirect('admin/ruangan', 'refresh');
	}


}
