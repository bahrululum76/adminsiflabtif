<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{		
		$data['mahasiswa'] = $this->M_database->getAllData('t_mahasiswa', 't_kelas', 't_mahasiswa.kelas_kode = t_kelas.kelas_kode', 'npm DESC');
		$data['kelas'] = $this->M_database->getWhere('t_kelas', null, null, array('kelas_status' => 1), 'kelas_id DESC');
		$data['halaman_title'] = "Mahasiswa";
		$data['tabTitle'] = 'Mahasiswa';
		$this->template->load('admin/template', 'admin/v-mahasiswa', $data);
	}

	function tambahMahasiswa()
	{
		$npm = $this->input->post('npm');
		$password = password_hash('12345', PASSWORD_DEFAULT);
		$nama = ucwords($this->input->post('nama'));
		$kelas_kode = $this->input->post('kelas_kode');

		$data = array(
					'npm' => $npm,
					'password' => $password,
					'nama' => $nama,
					'kelas_kode' => $kelas_kode,
					'status_bayar' => 0,
					'status_bayar_bak' => 0,
					'status_mhs' => 1,
					'created_at' => date('Y-m-d')
				);

		$cek_npm = $this->db->where('npm', $npm)->get('t_mahasiswa')->row();

		if ($cek_npm) {
			echo "gagal|Mahasiswa dengan NPM : <b>$npm</b> sudah ada, data gagal disimpan";
		} else {
			$this->M_database->insertData('t_mahasiswa', $data);
			echo "sukses|Data mahasiswa <b>$npm</b> berhasil disimpan";
		}
	}

	function editMahasiswa()
	{
		$mhs_id = $this->input->post('mhs_id');
		$npm = $this->input->post('npm');
		$npm_baru = $this->input->post('npm_baru');
		$nama = ucwords($this->input->post('nama'));
		$kelas_kode = $this->input->post('kelas_kode');		
		
		$data = array(
					'npm' => $npm_baru,
					'nama' => $nama,
					'kelas_kode' => $kelas_kode
				);

		$cek_npm = $this->db->where('npm', $npm_baru)->get('t_mahasiswa')->row();

		if ($npm_baru == $npm) {	
			$this->session->set_flashdata('edit', "Data mahasiswa <b>$nama</b> berhasil diedit");
			$this->M_database->updateData('t_mahasiswa', array('mhs_id' => $mhs_id), $data);
			redirect('admin/mahasiswa', 'refresh');
		} else if($cek_npm) {
			echo "gagal|Mahasiswa dengan NPM : <b>$npm</b> sudah ada, data gagal disimpan";
		} else {
			$this->session->set_flashdata('edit', "Data mahasiswa <b>$nama</b> berhasil diedit");
			$this->M_database->updateData('t_mahasiswa', array('mhs_id' => $mhs_id), $data);
			redirect('admin/mahasiswa', 'refresh');			
		} 

		
	}

	function hapusMahasiswa()
	{
		$mhs_id = $this->input->post('mhs_id');
		$npm = $this->input->post('npm');
		$nama = $this->input->post('nama');

		$delete = $this->M_database->deleteData('t_pesan_mhs', array('npm' => $npm));
		$delete = $this->M_database->deleteData('t_mahasiswa', array('mhs_id' => $mhs_id));
		$this->session->set_flashdata('hapus', "Data mahasiswa <b>$nama</b> berhasil dihapus");
		redirect('admin/mahasiswa', 'refresh');
	}

	function resetPassword()
	{
		$mhs_id = $this->input->post('mhs_id');
		$nama = $this->input->post('nama');
		$password = password_hash('12345', PASSWORD_DEFAULT);

		$data = array(
					'password' => $password
				);

		$this->M_database->updateData('t_mahasiswa', array('mhs_id' => $mhs_id), $data);
		$this->session->set_flashdata('edit', "Reset password mahasiswa <b>$nama</b> berhasil");
		redirect('admin/mahasiswa', 'refresh');
	}

	function statusMhs()
	{
		$npm = $this->input->post('npm');
		$status = $this->input->post('status');

		if ($status == 'Aktif') {
			$data = array('status_mhs' => 0);
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
			$data = array('status_mhs' => 1);
			$badge = '<span class="badge-status badge-ok">Aktif</span>';
		}

		$this->M_database->updateData('t_mahasiswa', array('npm' => $npm), $data);
		$this->M_database->deleteData('t_device_token', array('npm' => $npm));

		echo $badge;
	}

	function fetch_mahasiswa() {		
		$fetch_data = $this->M_database->render_datatable();
		$data = array();
		foreach ($fetch_data as $row => $value) {
			$registrasi = $this->M_database->cekdata('registrasi_praktikum', 'npm', $value->npm);

			$title = 'hapus';
			$modal = 'modalHapus';
			$bg = '';

			if ($value->created_at == date('Y-m-d')) {
				$bg = '<div class="hide"></div>';
			}

			if ($registrasi) {
					$title = 'tidak bisa hapus';
					$modal = 'modalAlert';
			}

			$status = '';
			if ($value->status_mhs == 0){ 
				$status = '<span class="badge-status badge-not">Tidak aktif</span>';
			} else { 
				$status = '<span class="badge-status badge-ok">Aktif</span>';
			}
			$sub_array = array();
			$sub_array[] = $bg;
			$sub_array[] = $value->npm;
			$sub_array[] = $value->nama;
			$sub_array[] = $value->kelas_nama;
			$sub_array[] = '<div class="status-mhs td-data center-align" id="'.$value->npm.'">'.$status.'</div>';
			$sub_array[] = '<div class="center-align"><button class="waves-effect mybtn transparent modal-trigger btn-password-mhs" data-target="modalPassword"
								data-mhs_id="'.$value->mhs_id.'"
								data-npm="'.$value->npm.'"
								data-nama="'.$value->nama.'"
								data-kelas_nama="'.$value->kelas_nama.'"
								data-kelas_kode="'.$value->kelas_kode.'">
								<i class="material-icons-outlined grey-text">lock_open</i>
							</button>
							<button class="waves-effect mybtn transparent modal-trigger btn-edit-mhs" data-target="modalEdit"
								data-mhs_id="'.$value->mhs_id.'"
								data-npm="'.$value->npm.'"
								data-nama="'.$value->nama.'"
								data-kelas_nama="'.$value->kelas_nama.'"
								data-kelas_kode="'.$value->kelas_kode.'">
								<i class="material-icons-outlined amber-text">edit</i>
							</button>
							<button title="'.$title.'" class="waves-effect mybtn transparent modal-trigger btn-hapus-mhs" data-target="'.$modal.'"
								data-mhs_id="'.$value->mhs_id.'"
								data-npm="'.$value->npm.'"
								data-nama="'.$value->nama.'"><i class="material-icons red-text text-lighten-1">delete</i>
							</button></div>';
			$data[] = $sub_array;
		}
		$output = array(
			"draw" 				=> intval($_POST["draw"]),
			"recordsTotal" 		=> $this->M_database->get_all_data(),
			"recordsFiltered" 	=> $this->M_database->get_filtered_data(),
			"data"				=> $data
		);
		echo json_encode($output);
	}


}
