<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kritik_saran extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}


	public function index()
	{
		$data['tabTitle'] = 'Kritik & Saran';
		$data['krisar'] = $this->M_database->getAllData('t_kritik_saran', null, null, 'ks_status ASC', 'ks_id DESC');
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
        $this->template->load('asisten/template-asisten', 'asisten/v-kritik-saran', $data);

    }
    
    function statusKrisar()
	{
		$krisar_id = $this->input->post('krisar_id');
		$status = $this->input->post('status');

		if ($status == 'Diterima') {
			$data = array('ks_status' => 0);
			$badge = '<span class="badge-status badge-not">Belum</span>';
		} else  {
			$data = array('ks_status' => 1);
			$badge= '<span class="badge-status badge-ok">Diterima</span>';
		}

		$this->M_database->updateData('t_kritik_saran', array('ks_id' => $krisar_id), $data);

		echo $badge;
	}

}
