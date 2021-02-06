<?php 
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 2) {
        $panggilan = $nama[0]." ".$nama[1];
    } else {
        $panggilan = $user->asisten_nama;
    }      
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Pembayaran Praktikum</span>            
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
                <?= $user->asisten_nama ?>
                <i class="material-icons right" style="position:relative; bottom: 2px; right: 8px">arrow_drop_down</i>
                <div class="dd-body dd-body-profil">
                    <a href="<?=base_url()?>bak/profil"><i class="material-icons left">face</i>Edit Profil</a>
                    <a href="<?=base_url()?>bak/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>                    
                    <div class="center-align">
                    <button style="margin: 10px 0;" class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger" data-target="modalLogout" href="<?=base_url()?>auth/logout"><i class="material-icons left">exit_to_app</i><span>Logout</span></button>                    
                    </div>
                </div>
            </span>
            <div class="line"></div>
        </div> 
        <div class="col s12">
            <div class="main-panel">
                <div class="row">
                    <div class="col s12">
                        <div class="searchbar-table right">
                            <input type="text" placeholder="Cari Mahasiswa" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                            <i class="material-icons right">search</i>
                        </div>
                        <div class="mini-counter blue lighten-3">
                            <span class="badge-status blue-gradient left">Mahasiswa</span>
                            <span class="badge-status right"><?=$jumlah_mhs?></span>
                        </div>
                        <div class="mini-counter green lighten-4">
                            <span class="badge-status badge-ok left">Lunas</span>
                            <span class="badge-status lunas right"><?=$jumlah_lunas?></span>
                        </div>
                        <div class="mini-counter blue-grey lighten-3">
                            <span class="badge-status badge-not left">Belum</span>
                            <span class="badge-status belum right"><?=$jumlah_belum?></span>
                        </div>                                                                   
                    </div>                    
                    <div class="col s12 right-align">
                        <br>
                        <a target="_blank" href="<?=base_url()?>bak/pembayaran/cetak_pembayaran_praktikum">                    
                            <button class="tombol tombol-sm tombol-primary">
                               <i class="material-icons left">print</i>
                               <span>Cetak</span>
                            </button>
                        </a>                                                                                       
                    </div>	                                                                                 
                </div>
                <!-- Table data  -->
                <div class="table-wrapper">                                
                    <table class="highlight datatable">                                    
                        <thead>
                            <tr>
                                <th class="blue-text text-accent-3">#</th>
                                <th>NPM</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th class="center">Tanggal Bayar</th>
                                <th class="center">Status Pembayaran</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            foreach ($mahasiswa->result() as $key => $value) { 

                                    $status = '';

                                    if ($value->status_bayar_bak == 0){ 
                                        $status = '<span class="badge-status badge-not">Belum</span>';
                                    } else { 
                                        $status = '<span class="badge-status badge-ok">Lunas</span>';
                                    }
                                ?>  

                                <tr>
                                    <td><?= $key+1 ?></td>
                                    <td><?= $value->npm ?></td>
                                    <td><?= $value->nama ?></td>
                                    <td><?= $value->kelas_nama ?></td>
                                    <td class="center tgl_bayar_<?=$value->npm?>"><?=$value->tgl_bayar_bak?></td>                                               
                                    <td class="status-bayar td-data center" id="<?= $value->npm ?>"><?= $status ?></td>                                               
                                </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>