<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$jumlah_data = $this->M_database->jumlah_data('t_kelas');
		$offset = $this->input->get('per_page');

		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/kelas';
		$config['page_query_string'] = TRUE;
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 50;
		$config['first_link']       = '...';
        $config['last_link']        = '...';
        $config['next_link']        = 'NEXT';
        $config['prev_link']        = 'PREV';
        $config['full_tag_open']    = '<div class="custom-pagination">';
        $config['full_tag_close']   = '</div>';
        $config['num_tag_open']     = '<button class="btn transparent z-depth-0" style="padding: 0">';
        $config['num_tag_close']    = '</button>';
        $config['cur_tag_open']     = '<button class="btn indigo" style="padding: 0  24px">';
        $config['cur_tag_close']    = '</button>';
        $config['next_tag_open']    = '<button class="btn transparent z-depth-0 grey-text" style="padding: 0">';
        $config['next_tagl_close']  = '</button>';
        $config['prev_tag_open']    = '<button class="btn transparent z-depth-0 grey-text" style="padding: 0">';
        $config['prev_tagl_close']  = '</button>';
        $config['first_tag_open']   = '<button class="btn transparent z-depth-0" style="padding: 0">';
        $config['first_tagl_close'] = '</button>';
        $config['last_tag_open']    = '<button class="btn transparent z-depth-0" style="padding: 0">';
        $config['last_tagl_close']  = '</button>';
		$this->pagination->initialize($config);		
		$data['pagination'] = $this->pagination->create_links();

		$data['kelas'] = $this->M_database->getAllData('t_kelas', null, null, 'kelas_status DESC', 'kelas_id DESC', $config['per_page'], $offset);
		$data['halaman_title'] = "Master Kelas";
		$data['tabTitle'] = 'Master Kelas';
		$this->template->load('admin/template', 'admin/v-kelas', $data);
	}

	function tambahKelas()
	{
		$kelas_kode = $this->input->post('kelas_kode');
		$kelas_nama = $this->input->post('kelas_nama');

		$data = array(
					'kelas_kode' => $kelas_kode,
					'kelas_nama' => $kelas_nama,
					'kelas_status' => 1
				);

		$this->session->set_flashdata('tambah', "Data kelas <b>$kelas_nama</b> berhasil disimpan");
		$this->M_database->insertData('t_kelas', $data);

		redirect('admin/kelas', 'refresh');
	}

	function editKelas()
	{
		$kelas_id = $this->input->post('kelas_id');
		$kelas_kode = $this->input->post('kelas_kode');
		$kelas_nama = $this->input->post('kelas_nama');

		$data = array(
					'kelas_kode' => $kelas_kode,
					'kelas_nama' => $kelas_nama
				);

		$this->session->set_flashdata('edit', "Data kelas <b>$kelas_nama</b> berhasil diedit");
		$this->M_database->updateData('t_kelas', array('kelas_id' => $kelas_id), $data);

		redirect('admin/kelas', 'refresh');
	}

	function hapusKelas()
	{
		$kelas_id = $this->input->post('kelas_id');
		$kelas_nama = $this->input->post('kelas_nama');

		$this->session->set_flashdata('hapus', "Data kelas <b>$kelas_nama</b> berhasil dihapus");
		$this->M_database->deleteData('t_kelas', array('kelas_id' => $kelas_id));

		redirect('admin/kelas', 'refresh');
	}

	function statusKelas()
	{
		$kelas_id = $this->input->post('kelas_id');
		$status = $this->input->post('status');
		$kelas = $this->db->select('kelas_kode')->where('kelas_id', $kelas_id)->get('t_kelas')->row();
		$kelas_kode = $kelas->kelas_kode;

		if ($status == 'Aktif') {
			$data = array('kelas_status' => 0);
			$data_mhs = array('status_mhs' => 0);
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
			$data = array('kelas_status' => 1);
			$data_mhs = array('status_mhs' => 1);
			$badge = '<span class="badge-status badge-ok">Aktif</span>';
		}		

		$this->M_database->updateData('t_kelas', array('kelas_id' => $kelas_id), $data);
		$this->M_database->updateData('t_mahasiswa', array('kelas_kode' => $kelas_kode), $data_mhs);

		$npm = [];
		$token = $this->M_database->getWhere('t_mahasiswa', 't_device_token', 't_mahasiswa.npm=t_device_token.npm', array('kelas_kode' => $kelas_kode));
		foreach ($token->result() as $key => $value) {
			array_push($npm, $value->npm);
		}

		if ($npm) {			
			$this->db->where_in('npm', $npm)->delete('t_device_token');
		}

		echo $badge;
	}


}
