<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_null();
	}
	

	function index()
	{
		$asisten_id =  $this->session->userdata('asisten_id');
		$asisten = $this->M_database->getWhere('t_asisten', 't_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id', array('asisten_id' => $asisten_id))->row();
		$data['jabatan'] = $this->M_database->getAllData('t_jabatan', null, null, null);
		
		$data['tabTitle'] = 'Profil';
		$data['asisten_id'] = $asisten->asisten_id;
		$data['username'] = $asisten->username;
		$data['asisten_nama'] = $asisten->asisten_nama;
		$data['jabatan_id'] = $asisten->jabatan_id;
		$data['jabatan_nama'] = $asisten->jabatan_nama;
		$format = date_create($asisten->tgl_lahir);
		$date = date_format($format, 'm');
		$bulan = 'Pilih Bulan';
		switch ($date) {
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
				$bulan = 'Pilih Bulan';
				break;
		}
		$data['bulan'] = $bulan;
		$tgl_lahir = explode('-', $asisten->tgl_lahir);
		$data['tanggal'] = $tgl_lahir[2];
		$data['n_bulan'] = $tgl_lahir[1];
		$data['tahun'] = $tgl_lahir[0];
		$data['alamat'] = $asisten->alamat;
		$data['email'] = $asisten->email;
		$data['tahun_masuk'] = $asisten->thn_masuk;
		$data['tahun_keluar'] = $asisten->thn_keluar;
		$data['foto'] = $asisten->foto;		
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
		$this->template->load('asisten/template-asisten', 'asisten/v-profil', $data);
	}

	function editProfil() {

		$asisten_id = $this->session->userdata('asisten_id');
		$asisten_nama = $this->input->post('asisten_nama');
		$tgl_lahir = $this->input->post('tgl_lahir');
		$bulan_lahir = $this->input->post('bulan_lahir');
		$tahun_lahir = $this->input->post('tahun_lahir');
		$alamat = $this->input->post('alamat');
		$email = $this->input->post('email');

        $data = array(
            	'asisten_nama' => $asisten_nama,
            	'tgl_lahir' => $tahun_lahir.'-'.$bulan_lahir.'-'.$tgl_lahir,
            	'email' => $email,
            	'alamat' => $alamat
            );

        $this->session->set_flashdata('edit', 'Profil berhasil diedit');
        $this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), $data);

        redirect("asisten/profil", 'refresh');

	}

	function uploadFoto()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		$foto_nama = $this->input->post('foto_nama');								 

		$config['upload_path'] = './assets/images/profil/';
		$config['allowed_types'] = 'jpg|png|jpeg|webp';
		$config['file_name'] = 'asisten'.time();
		$config['max_size'] = 2000;
		$config['max_width'] = 1080;
		$config['max_height'] = 1080;
		$config['overwrite'] = true;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('foto')) {

		 	if ($foto_nama == 'default-profil-l.jpg' || $foto_nama == 'default-profil-p.jpg') {
            	$foto = $this->upload->data();          		 		
		 	} else {
		 		unlink('./assets/images/profil/'.$foto_nama);
            	$foto = $this->upload->data();          
		 	}         
                
			$data_foto = $foto['file_name'];
			$this->compressImage($data_foto);
			echo "$data_foto";          
        } else {
        	$data_foto = $foto_nama;
			echo "gagal";
		}
		
		$this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), array('foto' => $data_foto));


	}

	function view_password()
	{
		$data['tabTitle'] = 'Ganti Password';
		$data['jmlTicket'] = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
		$data['jmlPesanMhs'] = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
		$this->template->load('asisten/template-asisten', 'asisten/v-password', $data);
	}

	function gantiPassword()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		$old_pass = $this->input->post('old_pass');
		$password = $this->input->post('password');
		$asisten = $this->db->select('password')->where('asisten_id', $asisten_id)->get('t_asisten')->row();

		if (password_verify($old_pass, $asisten->password)) {			
			$data = array('password' => password_hash($password, PASSWORD_DEFAULT));
			$this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), $data);
			$this->session->sess_destroy();
			echo 'sukses|'.base_url().'auth';
		} else {
			echo 'gagal|asisten';
		}
	}

	function compressImage($path)
	{
		$config['image_library']='gd2';
		$config['source_image']='./assets/images/profil/'.$path;
		$config['create_thumb']= FALSE;
		$config['maintain_ratio']= FALSE;
		$config['quality']= '50%';
		$config['width']= 300;
		$config['height']= 300;
		$config['new_image']= './assets/images/profil/'.$path;
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
	}


}
