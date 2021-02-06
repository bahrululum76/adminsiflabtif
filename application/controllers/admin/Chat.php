<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Chat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$uid = $this->session->userdata('uid');
		$data['tabTitle'] = 'Pesan';
		$data['user'] = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
		$data['jml_notif'] = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => "asisten_$uid"));
		$data['asisten'] = $this->M_database->getWhere('t_asisten', 't_jabatan', 't_asisten.jabatan_id=t_jabatan.jabatan_id', array('status' => 1, 't_asisten.asisten_id !=' => $uid, 't_asisten.jabatan_id !=' => 12), 'asisten_nama ASC');
		$data['dosen'] = $this->M_database->getWhere('t_dosen', 't_jabatan', 't_dosen.jabatan_id=t_jabatan.jabatan_id', array('dosen_status' => 1), 't_dosen.jabatan_id ASC', 'dosen_nama ASC');
		$data['bak'] = $this->M_database->getWhere('t_asisten', 't_jabatan', 't_asisten.jabatan_id=t_jabatan.jabatan_id', array('status' => 1, 't_asisten.jabatan_id' => 12), 'asisten_nama ASC');
		$data['halaman_title'] = 'Pesan';
        $this->template->load('admin/template', 'admin/v-chat', $data);
	}
	
	public function kirim_pesan()
	{
		$uid = $this->session->userdata('uid');
		$jabatan = $this->session->userdata('jabatan');
		$pesan = $this->input->post('pesan');
		$kanal = $this->input->post('kanal');
		$penerima = $this->input->post('penerima');
		$pengirim = $this->input->post('pengirim');
		$bulan = bulanIndonesia();
				
		$data = array(
			'pesan_isi'	=> $pesan,
			'pesan_user' => $jabatan.$uid.'-'.$penerima,
			'pesan_nama_pengirim' => $pengirim,
			'pesan_kanal' => $kanal,
			'date_created' => date('j').' '.strtoupper($bulan).' '.date('Y').'-'.date('H.i')
		);
		
		$this->M_database->insertData('t_pesan', $data);
		$last_id = $this->db->insert_id();		
		
		if ($kanal == '-') {			
			$dm = array(
				'pesan_id' => $last_id,
				'pesan_pengirim' => $jabatan.$uid,
				'pesan_penerima' => $penerima
			);
			$this->M_database->insertData('t_pesan_status', $dm);		
		} else if ($kanal == 'asisten') {
			$asisten = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1, 'jabatan_id !=' => 12), null)->result();
			$grupAsisten = [];
			foreach ($asisten as $key => $value) {
				array_push($grupAsisten, array(
					'pesan_id' => $last_id,
					'pesan_pengirim' => 'asisten',
					'pesan_penerima' => 'asisten_'.$value->asisten_id
				));
			}
			$this->db->insert_batch('t_pesan_status', $grupAsisten);
		} else {
			$asisten = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1, 'jabatan_id !=' => 12), null)->result();
			$grupAsisten = [];
			foreach ($asisten as $key => $value) {
				array_push($grupAsisten, array(
					'pesan_id' => $last_id,
					'pesan_pengirim' => 'koorlab',
					'pesan_penerima' => 'asisten_'.$value->asisten_id
				));
			}
			$this->M_database->insert_batch('t_pesan_status', $grupAsisten);

			$koorlab = $this->M_database->getWhere('t_dosen', null, null, array('dosen_status' => 1, 'jabatan_id' => 1), null)->result();
			$grupKoorlab = [];
			foreach ($koorlab as $key => $value) {
				array_push($grupKoorlab, array(
					'pesan_id' => $last_id,
					'pesan_pengirim' => 'koorlab',
					'pesan_penerima' => 'dosen_'.$value->dosen_id
				));
			}
			$this->M_database->insert_batch('t_pesan_status', $grupKoorlab);
		}						

		$options = array(
			'cluster' => 'ap1',
			'useTLS' => true
		);
		$pusher = new Pusher\Pusher(
			'bf535588d7d38b92fa01',
			'0477973f39c9a381253d',
			'1029875',
			$options
		);

		$data3['message'] = 'pesan';
		$pusher->trigger('my-channel', 'my-event', $data3);

		echo 'send';
	}

	function fetch_pesan()
	{
		$jabatan = $this->session->userdata('jabatan');
		$uid = $this->session->userdata('uid');

		$pengirim = $jabatan.$uid;
		$penerima = $this->input->get('penerima');
		$kanal = $this->input->get('kanal');
		
		$user1 = $pengirim.'-'.$penerima;
		$user2 = $penerima.'-'.$pengirim;

		$bulan = bulanIndonesia();
		
		$this->M_database->deleteData('t_pesan_status', array('pesan_pengirim' => $penerima, 'pesan_penerima' => $pengirim));

		if ($kanal == '-') {			
			$pesan = $this->M_database->getPesan($user1 , $user2);
		} else {
			$pesan = $this->M_database->getPesanGrup($kanal);
		}
				
		$total_pesan = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $pengirim));

		$isi = [];
		
		$addPengirim = array_push($isi, $pengirim);
		$addPenerima = array_push($isi, $penerima);
		$addTotal = array_push($isi, $total_pesan);
		$addPesan = array_push($isi, $pesan);
		$addNow = array_push($isi, date('j').' '.strtoupper($bulan).' '.date('Y'));
		
		echo json_encode($isi);			
	}

	function cek_pesan()
	{
		$this->M_database->updateData('t_device_token_admin', array('user_id' => $this->session->userdata('u_token')), array('last_active' => date('Y-m-d H:i:s', time() + 30)));
		$uid = $this->session->userdata('uid');
		$jabatan = $this->session->userdata('jabatan');

		$notif = $this->db->order_by('id ASC')->group_by('pesan_pengirim')->limit(4)->where('pesan_penerima', $jabatan.$uid)->join('t_pesan', 't_pesan_status.pesan_id=t_pesan.pesan_id')->get('t_pesan_status')->result_array();
		$total_pesan = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $jabatan.$uid));
		$jmlTicket = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$jmlPesanMhs = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
		
		$isi = [];
		$addTotal = array_push($isi, $total_pesan);
		$addNotif = array_push($isi, $notif);
		$addTicketing = array_push($isi, $jmlTicket + $jmlPesanMhs);

		$last_msg = $this->db->order_by('id DESC')->limit(1)->where('pesan_penerima', $jabatan.$uid)->join('t_pesan', 't_pesan_status.pesan_id=t_pesan.pesan_id')->get('t_pesan_status')->row_array();
		if ($last_msg) {			
			if ($last_msg['pesan_pengirim'] == 'asisten') {
				$last_msg['jumlah'] = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $jabatan.$uid, 'pesan_pengirim' => 'asisten'));
				$last_msg['pesan_nama_pengirim'] = 'ASLABTIF';
				$last_msg['foto'] = base_url().'assets/images/icons/icon-96x96.png';
			} else if ($last_msg['pesan_pengirim'] == 'koorlab') {
				$last_msg['jumlah'] = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $jabatan.$uid, 'pesan_pengirim' => 'koorlab'));
				$last_msg['pesan_nama_pengirim'] = 'Koordinator Lab';
				$last_msg['foto'] = base_url().'assets/images/icons/icon-96x96.png';
			} else {
				$last_msg['jumlah'] = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $jabatan.$uid, 'pesan_pengirim' => $last_msg['pesan_pengirim']));
				$lastPengirim = explode('_', $last_msg['pesan_pengirim']);
				if ($lastPengirim[0] == 'asisten') {
					$lastFoto = $this->db->select('foto')->where('asisten_id', $lastPengirim[1])->get('t_asisten')->row_array();
				} else {
					$lastFoto = $this->db->select('foto')->where('dosen_id', $lastPengirim[1])->get('t_dosen')->row_array();
				}
				$last_msg['foto'] = base_url().'assets/images/profil/'.$lastFoto['foto'];
			}
			$last_msg['link'] = base_url().'admin/chat?'.$last_msg['pesan_pengirim'];
			$addLastMessage = array_push($isi, $last_msg);
		}


		foreach ($notif as $key => $value) {
			
			$isi[1][$key]['jumlah'] = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $jabatan.$uid, 'pesan_pengirim' => $value['pesan_pengirim']));
			
			$pengirim = $value['pesan_pengirim'];
			$role = explode('_', $value['pesan_pengirim']);
			
			if ($pengirim == 'asisten') {
				$isi[1][$key]['foto'] = base_url().'assets/images/icons/icon-96x96.png';
				$isi[1][$key]['pesan_nama_pengirim'] = 'ASLABTIF';
			} else if ($pengirim == 'koorlab') {
				$isi[1][$key]['foto'] = base_url().'assets/images/icons/icon-96x96.png';
				$isi[1][$key]['pesan_nama_pengirim'] = 'Koordinator Lab';
			} else {
				if ($role[0] == 'asisten') {
					$foto = $this->db->select('foto')->where('asisten_id', $role[1])->get('t_asisten')->row_array();
				} else {
					$foto = $this->db->select('foto')->where('dosen_id', $role[1])->get('t_dosen')->row_array();
				}
				$isi[1][$key]['foto'] = base_url().'assets/images/'."profil/$foto[foto]";
				$isi[1][$key]['pesan_nama_pengirim'] = $value['pesan_nama_pengirim'];
			}
			
			$isi[1][$key]['link'] = base_url().'admin/chat?'.$pengirim;
		}
		
		echo json_encode($isi);		
	}

	function kirimNotifikasi()
	{	
		$jabatan = $this->session->userdata('jabatan');
		$uid = $this->session->userdata('uid');
		$pengirim = $jabatan.$uid;

		$u_token = $this->input->post('token');
		$kanal = $this->input->post('kanal');
		$pesan = $this->input->post('pesan');

		$url = base_url().'NotificationRoute?user='.$pengirim.'&url=chat';

		if ($kanal != '-') {
			$url = base_url().'NotificationRoute?user='.$kanal.'&url=chat';			
		}
		
		if ($kanal == 'koorlab') {
			$judul = 'Koordinator Lab';
		} else if ($kanal == 'asisten') {
			$judul = 'ASLABTIF';
		} else {
			$judul = $this->session->userdata('nama');
		}
		$push = [];	

		$push['judul'] = $judul;
		$push['pesan'] = $pesan;
		$push['url'] = $url;
		$pushMessage = json_encode($push);

		$notifications = [];

		if ($kanal == '-') {
			$token = $this->M_database->getWhere('t_device_token_admin', null, null, array('user_id' => $u_token))->row();
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
		} else {
			if ($kanal == 'asisten') {				
				$token = $this->M_database->getWhere('t_device_token_admin', null, null, array('user_role' => 'asisten'))->result();
			} else if ($kanal == 'koorlab') {
				$token = $this->db->where('user_role', 'asisten')->or_where('user_role', 'koorlab')->get('t_device_token_admin')->result();
			}
			foreach ($token as $key => $value) {
				array_push($notifications,
					[
						'subscription' => Subscription::create([
							"endpoint" => $value->endpoint,
							"keys" => [
								'p256dh' => $value->p256dh,
								'auth' => $value->auth
							]
						]),				
						'payload' => $pushMessage,
						'agent' => $value->endpoint,
						'last_active' => $value->last_active
					]
				);
			}
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
				if (date('Y-m-d H:i:s') > date($notification['last_active'])) {					
					$webPush->queueNotification(
						$notification['subscription'],
						$notification['payload'] 
					);
				}
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
