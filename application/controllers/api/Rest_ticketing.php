<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Rest_ticketing extends CI_Controller {

    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {      
        $this->__resTraitConstruct();
        $this->load->model('M_database_API');
    }

    public function index_get()
    {   
        $npm = $this->get('npm');

        $pesan = $this->M_database_API->cekPesan($npm);        
        $ticket = $this->M_database_API->getTicket($npm);

        $this->response([
            'status' => true,
            'ticket' => $ticket,
            'jmlPesanMhs' => $pesan                   
        ], 200);

    }

    public function pesan_get()
    {
        $npm = $this->get('npm');
        
        $this->M_database_API->updateData('t_pesan_mhs', array('npm' => $npm, 'pengirim' => 'admin'), array('pesan_status' => 1));
        $pesan = $this->M_database_API->getPesanMhs($npm);

        $this->response([
            'status' => true,
            'pesan' => $pesan,
            'today' => date('j').' '.strtoupper(bulanIndonesia(date('m'))).' '.date('Y')
        ], 200);

    }

    public function kritiksaran_post()    
    {
        $ks = $this->post('kritik_saran');  

        $kritikSaran = $this->M_database_API->insertData('t_kritik_saran', array('kritik_saran' => $ks));
        
        if ($kritikSaran) {            
            $this->response([
                'ks' => true,
                'message' => 'sukses'
            ], 201);
        } else {
            $this->response([
                'ks' => false,
                'message' => 'gagal'
            ], 204);
        }
    }
    
    public function kirimpesan_post()    
    {
        $npm = $this->post('npm');        
        $isi_pesan = $this->post('pesan');
        
        $data = array(
			'npm' => $npm,
            'pesan' => $isi_pesan,
            'pengirim' => 'mahasiswa',
            'pesan_status' => 0,
            'date_created' => date('j').' '.strtoupper(bulanIndonesia(date('m'))).' '.date('Y').'-'.date('H.i')
		);

		$pesan = $this->M_database_API->insertData('t_pesan_mhs', $data);
        
        if ($pesan) {            
            $this->response([
                'kirim' => true,
                'message' => 'sukses',
                'pesan' => $data
            ], 201);
        } else {
            $this->response([
                'kirim' => false,
                'message' => 'gagal'
            ], 204);
        }
    }

    public function pindahshift_post()    
    {
        $npm = $this->post('npm');        
        $shift_asal = $this->post('shift_asal');        
        $shift_tujuan = $this->post('shift_tujuan');        
        $deskripsi = $this->post('deskripsi');

        $data = array(
            'ticket_kode' => "PS".date('Ymdhis'),
            'shift_asal' => $shift_asal,
            'shift_tujuan' => $shift_tujuan,
            'ticket_deskripsi' => $deskripsi,
            'npm' => $npm,
            'ticket_status' => 0,
            'date_created' => date('d-m-Y').' '.date('H.i')
		);
		
		$data2 = array(
            'npm' => $npm,
            'pesan' => 'Permintaan pindah shift dari '.$shift_asal.' ke '.$shift_tujuan.', kode ticket PS'.date('Ymdhis'),
            'pengirim' => 'mahasiswa',
            'pesan_status' => 0,
            'date_created' => date('j').' '.strtoupper(bulanIndonesia(date('m'))).' '.date('Y').'-'.date('H.i')
        );

        $this->db->trans_start();
		$this->M_database_API->insertData('t_ticket', $data);
		$id = $this->db->insert_id();
		$ticket = $this->db->where('ticket_id', $id)->get('t_ticket')->row_array();			
		$this->M_database_API->insertData('t_pesan_mhs', $data2);
		$this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {            
            $this->response([
                'status' => false,
                'message' => 'gagal'
            ], 204);
        } else {
            $this->response([
                'status' => true,
                'message' => 'sukses',
                'ticket_id' => $id,
                'ticket' => $data,
                'pesan' => $data2
            ], 201);
        }

    }

    public function batalticket_post()
    {
        $ticket_id = $this->post('ticket_id');

        $ticket = $this->M_database_API->updateData('t_ticket', array('ticket_id' => $ticket_id), array('ticket_status' => 3));

        $this->response([
            'status' => true,
            'message' => 'sukses'            
        ], 200);       
    }
    
    public function cekticket_post()
    {
        $ticket_id = $this->post('ticket_id');        
        $ticket = $this->db->where('ticket_id', $ticket_id)->get('t_ticket')->row_array();

        $this->response([
            'status' => true,
            'ticket_status' => $ticket['ticket_status']           
        ], 200);       
    }    

}

?>