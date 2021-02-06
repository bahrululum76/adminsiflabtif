<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Ticketing extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("M_database");
		cek_session();
	}


	public function index()
	{
		$data['jumlah_mhs'] = $this->M_database->countWhere('t_mahasiswa', 'status_mhs = 1');
		$data['jumlah_kelas'] = $this->M_database->countWhere('t_kelas', 'kelas_status = 1');
		$data['jumlah_jadwal'] = $this->M_database->countWhere('t_jadwal', 'status = 1');
		$data['jumlah_asisten'] = $this->M_database->countWhere('t_asisten', 'status = 1');
		$data['jadwal'] = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC');
        $data['pindah_shift'] = $this->M_database->getTicketPindah();
        $data['pesan_mhs'] = $this->M_database->getPesanMhs();
		$data['halaman_title'] = "E - Ticketing";
		$data['tabTitle'] = 'E - Ticketing';
		$this->template->load('admin/template', 'admin/v-ticketing', $data);
	}

	public function fetch_jadwal()
	{
		$matkum_id = $this->input->get('matkum_id');
		$shift_asal = $this->input->get('shift_asal');

		$jadwal = $this->M_database->getWhere('t_jadwal', 't_ruangan', 't_jadwal.ruangan_id=t_ruangan.ruangan_id', array('status' => 1, 'matkum_id' => $matkum_id, 'jadwal_kode !=' => $shift_asal), 'jadwal_kode ASC');

		foreach ($jadwal->result() as $key => $value) {
			$mhs = $this->M_database->countWhere('registrasi_praktikum', array('jadwal_kode' => $value->jadwal_kode));
			$opt .= '<option value="'.$value->jadwal_kode.'">'.strtoupper($value->jadwal_kode).'</option>';
			$item .= '<div class="jadwal-ticket" id="'.$value->jadwal_kode.'">
                        <span>'.$value->jadwal_kode.'</span>                        
                        <span style="margin-top:8px">'.$value->jadwal_hari.'</span>
                        <span>'.$value->jadwal_jam.'</span>
                        <div><span class="badge-status orange-gradient" style="margin:8px auto 4px">Jumlah PC: '.$value->ruangan_kapasitas.'</span><span class="badge-status badge-not">Mahasiswa: '.$mhs.'</span></div>
                    </div>';
		}

		echo "$opt|$item";
	}

	public function balas_pesan()
	{
		$npm = $this->input->post('npm');
		$nama = $this->input->post('nama');
		$pesan = $this->input->post('pesan');
		$bulan = date('m');
		switch ($bulan) {
			case '01':
				$bulan = 'Januari';
				break;
			case '02':
				$bulan = 'Februari';
				break;
			case '03':
				$bulan = 'Maret';
				break;
			case '04':
				$bulan = 'April';
				break;
			case '05':
				$bulan = 'Mei';
				break;
			case '06':
				$bulan = 'Juni';
				break;
			case '07':
				$bulan = 'Juli';
				break;
			case '08':
				$bulan = 'Agustus';
				break;
			case '09':
				$bulan = 'September';
				break;
			case '10':
				$bulan = 'Oktober';
				break;
			case '11':
				$bulan = 'November';
				break;
			case '12':
				$bulan = 'Desember';
				break;
			default:
				$bulan = date('F');
				break;
		}
		$data = array (
			'npm' => $npm,
			'pesan' => $pesan,
			'pesan_status' => 0,
			'pengirim' => 'admin',
			'date_created' => date('j').' '.strtoupper($bulan).' '.date('Y').'-'.date('H.i')
		);
		
		$this->M_database->insertData('t_pesan_mhs', $data);		
	}
	
	public function proses_ticket()
	{
		$ticket_id = $this->input->post('ticket_id');
		$ticket_status = $this->input->post('ticket_status');
		$shift_tujuan = $this->input->post('shift_tujuan');

		$status = '';
		if ($ticket_status == 0){ 
			$status = '<span class="badge-status yellow darken-1">Pending</span>';
		} else if ($ticket_status == 1) { 
			$status = '<span class="badge-status green accent-4 white-text">Diproses</span>';
		} else if ($ticket_status == 2) {
			$status = '<span class="badge-status blue white-text">Disetujui</span>';
		} else {
			$status = '<span class="badge-status red darken-1 white-text">Dibatalkan</span>';
		}

		$data = array (
			'ticket_status' => $ticket_status,
			'shift_tujuan' => $shift_tujuan			
		);

		$this->M_database->updateData('t_ticket', array('ticket_id' => $ticket_id), $data);

		echo "$status|$ticket_status|$shift_tujuan";
	}

	function fetch_pesan()
	{
		$npm = $this->input->get('npm');
		
		$bulan = date('m');
		switch ($bulan) {
			case '01':
				$bulan = 'Januari';
				break;
			case '02':
				$bulan = 'Februari';
				break;
			case '03':
				$bulan = 'Maret';
				break;
			case '04':
				$bulan = 'April';
				break;
			case '05':
				$bulan = 'Mei';
				break;
			case '06':
				$bulan = 'Juni';
				break;
			case '07':
				$bulan = 'Juli';
				break;
			case '08':
				$bulan = 'Agustus';
				break;
			case '09':
				$bulan = 'September';
				break;
			case '10':
				$bulan = 'Oktober';
				break;
			case '11':
				$bulan = 'November';
				break;
			case '12':
				$bulan = 'Desember';
				break;
			default:
				$bulan = date('F');
				break;
		}
		$this->M_database->updateData('t_pesan_mhs', array('npm' => $npm, 'pengirim' => 'mahasiswa'), array('pesan_status' => 1));
		$pesanMhs = $this->M_database->getwhere('t_pesan_mhs', 't_mahasiswa', 't_pesan_mhs.npm=t_mahasiswa.npm', array('t_pesan_mhs.npm' => $npm), 'pesan_id ASC')->result();
		$pesan = [];
		$addPesan = array_push($pesan, $pesanMhs);
		$addNow = array_push($pesan, date('j').' '.strtoupper($bulan).' '.date('Y'));
      	echo json_encode($pesan);
	}

	public function cekPesanMhs()
	{
		$pesanAll = $this->db->order_by('pesan_id DESC')->group_by('npm')->get('t_pesan_mhs')->result();
		$pesan = [];
		$jmlPesan = [];
		$lastPesan = [];
		foreach ($pesanAll as $key => $value) {
			$jmlPesanMhs = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'npm' => $value->npm, 'pesan_status' => 0));
			$lastPesanMhs = $this->db->select('date_created, kelas_nama, nama, t_pesan_mhs.npm, pesan')->order_by('pesan_id DESC')->limit(1)->where('t_pesan_mhs.npm', $value->npm)->join('t_mahasiswa', 't_pesan_mhs.npm=t_mahasiswa.npm')->join('t_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode')->get('t_pesan_mhs')->row();
			array_push($jmlPesan, $jmlPesanMhs);
			array_push($lastPesan, $lastPesanMhs);
		}

		array_push($pesan, $jmlPesan);
		array_push($pesan, $lastPesan);

		echo json_encode($pesan);
	}

	function kirimNotifikasi()
	{	
		$npm = $this->input->post('npm');
		$pesan = $this->input->post('pesan');

		
		$push = [];			
		$push['judul'] = 'Admin Labtif';
		$push['pesan'] = $pesan;
		$push['url'] = config_item('siflabtif_client').'/NotificationRoute?url=ticketing';
		$pushMessage = json_encode($push);
		
		$tokens = $this->db->where('npm', $npm)->get('t_device_token')->result();
		$notifications = [];
		foreach ($tokens as $token) {				
			array_push($notifications,
				[
					'subscription' => Subscription::create([
						"endpoint" => $token->endpoint,
						"keys" => [
							'p256dh' => $token->p256dh,
							'auth' => $token->auth
						],					
					]),
					'payload' => $pushMessage,
					'agent' => $token->endpoint
				]
			);
		}

		$auth = array(
			'VAPID' => array(
				'subject' => base_url(),
				'publicKey' => 'BPDJRLtSfOnHnYhuaI_qROVZR9c_YTCTJ3Yp6G3hNiTfG5GG9zU-bMs-NxJPGlmzMA8IDeT52a92tJRcL-qTYLg',
				'privateKey' => 'LtmDip-uATrvkViEo2RSQ3ZzQKL53uHGoZn2Dw1B0LY' // in the real world, this would be in a secret file
			),
		);

		if (extension_loaded('gmp')) {
			$webPush = new WebPush($auth);
			// send multiple notifications with payload
			foreach ($notifications as $notification) {
				if (strpos($notification['agent'], 'mozilla') > 0) {
					$webPush->setAutomaticPadding(0);
				}
				$webPush->queueNotification(
					$notification['subscription'],
					$notification['payload'] 
				);
			}

			/**
			 * Check sent results
			 * @var MessageSentReport $report
			 */
			foreach ($webPush->flush() as $report) {
				$endpoint = $report->getRequest()->getUri()->__toString();
				if ($report->isSuccess()) {
					// echo "[v] Message sent successfully for subscription {$endpoint}.";
				} else {
					$this->M_database->deleteData('t_device_token', array('endpoint' => $endpoint));
					// echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
				}
			}
			echo 'send notification';
		} else {
            echo 'notifikasi pesan gagal. Ekstensi gmp tidak terinstall di server';
		}	
	}

	function notifikasiTicketing()
	{	
		$kanal = $this->input->post('kanal');
        $jmlPesanMhs = $this->M_database->countWhere('t_pesan_mhs', array('pesan_status' => 0, 'pengirim' => 'mahasiswa'));
        $jmlTicket = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$url = base_url().'NotificationRoute?url=ticketing';

		if ($kanal == 'pesan') {
			$pesan = $jmlPesanMhs.' pesan masuk';			
		} else {
            $pesan = $jmlTicket.' tiket baru';
        }
				
		$push = [];	

		$push['judul'] = 'E - Ticketing';
		$push['pesan'] = $pesan;
		$push['url'] = $url;
		$pushMessage = json_encode($push);

		$notifications = [];

        $tokens = $this->M_database->getWhere('t_device_token_admin', null, null, array('user_role' => 'admin'))->result();
        foreach ($tokens as $key => $token) {            
            array_push($notifications,
                [
                    'subscription' => Subscription::create([
                        "endpoint" => $token->endpoint,
                        "keys" => [
                            'p256dh' => $token->p256dh,
                            'auth' => $token->auth
                        ],					
                    ]),
                    'payload' => $pushMessage,
                    'agent' => $token->endpoint,
                    'last_active' => $token->last_active
                ]
            );
        }

		$auth = array(
			'VAPID' => array(
				'subject' => base_url(),
				'publicKey' => 'BPDJRLtSfOnHnYhuaI_qROVZR9c_YTCTJ3Yp6G3hNiTfG5GG9zU-bMs-NxJPGlmzMA8IDeT52a92tJRcL-qTYLg',
				'privateKey' => 'LtmDip-uATrvkViEo2RSQ3ZzQKL53uHGoZn2Dw1B0LY' // in the real world, this would be in a secret file
			),
		);

		if (extension_loaded('gmp')) {
			$webPush = new WebPush($auth);
			// send multiple notifications with payload
			foreach ($notifications as $notification) {
				if (strpos($notification['agent'], 'mozilla') > 0) {
					$webPush->setAutomaticPadding(0);
				}
                $webPush->queueNotification(
                    $notification['subscription'],
                    $notification['payload'] 
                );				
			}

			/**
			 * Check sent results
			 * @var MessageSentReport $report
			 */

			foreach ($webPush->flush() as $report) {
				$endpoint = $report->getRequest()->getUri()->__toString();
				if ($report->isSuccess()) {					
					// echo "[v] Message sent successfully for subscription {$endpoint}.";
				} else {
					$this->M_database->deleteData('t_device_token_admin', array('endpoint' => $endpoint));
					// echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
				}			
			}
			echo 'send notification';
		} else {
            echo 'notifikasi pesan gagal. Ekstensi gmp tidak terinstall di server';
		}
	}

}
