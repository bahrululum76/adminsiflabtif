<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventori extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}


	public function index()
	{
		$menu = $this->input->get('menu');
		$laporan_id = $this->input->get('lid');
		$ruangan_id = $this->input->get('rid');
		$data['tabTitle'] = 'Inventori';
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
		if ($menu != null) {
			$data['barang'] = $this->M_database->getBarang($ruangan_id);
			$data['kategori'] = $this->M_database->getAllData('t_inv_kategori', null, null, 'kategori_nama ASC');
			$data['merek'] = $this->M_database->getAllData('t_inv_merek', null, null, 'merek_nama ASC');
			$data['laporan'] = $this->M_database->getWhere('t_inv_laporan', 't_asisten', 't_inv_laporan.asisten_id=t_asisten.asisten_id', array('ruangan_id' => $ruangan_id), 'laporan_id DESC');
			$data['ruangan'] = $this->M_database->getWhere('t_ruangan', null, null, array('ruangan_pj' => $this->session->userdata('jabatan_id')), 'ruangan_id ASC');
			if ($menu == 'barang') {
				$this->template->load('asisten/template-asisten', 'asisten/inventori/v-barang', $data);
			} else if ($menu == 'pclab') {
				$data['pc'] = $this->M_database->getPC($ruangan_id);
				$this->template->load('asisten/template-asisten', 'asisten/inventori/v-pc', $data);
			} else if ($menu == 'laporan') {
				$this->template->load('asisten/template-asisten', 'asisten/inventori/v-laporan', $data);
			} else if ($menu == 'buat-laporan') {
				$data['pc'] = $this->M_database->getPC($ruangan_id);
				$this->template->load('asisten/template-asisten', 'asisten/inventori/v-laporan-buat', $data);
			} else if ($menu == 'lihat-laporan') {
				$data['pc'] = $this->M_database->getLaporanPC($laporan_id);
				$data['barang'] = $this->M_database->getLaporanInventori($laporan_id);
				$this->template->load('asisten/template-asisten', 'asisten/inventori/v-laporan-lihat', $data);
			} else {
				$this->template->load('asisten/template-asisten', 'asisten/inventori/v-inventori', $data);
			}
		} else {			
			$this->template->load('asisten/template-asisten', 'asisten/inventori/v-inventori', $data);
		}

    }
    
    public function tambahBarang()
	{
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$tipe = $this->input->post('tipe');
		$barang = $this->input->post('barang');
		$jumlah = $this->input->post('jumlah');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$data = array(
			'barang_tipe' => $tipe,
			'kategori_id' => $kategori,
			'merek_id' => $merek,
			'barang_jumlah' => $jumlah,
			'ruangan_id' => $rid
		);
		$this->M_database->insertData('t_inv_barang', $data);
		$this->session->set_flashdata('tambah', "Data barang <b>$barang</b> berhasil disimpan");
		redirect("asisten/inventori?menu=barang&sub=barang&rid=$rid&ruangan=$ruangan");
	}
	
	public function editBarang()
	{
		$barang_id = $this->input->post('barang_id');
		$kategori = $this->input->post('kategori');
		$merek = $this->input->post('merek');
		$tipe = $this->input->post('tipe');
		$barang = $this->input->post('barang');
		$jumlah = $this->input->post('jumlah');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$data = array(
			'barang_tipe' => $tipe,
			'kategori_id' => $kategori,
			'merek_id' => $merek,
			'barang_jumlah' => $jumlah,
			'ruangan_id' => $rid
		);
		$this->M_database->updateData('t_inv_barang', array('barang_id' => $barang_id), $data);
		$this->session->set_flashdata('edit', "Data barang <b>$barang</b> berhasil diedit");
		redirect("asisten/inventori?menu=barang&sub=barang&rid=$rid&ruangan=$ruangan");
	}

	public function hapusBarang()
	{
		$barang_id = $this->input->post('barang_id');
		$barang = $this->input->post('barang');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$this->M_database->deleteData('t_inv_barang', array('barang_id' => $barang_id));
		$this->session->set_flashdata('hapus', "Data barang <b>$barang</b> berhasil dihapus");
		redirect("asisten/inventori?menu=barang&sub=barang&rid=$rid&ruangan=$ruangan");
	}

    public function tambahKategori()
	{
		$kategori = $this->input->post('kategori');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$data = array(
			'kategori_nama' => $kategori,
			'ruangan_id' => $rid
		);
		$this->M_database->insertData('t_inv_kategori', $data);
		$this->session->set_flashdata('tambah', "Data kategori <b>$kategori</b> berhasil disimpan");
		redirect("asisten/inventori?menu=barang&sub=kategori&rid=$rid&ruangan=$ruangan");
	}
	
	public function editKategori()
	{
		$kategori_id = $this->input->post('kategori_id');
		$kategori_nama = $this->input->post('kategori_nama');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$data = array(
			'kategori_nama' => $kategori_nama,
			'ruangan_id' => $rid
		);
		$this->M_database->updateData('t_inv_kategori', array('kategori_id' => $kategori_id), $data);
		$this->session->set_flashdata('edit', "Data kategori <b>$kategori_nama</b> berhasil diedit");
		redirect("asisten/inventori?menu=barang&sub=kategori&rid=$rid&ruangan=$ruangan");
	}
	
	public function hapusKategori()
	{
		$kategori_id = $this->input->post('kategori_id');
		$kategori_nama = $this->input->post('kategori_nama');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$this->M_database->deleteData('t_inv_kategori', array('kategori_id' => $kategori_id));
		$this->session->set_flashdata('hapus', "Data kategori <b>$kategori_nama</b> berhasil diedit");
		redirect("asisten/inventori?menu=barang&sub=kategori&rid=$rid&ruangan=$ruangan");
	}
	
	public function tambahMerek()
	{
		$merek = $this->input->post('merek');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$data = array(
			'merek_nama' => $merek,
			'ruangan_id' => $rid
		);
		$this->M_database->insertData('t_inv_merek', $data);
		$this->session->set_flashdata('tambah', "Data merek <b>$merek</b> berhasil disimpan");
		redirect("asisten/inventori?menu=barang&sub=merek&rid=$rid&ruangan=$ruangan");
	}
	
	public function editMerek()
	{
		$merek_id = $this->input->post('merek_id');
		$merek_nama = $this->input->post('merek_nama');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$data = array(
			'merek_nama' => $merek_nama,
			'ruangan_id' => $rid
		);
		$this->M_database->updateData('t_inv_merek', array('merek_id' => $merek_id), $data);
		$this->session->set_flashdata('edit', "Data merek <b>$merek_nama</b> berhasil diedit");
		redirect("asisten/inventori?menu=barang&sub=merek&rid=$rid&ruangan=$ruangan");
	}

	public function hapusMerek()
	{
		$merek_id = $this->input->post('merek_id');
		$merek_nama = $this->input->post('merek_nama');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$this->M_database->deleteData('t_inv_merek', array('merek_id' => $merek_id));
		$this->session->set_flashdata('hapus', "Data kategori <b>$merek_nama</b> berhasil diedit");
		redirect("asisten/inventori?menu=barang&sub=merek&rid=$rid&ruangan=$ruangan");
	}

	public function tambahPC()
	{
		$pc_nama = $this->input->post('pc_nama');
		$rid = $this->input->get('rid');
		$ruangan = $this->input->get('ruangan');
		$komponen = $this->input->post('komponen');

		$data = array(
			'pc_nama' => $pc_nama,
			'ruangan_id' => $rid
		);

		$this->M_database->insertData('t_pc', $data);

		$last_id = $this->db->insert_id();

		$dataKomponen = [];
		foreach ($komponen as $komp) {
			if ($komp != '') {				
				array_push($dataKomponen, array(
					'pc_id' => $last_id,
					'barang_id' => $komp
				));
			}
		}

		if ($dataKomponen != null) {			
			$this->db->insert_batch('t_pc_komponen', $dataKomponen);
		}

		$this->session->set_flashdata('tambah', "Data PC <b>$pc_nama</b> berhasil disimpan");
		redirect("asisten/inventori?menu=pclab&rid=$rid&ruangan=$ruangan");
	}

	public function editPC()
	{
		$pc_id = $this->input->post('pc_id');
		$pc_nama = $this->input->post('pc_nama');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$this->M_database->updateData('t_pc', array('pc_id' => $pc_id), array('pc_nama' => $pc_nama));
		$this->session->set_flashdata('edit', "Data PC <b>$pc_nama</b> berhasil diedit");
		redirect("asisten/inventori?menu=pclab&rid=$rid&ruangan=$ruangan");
	}

	public function hapusPC()
	{
		$pc_id = $this->input->post('pc_id');
		$pc_nama = $this->input->post('pc_nama');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$this->M_database->deleteData('t_pc', array('pc_id' => $pc_id));
		$this->session->set_flashdata('hapus', "Data PC <b>$pc_nama</b> berhasil dihapus");
		redirect("asisten/inventori?menu=pclab&rid=$rid&ruangan=$ruangan");
	}

	public function tambahKomponen()
	{
		$pc_id = $this->input->post('pc_id');
		$pc_nama = $this->input->post('pc_nama');
		$rid = $this->input->get('rid');
		$ruangan = $this->input->get('ruangan');
		$komponen = $this->input->post('komponen');

		$dataKomponen = [];
		foreach ($komponen as $komp) {
			if ($komp != '') {				
				array_push($dataKomponen, array(
					'pc_id' => $pc_id,
					'barang_id' => $komp
				));
			}
		}

		if ($dataKomponen != null) {			
			$this->db->insert_batch('t_pc_komponen', $dataKomponen);
		}

		$this->session->set_flashdata('tambah', "Komponen PC <b>$pc_nama</b> berhasil ditambah");
		redirect("asisten/inventori?menu=pclab&rid=$rid&ruangan=$ruangan");
	}

	public function editKomponen()
	{
		$pc_id = $this->input->post('pc_id');
		$pck_id = $this->input->post('pck_id');
		$pc_nama = $this->input->post('pc_nama');
		$komponen = $this->input->post('komponen');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$this->M_database->insertData('t_pc_komponen', array('pc_id' => $pc_id, 'barang_id' => $komponen));
		$this->M_database->updateData('t_pc_komponen', array('pck_id' => $pck_id), array('pck_status' => 0));
		$this->session->set_flashdata('edit', "Data komponen PC <b>$pc_nama</b> berhasil diedit");
		redirect("asisten/inventori?menu=pclab&rid=$rid&ruangan=$ruangan");
	}
	
	public function hapusKomponen()
	{
		$pck_id = $this->input->post('pck_id');
		$pc_nama = $this->input->post('pc_nama');
		$pck_nama = $this->input->post('pck_nama');
		$ruangan = $this->input->get('ruangan');
		$rid = $this->input->get('rid');
		$this->M_database->updateData('t_pc_komponen', array('pck_id' => $pck_id), array('pck_status' => 0));
		$this->session->set_flashdata('hapus', "Data komponen PC <b>$pck_nama</b> dari $pc_nama berhasil dihapus");
		redirect("asisten/inventori?menu=pclab&rid=$rid&ruangan=$ruangan");
	}

	public function buatLaporan()
	{
		$rid = $this->input->get('rid');
		$ruangan_url = $this->input->get('ruangan');
		$ruangan = strtoupper(str_replace('-', ' ', $this->input->get('ruangan')));
		$asisten_id = $this->session->userdata('asisten_id');
		$bulan = $this->input->post('tanggal');
		// Barang
		$barang_id = $this->input->post('barang_id');
		$bagus = $this->input->post('bagus');
		$rusak = $this->input->post('rusak');
		$hilang = $this->input->post('hilang');
		$keterangan = $this->input->post('keterangan');		
		// PC
		$pc_id = $this->input->post('pc_id');
		$pck_id = $this->input->post('pck_id');
		$komp_id = $this->input->post('komp_id');
		$keterangan_pc = $this->input->post('keterangan_pc');
		
		if (strpos($ruangan, 'TORIUM') > 1) {
			$ruangan = str_replace('LABORATORIUM', 'LAB', $ruangan);
		}

		$dataLaporan = array(
			'laporan_nama' => 'INVENTORI - '.$ruangan.' - '.strtoupper($bulan),
			'laporan_tgl' => date('d/m/Y'),
			'ruangan_id' => $rid,
			'asisten_id' => $asisten_id
		);

		$this->M_database->insertData('t_inv_laporan', $dataLaporan);
		$last_id = $this->db->insert_id();

		$barang = [];
		foreach ($barang_id as $key => $brg) {
			array_push($barang, array(
				'barang_id' => $brg,
				'kond_bagus' => $bagus[$key],
				'kond_rusak' => $rusak[$key],
				'kond_hilang' => $hilang[$key],
				'keterangan' => $keterangan[$key],
				'laporan_id' => $last_id
			));
		}

		$pc = [];
		foreach ($pck_id as $key => $pck) {
			array_push($pc, array(
				'pck_id' => $pck,
				'kondisi' => $this->input->post('kondisi'.$pck),
				'keterangan' => $keterangan_pc[$key],
				'laporan_id' => $last_id
			));
		}	

		$this->db->insert_batch('t_inv_laporan_barang', $barang);
		$this->db->insert_batch('t_inv_laporan_pc', $pc);
		$this->session->set_flashdata('tambah', "Laporan inventori berhasil disimpan");
		redirect("asisten/inventori?menu=laporan&rid=$rid&ruangan=$ruangan_url");
	}

	public function hapusLaporan()
	{
		$rid = $this->input->get('rid');
		$ruangan_url = $this->input->get('ruangan');
		$laporan_id = $this->input->post('laporan_id');
		$laporan_nama = $this->input->post('laporan_nama');
		$this->M_database->deleteData('t_inv_laporan', array('laporan_id' => $laporan_id));
		$this->session->set_flashdata('hapus', "Laporan <b>$laporan_nama</b> berhasil dihapus");
		redirect("asisten/inventori?menu=laporan&rid=$rid&ruangan=$ruangan_url");
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
