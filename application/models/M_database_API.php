<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_database_API extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function getTicket($npm)
	{
		$this->db->order_by('ticket_id DESC');
		$this->db->where('npm', $npm);
		return $this->db->get('t_ticket')->result_array();
	}

	public function getMatkum($where)
	{
		$this->db->limit(1);
		$this->db->order_by('t_jadwal.jadwal_id DESC');
		$this->db->select('t_jadwal.jadwal_id, t_jadwal.jadwal_kode, matkum');
		$this->db->join('t_jadwal', 'registrasi_praktikum.jadwal_kode = t_jadwal.jadwal_kode');
		$this->db->join('t_mata_praktikum', 't_jadwal.matkum_id = t_mata_praktikum.matkum_id');
		$this->db->where($where);
		return $this->db->get('registrasi_praktikum')->row_array();        
	}

	public function getInfo($kelas)
	{
		$this->db->order_by('t_info.info_id DESC');
		$this->db->where('kelas_kode', $kelas);
		$this->db->join('t_info', 't_info_kelas.info_id = t_info.info_id');
		return $this->db->get('t_info_kelas')->result_array();
	}

	public function getInfoImage($infoId)
	{
		$this->db->order_by('info_image_id ASC');
		$this->db->where('info_id', $infoId);
		return $this->db->get('t_info_image')->result_array();
	}

	public function getJadwalAktif($npm)
	{
		$this->db->order_by('registrasi_id DESC');
		$this->db->select('t_jadwal.jadwal_kode, t_jadwal.periode_id, t_mata_praktikum.matkum_id, t_mata_praktikum.matkum, t_jadwal.asisten_1, t_jadwal.asisten_2, t_jadwal.jadwal_hari, t_jadwal.jadwal_jam, t_jadwal.jadwal_kode, t_ruangan.ruangan_nama');
		$this->db->join('t_jadwal', 'registrasi_praktikum.jadwal_kode = t_jadwal.jadwal_kode');
		$this->db->join('t_mata_praktikum', 't_jadwal.matkum_id = t_mata_praktikum.matkum_id');
		$this->db->join('t_ruangan', 't_jadwal.ruangan_id = t_ruangan.ruangan_id');
		$this->db->where(array('npm' => $npm, 't_jadwal.status' => 1));
		return $this->db->get('registrasi_praktikum')->result_array();		
	}

	public function getAllJadwal($npm)
	{
		$this->db->order_by('t_jadwal.jadwal_id DESC');
		$this->db->select('registrasi_praktikum.jadwal_kode');
		$this->db->join('t_jadwal', 'registrasi_praktikum.jadwal_kode = t_jadwal.jadwal_kode');		
		$this->db->where('npm', $npm);
		return $this->db->get('registrasi_praktikum')->result_array();		
	}
	
	public function getJadwalTicketing($periodeId, $matkumId)
	{
		$this->db->order_by('jadwal_kode ASC');
		$this->db->join('t_kelas', 't_jadwal.kelas_kode = t_kelas.kelas_kode');		
		$this->db->join('t_ruangan', 't_jadwal.ruangan_id = t_ruangan.ruangan_id');		
		$this->db->where(array('periode_id' => $periodeId, 'matkum_id' => $matkumId));
		return $this->db->get('t_jadwal')->result_array();		
	}

	public function getAbsensi($jadwalKode)
	{
		$this->db->order_by('pertemuan ASC');
		$this->db->group_by('pertemuan');
		$this->db->select('t_absensi.pertemuan, t_absensi.absen_tgl');		
		$this->db->where(array('t_absensi.jadwal_kode' => $jadwalKode));
		return $this->db->get('t_absensi')->result_array();	
	}

	public function hitungKehadiran($npm, $jadwalKode)
    {
        $this->db->where(array('npm' => $npm, 'jadwal_kode' => $jadwalKode));
        $this->db->from('t_absensi');
        return $this->db->count_all_results();
	}

	public function getNilaiTugas($npm, $jadwalKode)
	{
		$this->db->order_by('t_tugas.tugas_id ASC');
		$this->db->select('t_tugas.tugas_judul, t_tugas.tugas_kode, t_tugas_nilai.nilai');
		$this->db->join('t_tugas', 't_tugas_nilai.tugas_id = t_tugas.tugas_id');
		$this->db->join('t_jadwal', 't_tugas.jadwal_kode = t_jadwal.jadwal_kode');
		$this->db->where(array('npm' => $npm, 't_tugas_nilai.jadwal_kode' => $jadwalKode));
		return $this->db->get_where('t_tugas_nilai')->result_array();		
	}
	
	public function getHasilAkhir($npm, $jadwalKode)
	{
		$this->db->select('nilai_tugas, nilai_ujian, p_ujian, p_tugas, p_kehadiran');
		$this->db->join('t_jadwal', 'registrasi_praktikum.jadwal_kode = t_jadwal.jadwal_kode');
		$this->db->where(array('npm' => $npm, 'registrasi_praktikum.jadwal_kode' => $jadwalKode));
		return $this->db->get('registrasi_praktikum')->row_array();		
	}	

	public function getTugas($jadwalMhs)
	{
		$this->db->order_by('batas_waktu ASC');
		$this->db->where('t_tugas.tugas_status', 1);
		$this->db->where("($jadwalMhs)", NULL, FALSE);		
		$this->db->select('t_tugas.tugas_id, t_tugas.tugas_judul, t_tugas.tugas_kode, t_mata_praktikum.matkum, t_tugas.batas_waktu, t_tugas.tugas_deskripsi');
		$this->db->join('t_jadwal', 't_tugas.jadwal_kode = t_jadwal.jadwal_kode');
		$this->db->join('t_mata_praktikum', 't_jadwal.matkum_id = t_mata_praktikum.matkum_id');		
		return $this->db->get('t_tugas')->result_array();			
	}

	public function getTugasDetail($tugasId, $tugasKode)
	{
		$this->db->select('t_tugas.tugas_id, t_tugas.tugas_judul, t_tugas.tugas_kode, t_mata_praktikum.matkum, t_tugas.tugas_deskripsi');
		$this->db->join('t_jadwal', 't_tugas.jadwal_kode = t_jadwal.jadwal_kode');
		$this->db->join('t_mata_praktikum', 't_jadwal.matkum_id = t_mata_praktikum.matkum_id');
		$this->db->where(array('tugas_id' => $tugasId, 'tugas_kode' => $tugasKode));		
		return $this->db->get_where('t_tugas')->row_array();			
	}

	public function getTugasImage($tugasKode)
	{
		$this->db->order_by('tugas_image_id ASC');
		$this->db->where('tugas_kode', $tugasKode);
		return $this->db->get('t_tugas_image')->result_array();
	}

	public function getMahasiswa($where)
	{	
		$this->db->join('t_kelas', 't_mahasiswa.kelas_kode = t_kelas.kelas_kode');
		$this->db->where($where);
		return $this->db->get('t_mahasiswa')->row_array();		
	}
	
	public function getNamaAsisten($asistenId)
	{
		$this->db->select('asisten_nama, foto, username');
		$this->db->where('asisten_id', $asistenId);
		return $this->db->get('t_asisten')->row();
	}

	public function getRegistrasi($table, $where = null, $order = null)
	{		
		$this->db->order_by($order);
		$this->db->select('t_jadwal.jadwal_kode');
		$this->db->join('t_jadwal', 'registrasi_praktikum.jadwal_kode = t_jadwal.jadwal_kode');;
		return $this->db->get_where($table, $where)->result_array();		
		
	}

	public function checkDeviceToken($endpoint)
	{
		$this->db->where('endpoint', $endpoint);
		return $this->db->get('t_device_token')->row_array();
	}

	public function cekStatusBayar($npm)
	{
		$this->db->select('status_bayar_bak');
		$this->db->where('npm', $npm);
		return $this->db->get('t_mahasiswa')->row()->status_bayar_bak;
	}
	
	public function cekModeUjian()
	{
		$this->db->select('mode_ujian');
		return $this->db->get('t_lab')->row()->mode_ujian;
	}

	public function cekPesan($npm)
	{
		$this->db->where(array('pengirim' => 'admin', 'npm' => $npm, 'pesan_status' => 0));
        $this->db->from('t_pesan_mhs');
        return $this->db->count_all_results();		
	}
	
	public function jumlahMahasiswa($jadwalKode)
	{
		$this->db->where('jadwal_kode', $jadwalKode);
        $this->db->from('registrasi_praktikum');
        return $this->db->count_all_results();		
	}

	// GET PESAN

    public function getPesanMhs($npm)
    {
		$this->db->order_by('pesan_id ASC');
		$this->db->select('date_created, pesan_id, pesan, pesan_status, pengirim');
        $this->db->join('t_mahasiswa', 't_pesan_mhs.npm=t_mahasiswa.npm');
        $this->db->where('t_pesan_mhs.npm', $npm);
        return $this->db->get('t_pesan_mhs')->result_array();
    }

	// GET ALL DATA WHERE
    public function getWhere($table, $join = null, $onjoin = null, $where = null, $order = null)
    {   
        $this->db->order_by($order);

        if ($join == null) {
            $this->db->where($where);
            return $this->db->get($table)->result_array();;
        } else {
            $this->db->join($join, $onjoin);
            $this->db->where($where);
            return $this->db->get($table)->result_array();;
        }        
	}		

	// Cek absen
	public function cekdataAbsen($table, $att1, $att2, $isi1, $isi2){
        return $this->db->query("SELECT COUNT($att1) AS jumlah FROM (SELECT * FROM $table WHERE $att1='$isi1' AND $att2='$isi2' LIMIT 1) tabel")->row()->jumlah;
    }

    public function cekdataAbsenMhs($table, $att1, $att2, $att3, $isi1, $isi2, $isi3){
        return $this->db->query("SELECT COUNT($att1) AS jumlah FROM (SELECT * FROM $table WHERE $att1='$isi1' AND $att2='$isi2' AND $att3='$isi3' LIMIT 1) tabel")->row()->jumlah;
    }

	// BASIC FUNCTION
	public function insertData($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->affected_rows();
	}

	public function updateData($table, $where, $data)
    {
    	$this->db->where($where);
    	$this->db->update($table, $data);
    }

	public function deleteData($table, $where)
    {
    	$this->db->where($where);
    	$this->db->delete($table);
    }

    public function countWhere($table = null, $where = null)
    {
        $this->db->where($where);
        $this->db->from($table);
        return $this->db->count_all_results();
	}
	
}