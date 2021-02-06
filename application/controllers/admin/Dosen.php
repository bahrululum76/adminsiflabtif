<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$jumlah_data = $this->M_database->jumlah_data('t_dosen');
		$offset = $this->input->get('per_page');

		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/dosen';
		$config['page_query_string'] = TRUE;
		$config['total_rows'] = $jumlah_data;
		$config['per_page'] = 50;
		$config['first_link']       = '...';
        $config['last_link']        = '...';
        $config['next_link']        = 'NEXT';
        $config['prev_link']        = 'PREV';
        $config['full_tag_open']    = '<div class="custom-pagination">';
        $config['full_tag_close']   = '</div>';
        $config['num_tag_open']     = '<button class="btn transparent z-depth-0" style="padding: 0">';
        $config['num_tag_close']    = '</button>';
        $config['cur_tag_open']     = '<button class="btn indigo" style="padding: 0  24px">';
        $config['cur_tag_close']    = '</button>';
        $config['next_tag_open']    = '<button class="btn transparent z-depth-0 grey-text" style="padding: 0">';
        $config['next_tagl_close']  = '</button>';
        $config['prev_tag_open']    = '<button class="btn transparent z-depth-0 grey-text" style="padding: 0">';
        $config['prev_tagl_close']  = '</button>';
        $config['first_tag_open']   = '<button class="btn transparent z-depth-0" style="padding: 0">';
        $config['first_tagl_close'] = '</button>';
        $config['last_tag_open']    = '<button class="btn transparent z-depth-0" style="padding: 0">';
        $config['last_tagl_close']  = '</button>';
		$this->pagination->initialize($config);		
		$data['pagination'] = $this->pagination->create_links();
		$data['tabTitle'] = 'Dosen';
		$data['dosen'] = $this->M_database->getAllData('t_dosen', 't_jabatan', 't_dosen.jabatan_id = t_jabatan.jabatan_id', 'dosen_status DESC', 't_jabatan.jabatan_id ASC', $config['per_page'], $offset);
		$data['jabatan'] = $this->db->select('jabatan_id, jabatan_nama')->where('jabatan_id', 1)->or_where(array('jabatan_id' => 13))->get('t_jabatan');
        $data['halaman_title'] = "Dosen";
		$this->template->load('admin/template', 'admin/v-dosen', $data);
	}	

	function tambahDosen() {

		$nidn = $this->input->post('nidn');
		$password = password_hash('12345', PASSWORD_DEFAULT);
		$dosen_nama = ucwords($this->input->post('dosen_nama'));
		$dosen_foto = $this->input->post('dosen_foto');
		$jabatan_id = $this->input->post('jabatan_id');
		$email = $this->input->post('email');

        $data = array(
            	'dosen_nidn' => $nidn,
            	'password' => $password,
            	'dosen_nama' => $dosen_nama,
            	'jabatan_id' => $jabatan_id,
            	'email' => $email,
            	'dosen_status' => 1,
            	'foto' => $dosen_foto
            );

        $cek_dosen = $this->db->where('dosen_nidn', $nidn)->get('t_dosen')->row();				
		if ($cek_dosen) {
			echo "gagal|NIDN <b>$nidn</b> sudah ada, data gagal disimpan";
		} else {
			$this->M_database->insertData('t_dosen', $data);
			$dosen_akhir = $this->M_database->getAllData('t_dosen', 't_jabatan', 't_dosen.jabatan_id = t_jabatan.jabatan_id', 'dosen_id DESC', null, 1, null)->row();		
			$dosen = $this->M_database->cekdata('t_jadwal', 'dosen_id', $dosen_akhir->dosen_id);
			$title = 'hapus';
			$modal = 'modalHapus';                     

			if ($dosen) {
					$title = 'tidak bisa hapus';
					$modal = 'modalAlert';
			}
			$status = '';
			if ($dosen_akhir->dosen_status == 0){ 
				$status = '<span class="badge-status badge-not">Tidak aktif</span>';
			} else { 
				$status = '<span class="badge-status badge-ok">Aktif</span>';
			}
			$table = '<tr class="green lighten-5">
						<td></td>                                             
						<td><img src="'.base_url().'assets/images/profil/'.$dosen_akhir->foto.'" alt="profil foto" class="foto-profil-small"></td>
						<td>'.$dosen_akhir->dosen_nama.'</td>
						<td>'.$dosen_akhir->jabatan_nama.'</td>
						<td>'.$dosen_akhir->dosen_nidn.'</td>
						<td>'.$dosen_akhir->email.'</td>
						<td class="status-dosen td-data center" id="'.$dosen_akhir->dosen_id.'">'.$status.'</td>                                                
						<td class="center">							
							<button class="waves-effect mybtn transparent modal-trigger btn-password-dosen" data-target="modalPassword"
							data-dosen_id="'.$dosen_akhir->dosen_id.'"
							data-dosen_nama="'.$dosen_akhir->dosen_nama.'"><i class="material-icons-outlined grey-text">lock_open</i>
							</button>
							<button class="waves-effect mybtn transparent modal-trigger btn-edit-dosen" data-target="modalEdit"
								data-dosen_id="'.$dosen_akhir->dosen_id.'"
								data-dosen_nama="'.$dosen_akhir->dosen_nama.'"
								data-jabatan_nama="'.$dosen_akhir->jabatan_nama.'"
								data-jabatan_id="'.$dosen_akhir->jabatan_id.'"
								data-nidn="'.$dosen_akhir->dosen_nidn.'"
								data-email="'.$dosen_akhir->email.'"><i class="material-icons amber-text">edit</i>
							</button>							
							<button title="'.$title.'" class="waves-effect mybtn transparent modal-trigger btn-hapus-dosen" data-target="'.$modal.'"
								data-dosen_id="'.$dosen_akhir->dosen_id.'"
								data-dosen_nama="'.$dosen_akhir->dosen_nama.'"
								data-foto_nama="'.$dosen_akhir->foto.'"><i class="material-icons red-text text-lighten-1">delete</i>
							</button>
						</td>
					</tr>';
			echo "sukses|Data dosen <b>$dosen_nama</b> berhasil disimpan|$table";
		}

	}

	function editdosen() {

		$dosen_id = $this->input->post('dosen_id');
		$nidn = $this->input->post('nidn');
		$nidn_baru = $this->input->post('nidn_baru');
		$dosen_nama = ucwords($this->input->post('dosen_nama'));
		$jabatan_id = $this->input->post('jabatan_id');
		$email = $this->input->post('email');

        $data = array(
            	'dosen_nidn' => $nidn_baru,
            	'dosen_nama' => $dosen_nama,
            	'jabatan_id' => $jabatan_id,
            	'email' => $email,
			);
			
		$cek_dosen = $this->db->where('dosen_nidn', $nidn_baru)->get('t_dosen')->row();
		
		if ($nidn_baru == $nidn) {	
			$this->session->set_flashdata('edit', "Profil dosen <b>$dosen_nama</b> berhasil diedit");
        	$this->M_database->updateData('t_dosen', array('dosen_id' => $dosen_id), $data);
			redirect('admin/dosen','refresh');
		} else if($cek_dosen) {
			echo "gagal|NIDN <b>$nidn</b> sudah ada, data gagal disimpan";
		} else {
			$this->session->set_flashdata('edit', "Profil dosen <b>$dosen_nama</b> berhasil diedit");
        	$this->M_database->updateData('t_dosen', array('dosen_id' => $dosen_id), $data);
			redirect('admin/dosen','refresh');			
		}

	}

	function hapusdosen()
	{
		$dosen_id = $this->input->post('dosen_id');
		$dosen_nama = ucwords($this->input->post('dosen_nama'));
		$foto = $this->input->post('foto');

		if ($foto == 'default-profil-l.jpg' || $foto == 'default-profil-p.jpg') {
			// no action
		} else {
			unlink('assets/images/profil/'.$foto);
		}

		$this->session->set_flashdata('hapus', "Data dosen <b>$dosen_nama</b> berhasil dihapus");
		$this->M_database->deleteData('t_dosen', array('dosen_id' => $dosen_id));

		redirect('admin/dosen', 'refresh');
	}

	function statusdosen()
	{
		$dosen_id = $this->input->post('dosen_id');
		$status = $this->input->post('status');
		$nidn = $this->input->post('nidn');

		if ($status == 'Aktif') {
			$data = array('dosen_status' => 0);
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
			$data = array('dosen_status' => 1);
			$badge = '<span class="badge-status badge-ok">Aktif</span>';
		}

		$this->M_database->updateData('t_dosen', array('dosen_id' => $dosen_id), $data);
		$this->M_database->deleteData('t_device_token_admin', array('user_id' => $nidn));

		echo $badge;
	}

	function resetPassword()
	{
		$dosen_id = $this->input->post('dosen_id');
		$dosen_nama = $this->input->post('dosen_nama');
		$password = password_hash('12345', PASSWORD_DEFAULT);

		$data = array(
					'password' => $password
				);

		$this->session->set_flashdata('edit', "Reset password dosen <b>$dosen_nama</b> berhasil");
		$this->M_database->updateData('t_dosen', array('dosen_id' => $dosen_id), $data);

		redirect('admin/dosen', 'refresh');
	}


}
