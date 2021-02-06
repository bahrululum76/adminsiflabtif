<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Pengumuman extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$info_id = $this->db->select('info_id')->order_by('info_id DESC')->limit(1)->get('t_info')->row();
		$id = 0;
		if ($info_id == null) {
			$id = 1;
		} else {
			$id = $info_id->info_id + 1;
		}

		$data['tabTitle'] = 'Info Praktikum';
		$data['kode_info'] = $id;
		$data['info'] = $this->M_database->getAllData('t_info', null, null, 'info_id DESC');
		$data['kelas'] = $this->M_database->getWhere('t_kelas', null, null, array('kelas_status' => 1), 'kelas_id DESC');
		$data['foto_info'] = $this->M_database->getWhere('t_info_image', null, null, array('info_id' => $id), null);
        $data['halaman_title'] = "Info Praktikum";
		$this->template->load('admin/template', 'admin/v-pengumuman', $data);
	}

	function tambahInfo() {
		$info_id = $this->input->post('info_id');
		$info_caption = $this->input->post('info_caption');
		$date_created = date('Y-m-d H:i:s');
		$kelas = $this->input->post('kelas');

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
		
		$data = array(
				'info_id' => $info_id,
				'info_caption' => $info_caption,
				'date_created' => strtoupper($bulan).' '.date('j')
			);
			
		$this->M_database->insertData('t_info', $data);

		if (in_array('all', $kelas)) {
			$kelasAktif = $this->M_database->getWhere('t_kelas', null, null, array('kelas_status' => 1), null);
			$infoKelas = [];
			foreach ($kelasAktif->result() as $kelasAktif) {
				array_push($infoKelas, array(
					'kelas_kode' => $kelasAktif->kelas_kode,
					'info_id' => $info_id
				));
			}
			$this->db->insert_batch('t_info_kelas', $infoKelas);
		} else {
			$infoKelas = [];
			foreach ($kelas as $kelas) {
				array_push($infoKelas, array(
					'kelas_kode' => $kelas,
					'info_id' => $info_id
				));
			}
			$this->db->insert_batch('t_info_kelas', $infoKelas);
		}

        $this->session->set_flashdata('tambah', 'Info Praktikum berhasil disimpan');
        redirect('admin/pengumuman','refresh');
	}

	function editInfo() {

		$info_id = $this->input->post('info_id');
		$info_caption = $this->input->post('info_caption');
		$kelas = $this->input->post('kelas');			

        $data = array(
            	'info_caption' => $info_caption,
			);
		
		$this->M_database->updateData('t_info', array('info_id' => $info_id) , $data);
		$this->M_database->deleteData('t_info_kelas', array('info_id' => $info_id));

		if (in_array('all', $kelas)) {
			$kelasAktif = $this->M_database->getWhere('t_kelas', null, null, array('kelas_status' => 1), null);
			$infoKelas = [];
			foreach ($kelasAktif->result() as $kelasAktif) {
				array_push($infoKelas, array(
					'kelas_kode' => $kelasAktif->kelas_kode,
					'info_id' => $info_id
				));
			}
			$this->db->insert_batch('t_info_kelas', $infoKelas);
		} else {
			$infoKelas = [];
			foreach ($kelas as $kelas) {
				array_push($infoKelas, array(
					'kelas_kode' => $kelas,
					'info_id' => $info_id
				));
			}
			$this->db->insert_batch('t_info_kelas', $infoKelas);
		}

        $this->session->set_flashdata('edit', 'Info Praktikum berhasil diedit');

        redirect('admin/pengumuman','refresh');

	}

	function hapusInfo()
	{
		$info_id = $this->input->post('info_id');

		$info_image = $this->db->where('info_id', $info_id)->get('t_info_image');
		foreach ($info_image->result() as $key => $value) {
			unlink('./assets/images/info/'.$value->info_image);				
		}

		$this->M_database->deleteData('t_info', array('info_id' => $info_id));
		$this->M_database->deleteData('t_info_image', array('info_id' => $info_id));
		$this->M_database->deleteData('t_info_kelas', array('info_id' => $info_id));
		$this->session->set_flashdata('hapus', 'Info Praktikum berhasil dihapus');
		redirect('admin/pengumuman', 'refresh');
	}

	function kirimNotifikasi()
	{	
		$pesan = $this->input->post('pesan');
		$kelas = $this->input->post('kelas');
		$url = $this->input->post('halaman_tujuan');
		$kelas_in = "";

		foreach ($kelas as $kelas) {
			$kelas_in .= "kelas_kode = '$kelas' OR ";
		}

		$mhs = rtrim($kelas_in, ' OR ');
		$token = $this->db->select('t_mahasiswa.npm, endpoint, p256dh, auth, agent')->join('t_device_token', 't_mahasiswa.npm=t_device_token.npm')->where($mhs, NULL, FALSE)->get('t_mahasiswa')->result();	

		$push = [];	

		$push['judul'] = 'Info Praktikum';
		$push['pesan'] = $pesan;
		$push['url'] = config_item('siflabtif_client').'/NotificationRoute?url='.$url;

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
				'privateKey' => 'LtmDip-uATrvkViEo2RSQ3ZzQKL53uHGoZn2Dw1B0LY'
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

	function fetch_image()
	{
		$info_id = $this->input->get('info_id');
		$image = $this->M_database->getWhere('t_info_image', null, null, array('info_id' => $info_id));
		foreach ($image->result() as $key => $value) {
			echo '<div class="img-wrapper" id="img-wrapper-'.$value->info_image_id.'">
					<div class="preloader-wrapper small active hide" id="myloader-'.$value->info_image_id.'" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
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
						<img src="'.base_url().'assets/images/info/'.$value->info_image.'" alt="info-img" id="foto-info-'.$value->info_image_id.'" class="responsive-img img materialboxed">
						<div id="action-foto-'.$value->info_image_id.'" style="position:absolute; bottom:0; width:100%">                                
							<span class="material-icons red-text text-lighten-1 left delete-foto-info" id="hapus-'.$value->info_image_id.'" data-gambar="'.$value->info_image.'" data-id="'.$value->info_image_id.'" style="cursor: pointer;">close</span>
							<span class="material-icons-outlined amber-text text-lighten-1 right edit-foto-info" data-gambar="'.$value->info_image.'" data-id="'.$value->info_image_id.'" id="edit-'.$value->info_image_id.'" style="cursor: pointer;">edit</span>
						</div>
					</div>';
		}
	}

	function uploadFoto()
	{
		$image_id = $this->input->post('image_id');								 
		$image_edit = $this->input->post('image_edit');								 
		$info_id = $this->input->post('info_id');								 

		$config['upload_path'] = './assets/images/info/';
		$config['allowed_types'] = 'jpg|png|jpeg|webp';
		$config['file_name'] = 'info-img-'.time();
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
				unlink('./assets/images/info/'.$image_edit);
				$this->M_database->updateData('t_info_image', array('info_image_id' => $image_id), array('info_image' => $data_foto));
				echo "edit|$image_id|".base_url()."assets/images/info/$data_foto|$data_foto";
			} else {
				$this->M_database->insertData('t_info_image', array('info_image' => $data_foto, 'info_id' => $info_id));
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
							<img src="'.base_url().'assets/images/info/'.$data_foto.'" alt="info-img" id="foto-info-'.$id.'" class="responsive-img img materialboxed">
							<div id="action-foto-'.$id.'" style="position:absolute; bottom:0; width:100%">                                
								<span class="material-icons red-text text-lighten-1 left delete-foto-info" data-gambar="'.$data_foto.'" data-id="'.$id.'" id="hapus-'.$id.'" style="cursor: pointer;">close</span>
								<span class="material-icons-outlined amber-text text-lighten-1 right edit-foto-info" data-gambar="'.$data_foto.'" data-id="'.$id.'" id="edit-'.$id.'" style="cursor: pointer;">edit</span>
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
		$this->M_database->deleteData('t_info_image', array('info_image_id' => $id));
		if ($this->db->affected_rows() > 0) {
			unlink('./assets/images/info/'.$gambar);
			echo "berhasil";
		} else {
			echo "gagal";
		}
	}

	function compressImage($path)
	{
		$config['image_library']='gd2';
		$config['source_image']='./assets/images/info/'.$path;
		$config['create_thumb']= FALSE;
		$config['maintain_ratio']= FALSE;
		$config['quality']= '50%';
		$config['new_image']= './assets/images/info/'.$path;
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
	}


}
