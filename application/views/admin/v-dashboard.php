<style>
    .select2-selection__rendered {
        background-color: #F3E4B2;
    }    
</style>
<div class="col s12">
    <div class="main-wrapper">
        <div class="row">
            <div class="col s12 m6 l3">
                <div class="dashboard-card-counter green lighten-4">
                    <div class="counter-icon"><i class="left material-icons green-gradient">person</i></div>
                    <div class="counter-text">
                        <span><?= $jumlah_mhs ?></span>                        
                        <span>Mahasiswa</span>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="dashboard-card-counter orange lighten-4">
                    <div class="counter-icon"><i class="left material-icons orange-gradient">home</i></div>
                    <div class="counter-text">
                        <span><?= $jumlah_kelas ?></span>                        
                        <span>Kelas</span>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="dashboard-card-counter deep-orange lighten-4">
                    <div class="counter-icon"><i class="left material-icons red-gradient">class</i></div>
                    <div class="counter-text">
                        <span><?= $jumlah_jadwal ?></span>                        
                        <span>Jadwal</span>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l3">
                <div class="dashboard-card-counter blue lighten-4">
                    <div class="counter-icon"><i class="left material-icons blue-gradient">face</i></div>
                    <div class="counter-text">
                        <span><?= $jumlah_asisten ?></span>                        
                        <span>Asisten</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-panel">
            <div>Selamat datang di Sistem Informasi Laboratorium Teknik Informatika <span style="font-weight:500">(SIFLABTIF)</span></div>           
            <div><span style="font-weight:600;font-size:16px;color:#196bff">SIFLABTIF</span> memberikan pengelolaan yang mudah dan akurat dimulai dari pengelolaan asisten, mahasiswa, kelas, ruangan praktikum, penjadwalan, absensi, penilaian, penggajian hingga pembuatan laporan.</div>           
        </div>
        <div class="main-panel">
            <div><?= $hari_ini ?>, <?= $sekarang ?></div>
            <div class="line"></div>
            <div class="center-align">        
                <span class="panel-title-2" style="position:relative;bottom:14px;display:inline;">Jadwal Praktikum</span>
                <span class="select2-wrapper" style="width: 140px;margin-left:12px;text-align:left">
                    <select class="select2-no-search" style=" background-color; #FAE9DD;" id="jadwal-admin-dashboard">
                        <option class="hari-ini" value="<?= $hari_ini ?>"><?= strtoupper($hari_ini) ?></option>
                        <option value="Senin">SENIN</option>
                        <option value="Selasa">SELASA</option>
                        <option value="Rabu">RABU</option>
                        <option value="Kamis">KAMIS</option>
                        <option value="Jumat">JUMAT</option>
                        <option value="Sabtu">SABTU</option>
                    </select>
                </span>
            </div>
            <br>
            <div class="jadwal-dashboard-wrapper">
            <?php 
            if ($jadwal->result() == null) {
                echo '<div class="center-align">Tidak ada jadwal</div>';
            } else {
            foreach ($jadwal->result() as $key => $value) { 
                $asisten_1 = $this->db->where('asisten_id', $value->asisten_1)->get('t_asisten')->row();
                $asisten_2 = $this->db->where('asisten_id', $value->asisten_2)->get('t_asisten')->row();
                $pertemuan = $this->db->select('absen_tgl, pertemuan')->order_by('pertemuan', 'DESC')->limit(1)->where(array('jadwal_kode' => $value->jadwal_kode))->get('t_absensi_asisten')->row();                
                if ($value->ruangan_id == 1) {
                    $color = 'pink';
                    $ruangan = 'LD';
                } else if ($value->ruangan_id == 3) {
                    $color = 'amber';
                    $ruangan = 'LJ';                
                } else if ($value->ruangan_id == 4) {
                    $color = 'blue';
                    $ruangan = 'LM';
                }
                ?>
                <div class="jadwal-item-dashboard">
                    <div class="ruangan <?= $color ?>"><?= $ruangan ?></div>
                    <div class="matkum"><?=$value->matkum?></div>
                    <div style="margin-bottom:8px"><i class="tiny material-icons-outlined" style="position:relative;top:3px;margin-right:4px">schedule</i> <?= $value->jadwal_jam ?></div>                  
                    <div><?= strtoupper($value->jadwal_kode) ?></div>
                    <div>Kelas : <b><?=$value->kelas_nama?></b></div>
                    <div>Pengajar 1 &nbsp;: <b><?=$asisten_1->asisten_nama?></b></div>
                    <div>Pengajar 2 : <b><?=$asisten_2->asisten_nama?></b></div>
                    <div style="margin-top:8px">Pertemuan : <b><?=$pertemuan->pertemuan?></b></div>
                </div>
            <?php } } ?>	
            </div>
            <br>
            <br>
        </div>
    </div>
</div>