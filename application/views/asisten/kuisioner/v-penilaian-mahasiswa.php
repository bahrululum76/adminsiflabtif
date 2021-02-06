<!-- Get deskripsi jadwal dan periode terakhir  -->
<?php 
    $idp = $this->input->get('idp');
    $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $idp))->row();
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
                <a class="nav__link" href="<?=base_url()?>asisten/kuisioner?menu=nilai-saya"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>                
                <span class="panel-title-2">Penilaian Mahasiswa</span>
                <div class="line"></div>                            
                <div class="col s12">
                    <div class="head-panel">
                        <div class="row" style="margin: 0">
                            <div class="head-p" style="align-items:baseline;position:relative">
                                <div style="align-items:center">
                                    <span>ID Praktikum</span>
                                    <span class="select2-wrapper">
                                        <select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
                                            <?php 
                                                    foreach ($jadwal->result() as $key => $value):
                                                        $selected = '' ; 
                                                        if ($idp == $value->jadwal_kode) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        } 
                                                        ?>
                                            <option <?= $selected ?>
                                                value="<?= base_url() ?>asisten/kuisioner?menu=penilaian-mahasiswa&idp=<?= $value->jadwal_kode ?>">
                                                <?= strtoupper($value->jadwal_kode) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </span>
                                </div>
                                <div>
                                    <span>Praktikum</span>
                                    <span>:</span>
                                    <span class="isi"><?= ucwords($jadwal_ini->matkum) ?></span>
                                </div>
                            </div>
                            <br>
                            <div class="head-p">
                                <div>
                                    <span>Kelas</span>
                                    <span>:</span>
                                    <span class="isi"><?= $jadwal_ini->kelas_nama ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>                                                                        
                    <ul class="tabs">
                        <li class="tab col"><a href="#penilaian-asisten" class="active">Hasil Penilaian Mahasiswa</a></li>
                        <li class="tab col"><a href="#komentar">Kritik & Saran Praktikum</a></li>
                        <li class="tab col"><a href="#peringkat">Pesan Mahasiswa</a></li>
                    </ul>
                    <!-- PENILAIAN MAHASISWA -->
                    <div id="penilaian-asisten" style="overflow-x:auto">
                        <br>
                        <div class="panel blue lighten-5">
                            <div>Hai, <b><?= $panggilan ?></b>. Berikut adalah hasil penilaian Anda di praktikum <b><?= $idp ?></b>.</div>
                        </div>
                        <br> 
                        <div class="panel blue lighten-5">
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
                        <div>Berikut adalah kritik & saran untuk praktikum <b><?= $idp ?></b>.</div>
                        <br>
                        <?php
                            foreach ($komentar as $key => $value) {
                        ?>
                        <div class="panel green lighten-5" style="margin-bottom:12px">
                            <?=$value['komentar']?>
                        </div>
                        <?php } ?>
                    </div>                    
                    <!-- KOMENTAR -->
                    <div id="peringkat">
                        <br/>
                        <div>Berikut adalah pesan atau saran untuk Anda pada praktikum <b><?= $idp ?></b>.</div>
                        <br>
                        <?php
                            foreach ($komentarmhs as $key => $value) {
                        ?>
                        <div class="panel blue lighten-5" style="margin-bottom:12px">
                            <?=$value['komentar']?>
                        </div>
                        <?php } ?>
                    </div>                                       
                </div>
            </div>
        </div>            
    </div>
</div>