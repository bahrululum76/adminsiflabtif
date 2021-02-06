<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asisten extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$jumlah_data = $this->M_database->jumlah_data('t_asisten');
		$offset = $this->input->get('per_page');

		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/asisten';
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
		$data['asisten'] = $this->M_database->getAllData('t_asisten', 't_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id', 'status DESC', 'asisten_id DESC', $config['per_page'], $offset);
		$data['jabatan'] = $this->db->select('jabatan_id, jabatan_nama')->where('jabatan_id !=', 1)->where('jabatan_id !=', 13)->get('t_jabatan');
		$data['halaman_title'] = "Asisten";
		$data['tabTitle'] = 'Asisten';
		$this->template->load('admin/template', 'admin/v-asisten', $data);
	}

	function view_tambah()
	{
		$data['asisten'] = $this->M_database->getAllData('t_asisten', 't_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id', null);
		$data['jabatan'] = $this->M_database->getAllData('t_jabatan', null, null, 'jabatan_nama ASC');
		$this->template->load('admin/template', 'admin/v-asisten-tambah', $data);
	}

	function view_edit()
	{
		$asisten_id =  $this->uri->segment(4);
		$asisten = $this->M_database->getWhere('t_asisten', 't_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id', array('asisten_id' => $asisten_id))->result();
		$data['jabatan'] = $this->M_database->getAllData('t_jabatan', null, null, 'jabatan_nama ASC');


		foreach ($asisten as $key => $value) {
			$data['asisten_id'] = $value->asisten_id;
			$data['username'] = $value->username;
			$data['asisten_nama'] = $value->asisten_nama;
			$data['jabatan_id'] = $value->jabatan_id;
			$data['jabatan_nama'] = $value->jabatan_nama;
			$format = date_create($value->tgl_lahir);
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
			$tgl_lahir = explode('-', $value->tgl_lahir);
			$data['tanggal'] = $tgl_lahir[2];
			$data['n_bulan'] = $tgl_lahir[1];
			$data['tahun'] = $tgl_lahir[0];
			$data['alamat'] = $value->alamat;
			$data['email'] = $value->email;
			$data['tahun_masuk'] = $value->thn_masuk;
			$data['tahun_keluar'] = $value->thn_keluar;
			$data['foto'] = $value->foto;
		}

		$this->template->load('admin/template', 'admin/v-asisten-edit', $data);
	}

	function tambahAsisten() {

		$username = $this->input->post('username');
		$password = password_hash('12345', PASSWORD_DEFAULT);
		$asisten_nama = ucwords($this->input->post('asisten_nama'));
		$asisten_foto = $this->input->post('asisten_foto');
		$tgl_lahir = $this->input->post('tgl_lahir');
		$bulan_lahir = $this->input->post('bulan_lahir');
		$tahun_lahir = $this->input->post('tahun_lahir');
		$jabatan_id = $this->input->post('jabatan_id');
		$alamat = $this->input->post('alamat');
		$email = $this->input->post('email');		
		$tahun_masuk = $this->input->post('tahun_masuk');		
		$tahun_keluar = $this->input->post('tahun_keluar');		

        $data = array(
            	'username' => $username,
            	'password' => $password,
            	'asisten_nama' => $asisten_nama,
            	'tgl_lahir' => $tahun_lahir.'-'.$bulan_lahir.'-'.$tgl_lahir,
            	'jabatan_id' => $jabatan_id,
            	'alamat' => $alamat,
            	'email' => $email,
            	'thn_masuk' => $tahun_masuk,
            	'thn_keluar' => $tahun_keluar,
            	'status' => 1,
            	'foto' => $asisten_foto
            );

        $cek_asisten = $this->db->where('username', $username)->get('t_asisten')->row();				
		if ($cek_asisten) {
			echo "gagal|Username <b>$username</b> sudah ada, data gagal disimpan";
		} else {
			$this->M_database->insertData('t_asisten', $data);
			$asisten_akhir = $this->M_database->getAllData('t_asisten', 't_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id', 'asisten_id DESC', null, 1, null)->row();		
			$asisten_1 = $this->M_database->cekdata('t_jadwal', 'asisten_1', $asisten_akhir->asisten_id);
			$asisten_2 = $this->M_database->cekdata('t_jadwal', 'asisten_2', $asisten_akhir->asisten_id);

			$title = 'hapus';
			$modal = 'modalHapus';

			if ($asisten_1 != 0 || $asisten_2 != 0) {
					$title = 'tidak bisa hapus';
					$modal = 'modalAlert';
			}

			$status = '';
			if ($asisten_akhir->status == 0){ 
				$status = '<span class="badge-status badge-not">Tidak aktif</span>';
			} else { 
				$status = '<span class="badge-status badge-ok">Aktif</span>';
			}
			$table = '<tr class="green lighten-5">
						<td></td>                                             
						<td><img src="'.base_url().'assets/images/profil/'.$asisten_akhir->foto.'" alt="profil foto" class="foto-profil-small"></td>
						<td>'.$asisten_akhir->asisten_nama.'</td>
						<td>'.$asisten_akhir->jabatan_nama.'</td>
						<td class="status-asisten td-data center" id="'.$asisten_akhir->asisten_id.'">'.$status.'</td>                                                
						<td class="center">
							<button class="waves-effect mybtn transparent modal-trigger btn-lihat-asisten" data-target="modalLihat"
								data-asisten_id="'.$asisten_akhir->asisten_id.'"
								data-asisten_nama="'.$asisten_akhir->asisten_nama.'"
								data-jabatan_nama="'.$asisten_akhir->jabatan_nama.'"
								data-username="'.$asisten_akhir->username.'"
								data-tgl_lahir="'.$asisten_akhir->tgl_lahir.'"
								data-alamat="'.$asisten_akhir->alamat.'"
								data-email="'.$asisten_akhir->email.'"
								data-foto="'.base_url().'assets/images/profil/'.$asisten_akhir->foto.'"
								data-tahun_masuk="'.$asisten_akhir->thn_masuk.'"
								data-tahun_keluar="'.$asisten_akhir->thn_keluar.'"><i class="material-icons-outlined blue-text text-accent-1">remove_red_eye</i>
							</button>
							<button class="waves-effect mybtn transparent modal-trigger btn-password-asisten" data-target="modalPassword"
							data-asisten_id="'.$asisten_akhir->asisten_id.'"
							data-asisten_nama="'.$asisten_akhir->asisten_nama.'"><i class="material-icons-outlined grey-text">lock_open</i>
							</button>
							<button class="waves-effect mybtn transparent modal-trigger btn-edit-asisten" data-target="modalEdit"
								data-asisten_id="'.$asisten_akhir->asisten_id.'"
								data-asisten_nama="'.$asisten_akhir->asisten_nama.'"
								data-jabatan_nama="'.$asisten_akhir->jabatan_nama.'"
								data-jabatan_id="'.$asisten_akhir->jabatan_id.'"
								data-username="'.$asisten_akhir->username.'"
								data-tgl_lahir="'.$asisten_akhir->tgl_lahir.'"
								data-alamat="'.$asisten_akhir->alamat.'"
								data-email="'.$asisten_akhir->email.'"								
								data-tahun_masuk="'.$asisten_akhir->thn_masuk.'"
								data-tahun_keluar="'.$asisten_akhir->thn_keluar.'"><i class="material-icons amber-text">edit</i>
							</button>							
							<button title="'.$title.'" class="waves-effect mybtn transparent modal-trigger btn-hapus-asisten" data-target="'.$modal.'"
								data-asisten_id="'.$asisten_akhir->asisten_id.'"
								data-asisten_nama="'.$asisten_akhir->asisten_nama.'"
								data-foto_nama="'.$asisten_akhir->foto.'"><i class="material-icons red-text text-lighten-1">delete</i>
							</button>
						</td>
					</tr>';
			echo "sukses|Data asisten <b>$asisten_nama</b> berhasil disimpan|$table";
		}

	}

	function editAsisten() {

		$asisten_id = $this->input->post('asisten_id');
		$username = $this->input->post('username');
		$username_baru = $this->input->post('username_baru');
		$asisten_nama = ucwords($this->input->post('asisten_nama'));
		$tgl_lahir = $this->input->post('tgl_lahir');
		$bulan_lahir = $this->input->post('bulan_lahir');
		$tahun_lahir = $this->input->post('tahun_lahir');
		$jabatan_id = $this->input->post('jabatan_id');
		$alamat = $this->input->post('alamat');
		$email = $this->input->post('email');
		$tahun_masuk = $this->input->post('tahun_masuk');		
		$tahun_keluar = $this->input->post('tahun_keluar');				

        $data = array(
            	'username' => $username_baru,
            	'asisten_nama' => $asisten_nama,
            	'tgl_lahir' => $tahun_lahir.'-'.$bulan_lahir.'-'.$tgl_lahir,
            	'jabatan_id' => $jabatan_id,
            	'alamat' => $alamat,
				'email' => $email,
				'thn_masuk' => $tahun_masuk,
            	'thn_keluar' => $tahun_keluar            	
			);
			
		$cek_asisten = $this->db->where('username', $username_baru)->get('t_asisten')->row();
		
		if ($username_baru == $username) {	
			$this->session->set_flashdata('edit', "Data asisten <b>$asisten_nama</b> berhasil diedit");
        	$this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), $data);
        	redirect('admin/asisten','refresh');
		} else if($cek_asisten) {
			echo "gagal|Username <b>$username</b> sudah ada, data gagal disimpan";
		} else {
			$this->session->set_flashdata('edit', "Data asisten <b>$asisten_nama</b> berhasil diedit");
        	$this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), $data);
        	redirect('admin/asisten','refresh');			
		}        

	}

	function hapusAsisten()
	{
		$asisten_id = $this->input->post('asisten_id');
		$asisten_nama = $this->input->post('asisten_nama');
		$foto = $this->input->post('foto');

		if ($foto == 'default-profil-l.jpg' || $foto == 'default-profil-p.jpg') {
			// no action
		} else {
			unlink('assets/images/profil/'.$foto);
		}

		$this->session->set_flashdata('hapus', "Data asisten <b>$asisten_nama</b> berhasil dihapus");
		$this->M_database->deleteData('t_asisten', array('asisten_id' => $asisten_id));

		redirect('admin/asisten', 'refresh');
	}

	function statusAsisten()
	{
		$asisten_id = $this->input->post('asisten_id');
		$status = $this->input->post('status');
		$username = $this->input->post('username');

		if ($status == 'Aktif') {
			$data = array('status' => 0, 'thn_keluar' => date('Y'));
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
			$data = array('status' => 1, 'thn_keluar' => '-');
			$badge = '<span class="badge-status badge-ok">Aktif</span>';
		}

		$this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), $data);
		$this->M_database->deleteData('t_device_token_admin', array('user_id' => $username));

		echo $badge;
	}

	function resetPassword()
	{
		$asisten_id = $this->input->post('asisten_id');
		$asisten_nama = $this->input->post('asisten_nama');
		$password = password_hash('12345', PASSWORD_DEFAULT);

		$data = array(
					'password' => $password
				);

		$this->session->set_flashdata('edit', "Reset password asisten <b>$asisten_nama</b> berhasil");
		$this->M_database->updateData('t_asisten', array('asisten_id' => $asisten_id), $data);

		redirect('admin/asisten', 'refresh');
	}


}
