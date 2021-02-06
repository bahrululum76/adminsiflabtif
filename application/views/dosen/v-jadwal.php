<?php 
    $user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();    
?>
<style>
    .select2-selection__rendered {
        background-color: #F3E4B2;
    }    
</style>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 34px; position: relative; top: 4px">Dashboard</span>
                <span class="dd-pesan" style="display: inline-block; margin-right: 24px; position: relative; top: 8px">
                <span class="pulse-pesan hide" style="margin: 4px; animation: shadow-pulse-dots 1s infinite;display:block; width: 6px; height: 6px; background-color: #ff5252; border-radius: 10px; position:absolute; right: -10px; top: -10px">
                </span>
                <i class="material-icons-outlined grey-text text-darken-2">email</i>
                <div class="dd-body dd-body-pesan">
                    <div class="head">
                        <span>Pesan Masuk</span><span class="right counter" id="counter">0</span>
                    </div>
                    <div id="notif-pesan">
                        <div style="padding: 12px; text-align: center">Tidak ada pesan</div>
                    </div>                    
                </div>
            </span>
            <img src="<?= base_url() ?>assets/images/profil/<?=$user->foto?>" alt="profil" height="32" class="circle">
            <span class="dd-nama dd-trigger">
                <?= $user->dosen_nama ?>
                <i class="material-icons right" style="position:relative; bottom: 2px; right: 8px">arrow_drop_down</i>
                <div class="dd-body dd-body-profil">                    
                    <a href="<?=base_url()?>dosen/profil"><i class="material-icons left">face</i>Edit Profil</a>
                    <a href="<?=base_url()?>dosen/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>                    
                    <div class="center-align">
                    <button style="margin: 10px 0;" class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger" data-target="modalLogout" href="<?=base_url()?>auth/logout"><i class="material-icons left">exit_to_app</i><span>Logout</span></button>                    
                    </div>
                </div>
            </span>
            <div class="line"></div>
        </div> 
        <div class="col s12">
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
                echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                var_dump($this->db->query("select * from t_absensi_asisten where jadwal_kode = '".$value->jadwal_kode."'")->row());die;
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
</div>