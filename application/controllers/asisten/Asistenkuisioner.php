<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asistenkuisioner extends CI_Controller {
	
	function __construct(){
		parent::__construct();
        $this->load->model(array('M_database', 'M_kuisioner'));
        $this->load->library('session');
        $this->load->library('parser');
        $this->load->library('encryption');
        cek_session_null();
        $menu_kuisioner = $this->db->select('kuisioner_asisten')->get('t_lab')->row();
        if ($menu_kuisioner->kuisioner_asisten == 0) {
            redirect('asisten/dashboard');
        }
    }

    function index() {
        $menu = $this->input->get('menu');
        $data['tabTitle'] = 'Kuisioner';      
        $data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
        $data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        if (isset($menu)) {
            if ($menu == 'penilaian-diri') {
                $periode = $this->db->order_by('periode_id', 'ASC')->where('status', 1)->limit(1)->get('t_periode')->row();
                $status = $data['status'] = $this->M_kuisioner->getAllData('penilaian_diri', array('kode_asisten' => sha1($this->session->userdata('u_token').$this->session->userdata('u_token')), 'semester' => $periode->periode_id))->result_array();
                $this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-penilaian-diri', $data);
            } else if ($menu == 'penilaian-asisten') {
                $data['kategori'] = $this->M_kuisioner->getAllData('kategori_penilaian', array('tipe_penilaian' => '2'))->result_array();
                $data['uraian'] = $this->M_kuisioner->getUraian('2');
                $asisten = $this->input->post('asisten');
                if (isset($asisten)) {                    
                    $data['asisten'] = $this->M_database->getWhere('t_asisten', null, null, array('username' => $asisten))->row();
                    $this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-penilaian-asisten', $data);
                } else {
                    $data['periode'] = $this->db->order_by('periode_id', 'ASC')->where('status', 1)->limit(1)->get('t_periode')->row();
                    $data['asisten'] = $this->M_database->getWhere('t_asisten', 't_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id', array('status' => 1, 't_asisten.jabatan_id !=' => 12, 'asisten_id !=' => $this->session->userdata('asisten_id')), 'status DESC', 'asisten_nama ASC');
                    $this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-asisten', $data);
                }                     
            } else if ($menu == 'nilai-saya') {
                $this->detailPenilaianAsisten();
            } else if ($menu == 'penilaian-mahasiswa') {
                $this->detailPenilaianMahasiswa();
            } else {
                $this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-kuisioner', $data);
            }
        } else {
            $this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-kuisioner', $data);
        }
    }

    function formPenilaianAsisten($kd_asisten){
        $data['kategori'] = $this->M_kuisioner->getAllData('kategori_penilaian', array('tipe_penilaian' => '2'))->result_array();
        $data['uraian'] = $this->M_kuisioner->getUraian('2');
        $data['asisten'] = $this->M_kuisioner->getAllData('asisten', array('username' => $kd_asisten))->result_array();
        $data['view'] = 'asisten/kuisioner/formPenilaianAsisten';
		$this->load->view('asisten/template',$data);
    }

    function storePenilaianAsisten(){
        $periode = $this->db->order_by('periode_id', 'ASC')->where('status', 1)->limit(1)->get('t_periode')->row();
        $penilaian = array();
        $token = sha1($this->session->userdata('u_token').'_'.$this->input->post('kd_asisten').'_'.$periode->periode_id);
        $now = date('Y-m-d H:i:s');
		$kode_penilaian = $this->input->post('id_uraian');
		$nilai = $this->input->post('nilai');
        
        for ($i=0; $i < count($this->input->post('id_uraian')); $i++) { 
            $penilaian[$i] = array(
                'token' => $token,
                'kode_asisten' => sha1($this->session->userdata('u_token')),
                'menilai' => $this->input->post('kd_asisten'),
                'kode_penilaian' => $kode_penilaian[$i],
                'semester' => $periode->periode_id,
                'nilai' => $nilai[$i],
                'created_at' => $now
            );
        }

        $komentar = array(
            'token' => $token,
            'untuk' => sha1($this->input->post('kd_asisten')),
            'komentar' => $this->input->post('komentar'),
            'semester' => $periode->periode_id,
            'created_at' => $now
        );

        $status = array(
            'kode_asisten' => $this->session->userdata('u_token'),
            'menilai' => $this->input->post('kd_asisten'),
            'status' => 1,
            'semester' => $periode->periode_id,
            'created_at' => $now
        );

        $this->M_kuisioner->insertAllData('penilaian_asisten', $penilaian);
        $this->M_kuisioner->insertData('penilaian_komentar', $komentar);
        $this->M_kuisioner->insertData('status_penilaian_asisten', $status);

        redirect('asisten/penilaian_asisten');
    }

    function penilaianDiri(){
        // $data['status'] = $this->M_kuisioner->getAllData('status_penilaian_asisten', array('kode_asisten' => $this->session->userdata('u_token'), 'menilai' => $this->session->userdata('u_token')))->result_array();
        $data['view'] = 'asisten/kuisioner/penilaianDiri';
        $this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-penilaian-diri', $data);
    }

    function storePenilaianDiri(){
        $periode = $this->db->order_by('periode_id', 'ASC')->where('status', 1)->limit(1)->get('t_periode')->row();
        $penilaian = array();
        $now = date('Y-m-d H:i:s');

        $penilaian = array(
            'kode_asisten' => sha1($this->session->userdata('u_token').$this->session->userdata('u_token')),
            'semester' => $periode->periode_id,
            'deskripsi1' => $this->input->post('deskripsi1'),
            'deskripsi2' => $this->input->post('deskripsi2'),
            'deskripsi3' => $this->input->post('deskripsi3'),
            'created_at' => $now
        );

        $status = array(
            'kode_asisten' => $this->session->userdata('u_token'),
            'menilai' => $this->session->userdata('u_token'),
            'status' => 1,
            'created_at' => $now
        );

        $this->M_kuisioner->insertData('penilaian_diri', $penilaian);
        $this->M_kuisioner->insertData('status_penilaian_asisten', $status);

        redirect('asisten/kuisioner?menu=penilaian-diri');
    }
    
    function detailPenilaianAsisten()
    {
        $periode = $this->db->order_by('periode_id', 'ASC')->where('status', 1)->limit(1)->get('t_periode')->row();
		$asisten_id = $this->session->userdata('asisten_id');
        $kd_asisten = $this->session->userdata('u_token');
        $data['jadwal'] = $this->M_database->getJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id")->result_array();
        $data['jumlah_asisten'] = $this->M_database->countWhere('t_asisten', array('status' => 1, 'jabatan_id !=' => 12, 'asisten_id !=' => $this->session->userdata('asisten_id')));
        $data['jumlah_penilai'] = $this->M_kuisioner->countPenilai($this->session->userdata('u_token'), $periode->periode_id);
        $data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
        $data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $data['peringkat'] = $this->M_kuisioner->getAllAsisten();
        $data['asisten'] = $this->M_kuisioner->getAllData('t_asisten', array('username' => $kd_asisten, 'status' => 1))->result_array();
        $data['menilai'] = $this->M_kuisioner->getAllMenilaiAsisten($kd_asisten);
        $data['uraian'] = $this->M_kuisioner->getDetailRataAsisten($kd_asisten);
        $data['kategori'] = $this->M_kuisioner->getAllData('kategori_penilaian', array('tipe_penilaian' => '2'))->result_array();
        $data['komentar'] = $this->M_kuisioner->getAllData('penilaian_komentar', array('untuk' => sha1($kd_asisten)), array('created_at' => 'DESC'))->result_array();
        $data['nilai'] = $this->M_kuisioner->getNilaiAsisten($kd_asisten);
        $data['diri'] = $this->M_kuisioner->getAllData('penilaian_diri', array('kode_asisten' => sha1($kd_asisten.$kd_asisten), 'semester' => $periode->periode_id))->result_array();

        $data['tabTitle'] = 'Kuisioner';      
		$this->template->load('asisten/template-asisten', 'asisten/kuisioner/v-nilai-saya', $data);
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
