<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catatan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_bak();
    }
    
	public function index()
	{
		$uid = $this->session->userdata('uid');
		$jabatan = $this->session->userdata('jabatan');
		$user = $jabatan.$uid;
		
		$data['tabTitle'] = 'Catatan';
		$data['catatanPin'] = $this->M_database->getWhere('t_catatan', null, null, array('catatan_status' => 1, 'catatan_user' => $user), 'catatan_role ASC', 'catatan_id DESC')->result();
		$data['catatan'] = $this->M_database->getWhere('t_catatan', null, null, array('catatan_status' => 0, 'catatan_user' => $user), 'catatan_role DESC', 'catatan_id DESC')->result();
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $this->template->load('bak/template-bak', 'bak/v-catatan', $data);

	}
	
	public function tambahCatatan()
	{
		$catatan_user = $this->session->userdata('jabatan').$this->session->userdata('uid');
		$catatan_role = str_replace('_', '', $this->session->userdata('jabatan'));
		$catatan_judul = $this->input->post('catatan_judul');
		$catatan_isi = $this->input->post('catatan_isi');
		$catatan_warna = $this->input->post('catatan_warna');
		$catatan_status = $this->input->post('catatan_status');
		
		$data = array(
			'catatan_judul' => $catatan_judul,		
			'catatan_isi' => $catatan_isi,		
			'catatan_user' => $catatan_user,		
			'catatan_role' => $catatan_role,
			'created_at' => date('Y-m-d H:i:s'),
			'catatan_warna' => $catatan_warna,		
			'catatan_status' => $catatan_status		
		);

		$this->M_database->insertData('t_catatan', $data);
		$this->session->set_flashdata('tambah', 'Catatan berhasil disimpan');

		redirect('bak/catatan', 'refresh');
	}
	
	public function editCatatan()
	{
		$catatan_id = $this->input->post('catatan_id');
		$catatan_judul = $this->input->post('catatan_judul');
		$catatan_isi = $this->input->post('catatan_isi');
		$catatan_warna = $this->input->post('catatan_warna');
		$catatan_status = $this->input->post('catatan_status');
		
		$data = array(
			'catatan_judul' => $catatan_judul,		
			'catatan_isi' => $catatan_isi,			
			'catatan_warna' => $catatan_warna,		
			'catatan_status' => $catatan_status		
		);

		$this->M_database->updateData('t_catatan', array('catatan_id' => $catatan_id), $data);
		$this->session->set_flashdata('edit', 'Catatan berhasil diedit');

		redirect('bak/catatan', 'refresh');
	}
	
	public function hapusCatatan()
	{
		$catatan_id = $this->input->post('catatan_id');	

		$this->M_database->deleteData('t_catatan', array('catatan_id' => $catatan_id));
		$this->session->set_flashdata('hapus', 'Catatan berhasil dihapus');

		redirect('bak/catatan', 'refresh');
	}

}
