<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aplikasi extends CI_Controller {

    function __construct()
    {
      parent::__construct();
      $this->load->model("M_database");
      cek_session();
    }


    public function index()
    {
      $data['tabTitle'] = 'Aplikasi';
      $data['lab'] = $this->db->get('t_lab')->row();		
      $data['jadwal'] = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC');		
      $data['halaman_title'] = "Aplikasi";
      $this->template->load('admin/template', 'admin/v-aplikasi', $data);
    }

    public function statusUjian()
    {
      $status = $this->input->post('status');

      if ($status == 'Aktif') {
        $data = array('mode_ujian' => 0);
        $badge = 'Tidak aktif';
      } else  {
        $data = array('mode_ujian' => 1);
        $badge = 'Aktif';
      }

      $this->db->set($data)->update('t_lab');

      echo $badge;
    }
    
    public function statusKsa()
    {
      $status = $this->input->post('status');

      if ($status == 'Aktif') {
        $data = array('kuisioner_asisten' => 0);
        $badge = 'Tidak aktif';
      } else  {
        $data = array('kuisioner_asisten' => 1);
        $badge = 'Aktif';
      }

      $this->db->set($data)->update('t_lab');

      echo $badge;
    }
    
    public function statusKsm()
    {
      $status = $this->input->post('status');

      if ($status == 'Aktif') {
        $data = array('kuisioner_mhs' => 0);
        $badge = 'Tidak aktif';
      } else  {
        $data = array('kuisioner_mhs' => 1);
        $badge = 'Aktif';
      }

      $this->db->set($data)->update('t_lab');

      echo $badge;
    }
    
    public function hapusPesan()
    {
      $asisten_id = $this->session->userdata('asisten_id');
      $password = $this->input->post('password');

      $asisten = $this->db->get_where('t_asisten', array('asisten_id' => $asisten_id))->row();
      $tugas = $this->M_database->getAllData('t_tugas_image')->result();

      if (password_verify($password, $asisten->password)) {
        foreach ($tugas as $key => $value) {
          unlink('./assets/images/tugas/'.$value->tugas_image);
        }
        $this->db->truncate('t_tugas_image');  
        $this->db->truncate('t_pesan_status');
        $this->db->truncate('t_pesan');          
        $pass = 'benar';
      } else {
        $pass = 'salah';
      }
       
      echo $pass;
    }
    
    public function hapusTicketing()
    {
      $asisten_id = $this->session->userdata('asisten_id');
      $password = $this->input->post('password');

      $asisten = $this->db->get_where('t_asisten', array('asisten_id' => $asisten_id))->row();

      if (password_verify($password, $asisten->password)) {                
        $this->db->truncate('t_pesan_mhs');          
        $this->db->truncate('t_ticket');
        $pass = 'benar';
      } else {
        $pass = 'salah';
      }
       
      echo $pass;
    }
    
    public function resetPembayaran()
    {
      $asisten_id = $this->session->userdata('asisten_id');
      $password = $this->input->post('password');

      $asisten = $this->db->get_where('t_asisten', array('asisten_id' => $asisten_id))->row();

      if (password_verify($password, $asisten->password)) {
        $pass = 'benar';
        $this->db->set(array('tgl_bayar_bak' => '-'))->update('t_mahasiswa');         
        $this->db->set(array('status_bayar_bak' => 0))->update('t_mahasiswa');         
        $this->db->set(array('status_bayar' => 0))->update('t_mahasiswa');         
      } else {
        $pass = 'salah';
      }
       
      echo $pass;
    }
      
    public function peraturan()
    {
      $prosedural = $this->input->post('prosedural');
      $tata_tertib = $this->input->post('tata_tertib');
      $sanksi = $this->input->post('sanksi');

      $data = array('lab_prosedural' => $prosedural, 'lab_tata_tertib' => $tata_tertib, 'lab_sanksi' => $sanksi);

      $this->session->set_flashdata('edit', 'Data LABTIF berhasil diperbarui');
      $this->db->set($data)->update('t_lab');

      redirect('admin/aplikasi');
    }
    
    public function social()
    {
      $facebook = $this->input->post('facebook');
      $instagram = $this->input->post('instagram');
      $youtube = $this->input->post('youtube');

      $data = array('lab_fb' => $facebook, 'lab_ig' => $instagram, 'lab_yt' => $youtube);

      $this->session->set_flashdata('edit', 'Data LABTIF berhasil diperbarui');
      $this->db->set($data)->update('t_lab');

      redirect('admin/aplikasi');
    }
    
    public function cetak_nilai_praktikum()
    {
      $this->load->library('fpdfc');
      $this->fpdfc->SetTitle('Cetak Nilai Praktikum');
      $jadwal_kode = $this->input->post('jadwal_kode');
      $jadwal_in = array();
      if (in_array('all', $jadwal_kode)) {
        $jadwal = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC')->result_array();     
      } else {
        foreach ($jadwal_kode as $jdl) {
          array_push($jadwal_in, array('jadwal_kode' => $jdl));
        }
        $jadwal = $jadwal_in;
      }

      $jadwals = $jadwal;      
      
      foreach ($jadwals as $key => $value) {
        $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $value['jadwal_kode']))->row();
        $asisten_1 = $this->db->select('asisten_nama')->where('asisten_id', $jadwal_ini->asisten_1)->get('t_asisten')->row();
        $asisten_2 = $this->db->select('asisten_nama')->where('asisten_id', $jadwal_ini->asisten_2)->get('t_asisten')->row();        
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
        $this->fpdfc->SetMargins(10,0,10);				      			
        $this->fpdfc->SetFont('Arial', 'B', 10);
        $this->fpdfc->Cell(0, 5, 'Nilai Praktikum', 0, 1, 'C');
        $this->fpdfc->SetFont('Arial', '', 8);
        $this->fpdfc->Cell(70, 5, '');
        $this->fpdfc->Cell(25, 5, 'ID Praktikum');
        $this->fpdfc->Cell(0, 5, ': '.strtoupper($value['jadwal_kode']), 0, 1);        
        $this->fpdfc->Cell(70, 5, '');
				$this->fpdfc->Cell(25, 5, 'Pengajar 1');
				$this->fpdfc->Cell(0, 5, ': '.$asisten_1->asisten_nama, 0, 1);
        $this->fpdfc->Cell(70, 5, '');
				$this->fpdfc->Cell(25, 5, 'Pengajar 2');
				$this->fpdfc->Cell(0, 5, ': '.$asisten_2->asisten_nama, 0, 1);
        $this->fpdfc->Cell(70, 5, '');
				$this->fpdfc->Cell(25, 5, 'Mata Praktikum');
				$this->fpdfc->Cell(0, 5, ': '.$jadwal_ini->matkum, 0, 1);       
        $this->fpdfc->Cell(70, 5, '');
				$this->fpdfc->Cell(25, 5, 'Kelas');
				$this->fpdfc->Cell(0, 5, ': '.$jadwal_ini->kelas_nama, 0, 1);
        $this->fpdfc->SetMargins(14,0,20);
        $this->fpdfc->Ln();
        $this->fpdfc->SetFillColor(187, 222, 251);
        $this->fpdfc->Cell(10, 7, 'No', 1, 0, 'C', true);
        $this->fpdfc->Cell(25, 7, 'NPM', 1, 0, 'C', true);
        $this->fpdfc->Cell(55, 7, 'Nama', 1, 0, 'C', true);
        $this->fpdfc->Cell(18, 7, 'Kelas', 1, 0, 'C', true);
        $this->fpdfc->Cell(23, 7, 'Kehadiran '.$jadwal_ini->p_kehadiran.'%', 1, 0, 'C', true);
        $this->fpdfc->Cell(18, 7, 'Tugas '.$jadwal_ini->p_tugas.'%', 1, 0, 'C', true);
        $this->fpdfc->Cell(18, 7, 'Ujian '.$jadwal_ini->p_ujian.'%', 1, 0, 'C', true);
        $this->fpdfc->Cell(16, 7, 'Nilai Akhir', 1, 1, 'C', true);
        // MAHASISWA
        $mahasiswa = $this->M_database->getWhere('registrasi_praktikum', 't_mahasiswa', 'registrasi_praktikum.npm=t_mahasiswa.npm', array('jadwal_kode' => $value['jadwal_kode']), 't_mahasiswa.npm ASC')->result();
        foreach ($mahasiswa as $key => $mhs) {
          $kehadiran = $this->M_database->kehadiran($value['jadwal_kode'], $mhs->npm);
          $total_kehadiran = $kehadiran->kehadiran * 10;
          $kelas = substr($mhs->kelas_kode, 2, strlen($mhs->kelas_kode)-6);
          $thn = substr($mhs->kelas_kode, -4);
          $total = ($total_kehadiran*$jadwal_ini->p_kehadiran/100) + ($mhs->nilai_tugas*$jadwal_ini->p_tugas/100) + ($mhs->nilai_ujian*$jadwal_ini->p_ujian/100);
          $this->fpdfc->Cell(10, 7, $key+1, 1, 0, 'C');
          $this->fpdfc->Cell(25, 7, $mhs->npm, 1, 0, 'C');
          $this->fpdfc->Cell(55, 7, $mhs->nama, 1, 0);
          $this->fpdfc->Cell(18, 7, strtoupper('IF '.$kelas.' '.$thn), 1, 0);
          $this->fpdfc->Cell(23, 7, $kehadiran->kehadiran*10, 1, 0, 'C');
          $this->fpdfc->Cell(18, 7, $mhs->nilai_tugas, 1, 0, 'C');
          $this->fpdfc->Cell(18, 7, $mhs->nilai_ujian, 1, 0, 'C');
          $this->fpdfc->Cell(16, 7, $total, 1, 1, 'C');
        }
        $this->fpdfc->Ln();     
        $this->fpdfc->Cell(55, 5, 'Pengajar 1', 0 , 0, 'C');
        $this->fpdfc->Cell(65, 5, '');
				$this->fpdfc->Cell(55, 5, 'Pengajar 2', 0, 0, 'C');
				$this->fpdfc->Ln();
				$this->fpdfc->Ln();
				$this->fpdfc->Ln();
        $this->fpdfc->Cell(55, 5, $asisten_1->asisten_nama, 0, 0, 'C');
        $this->fpdfc->Cell(65, 5, '');
				$this->fpdfc->Cell(55, 5, $asisten_2->asisten_nama, 0, 0, 'C');
      }

      $this->fpdfc->output('I', 'Nilai-praktikum.pdf');      
    }          
    
    public function cetak_absensi_ujian()
    {
      $this->load->library('fpdfc');
      $this->fpdfc->SetTitle('Cetak Absensi Ujian');
      $jadwal = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC')->result();

      foreach ($jadwal as $key => $value) {
        $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $value->jadwal_kode))->row();
        $asisten_1 = $this->db->select('asisten_nama')->where('asisten_id', $value->asisten_1)->get('t_asisten')->row();
        $asisten_2 = $this->db->select('asisten_nama')->where('asisten_id', $value->asisten_2)->get('t_asisten')->row();        
        $this->fpdfc->AddPage('P', 'A4', 0);
        $this->fpdfc->SetAutoPageBreak('auto', 10);
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
        $this->fpdfc->SetMargins(4,0,10);				      			
        $this->fpdfc->SetFont('Arial', 'B', 10);
        $this->fpdfc->Cell(0, 5, 'Absensi Praktikum', 0, 1, 'C');
        $this->fpdfc->SetFont('Arial', '', 8);
        $this->fpdfc->Cell(75, 5, '');
        $this->fpdfc->Cell(25, 5, 'ID Praktikum');
        $this->fpdfc->Cell(0, 5, ': '.strtoupper($value->jadwal_kode), 0, 1);        
        $this->fpdfc->Cell(75, 5, '');
				$this->fpdfc->Cell(25, 5, 'Pengajar 1');
				$this->fpdfc->Cell(0, 5, ': '.$asisten_1->asisten_nama, 0, 1);
        $this->fpdfc->Cell(75, 5, '');
				$this->fpdfc->Cell(25, 5, 'Pengajar 2');
				$this->fpdfc->Cell(0, 5, ': '.$asisten_2->asisten_nama, 0, 1);
        $this->fpdfc->Cell(75, 5, '');
				$this->fpdfc->Cell(25, 5, 'Kelas');
				$this->fpdfc->Cell(0, 5, ': '.$jadwal_ini->kelas_nama, 0, 1);
        $this->fpdfc->Cell(75, 5, '');
				$this->fpdfc->Cell(25, 5, 'Mata Praktikum');
				$this->fpdfc->Cell(0, 5, ': '.$jadwal_ini->matkum, 0, 1);
        $this->fpdfc->Cell(75, 5, '');
				$this->fpdfc->Cell(25, 5, 'Jadwal');
        $this->fpdfc->Cell(0, 5, ': '.$jadwal_ini->jadwal_hari.', '.$jadwal_ini->jadwal_jam, 0, 1);
        $this->fpdfc->SetMargins(30,0,20);
        $this->fpdfc->Ln();
        $this->fpdfc->SetFillColor(187, 222, 251);
        $this->fpdfc->Cell(10, 7, 'No', 1, 0, 'C', true);
        $this->fpdfc->Cell(30, 7, 'NPM', 1, 0, 'C', true);
        $this->fpdfc->Cell(70, 7, 'Nama', 1, 0, 'C', true);
        $this->fpdfc->Cell(40, 7, 'Tanda Tangan', 1, 1, 'C', true);
        // MAHASISWA
        $mahasiswa = $this->M_database->getWhere('registrasi_praktikum', 't_mahasiswa', 'registrasi_praktikum.npm=t_mahasiswa.npm', array('jadwal_kode' => $value->jadwal_kode), 't_mahasiswa.npm ASC')->result();
        foreach ($mahasiswa as $key => $mhs) {
          $this->fpdfc->Cell(10, 7, $key+1, 1, 0, 'C');
          $this->fpdfc->Cell(30, 7, $mhs->npm, 1, 0, 'C');
          $this->fpdfc->Cell(70, 7, $mhs->nama, 1);
          $this->fpdfc->Cell(40, 7, '', 1, 1);
        }        
        $this->fpdfc->Ln();     
        $this->fpdfc->Cell(50, 5, 'Pengajar 1', 0 , 0, 'C');
        $this->fpdfc->Cell(50, 5, '');
				$this->fpdfc->Cell(50, 5, 'Pengajar 2', 0, 0, 'C');
				$this->fpdfc->Ln();
				$this->fpdfc->Ln();
				$this->fpdfc->Ln();
        $this->fpdfc->Cell(50, 5, $asisten_1->asisten_nama, 0, 0, 'C');
        $this->fpdfc->Cell(50, 5, '');
				$this->fpdfc->Cell(50, 5, $asisten_2->asisten_nama, 0, 0, 'C');
      }

      $this->fpdfc->output('I', 'Absensi-ujian-praktikum.pdf');      
    }

}
