<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session_dosen();
	}
	

	function index()
	{
		$dosen_id =  $this->session->userdata('dosen_id');
		$dosen = $this->M_database->getWhere('t_dosen', 't_jabatan', 't_dosen.jabatan_id = t_jabatan.jabatan_id', array('dosen_id' => $dosen_id))->row();
		$data['jabatan'] = $this->M_database->getAllData('t_jabatan', null, null, null);

        $data['dosen_id'] = $dosen->dosen_id;
        $data['dosen_nidn'] = $dosen->dosen_nidn;
        $data['email'] = $dosen->email;
        $data['dosen_nama'] = $dosen->dosen_nama;
        $data['foto'] = $dosen->foto;		       
        $data['jabatan_id'] = $dosen->jabatan_id;
        $data['jabatan_nama'] = $dosen->jabatan_nama;       
		$data['tabTitle'] = 'Profil';
		$this->template->load('dosen/template-dosen', 'dosen/v-profil', $data);
	}

	function editProfil() {

		$dosen_id = $this->session->userdata('dosen_id');
		$dosen_nama = $this->input->post('dosen_nama');
		$email = $this->input->post('email');

        $data = array(
            	'dosen_nama' => $dosen_nama,
            	'email' => $email
            );

        $this->session->set_flashdata('edit', 'Profil berhasil diedit');
        $this->M_database->updateData('t_dosen', array('dosen_id' => $dosen_id), $data);

        redirect("dosen/profil", 'refresh');

	}

	function uploadFoto()
	{
		$dosen_id = $this->session->userdata('dosen_id');
		$foto_nama = $this->input->post('foto_nama');								 

		$config['upload_path'] = './assets/images/profil/';
		$config['allowed_types'] = 'jpg|png|jpeg|webp';
		$config['file_name'] = 'dosen'.time();
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
		
		$this->M_database->updateData('t_dosen', array('dosen_id' => $dosen_id), array('foto' => $data_foto));


	}

	function view_password()
	{
		$data['tabTitle'] = 'Ganti Password';
		$this->template->load('dosen/template-dosen', 'dosen/v-password');
	}

	function gantiPassword()
	{
		$dosen_id = $this->session->userdata('dosen_id');
		$old_pass = $this->input->post('old_pass');
		$password = $this->input->post('password');
		$asisten = $this->db->select('password')->where('dosen_id', $dosen_id)->get('t_dosen')->row();

		if (password_verify($old_pass, $asisten->password)) {			
			$data = array('password' => password_hash($password, PASSWORD_DEFAULT));
			$this->M_database->updateData('t_dosen', array('dosen_id' => $dosen_id), $data);
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
