<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kuisioner extends CI_Controller {
	
	function __construct(){
		parent::__construct();
        $this->load->model(array('M_database', 'M_kuisioner'));
        $this->load->library('session');
        $this->load->library('parser');
        $this->load->library('encryption');
        cek_session_dosen();        
    }

    public function index()
    {
        $prid = $this->input->get('prid');
        
        $data['tabTitle'] = 'Kuisioner Asisten';
        $data['periode'] = $this->M_database->getAllData('t_periode', null, null, 'periode_id DESC');
        
        if (isset($prid)) {                    
            $data['peringkat'] = $this->M_kuisioner->getAllAsistenPeriode($prid);
            $this->template->load('dosen/template-dosen', 'dosen/kuisioner/v-kuisioner', $data);           
        } else {            
            $this->template->load('dosen/template-dosen', 'dosen/kuisioner/v-kuisioner', $data);           
        }
    }

    public function detail()
    {
        $kd_asisten = $this->input->get('asisten');
        $prid = $this->input->get('prid');
        $data['periode'] = $this->M_database->getAllData('t_periode', null, null, 'periode_id DESC');
        $data['asisten'] = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1, 'jabatan_id !=' => 12), 'asisten_nama ASC');
        $data['kategori'] = $this->M_kuisioner->getAllData('kategori_penilaian', array('tipe_penilaian' => '2'))->result_array();
        $data['uraian'] = $this->M_kuisioner->getDetailRataAsisten($kd_asisten);
        $data['diri'] = $this->M_kuisioner->getAllData('penilaian_diri', array('kode_asisten' => sha1($kd_asisten.$kd_asisten), 'semester' => $prid))->result_array();
        $data['tabTitle'] = 'Kuisioner Asisten';

        $this->template->load('dosen/template-dosen', 'dosen/kuisioner/v-kuisioner-detail', $data);
    }
    
    function detailPenilaianAsisten()
    {
        $data['periode'] = $this->M_database->getAllData('t_periode', null, null, 'periode_id DESC');

		// $asisten_id = $this->session->userdata('asisten_id');
        // $kd_asisten = $this->session->userdata('u_token');
        // $data['jadwal'] = $this->M_database->getJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id")->result_array();
        // $data['jumlah_asisten'] = $this->M_database->countWhere('t_asisten', array('status' => 1, 'jabatan_id !=' => 12, 'asisten_id !=' => $this->session->userdata('asisten_id')));
        // $data['jumlah_penilai'] = $this->M_kuisioner->countPenilai($this->session->userdata('u_token'), $prid);
        // $data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
        // $data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $data['peringkat'] = $this->M_kuisioner->getAllAsisten();
        // $data['asisten'] = $this->M_kuisioner->getAllData('t_asisten', array('username' => $kd_asisten, 'status' => 1))->result_array();
        // $data['menilai'] = $this->M_kuisioner->getAllMenilaiAsisten($kd_asisten);
        // $data['uraian'] = $this->M_kuisioner->getDetailRataAsisten($kd_asisten);
        // $data['kategori'] = $this->M_kuisioner->getAllData('kategori_penilaian', array('tipe_penilaian' => '2'))->result_array();
        // $data['nilai'] = $this->M_kuisioner->getNilaiAsisten($kd_asisten);
        // $data['komentar'] = $this->M_kuisioner->getAllData('penilaian_komentar', array('untuk' => sha1($kd_asisten)), array('created_at' => 'DESC'))->result_array();
        // $data['diri'] = $this->M_kuisioner->getAllData('penilaian_diri', array('kode_asisten' => sha1($kd_asisten.$kd_asisten), 'semester' => $prid))->result_array();

        $data['tabTitle'] = 'Hasil Kuisioner';      
		$this->template->load('dosen/template-dosen', 'dosen/kuisioner/v-kuisioner', $data);
    }
    
    function detailPenilaianMahasiswa()
    {
        $idp = $this->input->get('idp');
        $kd_asisten = $this->session->userdata('u_token');
		$asisten_id = $this->session->userdata('asisten_id');
        $data['jadwal'] = $this->M_database->getJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id");
        
        $data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
        $data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $data['asisten'] = $this->M_kuisioner->getAllData('t_asisten', array('username' => $kd_asisten, 'status' => 1))->result_array();
        $data['menilai'] = $this->M_kuisioner->getAllMenilaiAsisten($kd_asisten);
        $data['uraian'] = $this->M_kuisioner->getDetailRataAsistenMahasiswa($kd_asisten, $idp);
        $data['kategori'] = $this->M_kuisioner->getAllData('kategori_penilaian', array('tipe_penilaian' => '1'))->result_array();
        $data['komentar'] = $this->M_kuisioner->getAllData('penilaian_komentar', array('untuk' => sha1($idp)), array('created_at' => 'DESC'))->result_array();
        $data['komentarmhs'] = $this->M_kuisioner->getAllData('penilaian_komentar', array('token' => sha1($idp), 'untuk' => sha1($kd_asisten)), array('created_at' => 'DESC'))->result_array();
        $data['nilai'] = $this->M_kuisioner->getNilaiAsistenMahasiswa($kd_asisten, $idp);

        $data['tabTitle'] = 'Kuisioner';      
		$this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-penilaian-mahasiswa', $data);
    }
	
	
}
