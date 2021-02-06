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
		
		$data['asisten_id'] = $asisten->asisten_id;
		$data['username'] = $asisten->username;
		$data['asisten_nama'] = $asisten->asisten_nama;
		$data['jabatan_id'] = $asisten->jabatan_id;
		$data['jabatan_nama'] = $asisten->jabatan_nama;		
		$data['email'] = $asisten->email;
		$data['foto'] = $asisten->foto;
		$data['tabTitle'] = 'Profil';		

		$this->template->load('bak/template-bak', 'bak/v-profil', $data);
	}

	function editProfil() {

		$asisten_id = $this->session->userdata('asisten_id');
		$asisten_nama = $this->input->post('asisten_nama');		
		$email = $this->input->post('email');

        $data = array(
            	'asisten_nama' => $asisten_nama,
            	'email' => $email
            );

        $this->session->set_flashdata('edit', 'Profil berhasil diedit');
        $this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), $data);

        redirect("bak/profil", 'refresh');

	}

	function uploadFoto()
	{
		$asisten_id = $this->session->userdata('asisten_id');
		$foto_nama = $this->input->post('foto_nama');								 

		$config['upload_path'] = './assets/images/profil/';
		$config['allowed_types'] = 'jpg|png|jpeg|webp';
		$config['file_name'] = 'bak'.time();
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
		$this->template->load('bak/template-bak', 'bak/v-password');
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
