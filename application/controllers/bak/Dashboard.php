<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_bak();
	}


	public function index()
	{		
		$data['tabTitle'] = 'Dashboard';
        $data['halaman_title'] = "Dashboard";
		$this->template->load('bak/template-bak', 'bak/v-dashboard', $data);
	}		

}
