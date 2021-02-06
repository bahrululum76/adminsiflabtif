<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$jadwal_kode = $this->input->get('idp');
		$data['tabTitle'] = 'Absensi';
		$data['mahasiswa'] = $this->M_database->getRegistrasiMahasiswa(array('registrasi_praktikum.jadwal_kode' => $jadwal_kode));
		$data['jadwal'] = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC');
        $data['halaman_title'] = "Absensi Praktikum";
        $data['absen_tgl'] = $this->M_database->cekTanggal(array('jadwal_kode' => $jadwal_kode));           

        $this->template->load('admin/template', 'admin/v-absensi', $data);

	}	

	function do_absen()
	{
		$jadwal_kode = $this->input->post('jadwal_kode');
		$npm = $this->input->post('npm');
		$kehadiran = $this->input->post('kehadiran');
		$pertemuan = $this->input->post('pertemuan');
		$tgl = $this->input->post('tgl');

		$data = array(		
				'jadwal_kode' => $jadwal_kode,
				'pertemuan' => $pertemuan,
				'npm' => $npm,
				'absen_tgl' => $tgl
			);
		
		// cek absensi
		$cek = $this->M_database->cekAbsen('t_absensi', $jadwal_kode, $npm, $pertemuan);

		if($kehadiran == 'Tidak Hadir' && $cek->hadir == 0)
		{
			$this->M_database->insertData('t_absensi',$data);
			$badge = '<span class="badge-status badge-ok">Hadir</span>'; 
		}
		else if($kehadiran == 'Hadir' && $cek->hadir == 1)
		{
			$this->M_database->hapusAbsen('t_absensi', 'jadwal_kode', 'npm', 'pertemuan', $jadwal_kode, $npm, $pertemuan);
			$badge = '<span class="badge-status badge-not">Tidak Hadir</span>'; 
		}
		else
		{
			$badge = '<span class="badge-status badge-not">Tidak Hadir</span>'; 
			
		}
		echo $badge;
	}

	function do_absen_asisten()
	{
		$jadwal_kode = $this->input->post('jadwal_kode');
		$asisten_id = $this->input->post('asisten_id');
		$kehadiran = $this->input->post('kehadiran');
		$pertemuan = $this->input->post('pertemuan');
		$tgl = $this->input->post('tgl');

		$data = array(		
				'jadwal_kode' => $jadwal_kode,
				'pertemuan' => $pertemuan,
				'asisten_id' => $asisten_id,
				'absen_tgl' => $tgl
			);

		// cek absensi
		$cek = $this->M_database->cekAbsenAsisten('t_absensi_asisten', $jadwal_kode, $asisten_id, $pertemuan);	

		if($kehadiran == 'Tidak Hadir' && $cek->hadir == 0){
			$this->M_database->insertData('t_absensi_asisten',$data);
			$badge = '<span class="badge-status badge-ok">Hadir</span>';
		}
		else if($kehadiran == 'Hadir' && $cek->hadir == 1){
			$this->M_database->hapusAbsen('t_absensi_asisten', 'jadwal_kode', 'asisten_id', 'pertemuan', $jadwal_kode, $asisten_id, $pertemuan);
			$badge = '<span class="badge-status badge-not">Tidak Hadir</span>';
		}
		else{
			$badge = '<span class="badge-status badge-not">Tidak Hadir</span>';
		}				
		echo "$badge";		
	}	



}
