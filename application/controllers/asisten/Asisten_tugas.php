<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Asisten_tugas extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}
	
	
	public function index()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		$jadwal_kode = $this->input->get('idp');
		$tk = $this->input->get('tk');
		$nomor_tugas = $this->M_database->countWhere('t_tugas', array('jadwal_kode' => $jadwal_kode)) + 1;
		$kode_tugas = "$jadwal_kode-$nomor_tugas";

		if (isset($jadwal_kode)) {			     	
			$jadwal = $this->db->where('jadwal_kode', $jadwal_kode)->get('t_jadwal')->row();
			if ($jadwal->asisten_1 != $asisten_id && $jadwal->asisten_2 != $asisten_id) {
				redirect('asisten/akses_ditolak');
			}
		}
		
		$data['tabTitle'] = 'Tugas Praktikum';
		$data['tugas'] = $this->M_database->getWhere('t_tugas', null, null, array('jadwal_kode' => $jadwal_kode), null);
		$data['foto_tugas'] = $this->M_database->getWhere('t_tugas_image', null, null, array('tugas_kode' => $kode_tugas), null);
		$data['jadwal'] = $this->M_database->getJadwalAsisten("t_jadwal.asisten_1 = $asisten_id OR t_jadwal.asisten_2 = $asisten_id");
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
		
		if ($tk == null) {			
			$this->template->load('asisten/template-asisten', 'asisten/v-tugas', $data);
		} else {
			$data['mahasiswa'] = $this->M_database->getDetailTugas(array('jadwal_kode' => $jadwal_kode));
			$data['tugas_ini'] = $this->M_database->getWhere('t_tugas', null, null, array('tugas_kode' => $tk))->row();
			$this->template->load('asisten/template-asisten', 'asisten/v-tugas-penilaian', $data);
		}
	}

	function view_penilaian()
	{
		$jadwal_kode = $this->input->get('idp');
		$tugas_id = $this->input->get('t');
		$data['mahasiswa'] = $this->M_database->getDetailTugas(array('jadwal_kode' => $jadwal_kode));
		$this->template->load('asisten/template-asisten', 'asisten/v-tugas-penilaian', $data);
	}

	function penilaian() {
		$npm = $this->input->post('npm');
		$tugas_id = $this->input->post('tugas_id');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$nilai = $this->input->post('nilai');
		$tgl_upload = $this->input->post('tgl_upload');
		$nilai_asal = $this->input->post('nilai_asal');

		if ($nilai == null) {
			$nilai = 0;
		}

		$n_tugas = $nilai/10;

		$cek_nilai = $this->db->where(array('npm' => $npm, 'tugas_id' => $tugas_id))->get('t_tugas_nilai')->row();
		$nilai_tugas = $this->db->where(array('npm' => $npm, 'jadwal_kode' => $jadwal_kode))->get('registrasi_praktikum')->row();
		
		
		$data = array(
			'npm' => $npm,
			'tugas_id' => $tugas_id,
			'tgl_upload' => $tgl_upload,
			'nilai' => $nilai,
			'jadwal_kode' => $jadwal_kode
		);
		
		if ($cek_nilai) {
			$n_akhir =  $nilai_asal /10;
			$nilai_fix = ($nilai_tugas->nilai_tugas - $n_akhir) + $n_tugas;
			if ($nilai > 0) {				
				$this->M_database->updateData('t_tugas_nilai', array('npm' => $npm, 'tugas_id' => $tugas_id), array('nilai' => $nilai));
				$this->M_database->updateData('registrasi_praktikum', array('npm' => $npm, 'jadwal_kode' => $jadwal_kode), array('nilai_tugas' => $nilai_fix));
				$penilaian = $nilai;
			} else {
				$this->M_database->deleteData('t_tugas_nilai', array('npm' => $npm, 'tugas_id' => $tugas_id));
				$this->M_database->updateData('registrasi_praktikum', array('npm' => $npm, 'jadwal_kode' => $jadwal_kode), array('nilai_tugas' => $nilai_fix));
				$penilaian = 0;
			}			
		} else {
			$this->M_database->insertData('t_tugas_nilai', $data);
			$this->M_database->updateData('registrasi_praktikum', array('npm' => $npm, 'jadwal_kode' => $jadwal_kode), array('nilai_tugas' => $nilai_tugas->nilai_tugas + $n_tugas));
			$penilaian = $nilai;			
		}
		echo $penilaian;
	}	

	function tambahTugas() {

		$tugas_kode = $this->input->post('tugas_kode');
		$tugas_judul = $this->input->post('tugas_judul');
		$tugas_deskripsi = $this->input->post('tugas_deskripsi');
		$tugas_judul = $this->input->post('tugas_judul');
		$tugas_status = $this->input->post('tugas_status');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$batas_waktu = $this->input->post('tanggal')." ".$this->input->post('jam').":00";

		$l = explode(" ", $batas_waktu);
		$waktu = date_create_from_format("d/m/Y", $l[1]);
		$tanggal = date_format($waktu, 'Y-m-d');
		$jam = $l[2];		

        $data = array(
            	'tugas_kode' => $tugas_kode,
            	'tugas_judul' => $tugas_judul,
            	'tugas_deskripsi' => $tugas_deskripsi,
            	'batas_waktu' => $tanggal." ".$jam,
            	'jadwal_kode' => $jadwal_kode,            	
            	'tugas_status' => $tugas_status            	
            );        

        $this->session->set_flashdata('tambah', 'Tugas praktikum berhasil ditambahkan');
		$this->M_database->insertData('t_tugas', $data);	

        redirect("asisten/tugas?idp=$jadwal_kode", 'refresh');
	}

	function editTugas() {
		$tugas_id = $this->input->post('tugas_id');
		$tugas_kode = $this->input->post('tugas_kode');
		$tugas_judul = $this->input->post('tugas_judul');
		$tugas_deskripsi = $this->input->post('tugas_deskripsi');
		$total_file = $this->input->post('total_file');
		$tugas_status = $this->input->post('tugas_status');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$batas_waktu = $this->input->post('tanggal')." ".$this->input->post('jam').":00";		

		$l = explode(" ", $batas_waktu);
		$waktu = date_create_from_format("d/m/Y", $l[1]);
		$tanggal = date_format($waktu, 'Y-m-d');
		$jam = $l[2];						

        $data = array(
            	'tugas_judul' => $tugas_judul,
            	'tugas_deskripsi' => $tugas_deskripsi,
            	'batas_waktu' => $tanggal." ".$jam,
            	'tugas_status' => $tugas_status
            );
        
        $this->session->set_flashdata('edit', "Tugas praktikum <b>$tugas_kode</b> berhasil diedit");
        $this->M_database->updateData('t_tugas', array('tugas_id' => $tugas_id) , $data);

        redirect("asisten/tugas?idp=$jadwal_kode", 'refresh');

	}

	function hapusTugas()
	{
		$jadwal_kode = $this->input->post('jadwal_kode');
		$tugas_id = $this->input->post('tugas_id');
		$tugas_kode = $this->input->post('tugas_kode');

		$tugas_image = $this->db->where('tugas_kode', $tugas_kode)->get('t_tugas_image');

		foreach ($tugas_image->result() as $key => $value) {
			unlink('./assets/images/tugas/'.$value->tugas_image);				
		}

		$this->M_database->deleteData('t_tugas', array('tugas_id' => $tugas_id));
		$this->M_database->deleteData('t_tugas_image', array('tugas_kode' => $tugas_kode));
		$this->session->set_flashdata('hapus', 'Tugas praktikum berhasil dihapus');

		redirect("asisten/tugas?idp=$jadwal_kode", 'refresh');
	}

	function hapusFileTugas()
	{
		$file = $this->input->post('file_tugas');
		$tugas_id = $this->input->post('tugas_id');
		$tugas_kode = $this->input->post('tugas_kode');
		$jadwal_kode = $this->input->post('jadwal_kode');

		if ($file != null) {			
			foreach ($file as $tugas) {
				$this->M_database->deleteData('t_tugas_file', array('file_nama' => $tugas));
				unlink('assets/tugas_file/'.$tugas);				
			}
			$this->session->set_flashdata('hapus', 'File tugas berhasil dihapus');
		} else {
			$this->session->set_flashdata('warning', 'Tidak ada file yang dipilih');
		}

		redirect("asisten/tugas?idp=$jadwal_kode&t=$tugas_id&tk=$tugas_kode", 'refresh');
	}

	function statusTugas()
	{
		$tugas_id = $this->input->post('tugas_id');
		$status = $this->input->post('status');

		if ($status == 'Aktif') {
            $data = array('tugas_status' => 0);
			$hasil = 0;
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
            $data = array('tugas_status' => 1);
			$hasil = 1;
			$badge= '<span class="badge-status badge-ok">Aktif</span>';
		}

		$this->M_database->updateData('t_tugas', array('tugas_id' => $tugas_id), $data);

		echo "$badge|$hasil";
	}

	function fetch_image()
	{
		$tugas_kode = $this->input->get('tugas_kode');
		$image = $this->M_database->getWhere('t_tugas_image', null, null, array('tugas_kode' => $tugas_kode));
		foreach ($image->result() as $key => $value) {
			echo '<div class="img-wrapper" id="img-wrapper-'.$value->tugas_image_id.'">
					<div class="preloader-wrapper small active hide" id="myloader-'.$value->tugas_image_id.'" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
							<div class="spinner-layer spinner-teal-only">
								<div class="circle-clipper left">
									<div class="circle"></div>
								</div><div class="gap-patch">
									<div class="circle"></div>
								</div><div class="circle-clipper right">
									<div class="circle"></div>
								</div>
							</div>
						</div>                                
						<img src="'.base_url().'assets/images/tugas/'.$value->tugas_image.'" alt="tugas-img" id="foto-tugas-'.$value->tugas_image_id.'" class="responsive-img img materialboxed">
						<div id="action-foto-'.$value->tugas_image_id.'" style="position:absolute; bottom:0; width:100%">                                
							<span class="material-icons red-text text-lighten-1 left delete-foto-tugas" id="hapus-'.$value->tugas_image_id.'" data-gambar="'.$value->tugas_image.'" data-id="'.$value->tugas_image_id.'" style="cursor: pointer;">close</span>
							<span class="material-icons-outlined amber-text text-lighten-1 right edit-foto-tugas" data-gambar="'.$value->tugas_image.'" data-id="'.$value->tugas_image_id.'" id="edit-'.$value->tugas_image_id.'" style="cursor: pointer;">edit</span>
						</div>
					</div>';
		}
	}
	
	function fetch_image_carousel()
	{
		$tugas_kode = $this->input->get('tugas_kode');
		$image = $this->M_database->getWhere('t_tugas_image', null, null, array('tugas_kode' => $tugas_kode))->result();
		if ($image) {			
			echo '<div class="carousel carousel-slider" style="overflow:unset">';
			foreach ($image as $key => $value) {
				echo '<div class="carousel-item" style="width:100%">
							<img src="'.base_url().'assets/images/tugas/'.$value->tugas_image.'" alt="tugas-img" style="border-radius:8px">
						</div>';
			}
			echo '</div>';
		} else {
			echo '';
		}
	}

	function uploadFoto()
	{
		$image_id = $this->input->post('image_id');								 
		$image_edit = $this->input->post('image_edit');								 
		$kode_t = $this->input->post('kode_t');								 

		$config['upload_path'] = './assets/images/tugas/';
		$config['allowed_types'] = 'jpg|png|jpeg|webp';
		$config['file_name'] = 'tugas-img-'.time();
		$config['max_size'] = 2000;
		$config['max_width'] = 1080;
		$config['max_height'] = 1080;
		$config['overwrite'] = true;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('foto')) {

			$foto = $this->upload->data();
			$data_foto = $foto['file_name'];
			$this->compressImage($data_foto);
			
			if ($image_id != '-') {
				unlink('./assets/images/tugas/'.$image_edit);
				$this->M_database->updateData('t_tugas_image', array('tugas_image_id' => $image_id), array('tugas_image' => $data_foto));
				echo "edit|$image_id|".base_url()."assets/images/tugas/$data_foto|$data_foto";
			} else {
				$this->M_database->insertData('t_tugas_image', array('tugas_image' => $data_foto, 'tugas_kode' => $kode_t));
				$id = $this->db->insert_id();
				echo 'tambah|<div class="img-wrapper" id="img-wrapper-'.$id.'">
						<div class="preloader-wrapper small active hide" id="myloader-'.$id.'" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
								<div class="spinner-layer spinner-teal-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div><div class="gap-patch">
										<div class="circle"></div>
									</div><div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>                                
							<img src="'.base_url().'assets/images/tugas/'.$data_foto.'" alt="tugas-img" id="foto-tugas-'.$id.'" class="responsive-img img materialboxed">
							<div id="action-foto-'.$id.'" style="position:absolute; bottom:0; width:100%">                                
								<span class="material-icons red-text text-lighten-1 left delete-foto-tugas" id="hapus-'.$id.'" data-gambar="'.$data_foto.'" data-id="'.$id.'" style="cursor: pointer;">close</span>
								<span class="material-icons-outlined amber-text text-lighten-1 right edit-foto-tugas" data-gambar="'.$data_foto.'" data-id="'.$id.'" id="edit-'.$id.'" style="cursor: pointer;">edit</span>
							</div>
						</div>';         
			}                
        } else {
			echo "gagal|$image_id";
		}
		
	}

	function hapusFoto()
	{
		$id = $this->input->post('id');
		$gambar = $this->input->post('gambar');
		$this->M_database->deleteData('t_tugas_image', array('tugas_image_id' => $id));
		if ($this->db->affected_rows() > 0) {
			unlink('./assets/images/tugas/'.$gambar);
			echo "berhasil";
		} else {
			echo "gagal";
		}
	}

	function compressImage($path)
	{
		$config['image_library']='gd2';
		$config['source_image']='./assets/images/tugas/'.$path;
		$config['create_thumb']= FALSE;
		$config['maintain_ratio']= FALSE;
		$config['quality']= '50%';
		$config['new_image']= './assets/images/tugas/'.$path;
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
	}
	
	function kirimNotifikasi()
	{	
		$pesan = $this->input->post('pesan');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$url = config_item('siflabtif_client').'/tugas';	
		$token = $this->M_database->getWhere('registrasi_praktikum', 't_device_token', 'registrasi_praktikum.npm=t_device_token.npm', array('jadwal_kode' => $jadwal_kode))->result();

		$push = [];	

		$push['judul'] = 'Tugas Praktikum';
		$push['pesan'] = $pesan;
		$push['url'] = $url;

		$pushMessage = json_encode($push);

		$notifications= [];

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
					'agent' => $value->endpoint
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
			$success = 0;			
			$failed = 0;			
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
					$success += 1;
					// echo "[v] Message sent successfully for subscription {$endpoint}.";									
				} else {
					$failed += 1;
					$this->M_database->deleteData('t_device_token', array('endpoint' => $endpoint));							
					// echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";				
				}
			}			
			echo "sending|$success|$failed";
		} else {
			echo "failed|gmp not installed";
		}
	}

}
