<!-- Get deskripsi jadwal dan periode terakhir  -->
<?php 
    $id_praktikum = $this->input->get('id_praktikum');
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }                       
?>                 
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Kuisioner</span>
            <?php if ($this->session->userdata('jabatan_id') == 6) : 
                $jmlPesanMhsHtml = 'hide';
                $jmlTicketHtml = 'hide';
                if ($jmlPesanMhs > 0) {
                    $jmlPesanMhsHtml = '';
                }    
                if ($jmlTicket > 0) {
                    $jmlTicketHtml = '';
                }    
            ?>                
            <a href="<?=base_url()?>admin/ticketing" class="tooltipped" data-position="bottom" 
            data-tooltip="<div class='tooltip-html'><div class='tooltip-html-title'>E-ticketing</div><div class='tooltip-html-item tooltip-html-item-first <?=$jmlPesanMhsHtml?>'><?=$jmlPesanMhs?> pesan belum dibaca</div><div class='tooltip-html-item <?=$jmlTicketHtml?>'><?=$jmlTicket?> tiket pending</div></div>">            
                <span style="display: inline-block; margin-right: 24px; position: relative; top: 8px; cursor:pointer">
                    <span class="pulse-ticket hide" style="margin: 4px; animation: shadow-pulse 1s infinite;display:block; width: 6px; height: 6px; background-color: #0077ff; border-radius: 10px; position:absolute; right: -10px; top: -10px">
                    </span>
                    <i class="material-icons-outlined grey-text text-darken-2">confirmation_number</i>                    
                </span>            
            </a>
            <?php endif ?>
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
                    <?= $panggilan ?>
                    <i class="material-icons right" style="position:relative; bottom: 2px; right: 8px">arrow_drop_down</i>
                    <div class="dd-body dd-body-profil">
                        <a href="#!" class="modal-trigger" data-target="modalLihatAsisten"><i class="material-icons left">credit_card</i>Lihat Profil</a>
                        <a href="<?=base_url()?>asisten/profil"><i class="material-icons left">face</i>Edit Profil</a>
                    <a href="<?=base_url()?>asisten/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>
                        <?php if($this->session->userdata('jabatan_id') == 6) { echo '<a href="'.base_url().'admin/dashboard"><i class="material-icons left">admin_panel_settings</i>Halaman Admin</a>'; } ?>
                        <div class="center-align">
                        <button style="margin: 10px 0;" class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger" data-target="modalLogout" href="<?=base_url()?>auth/logout"><i class="material-icons left">exit_to_app</i><span>Logout</span></button>                    
                        </div>
                    </div>
                </span>
                <div class="line"></div>
        </div>      
        <div class="col s12">
            <div class="main-panel">
                <a class="nav__link" href="<?=base_url()?>asisten/kuisioner"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                <?php 
                    if ($jadwal != NULL) { ?>
                    <a class="nav__link waves-effect waves-light tombol tombol-sm tombol-info right" href="<?=base_url()?>asisten/kuisioner?menu=penilaian-mahasiswa&idp=<?= $jadwal[0]['jadwal_kode'] ?>" style="position:relative;bottom:12px"><span>Penilaian Mahasiswa</span></a>
                <?php } ?>
                <span class="panel-title-2">Penilaian Asisten</span>
                <div class="line"></div>                            
                <div class="col s12">               
                <?php if($jumlah_asisten - 1 > $jumlah_penilai) { ?>
                    <div class="panel amber lighten-5">
                        <div><b>Oops!</b>, masih ada asisten yang belum menilai anda. Data penilaian tidak dapat ditampilkan.</div>
                    </div>
                <?php } else { ?>           
                    <ul class="tabs">
                        <li class="tab col"><a href="#penilaian-asisten" class="active">Hasil Penilaian Asisten</a></li>
                        <li class="tab col"><a href="#komentar">Komentar Asisten</a></li>
                        <li class="tab col"><a href="#penilaian-diri">Penilaian Diri</a></li>
                        <li class="tab col"><a href="#peringkat">Asisten Terbaik</a></li>
                    </ul>
                    <!-- PENILAIAN ASISTEN -->
                    <div id="penilaian-asisten" style="overflow-x:auto">
                        <br>
                        <div class="panel amber lighten-5">
                            <div>Hai, <b><?= $panggilan ?></b>. Berikut adalah hasil penilaian anda dari Asisten lainnya.</div>
                        </div>
                        <br> 
                        <div class="panel amber lighten-5">
                            <div>Rerata Nilai Anda : <b><?=@$nilai[0]['nilai']?></b></div>
                        </div>                    
                        <div class="tabel-wrapper">
                            <br>                         
                            <table class="striped">
                                <thead>                                    
                                    <tr>
                                        <th></th>
                                        <th class="center">Uraian Kinerja</th>
                                        <th class="center" width="200px">Nilai Rata-Rata</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                for ($i=0; $i < count($kategori); $i++) { 
                                ?>
                                    <tr class="green lighten-5">
                                        <th>#</th>
                                        <th colspan="2"><strong><?=$kategori[$i]['kategori']?></strong></th>
                                    </tr>
                                <?php
                                    for ($j=0; $j < count($uraian); $j++) { 
                                        if ($uraian[$j]['kategori'] == $kategori[$i]['kategori']) {
                                ?>
                                    <tr>
                                        <td><?=$no++?></td>
                                        <td><?=$uraian[$j]['uraian']?></td>
                                        <td class="center"><?=$uraian[$j]['nilai']?></td>
                                    </tr>
                                <?php
                                        }
                                    }
                                }
                                ?>                                    
                                </tbody>
                            </table>
                            <br>
                            <br>
                        </div>
                    </div>
                    <!-- KOMENTAR -->
                    <div id="komentar">
                        <br/>
                        <?php
                            foreach ($komentar as $key => $value) {
                        ?>
                        <div class="panel blue lighten-5" style="margin-bottom:12px">
                            <?=$value['komentar']?>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- PENILAIAN DIRI -->
                    <div id="penilaian-diri">
                        <br>
                        <div style="margin-bottom:4px;margin-left:12px"><strong>Deskripsi kelebihan dan kekurangan diri :</strong></div>
                        <div class="panel teal lighten-5" style="white-space:pre-line;padding-top:0">
                            <?=@$diri[0]['deskripsi1']?>
                        </div>
                        <br>
                        <div style="margin-bottom:4px;margin-left:12px"><strong>Solusi mengatasi kekurangan diri :</strong></div>                                
                        <div class="panel teal lighten-5" style="white-space:pre-line;padding-top:0">                            
                            <?=@$diri[0]['deskripsi2']?>
                        </div>
                        <br>
                        <div style="margin-bottom:4px;margin-left:12px"><strong>Memanfaatkan potensi kelebihan diri :</strong></div>                                
                        <div class="panel teal lighten-5" style="white-space:pre-line;padding-top:0">                            
                            <?=@$diri[0]['deskripsi2']?>
                        </div>                       
                    </div>
                    <!-- PERINGKAT -->
                    <div id="peringkat">
                        <br>
                        <div class="panel blue lighten-5">
                            <div>Berikut ini adalah <b>5 Asisten</b> dengan nilai tertinggi.</div>
                        </div>
                        <div class="table-wrapper">                        
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Asisten</th>
                                        <th>Nama</th>
                                        <th class="center">Rerata Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php                          
                                    foreach($peringkat as $key => $value) {
                                        if ($value['username'] == $user->username) {
                                            $bg = 'green lighten-5';
                                        } else {
                                            $bg = '';
                                        }
                                        if($key < 5) { ?>
                                            <tr class="<?= $bg ?>">
                                                <td><?=$key+1?></td>
                                                <td><?=$value['username']?></td>
                                                <td><?=$value['asisten_nama']?></td>
                                                <td class="center"><?=$value['nilai']?></td>                                        
                                            </tr>
                                    <?php } else { 
                                            if ($value['username'] == $user->username) { ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>                                        
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>                                        
                                                </tr>
                                                <tr class="green lighten-5">
                                                    <td><?=$key+1?></td>
                                                    <td><?=$value['username']?></td>
                                                    <td><?=$value['asisten_nama']?></td>
                                                    <td class="center"><?=$value['nilai']?></td>                                 
                                                </tr>
                                <?php   }   }   } ?>                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>            
    </div>
</div>