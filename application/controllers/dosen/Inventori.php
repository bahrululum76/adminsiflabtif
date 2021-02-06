<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventori extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_dosen();
	}


	public function index()
	{
		$menu = $this->input->get('menu');
		$laporan_id = $this->input->get('lid');
		$ruangan_id = $this->input->get('rid');
		$data['tabTitle'] = 'Inventori';
        $data['ruangan'] = $this->M_database->getAllData('t_ruangan', null, null, 'ruangan_id ASC');
		if ($menu != null) {
            if ($menu == 'laporan') {
                $data['laporan'] = $this->M_database->getWhere('t_inv_laporan', 't_asisten', 't_inv_laporan.asisten_id=t_asisten.asisten_id', array('ruangan_id' => $ruangan_id), 'laporan_id DESC');
				$this->template->load('dosen/template-dosen', 'dosen/inventori/v-laporan', $data);
			} elseif ($menu == 'lihat-laporan') {
				$data['pc'] = $this->M_database->getLaporanPC($laporan_id);
				$data['barang'] = $this->M_database->getLaporanInventori($laporan_id);
				$this->template->load('dosen/template-dosen', 'dosen/inventori/v-laporan-lihat', $data);
			} else {
				$this->template->load('dosen/template-dosen', 'dosen/inventori/v-laporan', $data);
			}
		} else {
			$this->template->load('dosen/template-dosen', 'dosen/inventori/v-laporan', $data);
		}

    }

	function GenerateWord()
		{
			//Get a random word
			$nb=rand(3,10);
			$w='';
			for($i=1;$i<=$nb;$i++)
				$w.=chr(rand(ord('a'),ord('z')));
			return $w;
		}

		function GenerateSentence()
		{
			//Get a random sentence
			$nb=rand(1,10);
			$s='';
			for($i=1;$i<=$nb;$i++)
				$s.=$this->GenerateWord().' ';
			return substr($s,0,-1);
		}

	public function cetak_inventori()
    {
		$laporan = $this->input->get('laporan');
		$laporan_id = $this->input->get('lid');
		$ruangan_id = $this->input->get('rid');
		$pj = explode('-', $laporan);
		$barang = $this->M_database->getLaporanInventori($laporan_id)->result();
		$koorlab = $this->db->order_by('dosen_id', 'DESC')->where('jabatan_id', 1)->limit(1)->get('t_dosen')->row();
		$laporanDetail = $this->M_database->getWhere('t_inv_laporan', 't_asisten', 't_inv_laporan.asisten_id=t_asisten.asisten_id', array('laporan_id' => $laporan_id))->row();				
		
		$this->load->library('fpdfc');
		$this->fpdfc->SetTitle("Cetak Laporan $laporan");
		$this->fpdfc->SetAutoPageBreak(true, 60);
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
        $this->fpdfc->Ln(29);				      			
        $this->fpdfc->SetFont('Arial', 'B', 10);
		$this->fpdfc->Cell(0, 5, "LAPORAN $laporan", 0, 1, 'C');
        $this->fpdfc->Ln();				      					
        $this->fpdfc->SetFont('Arial', '', 8);        				
		$this->fpdfc->SetMargins(13,0,10);
		$this->fpdfc->Cell(0, 8, "Barang $pj[1]", 0, 1, 'C');		
        $this->fpdfc->SetFillColor(187, 222, 251);
        $this->fpdfc->Cell(10,10, 'No.', 'LT', 0, 'C', true);
        $this->fpdfc->Cell(70,10, 'Nama Barang', 'LT', 0, 'C', true);
        $this->fpdfc->Cell(14,10, 'Jumlah', 'LT', 0, 'C', true);
		$this->fpdfc->Cell(42,5, 'Kondisi', 1, 0, 'C', true);
		$this->fpdfc->Cell(50,10, 'Keterangan', 'LTR', 0, 'C', true);
		$this->fpdfc->Cell(10,5, '', '', 1, 'C');
		$this->fpdfc->Cell(10,0, '', 0, 0, 'C');
        $this->fpdfc->Cell(70,0, '', 0, 0, 'C');
        $this->fpdfc->Cell(14,0, '', 0, 0, 'C');
        $this->fpdfc->Cell(14,5, 'Bagus', 1, 0, 'C', true);
        $this->fpdfc->Cell(14,5, 'Rusak', 1, 0, 'C', true);
		$this->fpdfc->Cell(14,5, 'Hilang', 1, 0, 'C', true);	
		$this->fpdfc->Cell(50,0, '', 0, 0, 'C');
		$this->fpdfc->Cell(34,5, '', 'LR', 1, 'C');
		foreach ($barang as $key => $value) {        
			if ($value->keterangan == null) {
				$keterangan = '-';                                    
			} else {
				$keterangan = $value->keterangan;
			}
			$merek_brg = $value->merek_nama;
			if ($value->merek_id == 0) {
				$merek_brg = 'TANPA MEREK';
			}       
		$this->fpdfc->SetWidths(array(10,70,14,14,14,14,50));		
		$this->fpdfc->SetAligns(array('C','L','C','C','C','C','L'));		
		$this->fpdfc->Row(array($key+1,$value->kategori_nama.' - '.$merek_brg.' '.$value->barang_tipe,$value->barang_jumlah,$value->kond_bagus,$value->kond_rusak,$value->kond_hilang,$keterangan));			
		}	
		$this->fpdfc->Ln();     
		$this->fpdfc->Ln();     
        $this->fpdfc->Cell(50, 5, 'PJ'.ucwords(strtolower($pj[1])), 0 , 0, 'C');
        $this->fpdfc->Cell(80, 5, '');
		$this->fpdfc->Cell(50, 5, 'Koordinator Laboratorium', 0, 0, 'C');
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
        $this->fpdfc->Cell(50, 5, $laporanDetail->asisten_nama, 0, 0, 'C');
        $this->fpdfc->Cell(80, 5, '');
		$this->fpdfc->Cell(50, 5, $koorlab->dosen_nama, 0, 1, 'C');
		
		$pc = $this->M_database->getLaporanPC($laporan_id)->result();		
		
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
		$this->fpdfc->Cell(0, 5, "LAPORAN $laporan", 0, 1, 'C');
        $this->fpdfc->Ln();				      					
        $this->fpdfc->SetFont('Arial', '', 8);        				
		$this->fpdfc->Cell(0, 8, "PC $pj[1]", 0, 1, 'C');		
		foreach ($pc as $key => $pclab) {
			$this->fpdfc->Cell(183,5, 'PC : '.$pclab->pc_nama,0,1,'L');
			$this->fpdfc->Cell(10,10, 'No.', 'LT', 0, 'C', true);
			$this->fpdfc->Cell(84,10, 'Komponen', 'LT', 0, 'C', true);
			$this->fpdfc->Cell(42,5, 'Kondisi', 1, 0, 'C', true);
			$this->fpdfc->Cell(50,10, 'Keterangan', 'LTR', 0, 'C', true);
			$this->fpdfc->Cell(10,5, '', '', 1, 'C');
			$this->fpdfc->Cell(10,0, '', 0, 0, 'C');
			$this->fpdfc->Cell(25,0, '', 0, 0, 'C');
			$this->fpdfc->Cell(48,0, '', 0, 0, 'C');
			$this->fpdfc->Cell(11,0, '', 0, 0, 'C');
			$this->fpdfc->Cell(14,5, 'Bagus', 1, 0, 'C', true);
			$this->fpdfc->Cell(14,5, 'Rusak', 1, 0, 'C', true);
			$this->fpdfc->Cell(14,5, 'Hilang', 1, 0, 'C', true);	
			$this->fpdfc->Cell(34,0, '', 0, 0, 'C');
			$this->fpdfc->Cell(50,5, '', 0, 1, 'C');			
			$komponen = $this->M_database->getLaporanKomponenPC($pclab->pc_id, $laporan_id)->result();
			foreach ($komponen as $key => $komp) {
				if ($komp->keterangan == null) {
					$keterangan = '-';                                    
				} else {
					$keterangan = $komp->keterangan;
				}
				$merek_brg = $komp->merek_nama;
				if ($komp->merek_id == 0) {
					$merek_brg = 'TANPA MEREK';
				}
				$bagus = "-";
				$rusak = "-";
				$hilang = "-";				
				if ($komp->kondisi == 1) {
					$bagus = 'V';
				} else if ($komp->kondisi == 2) {
					$rusak = 'V';
				} else {
					$hilang = 'V';
				}
				$this->fpdfc->SetWidths(array(10,84,14,14,14,50));		
				$this->fpdfc->SetAligns(array('C','L','C','C','C','L'));
				$this->fpdfc->Row(array($key+1, $komp->kategori_nama.' - '.$komp->merek_nama.' '.$komp->barang_tipe, $bagus, $rusak, $hilang, $keterangan));
			}
			$this->fpdfc->Ln();     
		}
		$this->fpdfc->Ln();     
        $this->fpdfc->Cell(50, 5, 'PJ'.ucwords(strtolower($pj[1])), 0 , 0, 'C');
        $this->fpdfc->Cell(80, 5, '');
		$this->fpdfc->Cell(50, 5, 'Koordinator Laboratorium', 0, 0, 'C');
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
		$this->fpdfc->Ln();
        $this->fpdfc->Cell(50, 5, $laporanDetail->asisten_nama, 0, 0, 'C');
        $this->fpdfc->Cell(80, 5, '');
		$this->fpdfc->Cell(50, 5, $koorlab->dosen_nama, 0, 0, 'C');             
      	$this->fpdfc->output('I', "$laporan.pdf");          		
	}

}
