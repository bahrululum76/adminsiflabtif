<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
        cek_session_bak();        
	}


	public function index()
	{
		$data['tabTitle'] = 'Pembayaran Praktikum';
		$data['jumlah_mhs'] = $this->M_database->countWhere('t_mahasiswa', 'status_mhs = 1');
		$data['jumlah_lunas'] = $this->M_database->countWhere('t_mahasiswa', array('status_mhs' => 1, 'status_bayar_bak' => 1));
		$data['jumlah_belum'] = $this->M_database->countWhere('t_mahasiswa', array('status_mhs' => 1, 'status_bayar_bak' => 0));
		$data['mahasiswa'] = $this->M_database->getWhere('t_mahasiswa', 't_kelas', 't_mahasiswa.kelas_kode = t_kelas.kelas_kode', array('status_mhs' => 1), 'npm DESC');
        $data['halaman_title'] = "Pembayaran Praktikum";
		$this->template->load('bak/template-bak', 'bak/v-pembayaran', $data);
	}

	function statusBayar()
	{
		$npm = $this->input->post('npm');
		$status = $this->input->post('status');

		if ($status == 'Lunas') {
			$tgl = '-';
			$data = array('status_bayar_bak' => 0, 'tgl_bayar_bak' => $tgl);
			$badge = '<span class="badge-status badge-not">Belum</span>';
			$pembayaran = 'belum';
		} else  {
			$tgl = date('d-m-Y');
			$data = array('status_bayar_bak' => 1, 'tgl_bayar_bak' => $tgl);
			$badge = '<span class="badge-status badge-ok">Lunas</span>';
			$pembayaran = 'lunas';
		}

		$this->M_database->updateData('t_mahasiswa', array('npm' => $npm), $data);

		echo "$pembayaran|$badge|$tgl";
	}	

	public function cetak_pembayaran_praktikum()
    {
		$periode = $this->db->order_by('periode_id', 'DESC')->limit(1)->get('t_periode')->row();
		$data['periode'] = $this->db->order_by('periode_id', 'DESC')->limit(1)->get('t_periode')->row();
		$mahasiswa = $this->M_database->getWhere('t_mahasiswa', 't_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode', array('status_mhs' => 1), 'npm DESC');
		$bulan_ini = date('m');
		switch ($bulan_ini) {
			case '01':
				$bulan_ini = 'Januari';
				break;
			case '02':
				$bulan_ini = 'Februari';
				break;
			case '03':
				$bulan_ini = 'Maret';
				break;
			case '04':
				$bulan_ini = 'April';
				break;
			case '05':
				$bulan_ini = 'Mei';
				break;
			case '06':
				$bulan_ini = 'Juni';
				break;
			case '07':
				$bulan_ini = 'Juli';
				break;
			case '08':
				$bulan_ini = 'Agustus';
				break;
			case '09':
				$bulan_ini = 'September';
				break;
			case '10':
				$bulan_ini = 'Oktober';
				break;
			case '11':
				$bulan_ini = 'November';
				break;
			case '12':
				$bulan_ini = 'Desember';
				break;
			default:
				$bulan_ini = 'Pilih Bulan';
				break;
		}
		$this->load->library('fpdfc');
		$this->fpdfc->SetTitle('Cetak Pembayaran Praktikum');

		$this->fpdfc->AddPage('P', 'A4', 0);
		$this->fpdfc->Image("assets/images/logo-teknik.png", 11, 10, 20, 19);
			$this->fpdfc->SetFont('Arial', 'B', 11);
			$this->fpdfc->Cell(23, 5, '');
			$this->fpdfc->Text(34, 14, 'Program Studi Teknik Informatika');
			$this->fpdfc->Text(34, 19, 'Fakultas Teknik');
			$this->fpdfc->Text(34, 24, 'Universitas Suryakancana Cianjur');
			$this->fpdfc->SetFont('Arial', '', 8);
			$this->fpdfc->Text(34, 29, 'Jln. Pasir Gede Raya (BLK RSU) Telp. (0263) 5019915 - Cianjur 43216');      
			$this->fpdfc->Text(144, 29, 'Tanggal :');
			$this->fpdfc->Text(161.5, 29.5, date('d'));
			$this->fpdfc->Rect(159, 26, 8, 5);
			$this->fpdfc->Text(174.3, 29.5, date('m'));
			$this->fpdfc->Text(169, 30, '/');
			$this->fpdfc->Rect(172, 26, 8, 5);
			$this->fpdfc->Text(189, 29.5, date('Y'));
			$this->fpdfc->Text(182, 30, '/');
			$this->fpdfc->Rect(185, 26, 14, 5);
			$this->fpdfc->SetLineWidth(0.5);
			$this->fpdfc->Line(10, 33, 200, 33);
			$this->fpdfc->SetLineWidth(0);
			$this->fpdfc->Line(10, 34, 200, 34);
			if ($this->fpdfc->PageNo() !== 1) {
				$this->fpdfc->Ln(24);                
			} else {
				$this->fpdfc->Ln(29);      
			}      
			$this->fpdfc->SetFont('Arial', 'B', 10);
			$this->fpdfc->Cell(0, 5, 'Pembayaran Praktikum', 0, 1, 'C');			
			$this->fpdfc->Cell(0, 5, $periode->periode_nama, 0, 1, 'C');			
			$this->fpdfc->SetMargins(15,0,20);
			$this->fpdfc->Ln();
			$this->fpdfc->SetFillColor(187, 222, 251);
			$this->fpdfc->SetFont('Arial', '', 8);
			$this->fpdfc->Cell(10, 7, 'No', 1, 0, 'C', true);
			$this->fpdfc->Cell(30, 7, 'NPM', 1, 0, 'C', true);
			$this->fpdfc->Cell(50, 7, 'Nama', 1, 0, 'C', true);
			$this->fpdfc->Cell(30, 7, 'Kelas', 1, 0, 'C', true);
			$this->fpdfc->Cell(30, 7, 'Tanggal Bayar', 1, 0, 'C', true);
			$this->fpdfc->Cell(30, 7, 'Status Pembayaran', 1, 1, 'C', true);
			$this->fpdfc->SetWidths(array(10,30,50,30,30, 30));		
			$this->fpdfc->SetAligns(array('C','L','L','L','C', 'C'));		
		foreach ($mahasiswa->result() as $key => $value) {
			if ($value->status_bayar_bak == 1) {
				$status_bayar = 'Lunas';
			} else {
				$status_bayar = 'Belum Lunas';
			}			
			$this->fpdfc->Row(array($key+1,$value->npm, $value->nama, $value->kelas_nama, $value->tgl_bayar_bak, $status_bayar));
		}
		$this->fpdfc->Ln();     
		$this->fpdfc->Ln();     
		$this->fpdfc->Cell(50, 5, '', 0 , 0, 'C');
		$this->fpdfc->Cell(70, 5, '');
		$this->fpdfc->Cell(50, 5, 'Cianjur, '.date('d').' '.$bulan_ini.' '.date('Y'), 0, 1, 'C');
		$this->fpdfc->Cell(50, 5, '', 0 , 0, 'C');
		$this->fpdfc->Cell(70, 5, '');
		$this->fpdfc->Cell(50, 5, 'Bagian Administrasi dan Keuangan', 0, 1, 'C');
		$this->fpdfc->Cell(50, 5, '', 0 , 0, 'C');
		$this->fpdfc->Cell(70, 5, '');
		$this->fpdfc->Cell(50, 5, 'FT UNSUR', 0, 1, 'C');
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Cell(50, 5, '', 0, 0, 'C');
		$this->fpdfc->Cell(70, 5, '');
		$this->fpdfc->Cell(50, 5, $this->session->userdata('nama'), 0, 0, 'C');

      $this->fpdfc->output('I', 'Pembayaran-praktikum.pdf');      
	}

}
