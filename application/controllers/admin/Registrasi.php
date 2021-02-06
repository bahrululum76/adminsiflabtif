<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}


	public function index()
	{
		$jadwal_kode = $this->input->get('idp');
		$data['jadwalkosong'] = $this->input->get('idp');

        $data['halaman_title'] = "Registrasi Praktikum";
		$data['tabTitle'] = 'Registrasi Praktikum';
		$data['mahasiswa'] = $this->M_database->getWhere('t_mahasiswa', 't_kelas', 't_mahasiswa.kelas_kode = t_kelas.kelas_kode', array('status_mhs' => 1), 'npm DESC');
		$data['jadwal'] = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC');
		$data['registrasi'] = $this->M_database->getRegistrasiMahasiswa(array('jadwal_kode' => $jadwal_kode));
        $data['visibility'] = '';
        if ($jadwal_kode == null) {
        	$data['visibility'] = 'hide';			
        } else {
        	$data['visibility'] = '';      	
        }

        $this->template->load('admin/template', 'admin/v-registrasi', $data);

	}

	function simpanRegistrasi()
	{
		$jadwal_kode = $this->input->post('jadwal_kode');
		$npm = $this->input->post('npm');	
		$nama = $this->input->post('nama');	
		$status = $this->input->post('status');	
		$id_praktikum = $this->input->post('id_praktikum');	

		$data = array(				
			'npm'=> $npm,			
			'jadwal_kode'=> $jadwal_kode,
			'status_modul'=> 0,
			'tgl_modul'=> "-",
			'nilai_tugas'=> 0,
			'nilai_ujian'=> 0
		);

		if ($status == "add") {
			$this->M_database->insertData('registrasi_praktikum', $data);
			$message = "add";
			$checkbox = '<label>
							<input type="checkbox" class="filled-in" data-nama="'.$nama.'" data-npm="'.$npm.'" value="'.$npm.'?>" name="npm" checked>
							<span class="checkbox" style="left: 10px"></span>
						</label>';
		} else if ($status == 'confirm') {
			$this->M_database->deleteData('registrasi_praktikum', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm));
			$this->M_database->deleteData('t_absensi', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm));
			$this->M_database->deleteData('t_tugas_nilai', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm));
			$message = "confirm";
			$checkbox = '<label>
							<input type="checkbox" class="filled-in" data-nama="'.$nama.'" data-npm="'.$npm.'" value="'.$npm.'?>" name="npm">
							<span class="checkbox" style="left: 10px"></span>
						</label>';
		} else {
			$cek_absensi = $this->db->where(array('jadwal_kode' => $jadwal_kode, 'npm' => $npm))->get('t_absensi')->row();
			if ($cek_absensi) {
				$message = "confirm";
				$checkbox = '<label>
								<input type="checkbox" class="filled-in" data-nama="'.$nama.'" data-npm="'.$npm.'" value="'.$npm.'?>" name="npm">
								<span class="checkbox" style="left: 10px"></span>
							</label>';
			} else {
				$this->M_database->deleteData('registrasi_praktikum', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm));
				$this->M_database->deleteData('t_absensi', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm));
				$this->M_database->deleteData('t_tugas_nilai', array('jadwal_kode' => $jadwal_kode, 'npm' => $npm));
				$message = "delete";
				$checkbox = '<label>
								<input type="checkbox" class="filled-in" data-nama="'.$nama.'" data-npm="'.$npm.'" value="'.$npm.'?>" name="npm">
								<span class="checkbox" style="left: 10px"></span>
							</label>';
			}
		}

		echo "$message|$checkbox";
	}

	function view_pindah()
	{
		$jadwal_kode = $this->input->get('idp');

		$data['mahasiswa'] = $this->M_database->getDetailTugas(array('jadwal_kode' => $jadwal_kode));
		$data['jadwal'] = $this->M_database->getWhere('t_jadwal', null, null, array('status' => 1), 'jadwal_kode ASC');
		$data['halaman_title'] = "Pindah Praktikum";

		$praktikum = $this->M_database->maxwhere('t_absensi','jadwal_kode', $jadwal_kode, 'pertemuan')->pertemuan;
		if ($praktikum == '') {
			$data['praktikum1'] = 'Belum ada pertemuan';
			$data['pertemuan1'] = 0;
		} else {
			$data['praktikum1'] = $praktikum;
			$data['pertemuan1'] = $praktikum;
		}
      
        $this->template->load('admin/template', 'admin/v-pindah-praktikum', $data);
	}

	function pindahPraktikum()
	{
		$terpilih = $this->input->post('terpilih');
		$id_praktikum = $this->input->post('id_praktikum');
		$praktikum_awal = $this->input->post('praktikum_awal');
		$praktikum_tujuan = $this->input->post('praktikum_tujuan');

		$praktikum1 = $this->M_database->maxwhere('t_absensi','jadwal_kode', $praktikum_awal, 'pertemuan')->pertemuan;
		$praktikum2 = $this->M_database->maxwhere('t_absensi','jadwal_kode', $praktikum_tujuan, 'pertemuan')->pertemuan;
		
		$jadwal = $this->db->where('jadwal_kode', $praktikum_tujuan)->get('t_jadwal')->row();

		if ($terpilih != null) {
			if($praktikum1 == $praktikum2){

				foreach($terpilih as $key => $npm){

					$mhs = explode('-', $npm);

					$data = array(
						'jadwal_kode' => $praktikum_tujuan
					);
				
					$data2 = array(
						'jadwal_kode' => $praktikum_tujuan,
						'nilai_tugas' => 0
					);
						
					$data_nilai = $this->db->join('t_tugas', 't_tugas_nilai.tugas_id=t_tugas.tugas_id')->where(array('t_tugas_nilai.jadwal_kode' => $praktikum_awal, 'npm' => $mhs[0]))->get('t_tugas_nilai')->result();

					$nilai = "";

					if ($data_nilai) {						
						foreach ($data_nilai as $key => $value) {
							$nilai .= '<li>'. $value->tugas_kode. ' = '. $value->nilai. '</li>';
						}
						$nilai = "Berikut nilai tugas dari praktikum sebelumnya $praktikum_awal :<br><ol>$nilai</ol>";
					}

					$catatan = "Mahasiswa <b>". $mhs[0]."</b> | <b>". $mhs[1]. "</b> baru saja dipindahkan ke jadwal kamu <b>($praktikum_tujuan)</b>.<br>$nilai";				
					$catatanAsisten = array(
						array(
							'catatan_judul' => "Mahasiswa baru di $praktikum_tujuan",
							'catatan_isi'	=> $catatan,
							'catatan_user' => 'asisten_'.$jadwal->asisten_1,
							'created_at' => date('Y-m-d H:i:s'),
							'catatan_status' => 1,
							'catatan_role' => 'admin',
							'catatan_warna' => '#CBF0F8'),
						array(
							'catatan_judul' => "Mahasiswa baru di $praktikum_tujuan",
							'catatan_isi'	=> $catatan,
							'catatan_user' => 'asisten_'.$jadwal->asisten_2,
							'created_at' => date('Y-m-d H:i:s'),
							'catatan_status' => 1,
							'catatan_role' => 'admin',
							'catatan_warna' => '#CBF0F8'
						)
					);
										
					$this->M_database->updateData('registrasi_praktikum', array('jadwal_kode' => $praktikum_awal, 'npm' => $mhs[0]) , $data2);
					$this->M_database->updateData('t_absensi', array('jadwal_kode' => $praktikum_awal, 'npm' => $mhs[0]) , $data);
					$this->db->insert_batch('t_catatan', $catatanAsisten);
					$this->M_database->deleteData('t_tugas_nilai', array('jadwal_kode' => $praktikum_awal, 'npm' => $mhs[0]));
				}
				
				$this->session->set_flashdata('edit', 'Data pindah praktikum berhasil disimpan');

				redirect("admin/pindah-praktikum?idp=$id_praktikum", 'refresh');
			}

			else{
				$this->session->set_flashdata('warning', 'Pindah praktikum gagal, jumlah pertemuan tidak sama');
				redirect("admin/pindah-praktikum?idp=$id_praktikum", 'refresh');
			}
		} else {
			$this->session->set_flashdata('edit', 'Tidak ada mahasiswa yang dipilih');
			redirect("admin/pindah-praktikum?idp=$id_praktikum", 'refresh');
		}				
	}

	function hapusRegistrasi()
	{
		$npm = $this->input->post('npm');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$id_praktikum = $this->input->post('id_praktikum');

		$this->M_database->deleteData('registrasi_praktikum', array('npm' => $npm, 'jadwal_kode' => $jadwal_kode));
		$this->M_database->deleteData('t_absensi', array('npm' => $npm, 'jadwal_kode' => $jadwal_kode));

		redirect("admin/registrasi/view_pindah?jadwal_kode=$jadwal_kode&id_praktikum=$id_praktikum", 'refresh');

	}

	function cekPertemuan()
	{
		$jadwal_kode = $this->input->post('jadwal_kode');
		$pertemuan1 = $this->input->post('pertemuan1');
		$pertemuan2 = $this->M_database->maxwhere('t_absensi','jadwal_kode', $jadwal_kode, 'pertemuan')->pertemuan;		
		
		if ($pertemuan2 == '') {
			$pertemuan = 'Belum ada pertemuan';
			$msg = 'gagal';
			$pertemuan2 = 0;
		} 
		
		if ($pertemuan1 == $pertemuan2) {
			$msg = 'sukses';
			if ($pertemuan2 == 0) {				
				$pertemuan = 'Belum ada pertemuan';
			} else {
				$pertemuan = $pertemuan2;
			}
		} else {
			$msg = 'gagal';
			if ($pertemuan2 == 0) {				
				$pertemuan = 'Belum ada pertemuan';
			} else {
				$pertemuan = $pertemuan2;
			}
		}
		
		echo "$msg|$pertemuan";	
	}

	function fetch_mahasiswa() {		
		$fetch_data = $this->M_database->render_datatable();
		$data = array();
		foreach ($fetch_data as $row => $value) {
			$cek = $this->db->query("SELECT COUNT(registrasi_id) AS exist FROM registrasi_praktikum WHERE jadwal_kode='pwl2b' AND npm='$value->npm'")->row();

			$checked = '';
			$marker = '';
			$val = '1';

			if ($cek->exist == 1) {
				$checked = 'checked';
				$marker = 'hide';
				$val = '1';
			}
			else
			{
				$checked = '';
				$marker = '';
				$val = '2';
			}

			$status = '';

			if ($value->status_bayar == 0){ 
				$status = '<span class="badge-status badge-not">Belum Lunas</span>';
				$hide_checkbox = "disabled";
			} else { 
				$status = '<span class="badge-status badge-ok">Lunas</span>';
				$hide_checkbox = "";
			}
			$sub_array = array();
			$sub_array[] = $row+1;
			$sub_array[] = $value->npm;
			$sub_array[] = $value->nama;
			$sub_array[] = $value->kelas_nama;
			$sub_array[] = '<div class="center-align">'.$status.'</div>';
			$sub_array[] = '<div class="center-align"><label>
								<input type="checkbox" class="filled-in checkboxs2'.$value->npm.'" data-npm="'.$value->npm.'"'.$hide_checkbox.' value="'.$value->npm.'" name="npm[]"'.$checked.'>
								<span class="checkbox2" style="left: 10px"></span>
							</label></div>';
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

	public function pushMessage()
	{
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

		$data3['message'] = 'hello world';
		$pusher->trigger('my-channel', 'my-event', $data3);
	}
}
