<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$data['tabTitle'] = 'Pembayaran Praktikum';
		$data['jumlah_mhs'] = $this->M_database->countWhere('t_mahasiswa', 'status_mhs = 1');
		$data['jumlah_lunas'] = $this->M_database->countWhere('t_mahasiswa', array('status_mhs' => 1, 'status_bayar' => 1));
		$data['jumlah_belum'] = $this->M_database->countWhere('t_mahasiswa', array('status_mhs' => 1, 'status_bayar' => 0));
		$data['mahasiswa'] = $this->M_database->getWhere('t_mahasiswa', 't_kelas', 't_mahasiswa.kelas_kode = t_kelas.kelas_kode', array('status_mhs' => 1), 'npm DESC');
        $data['halaman_title'] = "Pembayaran Praktikum";
		$this->template->load('admin/template', 'admin/v-pembayaran', $data);
	}

	function statusBayar()
	{
		$npm = $this->input->post('npm');
		$status = $this->input->post('status');

		if ($status == 'Lunas') {
			$data = array('status_bayar' => 0, 'status_bayar_bak' => 0);
			$badge = '<span class="badge-status badge-not">Belum</span>';
			$pembayaran = 'belum';
		} else  {
			$data = array('status_bayar' => 1, 'status_bayar_bak' => 1);
			$badge = '<span class="badge-status badge-ok">Lunas</span>';
			$pembayaran = 'lunas';
		}

		$this->M_database->updateData('t_mahasiswa', array('npm' => $npm), $data);

		echo "$pembayaran|$badge";
	}	

}
