<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class M_database extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

    // GET DATA JADWAL PERIODE
    public function getJadwal($where, $order = null, $limit = null)
    {
        $this->db->order_by($order);
        $this->db->limit($limit);
        $this->db->where($where);
        $this->db->join('t_mata_praktikum', 't_jadwal.matkum_id=t_mata_praktikum.matkum_id');
        $this->db->join('t_kelas', 't_jadwal.kelas_kode=t_kelas.kelas_kode');
        $this->db->join('t_ruangan', 't_jadwal.ruangan_id=t_ruangan.ruangan_id');
        $this->db->join('t_dosen', 't_jadwal.dosen_id=t_dosen.dosen_id', 'left');
        return $this->db->get('t_jadwal');
    }

    public function getAllJadwalAsisten($orwhere, $order = null, $order2 = null)
    {
        $this->db->order_by($order);
        $this->db->order_by($order2);
        $this->db->where("($orwhere)", NULL, FALSE);        
        $this->db->join('t_mata_praktikum', 't_jadwal.matkum_id = t_mata_praktikum.matkum_id');     
        $this->db->join('t_ruangan', 't_jadwal.ruangan_id = t_ruangan.ruangan_id');     
        return $this->db->get('t_jadwal');          
    }

    public function getJadwalAsisten($orwhere, $order = null)
    {
        $this->db->order_by($order);
        $this->db->where('status = 1');
        $this->db->where("($orwhere)", NULL, FALSE);        
        $this->db->join('t_mata_praktikum', 't_jadwal.matkum_id = t_mata_praktikum.matkum_id');     
        $this->db->join('t_ruangan', 't_jadwal.ruangan_id = t_ruangan.ruangan_id');     
        return $this->db->get('t_jadwal');          
    }

    public function getJadwalDashboard($orwhere, $order = null, $where = null)
    {
        $this->db->order_by($order);
        $this->db->where('status = 1');
        $this->db->where($where);
        $this->db->where("($orwhere)", NULL, FALSE);        
        $this->db->join('t_mata_praktikum', 't_jadwal.matkum_id = t_mata_praktikum.matkum_id');     
        $this->db->join('t_kelas', 't_jadwal.kelas_kode = t_kelas.kelas_kode');     
        $this->db->join('t_ruangan', 't_jadwal.ruangan_id = t_ruangan.ruangan_id');     
        return $this->db->get('t_jadwal');          
    }

    // GET DATA REGISTRASI MAHASISWA
    public function getRegistrasiMahasiswa($where)
    {
        $this->db->order_by('t_mahasiswa.npm ASC');
        $this->db->where($where);        
        $this->db->join('t_mahasiswa', 'registrasi_praktikum.npm=t_mahasiswa.npm');
        $this->db->join('t_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode');
        return $this->db->get('registrasi_praktikum');
    }

    // GET NILAI PRAKTIKUM
    public function getNilaiPraktikum($where)
    {
        $this->db->select('jadwal_kode, matkum_id, kelas_kode, p_kehadiran, p_tugas, p_ujian, asisten_1, asisten_2');
        $this->db->order_by('jadwal_kode ASC');
        $this->db->where($where);
        return $this->db->get('t_jadwal');
    }

    // GET MATKUM DOSEN
    public function getMatkumDosen($where)
    {
        $this->db->select('t_jadwal.matkum_id, matkum');
        $this->db->order_by('matkum ASC');
        $this->db->group_by('t_jadwal.matkum_id');
        $this->db->where($where);
        $this->db->where('status', 1);
        $this->db->join('t_mata_praktikum', 't_jadwal.matkum_id=t_mata_praktikum.matkum_id');
        return $this->db->get('t_jadwal');
    }
    // GET KELAS DOSEN
    public function getKelasDosen($where)
    {
        $this->db->select('t_jadwal.kelas_kode, kelas_nama');
        $this->db->order_by('kelas_nama ASC');
        $this->db->group_by('t_jadwal.kelas_kode');
        $this->db->where($where);
        $this->db->where('status', 1);
        $this->db->join('t_kelas', 't_jadwal.kelas_kode=t_kelas.kelas_kode');
        return $this->db->get('t_jadwal');
    }

    // GET DETAIL TUGAS

    public function getDetailTugas($where)
    {
        $this->db->order_by('t_mahasiswa.nama ASC');
        $this->db->where($where);        
        $this->db->join('t_mahasiswa', 'registrasi_praktikum.npm=t_mahasiswa.npm');
        $this->db->join('t_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode');
        return $this->db->get('registrasi_praktikum');
    }

    // GET TOKEN AKTIF
    public function getToken()
    {
        $this->db->where('kelas_status = 1');
        $this->db->join('t_mahasiswa', 't_device_token.npm=t_mahasiswa.npm');
        $this->db->join('t_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode');
        return $this->db->get('t_device_token');
    }

    // GET PESAN

    public function getPesanMhs()
    {
        $this->db->order_by('pesan_id', 'DESC');
        $this->db->join('t_mahasiswa', 't_pesan_mhs.npm=t_mahasiswa.npm');
        $this->db->join('t_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode');
        $this->db->group_by('t_pesan_mhs.npm');
        return $this->db->get('t_pesan_mhs');
    }

    public function getTicketPindah()
    {        
        $this->db->order_by('ticket_status', 'ASC');
        $this->db->order_by('ticket_id', 'ASC');
        $this->db->join('t_mahasiswa', 't_ticket.npm=t_mahasiswa.npm');
        $this->db->join('t_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode');
        $this->db->join('t_jadwal', 't_ticket.shift_asal=t_jadwal.jadwal_kode');
        return $this->db->get('t_ticket');
    }

    public function getPesan($user1, $user2)
    {
        $this->db->order_by('t_pesan.pesan_id', 'ASC');
        $this->db->where('pesan_user', $user1);
        $this->db->or_where('pesan_user', $user2);
        $this->db->join('t_pesan_status', 't_pesan.pesan_id=t_pesan_status.pesan_id', 'left');
        return $this->db->get('t_pesan')->result();
    }
    
    public function getDatePesan($user1, $user2)
    {
        $this->db->order_by('t_pesan.pesan_id', 'ASC');
        $this->db->where('pesan_user', $user1);
        $this->db->or_where('pesan_user', $user2);
        $this->db->group_by('date_created');
        return $this->db->get('t_pesan')->result();
    }

    public function getPesanGrup($kanal)
    {
        $this->db->order_by('pesan_id', 'ASC');
        $this->db->where('pesan_kanal', $kanal);
        return $this->db->get('t_pesan')->result();
    }

    public function getLaporanInventori($laporan_id)
    {
        $this->db->order_by('t_inv_kategori.kategori_nama', 'ASC');
        $this->db->join('t_inv_barang', 't_inv_laporan_barang.barang_id=t_inv_barang.barang_id');
        $this->db->join('t_inv_merek', 't_inv_barang.merek_id=t_inv_merek.merek_id', 'left');
        $this->db->join('t_inv_kategori', 't_inv_barang.kategori_id=t_inv_kategori.kategori_id');
        $this->db->where('laporan_id', $laporan_id);
        return $this->db->get('t_inv_laporan_barang');
    }
    
    public function getLaporanKomponenPC($pc_id, $laporan_id)
    {
        $this->db->order_by('kategori_nama', 'ASC');
        $this->db->join('t_inv_laporan_pc', 't_pc_komponen.pck_id=t_inv_laporan_pc.pck_id');
        $this->db->join('t_inv_barang', 't_pc_komponen.barang_id=t_inv_barang.barang_id', 'left');
        $this->db->join('t_inv_merek', 't_inv_barang.merek_id=t_inv_merek.merek_id', 'left');
        $this->db->join('t_inv_kategori', 't_inv_barang.kategori_id=t_inv_kategori.kategori_id');
        $this->db->where('t_pc_komponen.pc_id', $pc_id);
        $this->db->where('t_inv_laporan_pc.laporan_id', $laporan_id);
        return $this->db->get('t_pc_komponen');
    }
    
    public function getBarang($ruangan_id)
    {
        $this->db->order_by('t_inv_kategori.kategori_nama', 'ASC');
        $this->db->join('t_inv_merek', 't_inv_barang.merek_id=t_inv_merek.merek_id', 'left');
        $this->db->join('t_inv_kategori', 't_inv_barang.kategori_id=t_inv_kategori.kategori_id');
        $this->db->where('t_inv_barang.ruangan_id', $ruangan_id);
        return $this->db->get('t_inv_barang');
    }
    
    public function getLaporanPC($laporan_id)
    {
        $this->db->order_by('t_pc.pc_id', 'ASC');        
        $this->db->join('t_pc_komponen', 't_pc.pc_id=t_pc_komponen.pc_id');
        $this->db->join('t_inv_laporan_pc', 't_pc_komponen.pck_id=t_inv_laporan_pc.pck_id');
        $this->db->where('t_inv_laporan_pc.laporan_id', $laporan_id);
        $this->db->group_by('t_pc_komponen.pc_id');        
        return $this->db->get('t_pc');
    }

    public function getPC($ruangan_id)
    {
        $this->db->order_by('pc_id', 'ASC');        
        $this->db->where('t_pc.ruangan_id', $ruangan_id);
        return $this->db->get('t_pc');
    }
    
    public function getKomponenPC($pc_id)
    {
        $this->db->order_by('kategori_nama', 'ASC');
        $this->db->join('t_inv_barang', 't_pc_komponen.barang_id=t_inv_barang.barang_id', 'left');
        $this->db->join('t_inv_merek', 't_inv_barang.merek_id=t_inv_merek.merek_id', 'left');
        $this->db->join('t_inv_kategori', 't_inv_barang.kategori_id=t_inv_kategori.kategori_id');
        $this->db->where('t_pc_komponen.pc_id', $pc_id);
        $this->db->where('t_pc_komponen.pck_status', 1);
        return $this->db->get('t_pc_komponen');
    }

    // GET ALL DATA
	public function getAllData($table, $join = null, $onjoin = null, $order1 = null, $order2 = null, $limit = null, $offset = null)
    {
        $this->db->order_by($order1);
        $this->db->order_by($order2);
    	$this->db->limit($limit, $offset);

    	if ($join == null) {
    		return $this->db->get($table);
    	} else {
            $this->db->join($join, $onjoin);
        	return $this->db->get($table);
    	}        
    }


    // GET ALL DATA WHERE
    public function getWhere($table, $join = null, $onjoin = null, $where = null, $order1 = null, $order2 = null, $limit = null, $offset = null)
    {   
        $this->db->order_by($order1);
        $this->db->order_by($order2);
    	$this->db->limit($limit, $offset);

        if ($join == null) {
            $this->db->where($where);
            return $this->db->get($table);
        } else {
            $this->db->join($join, $onjoin);
            $this->db->where($where);
            return $this->db->get($table);
        }        
    }

	public function cekdata($table, $att, $isi){
		return $this->db->query("SELECT COUNT($att) AS jumlah FROM (SELECT * FROM $table WHERE $att='$isi' LIMIT 1) tabel")->row()->jumlah;
    }
    
	public function cekPesan($pesan_id, $penerima){
		return $this->db->query("SELECT COUNT($pesan_id) AS jumlah FROM (SELECT * FROM t_pesan_status WHERE pesan_id = '$pesan_id' AND pesan_penerima = '$penerima' LIMIT 1) tabel")->row()->jumlah;
	}

    public function cekdataAbsen($table, $att1, $att2, $att3, $isi1, $isi2, $isi3){
        return $this->db->query("SELECT COUNT($att1) AS jumlah FROM (SELECT * FROM $table WHERE $att1='$isi1' AND $att2='$isi2' AND $att3='$isi3' LIMIT 1) tabel")->row()->jumlah;
    }

    // cek absensi mahasiswa
    function cekAbsen($table, $jadwal_kode, $npm, $pertemuan){
        return $this->db->query("SELECT COUNT(absensi_id) AS hadir FROM $table WHERE jadwal_kode='$jadwal_kode' AND npm='$npm' AND pertemuan='$pertemuan'")->row();
    }

    function cekAbsenAsisten($table, $jadwal_kode, $asisten_id, $pertemuan){
        return $this->db->query("SELECT COUNT(absensi_asisten_id) AS hadir FROM $table WHERE jadwal_kode='$jadwal_kode' AND asisten_id='$asisten_id' AND pertemuan='$pertemuan'")->row();
    }
  
    // hapus absen
    function hapusAbsen($table, $att1, $att2, $att3, $isi1, $isi2, $isi3){
        $this->db->where($att1, $isi1)->where($att2, $isi2)->where($att3, $isi3);
        $this->db->delete($table);  
    }

    // cek pertemuan
    function cekPertemuan($jadwal_kode){
        return $this->db->query("SELECT MAX(pertemuan) AS pertemuan FROM t_absensi WHERE jadwal_kode='$jadwal_kode'")->row();   
    }

    // cek tanggal absen
    function cekTanggal($where)
    {
        return $this->db->select('absen_tgl, pertemuan')->order_by('pertemuan', 'ASC')->where($where)->get('t_absensi_asisten')->result_array();
    }

    // get total kehadiran mahasiswa

    function kehadiran($jadwal_kode, $npm){
        return $this->db->query("SELECT COUNT(npm) AS kehadiran  FROM t_absensi WHERE jadwal_kode='$jadwal_kode' AND npm='$npm'")->row();   
    }
    
    // get total kehadiran asisten

    function kehadiranAsisten($jadwal_kode, $asisten_id){
        return $this->db->query("SELECT COUNT(asisten_id) AS kehadiran  FROM t_absensi_asisten WHERE jadwal_kode='$jadwal_kode' AND asisten_id='$asisten_id'")->row();   
    }

    function nilai_tugas($jadwal_kode, $npm){
        $this->db->where(array('jadwal_kode' => $jadwal_kode, 'npm' => $npm));
        return $this->db->get('t_tugas_nilai');
    }

    // INSERT MULTIPLE DATA

    function insertBatch($table, $data){
        return $this->db->insert_batch($table, $data);
    }
    
    function insert_batch($table, $data){
        return $this->db->insert_batch($table, $data);
    }

    function maxwhere($table, $att1, $isi, $att2){
        return $this->db->where($att1, $isi)->select_max($att2)->get($table)->row();
    }


    // BASIC FUNCTION
    public function insertData($table, $data)
    {
    	$this->db->insert($table, $data);
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

    public function count($table = null)
    {
        return $this->db->count_all($table);
    }

    public function countWhere($table = null, $where = null)
    {
        $this->db->where($where);
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    function jumlah_data($table){
        return $this->db->get($table)->num_rows();
    }

    // DATATABLE SERVER SIDE

    // var $select_column = array('npm', 'nama', 'kelas_nama', 'kelas_status');
    // var $order_column = array('npm' => 'DESC');
    
    function get_all_mahasiswa() {
        $this->db->select('mhs_id, npm, nama, t_kelas.kelas_kode, kelas_nama, status_mhs, status_bayar, created_at');
        $this->db->from('t_mahasiswa');
        $this->db->join('t_kelas', 't_kelas.kelas_kode=t_mahasiswa.kelas_kode');
        if (isset($_POST["search"]["value"])) {
            $this->db->like("npm", $_POST["search"]["value"]);
            $this->db->or_like("nama", $_POST["search"]["value"]);
            $this->db->or_like("kelas_nama", $_POST["search"]["value"]);
        }
        if (isset($_POST['order'])) {
            # code...
        } else {
            $this->db->order_by("mhs_id", "DESC");
        }
    }

    function render_datatable() {
        $this->get_all_mahasiswa();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST["start"]);
        }
        $query = $this->db->get();
        return $query->result();
    }
 
    function get_filtered_data(){
        $this->get_all_mahasiswa();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_all_data()
    {
        $this->db->select("*");
        $this->db->from("t_mahasiswa");
        return $this->db->count_all_results();
    }

}



?>