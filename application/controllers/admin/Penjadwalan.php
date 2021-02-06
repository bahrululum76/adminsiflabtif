<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjadwalan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_database');
		cek_session();
	}

	public function index()
	{
		$periode_id = $this->input->get('periode_id');
		$data['periode'] = $this->uri->segment(4);	
		$data['matkum'] = $this->M_database->getWhere('t_mata_praktikum', null, null, array('matkum_status' => 1), 'matkum ASC');
		$data['kelas'] = $this->M_database->getWhere('t_kelas', null, null, array('kelas_status' => 1), 'kelas_id DESC');
		$data['asisten'] = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1, 'jabatan_id !=' => 12), 'asisten_nama ASC');
		$data['dosen'] = $this->M_database->getWhere('t_dosen', null, null, array('dosen_status' => 1), 'dosen_nama ASC');
		$data['jam'] = $this->M_database->getAllData('t_jam');
		$data['ruangan'] = $this->M_database->getAllData('t_ruangan');
		$data['halaman_title'] = "Penjadwalan";
		$data['tabTitle'] = 'Penjadwalan';
		$data['jadwal'] = $this->M_database->getJadwal(array('periode_id' => $periode_id), 'jadwal_id DESC');
		$this->template->load('admin/template', 'admin/v-penjadwalan', $data);
	}

	function view_tambah()
	{
		$data['halaman_title'] = "Penjadwalan";
		$data['periode'] = $this->uri->segment(4);
		$data['matkum'] = $this->M_database->getAllData('t_mata_praktikum', null, null, 'matkum ASC');
		$data['kelas'] = $this->M_database->getAllData('t_kelas');
		$data['asisten'] = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1), 'asisten_nama ASC');
		$data['jam'] = $this->M_database->getAllData('t_jam');
		$data['ruangan'] = $this->M_database->getAllData('t_ruangan');
		$this->template->load('admin/template', 'admin/v-penjadwalan-tambah', $data);
	}

	function view_edit()
	{
		$jadwal_id = $this->uri->segment(4);
		$jadwal = $this->M_database->getJadwal(array('jadwal_id' => $jadwal_id))->result();
		$data['matkum'] = $this->M_database->getAllData('t_mata_praktikum', null, null, 'matkum ASC');
		$data['kelas'] = $this->M_database->getAllData('t_kelas');
		$data['asisten'] = $this->M_database->getWhere('t_asisten', null, null, array('status' => 1), 'asisten_nama ASC');
		$data['jam'] = $this->M_database->getAllData('t_jam');
		$data['ruangan'] = $this->M_database->getAllData('t_ruangan');

		foreach ($jadwal as $key => $value) {			

			$data['jadwal_id'] = $value->jadwal_id;
			$data['jadwal_kode'] = $value->jadwal_kode;
			$data['periode_id'] = $value->periode_id;
			$data['matkum_id'] = $value->matkum_id;
			$data['matkum_nama'] = $value->matkum;
			$data['kelas_id'] = $value->kelas_id;
			$data['kelas_nama'] = $value->kelas_nama;
			$data['asisten_1'] = $value->asisten_1;
			$data['asisten_2'] = $value->asisten_2;
			$data['jadwal_hari'] = $value->jadwal_hari;
			$data['jadwal_jam'] = $value->jadwal_jam;
			$data['ruangan_id'] = $value->ruangan_id;
			$data['ruangan_nama'] = $value->ruangan_nama;
		}

		$this->template->load('admin/template', 'admin/v-penjadwalan-edit', $data);
	}

	function tambahJadwal()
	{
		$periode_id = $this->input->post('periode_id');
		$jadwal_kode = str_replace(' ', '', $this->input->post('jadwal_kode'));
		$matkum_id 	= $this->input->post('matkum_id');
		$periode_id = $this->input->post('periode_id');
		$kelas_kode = $this->input->post('kelas_kode');
		$dosen_id = $this->input->post('dosen_id');
		$asisten_1 = $this->input->post('asisten_1');
		$asisten_2 = $this->input->post('asisten_2');
		$jadwal_hari = $this->input->post('jadwal_hari');
		$jadwal_jam = $this->input->post('jam_mulai').' - '.$this->input->post('jam_selesai');
		$ruangan_id = $this->input->post('ruangan_id');

		$data = array(
					'jadwal_kode' => $jadwal_kode,
					'matkum_id' => $matkum_id,
					'kelas_kode' => $kelas_kode,
					'dosen_id' => $dosen_id,
					'asisten_1' => $asisten_1,
					'asisten_2' => $asisten_2,
					'periode_id' => $periode_id,
					'jadwal_hari' => $jadwal_hari,
					'jadwal_jam' => $jadwal_jam,
					'ruangan_id' => $ruangan_id,
					'periode_id' => $periode_id,
					'p_kehadiran' => 30,
					'p_tugas' => 30,
					'p_ujian' => 40,
					'status' => 1					
				);
								
		$cek_jadwal = $this->db->where('jadwal_kode', $jadwal_kode)->get('t_jadwal')->row();				
		if ($cek_jadwal) {
			echo "gagal|Jadwal praktikum <b>$jadwal_kode</b> sudah ada, data gagal disimpan";
		} else {
			$this->M_database->insertData('t_jadwal', $data);
			$jadwal_akhir = $this->M_database->getJadwal(array('periode_id' => $periode_id), 'jadwal_id DESC', 1)->row();
			$asisten_1 = $this->db->where('asisten_id', $jadwal_akhir->asisten_1)->get('t_asisten')->row();
			$asisten_2 = $this->db->where('asisten_id', $jadwal_akhir->asisten_2)->get('t_asisten')->row();
			$jadwal = $this->M_database->cekdata('registrasi_praktikum', 'jadwal_kode', $jadwal_akhir->jadwal_kode);
			$title = 'hapus';
			$modal = 'modalHapus';                     

			if ($jadwal != 0) {
					$title = 'tidak bisa hapus';
					$modal = 'modalAlert';
			}
			$status = '';
			if ($jadwal_akhir->status == 0){ 
				$status = '<span class="badge-status badge-not">Tidak aktif</span>';
			} else { 
				$status = '<span class="badge-status badge-ok">Aktif</span>';
			}
			$table = '<tr class="green lighten-5">
						<td></td>                                             
						<td>'.$jadwal_akhir->jadwal_kode.'</td>
						<td>'.$jadwal_akhir->matkum.'</td>
						<td>'.$jadwal_akhir->kelas_nama.'</td>
						<td>
							<ul class="browser-default" style="padding-left:18px">
								<li>'.$asisten_1->asisten_nama.'</li>
								<li>'.$asisten_2->asisten_nama.'</li>
							</ul>
						</td>					
						<td class="center">'.$jadwal_akhir->jadwal_hari.'<br>'.$jadwal_akhir->jadwal_jam.'</td>						
						<td>'.$jadwal_akhir->ruangan_nama.'</td>
						<td>'.$jadwal_akhir->dosen_nama.'</td>
						<td class="status-jadwal td-data center" id="'.$jadwal_akhir->jadwal_id.'">'.$status.'</td>                                                
						<td class="center">
							<button class="waves-effect mybtn transparent modal-trigger btn-edit-jadwal" data-target="modalEdit" 
							data-jadwal_id="'.$jadwal_akhir->jadwal_id.'" 
							data-jadwal_kode="'.$jadwal_akhir->jadwal_kode.'"
							data-matkum_id="'.$jadwal_akhir->matkum_id.'"
							data-dosen_id="'.$jadwal_akhir->dosen_id.'"
							data-kelas_kode="'.$jadwal_akhir->kelas_kode.'"
							data-asisten_1="'.$jadwal_akhir->asisten_1.'"
							data-asisten_2="'.$jadwal_akhir->asisten_2.'"
							data-jadwal_hari="'.$jadwal_akhir->jadwal_hari.'"
							data-jadwal_jam="'.$jadwal_akhir->jadwal_jam.'"
							data-ruangan_id="'.$jadwal_akhir->ruangan_id.'"
							><i class="material-icons amber-text">edit</i></button>
							<button title="'.$title.'" class="waves-effect mybtn transparent modal-trigger btn-hapus-jadwal" data-target="'.$modal.'"
								data-jadwal_id="'.$jadwal_akhir->jadwal_id.'"
								data-jadwal_kode="'.$jadwal_akhir->jadwal_kode.'"
								data-jadwal_jam="'.$jadwal_akhir->jadwal_jam.'"
								data-periode_id="'.$jadwal_akhir->periode_id.'"><i class="material-icons red-text text-lighten-1 ">delete</i>
							</button>
						</td>
					</tr>';
			echo "sukses|Jadwal praktikum <b>$jadwal_kode</b> berhasil disimpan|$table";
		}
	}

	function editJadwal()
	{
		$kode_asal = $this->input->post('kode_asal');
		$periode_id = $this->input->post('periode_id');
		$jadwal_id = $this->input->post('jadwal_id');
		$jadwal_kode = str_replace(' ', '', $this->input->post('jadwal_kode'));
		$matkum_id 	= $this->input->post('matkum_id');
		$dosen_id 	= $this->input->post('dosen_id');
		$kelas_kode = $this->input->post('kelas_kode');
		$asisten_1 = $this->input->post('asisten_1');
		$asisten_2 = $this->input->post('asisten_2');
		$jadwal_hari = $this->input->post('jadwal_hari');
		$jadwal_jam = $this->input->post('jam_mulai').' - '.$this->input->post('jam_selesai');
		$ruangan_id = $this->input->post('ruangan_id');

		$cek_jadwal = $this->db->where('jadwal_kode', $jadwal_kode)->get('t_jadwal')->row();
		$cek_registrasi = $this->M_database->countWhere('registrasi_praktikum', array('jadwal_kode' => $kode_asal));

		$data = array(
					'jadwal_kode' => $jadwal_kode,
					'matkum_id' => $matkum_id,
					'kelas_kode' => $kelas_kode,
					'asisten_1' => $asisten_1,
					'asisten_2' => $asisten_2,
					'jadwal_hari' => $jadwal_hari,
					'jadwal_jam' => $jadwal_jam,
					'dosen_id' => $dosen_id,
					'ruangan_id' => $ruangan_id								
				);
		if ($kode_asal == $jadwal_kode) {	
			$this->M_database->updateData('t_jadwal', array('jadwal_id' => $jadwal_id), $data);
			$this->session->set_flashdata('edit', 'Jadwal <b>'.$jadwal_kode.'</b> berhasil diedit');
			echo "sukses|admin/penjadwalan?periode_id=$periode_id";
		} else if ($cek_registrasi > 0) { 
			echo "gagal|Jadwal praktikum <b>$kode_asal</b> sudah digunakan, ID praktikum tidak dapat diganti";
		} else if($cek_jadwal) {
			echo "gagal|Jadwal praktikum <b>$jadwal_kode</b> sudah ada, data gagal disimpan";
		} else {
			$this->M_database->updateData('t_jadwal', array('jadwal_id' => $jadwal_id), $data);
			$this->session->set_flashdata('edit', 'Jadwal <b>'.$jadwal_kode.'</b> berhasil diedit');
			echo "sukses|admin/penjadwalan?periode_id=$periode_id";			
		}
	}

	function hapusJadwal()
	{
		$jadwal_id = $this->input->post('jadwal_id');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$periode_id = $this->input->post('periode_id');

		$this->M_database->deleteData('t_jadwal', array('jadwal_id' => $jadwal_id));
		$this->session->set_flashdata('hapus', 'Jadwal <b>'.$jadwal_kode.'</b> berhasil dihapus');

		redirect("admin/penjadwalan?periode_id=$periode_id");
	}

	function statusJadwal()
	{
		$jadwal_id = $this->input->post('jadwal_id');
		$jadwal_kode = $this->input->post('jadwal_kode');
		$status = $this->input->post('status');

		if ($status == 'Aktif') {
			$data = array('status' => 0);
			$badge = '<span class="badge-status badge-not">Tidak aktif</span>';
		} else  {
			$data = array('status' => 1);
			$badge = '<span class="badge-status badge-ok">Aktif</span>';
		}

		$this->M_database->updateData('t_jadwal', array('jadwal_id' => $jadwal_id), $data);
		$this->M_database->updateData('t_absensi_asisten', array('jadwal_kode' => $jadwal_kode), $data);

		echo $badge;
	}

}
