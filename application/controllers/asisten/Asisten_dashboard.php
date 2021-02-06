<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asisten_dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}

	public function index()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		$uid = $this->session->userdata('uid');
		$jabatan = $this->session->userdata('jabatan');
		$hari = date('D');
		switch ($hari) {
			case 'Sun':
				$hari = 'Minggu';
				break;
			case 'Mon':
				$hari = 'Senin';
				break;
			case 'Tue':
				$hari = 'Selasa';
				break;
			case 'Wed':
				$hari = 'Rabu';
				break;
			case 'Thu':
				$hari = 'Kamis';
				break;
			case 'Fri':
				$hari = 'Jumat';
				break;
			case 'Sat':
				$hari = 'Sabtu';
				break;
			default:
				$hari = 'Tidak ada';
				break;
		}
		$data['tabTitle'] = 'Dashboard';
		$data['hari_ini'] = $hari;		
		$data['halaman_title'] = ucfirst($this->uri->segment(2). " " .$this->uri->segment(3));
		$data['jadwal'] = $this->M_database->getJadwalDashboard("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id", 'jadwal_jam ASC', array('jadwal_hari' => $hari))->result();
		$data['catatan'] = $this->db->order_by('catatan_id', 'DESC')->where(array('catatan_status' => 1, 'catatan_user' => $jabatan.$uid))->where("catatan_role='admin' OR catatan_role='koorlab'", NULL, FALSE)->get('t_catatan')->result();
		$data['hari_ini_title'] = "Jadwal hari ini,";
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
		// $now = date('Y-m-d H:i:s');
		// $tugas = $this->db->where("batas_waktu < '$now'")->get('t_tugas')->result();
		// foreach ($tugas as $key => $value) {
		// 	$this->M_database->updateData('t_tugas', array('tugas_id' => $value->tugas_id), array('tugas_status' => 0));
		// }							
		$this->template->load('asisten/template-asisten', 'asisten/v-dashboard', $data);
	}

	public function lihat_jadwal()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		$jadwal_hari = $this->input->get('jadwal_hari');
		$jadwal = $data['jadwal'] = $this->M_database->getJadwalDashboard("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id", 'jadwal_jam ASC', array('jadwal_hari' => $jadwal_hari))->result();
		foreach ($jadwal as $key => $value) {
			$asisten_1 = $this->db->where('asisten_id', $value->asisten_1)->get('t_asisten')->row();
			$asisten_2 = $this->db->where('asisten_id', $value->asisten_2)->get('t_asisten')->row();

			$nama1 = explode(' ', $asisten_1->asisten_nama);
			if (sizeof($nama1) > 1) {
				if ($nama1[0] == 'M' || $nama1[0] == 'M.' || $nama1[0] == 'Muhamad' || $nama1[0] == 'Muhammad' || $nama1[0] == 'Moh' || $nama1[0] == 'Moh.' || $nama1[0] == 'Mohamed' || $nama1[0] == 'Mohammed' || $nama1[0] == 'Moch' || $nama1[0] == 'Moch.' || $nama1[0] == 'Mochamad' || $nama1[0] == 'Mochammad' || $nama1[0] == 'Much' || $nama1[0] == 'Much.' || $nama1[0] == 'Muchamad' || $nama1[0] == 'Muchammad') {
					$panggilan_1 = $nama1[1];
				} else {
					$panggilan_1 = $nama1[0];
				}
			} else {
				$panggilan_1 = $asisten_1->asisten_nama;
			}
			

			$nama2 = explode(' ', $asisten_2->asisten_nama);
			if (sizeof($nama2) > 1) {
				if ($nama2[0] == 'M' || $nama2[0] == 'M.' || $nama2[0] == 'Muhamad' || $nama2[0] == 'Muhammad' || $nama2[0] == 'Moh' || $nama2[0] == 'Moh.' || $nama2[0] == 'Mohamed' || $nama2[0] == 'Mohammed' || $nama2[0] == 'Moch' || $nama2[0] == 'Moch.' || $nama2[0] == 'Mochamad' || $nama2[0] == 'Mochammad' || $nama2[0] == 'Much' || $nama2[0] == 'Much.' || $nama2[0] == 'Muchamad' || $nama2[0] == 'Muchammad') {
				$panggilan_2 = $nama2[1];
				} else {
					$panggilan_2 = $nama2[0];
				}
			} else {
				$panggilan_2 = $asisten_2->asisten_nama;
			}			

			echo '<div class="jadwal-item">
                        <span class="txt-hari">'.$value->jadwal_hari.'</span>                                
                        <span class="txt-jam">'.$value->jadwal_jam.'</span>                                                           
                        <span class="txt-kode">'.strtoupper($value->jadwal_kode).'</span>                                
                        <span class="txt-matkum">'.ucwords($value->matkum).'</span>                                
                        <span class="txt-asisten">'.$panggilan_1.' & '.$panggilan_2.'</span>                       
                        <div class="line-dashed"></div>
                        <span class="txt-ruangan">'.$value->ruangan_nama.'</span>
                    </div>';
		}
		if ($jadwal == null) {
			echo "kosong|<div class='jadwal-item center-align'>Tidak ada jadwal</div>";
		}
	}

	public function akses()
	{
		$this->template->load('asisten/template-asisten', 'asisten/v-akses');
	}


}
