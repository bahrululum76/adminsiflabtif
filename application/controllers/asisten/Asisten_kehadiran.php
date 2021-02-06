<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asisten_kehadiran extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}


	public function index()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		
		$data['tabTitle'] = 'Kehadiran Asisten';
		$data['jadwal'] = $this->M_database->getAllJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id", 'status DESC', 'jadwal_id DESC');
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $this->template->load('asisten/template-asisten', 'asisten/v-kehadiran', $data);

	}		

}
