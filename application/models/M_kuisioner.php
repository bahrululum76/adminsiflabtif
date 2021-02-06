<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    class M_kuisioner extends CI_Model{
		
		function __construct(){
			parent::__construct();			
		}

		function getAllData($table, $where = null, $order = null)
        {
            if ($where !== null) {
                foreach ($where as $key => $value) {
                    $this->db->where($key, $value);
                }
            }

            if ($order !== null) {
                foreach ($order as $key => $value) {
                    $this->db->order_by($key, $value);
                }
            }

            $query = $this->db->get($table);

            return $query;
        }

        function getRataPraktikum(){
            $sql = "SELECT kode_praktikum, AVG(nilai) AS nilai FROM `penilaian_mahasiswa` GROUP BY kode_praktikum";
            
            $query = $this->db->query($sql);

		    return $query->result_array();
        }

        function getDetailRataPraktikum($kd_praktikum){
            $sql = "SELECT penilaian_mahasiswa.kode_penilaian, aspek_penilaian.uraian, kategori_penilaian.kategori, AVG(penilaian_mahasiswa.nilai) AS nilai FROM `penilaian_mahasiswa` LEFT JOIN aspek_penilaian ON penilaian_mahasiswa.kode_penilaian = aspek_penilaian.id LEFT JOIN kategori_penilaian ON aspek_penilaian.id_kategori = kategori_penilaian.id WHERE kode_praktikum = '".$kd_praktikum."' GROUP BY kode_penilaian";

            $query = $this->db->query($sql);

		    return $query->result_array();
        }

        function getDetailRataAsisten($kd_asisten){
            $sql = "SELECT penilaian_asisten.kode_penilaian, aspek_penilaian.uraian, kategori_penilaian.kategori, AVG(penilaian_asisten.nilai) AS nilai FROM `penilaian_asisten` LEFT JOIN aspek_penilaian ON penilaian_asisten.kode_penilaian = aspek_penilaian.id LEFT JOIN kategori_penilaian ON aspek_penilaian.id_kategori = kategori_penilaian.id WHERE menilai = '".$kd_asisten."' GROUP BY kode_penilaian";

            $query = $this->db->query($sql);

		    return $query->result_array();
        }

        function getNilaiAsisten($kd_asisten){
            $sql = "SELECT kode_asisten, AVG(nilai) as nilai FROM `penilaian_asisten` WHERE menilai = '".$kd_asisten."'";

            $query = $this->db->query($sql);

		    return $query->result_array();
        }
        
        function getDetailRataAsistenMahasiswa($kd_asisten, $idp){
            $sql = "SELECT penilaian_mahasiswa.kode_penilaian, aspek_penilaian.uraian, kategori_penilaian.kategori, AVG(penilaian_mahasiswa.nilai) AS nilai FROM `penilaian_mahasiswa` LEFT JOIN aspek_penilaian ON penilaian_mahasiswa.kode_penilaian = aspek_penilaian.id LEFT JOIN kategori_penilaian ON aspek_penilaian.id_kategori = kategori_penilaian.id WHERE untuk = '".$kd_asisten."' AND kode_praktikum = '".$idp."' GROUP BY kode_penilaian";

            $query = $this->db->query($sql);

		    return $query->result_array();
        }        
        
        function getNilaiAsistenMahasiswa($kd_asisten, $idp){
            $sql = "SELECT untuk, AVG(nilai) as nilai FROM `penilaian_mahasiswa` WHERE untuk = '".$kd_asisten."' AND kode_praktikum = '".$idp."'";

            $query = $this->db->query($sql);

		    return $query->result_array();
        }

        function getUraian($tipe) {
            $sql = "SELECT aspek_penilaian.*, kategori_penilaian.kategori FROM `aspek_penilaian` LEFT JOIN kategori_penilaian ON kategori_penilaian.id = aspek_penilaian.id_kategori WHERE aspek_penilaian.tipe_penilaian = '".$tipe."' AND aspek_penilaian.status = '1'";
            
            $query = $this->db->query($sql);

		    return $query->result_array();
        }

        function countPenilai($username, $semester)
        {
            $this->db->where(array('menilai' => $username, 'semester' => $semester));
            $this->db->from('status_penilaian_asisten');
            return $this->db->count_all_results();
        }

        function getAllAsisten() {
            $sql = "SELECT t_asisten.asisten_id, t_asisten.foto, t_asisten.username, t_asisten.asisten_nama, MAX(penilaian_asisten.avg) AS `nilai`
            FROM t_asisten
            LEFT JOIN (SELECT kode_asisten, menilai, semester, AVG(nilai) AS `avg` FROM penilaian_asisten GROUP BY menilai) penilaian_asisten ON penilaian_asisten.menilai = t_asisten.username
            WHERE t_asisten.status = '1' AND t_asisten.jabatan_id != 12
            GROUP BY t_asisten.username
            ORDER BY MAX(penilaian_asisten.avg) DESC";

            $query = $this->db->query($sql);

            return $query->result_array();
        }
        
        function getAllAsistenPeriode($prid) {
            $sql = "SELECT t_asisten.asisten_id, t_asisten.foto, t_asisten.username, t_asisten.asisten_nama, MAX(penilaian_asisten.avg) AS `nilai`
            FROM t_asisten
            LEFT JOIN (SELECT kode_asisten, menilai, semester, AVG(nilai) AS `avg` FROM penilaian_asisten GROUP BY menilai) penilaian_asisten ON penilaian_asisten.menilai = t_asisten.username
            WHERE t_asisten.status = '1' AND t_asisten.jabatan_id != 12 AND semester = $prid
            GROUP BY t_asisten.username
            ORDER BY MAX(penilaian_asisten.avg) DESC";

            $query = $this->db->query($sql);

            return $query->result_array();
        }

        function getAllMenilaiAsisten($kd_asisten){
            $sql = "SELECT t_asisten.username, t_asisten.asisten_nama, status_penilaian_asisten.status FROM t_asisten LEFT JOIN status_penilaian_asisten ON status_penilaian_asisten.menilai = t_asisten.username AND status_penilaian_asisten.kode_asisten = '".$kd_asisten."' WHERE t_asisten.status = '1'";

            $query = $this->db->query($sql);

            return $query->result_array();
        }

        function insertData($table, $data)
        {
            $this->db->insert($table, $data);
        }

        function insertAllData($table, $data)
        {
            $this->db->insert_batch($table, $data);
        }
	}
