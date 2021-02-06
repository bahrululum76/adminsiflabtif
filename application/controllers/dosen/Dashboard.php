<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_dosen();
	}


	public function index()
	{	
		$asisten_id = $this->session->userdata('asisten_id');
		$data['jml_notif'] = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => "asisten_$asisten_id"));	
		$data['halaman_title'] = "Dashboard";
		$data['tabTitle'] = 'Dashboard';
		$this->template->load('dosen/template-dosen', 'dosen/v-dashboard', $data);
	}
	
	public function jadwalPraktikum()
	{
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
			default:
				$hari = 'Sabtu';
				break;
		}
		$bulan = date('m');
		switch ($bulan) {
			case '01':
				$bulan = 'Januari';
				break;
			case '02':
				$bulan = 'Februari';
				break;
			case '03':
				$bulan = 'Maret';
				break;
			case '04':
				$bulan = 'April';
				break;
			case '05':
				$bulan = 'Mei';
				break;
			case '06':
				$bulan = 'Juni';
				break;
			case '07':
				$bulan = 'Juli';
				break;
			case '08':
				$bulan = 'Agustus';
				break;
			case '09':
				$bulan = 'September';
				break;
			case '10':
				$bulan = 'Oktober';
				break;
			case '11':
				$bulan = 'November';
				break;
			case '12':
				$bulan = 'Desember';
				break;
			default:
				$bulan = 'Pilih Bulan';
				break;
		}
		
		$data['sekarang'] = date('d')." $bulan ".date('Y');
		$data['hari_ini'] = $hari;
		$data['tabTitle'] = 'Jadwal Praktikum';
		$periode = $this->db->order_by('periode_id', 'DESC')->limit(1)->get('t_periode')->row();		
		$data['jadwal'] = $this->M_database->getJadwal(array('periode_id' => $periode->periode_id, 'status' => 1, 'jadwal_hari' => $hari), 'jadwal_jam ASC');
		// var_dump($this->M_database->getJadwal(array('periode_id' => $periode->periode_id, 'status' => 1, 'jadwal_hari' => $hari), 'jadwal_jam ASC')->row());die;
		$this->template->load('dosen/template-dosen', 'dosen/v-jadwal', $data);
	}

	public function lihat_jadwal()
	{
		$periode = $this->db->order_by('periode_id', 'DESC')->limit(1)->get('t_periode')->row();
		$jadwal_hari = $this->input->get('jadwal_hari');
		$jadwal = $this->M_database->getJadwal(array('periode_id' => $periode->periode_id, 'status' => 1, 'jadwal_hari' => $jadwal_hari), 'jadwal_jam ASC')->result();
		foreach ($jadwal as $key => $value) {
			$asisten_1 = $this->db->where('asisten_id', $value->asisten_1)->get('t_asisten')->row();
			$asisten_2 = $this->db->where('asisten_id', $value->asisten_2)->get('t_asisten')->row();			
			$pertemuan = $this->db->select('absen_tgl, pertemuan')->order_by('pertemuan', 'DESC')->limit(1)->where(array('jadwal_kode' => $value->jadwal_kode))->get('t_absensi_asisten')->row();                
			if ($value->ruangan_id == 1) {
				$color = 'pink';
				$ruangan = 'LD';
			} else if ($value->ruangan_id == 3) {
				$color = 'amber';
				$ruangan = 'LJ';                
			} else if ($value->ruangan_id == 4) {
				$color = 'blue';
				$ruangan = 'LM';
			}

			if ($pertemuan == null) {
				$pertemuan = "1";
			} else {
				$pertemuan = $pertemuan->pertemuan;
			}
	
			echo '<div class="jadwal-item-dashboard">
				<div class="ruangan '.$color.'">'.$ruangan.'</div>
				<div class="matkum">'.$value->matkum.'</div>
				<div style="margin-bottom:8px"><i class="tiny material-icons-outlined" style="position:relative;top:3px;margin-right:4px">schedule</i> '.$value->jadwal_jam.'</div>                  
				<div>'.strtoupper($value->jadwal_kode).'</div>
				<div>Kelas : <b>'.$value->kelas_nama.'</b></div>
				<div>Pengajar 1 &nbsp;: <b>'.$asisten_1->asisten_nama.'</b></div>
				<div>Pengajar 2 : <b>'.$asisten_2->asisten_nama.'</b></div>
				<div style="margin-top:8px">Pertemuan : <b>'.$pertemuan.'</b></div>
			</div>';
		}
		if ($jadwal == null) {
			echo "kosong|<div class='center-align'>Tidak ada jadwal</div>";
		}
	}

}
