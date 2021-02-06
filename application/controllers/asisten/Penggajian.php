<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggajian extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}


	public function index()
	{
		$pertemuan = $this->input->get('pertemuan');
    	$sampai = $this->input->get('sampai');
		$bulan = $this->input->get('bulan');
		
		$data['pertemuan'] = $this->input->get('pertemuan');
    	$data['sampai'] = $this->input->get('sampai');
		$data['bulan'] = $this->input->get('bulan');
		
		if ($pertemuan > 1) {
			redirect("asisten/penggajian?pertemuan=1&sampai=$sampai&bulan=$bulan");
		}
		
		$data['tabTitle'] = 'Penggajian';
		$data['asisten'] = $this->M_database->getWhere('t_asisten', 't_jabatan', 't_asisten.jabatan_id=t_jabatan.jabatan_id', array('status' => 1, 't_asisten.jabatan_id !=' => 12), 'asisten_nama ASC');
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $this->template->load('asisten/template-asisten', 'asisten/v-penggajian', $data);

    }
    
    public function cetak_penggajian()
    {
		$this->load->library('fpdfc');
      	$this->fpdfc->SetTitle('Cetak Penggajian Praktikum');
		$pertemuan = $this->input->get('pertemuan');
    	$sampai = $this->input->get('sampai');
		$bulan = $this->input->get('bulan');	
		
		if ($pertemuan > 1) {
			redirect("asisten/penggajian?pertemuan=1&sampai=$sampai&bulan=$bulan");
		}

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
		
		$koorlab = $this->db->order_by('dosen_id', 'DESC')->where('jabatan_id', 1)->limit(1)->get('t_dosen')->row();
		$asisten = $this->M_database->getWhere('t_asisten', 't_jabatan', 't_asisten.jabatan_id=t_jabatan.jabatan_id', array('status' => 1, 't_asisten.jabatan_id !=' => 12), 'asisten_nama ASC')->result();
				
        $this->fpdfc->SetMargins(10,10,0);
		$this->fpdfc->Ln();
        $this->fpdfc->AddPage('P', 'A4', 0);
        $this->fpdfc->Image("assets/images/logo-teknik.png", 11, 10, 20, 19);
        $this->fpdfc->SetFont('Arial', 'B', 11);
        $this->fpdfc->Cell(23, 5, '');
        $this->fpdfc->Cell(100, 5, 'Program Studi Teknik Informatika', 0, 1);
        $this->fpdfc->Cell(23, 5, '');
        $this->fpdfc->Cell(80, 5, 'Fakultas Teknik', 0, 1);
        $this->fpdfc->Cell(23, 5, '');
        $this->fpdfc->Cell(80, 5, 'Universitas Suryakancana Cianjur', 0, 1);
        $this->fpdfc->SetFont('Arial', '', 8);
        $this->fpdfc->Cell(23, 5, '');
        $this->fpdfc->Cell(110,5, 'Jln. Pasir Gede Raya (BLK RSU) Telp. (0263) 5019915 - Cianjur 43216', 0);
        $this->fpdfc->Cell(15, 5, 'Tanggal :');
        $this->fpdfc->Cell(7, 5, date('d'), 1, 0, 'C');
        $this->fpdfc->Cell(4, 5, ' / ');
        $this->fpdfc->Cell(7, 5, date('m'), 1, 0, 'C');
        $this->fpdfc->Cell(4, 5, ' / ');
        $this->fpdfc->Cell(15, 5, date('Y'), 1, 1, 'C');
        $this->fpdfc->SetLineWidth(0.5);
        $this->fpdfc->Line(10, 32, 200, 32);
        $this->fpdfc->SetLineWidth(0);
        $this->fpdfc->Line(10, 33, 200, 33);
        $this->fpdfc->Ln();				      			
        $this->fpdfc->Ln();				      			
        $this->fpdfc->SetFont('Arial', 'B', 10);
		$this->fpdfc->Cell(0, 5, 'Penggajian Praktikum', 0, 1, 'C');
        $this->fpdfc->Ln();				      					
        $this->fpdfc->SetFont('Arial', '', 8);
        $this->fpdfc->Cell(85, 5, '');
        $this->fpdfc->Cell(25, 5, 'Pertemuan');
        $this->fpdfc->Cell(0, 5, ': 1', 0, 1);        
        $this->fpdfc->Cell(85, 5, '');
		$this->fpdfc->Cell(25, 5, 'Sampai');
		$this->fpdfc->Cell(0, 5, ': '.$sampai, 0, 1);
        $this->fpdfc->Cell(85, 5, '');
		$this->fpdfc->Cell(25, 5, 'Bulan');
		$this->fpdfc->Cell(0, 5, ': '.$bulan, 0, 1);
        $this->fpdfc->Cell(75, 5, '');				
        $this->fpdfc->SetMargins(13,0,10);
        $this->fpdfc->Ln();
        $this->fpdfc->SetFillColor(187, 222, 251);
        $this->fpdfc->Cell(10, 7, 'No.', 1, 0, 'C', true);
        $this->fpdfc->Cell(45, 7, 'Nama', 1, 0, 'C', true);
        $this->fpdfc->Cell(38, 7, 'Jabatan', 1, 0, 'C', true);
        $this->fpdfc->Cell(14, 7, 'Jumlah', 1, 0, 'C', true);
        $this->fpdfc->Cell(27, 7, 'Honor/Pertemuan', 1, 0, 'C', true);
        $this->fpdfc->Cell(25, 7, 'Honor/Jabatan', 1, 0, 'C', true);
		$this->fpdfc->Cell(25, 7, 'Total', 1, 1, 'C', true);
		$totalJumlah = 0;
		$totalHp = 0;
		$totalHj = 0;
		$totalSubTotal = 0;
		foreach ($asisten as $key => $value) {
        $jumlah = 0;                                    
		for ($i=1; $i <= $sampai; $i++) {
			$totalPertemuan = $this->M_database->countWhere('t_absensi_asisten', array('pertemuan' => $i, 'asisten_id' => $value->asisten_id, 'status' => 1));
			$jumlah += $totalPertemuan;                                   
		}

		$hp = $value->honor_pertemuan * $jumlah;
		$hj = $value->honor_perbulan * $bulan;
		$subTotal = $hp + $hj;                    
		$totalJumlah += $jumlah;                                   
		$totalHp += $hp;
		$totalHj += $hj;
		$totalSubTotal += $subTotal;        
		$this->fpdfc->Cell(10, 7, $key+1, 1, 0, 'C');
		$this->fpdfc->Cell(45, 7, $value->asisten_nama, 1, 0);
		$this->fpdfc->Cell(38, 7, $value->jabatan_nama, 1, 0);
		$this->fpdfc->Cell(14, 7, $jumlah, 1, 0, 'C');        
		$this->fpdfc->Cell(27, 7, "Rp. ". number_format($hp, 0, '.', '.'), 1, 0);        
		$this->fpdfc->Cell(25, 7, "Rp. ". number_format($hj, 0, '.', '.'), 1, 0);        
		$this->fpdfc->Cell(25, 7, "Rp. ". number_format($subTotal, 0, '.', '.'), 1, 1);
		}     
		$this->fpdfc->Cell(93, 7, 'Total', 1, 0, 'C', true);		
		$this->fpdfc->Cell(14, 7, $totalJumlah, 1, 0, 'C');        
		$this->fpdfc->Cell(27, 7, "Rp. ". number_format($totalHp, 0, '.', '.'), 1, 0);        
		$this->fpdfc->Cell(25, 7, "Rp. ". number_format($totalHj, 0, '.', '.'), 1, 0);        
		$this->fpdfc->Cell(25, 7, "Rp. ". number_format($totalSubTotal, 0, '.', '.'), 1, 1);   
        $this->fpdfc->Ln();     
        $this->fpdfc->Cell(50, 5, '', 0 , 0, 'C');
        $this->fpdfc->Cell(80, 5, '');
		$this->fpdfc->Cell(50, 5, 'Cianjur, '.date('d').' '.$bulan_ini.' '.date('Y'), 0, 0, 'C');
		$this->fpdfc->Ln();
		$this->fpdfc->Cell(50, 5, '', 0 , 0, 'C');
        $this->fpdfc->Cell(80, 5, '');
		$this->fpdfc->Cell(50, 5, 'Koordinator Laboratorium', 0, 0, 'C');
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
        $this->fpdfc->Cell(50, 5, '', 0, 0, 'C');
        $this->fpdfc->Cell(80, 5, '');
		$this->fpdfc->Cell(50, 5, $koorlab->dosen_nama, 0, 0, 'C');      

      	$this->fpdfc->output('I', 'Penggajian-praktikum.pdf');          		
	}

}
