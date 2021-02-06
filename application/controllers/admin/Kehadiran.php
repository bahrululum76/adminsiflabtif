<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kehadiran extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$asisten_id = $this->input->get('asisten_id');
		$data['asistenkosong'] = $this->input->get('asisten_id');

		$data['jadwal'] = $this->db->where('asisten_1', $asisten_id)->or_where('asisten_2', $asisten_id)->get('t_jadwal');
		$data['asisten'] = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1), 'asisten_nama ASC');
        $data['halaman_title'] = "Kehadiran Asisten";
        $data['visibility'] = '';

        if ($asisten_id == null) {
        	$data['visibility'] = 'hide';			
        } else {
        	$data['visibility'] = '';      	
        }

        $this->template->load('admin/template', 'admin/v-kehadiran', $data);

	}	

}
