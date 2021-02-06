<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kehadiran_asisten extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_dosen();
	}


	public function index()
	{
		$asisten_id = $this->input->get('asisten_id');
		$data['asistenkosong'] = $this->input->get('asisten_id');
		$data['tabTitle'] = 'Kehadiran Asisten';
		// $data['jadwal'] = $this->db->where('status', 1)->where("asisten_1='$asisten_id' OR asisten_2='$asisten_id'")->get('t_jadwal');
		if ($asisten_id != null) {
			$data['jadwal'] = $this->M_database->getJadwalDashboard("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id", 'jadwal_id ASC', array('jadwal_hari !=' => 'hari'));
		}
		$data['asisten'] = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1, 'jabatan_id !=' => 12), 'asisten_nama ASC');
        $data['halaman_title'] = "Kehadiran Asisten";
        $data['visibility'] = '';

        if ($asisten_id == null) {
        	$data['visibility'] = 'hide';			
        } else {
        	$data['visibility'] = '';      	
        }

        $this->template->load('dosen/template-dosen', 'dosen/v-kehadiran-asisten', $data);

	}	

}
