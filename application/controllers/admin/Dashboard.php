<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("M_database");
		cek_session();
	}


	public function index()
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
			case 'Sat':
				$hari = 'Sabtu';
				break;
			default:
				$hari = 'Tidak ada';
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
		$data['tabTitle'] = 'Dashboard';
		$periode = $this->db->order_by('periode_id', 'DESC')->limit(1)->get('t_periode')->row();
		$data['jumlah_mhs'] = $this->M_database->countWhere('t_mahasiswa', 'status_mhs = 1');
		$data['jumlah_kelas'] = $this->M_database->countWhere('t_kelas', 'kelas_status = 1');
		$data['jumlah_jadwal'] = $this->M_database->countWhere('t_jadwal', 'status = 1');
		$data['jumlah_asisten'] = $this->M_database->countWhere('t_asisten', array('status' => 1, 'jabatan_id !=' => 12));
		$data['jadwal'] = $this->M_database->getJadwal(array('periode_id' => $periode->periode_id, 'status' => 1, 'jadwal_hari' => $hari), 'jadwal_jam ASC');
		$data['halaman_title'] = "Dashboard";
		$this->template->load('admin/template', 'admin/v-dashboard', $data);
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
