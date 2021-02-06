<?php 
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }
    $bulan = date('m');
    switch ($bulan) {
        case '01':
            $bulan = 'Januari';
            break;
        case '02':
            $bulan = 'Februari';
            break;
        case '03':
            $bulan = 'Maret';
            break;
        case '04':
            $bulan = 'April';
            break;
        case '05':
            $bulan = 'Mei';
            break;
        case '06':
            $bulan = 'Juni';
            break;
        case '07':
            $bulan = 'Juli';
            break;
        case '08':
            $bulan = 'Agustus';
            break;
        case '09':
            $bulan = 'September';
            break;
        case '10':
            $bulan = 'Oktober';
            break;
        case '11':
            $bulan = 'November';
            break;
        case '12':
            $bulan = 'Desember';
            break;
        default:
            $bulan = 'Pilih Bulan';
            break;
    }
    $sekarang = date('d')." $bulan ".date('Y');                                
?>
<style>
    .select2-selection__rendered {
        background-color: #F3E4B2;
    }    
</style>

<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Dashboard</span>
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
        <div class="dashboard-panel">
            <div class="row">
                <div class="col s12">
                    <div class="left-align"><?=$hari_ini?>, <?=$sekarang?></div>                              
                    <?php 
                    if ($catatan) { ?>
                        <div id="jadwal-title" style="font-size: 16px; font-weight: 500; margin-top: 12px;margin-bottom:-6px">Info Praktikum</div>                                        
                        <div class="catatan-wrapper catatan-wrapper-pin catatan-info">
                        <?php foreach ($catatan as $key => $value) { 
                            $border = '';
                            if ($value->catatan_warna == '#ffffff') {
                                $border = 'border: 1px solid #e0e0e0;';
                            }
    
                            $date_created = explode('-', $value->created_at);
                            switch ($date_created[1]) {
                                case '01':
                                    $bulan = 'Januari';
                                    break;
                                case '02':
                                    $bulan = 'Februari';
                                    break;
                                case '03':
                                    $bulan = 'Maret';
                                    break;
                                case '04':
                                    $bulan = 'April';
                                    break;
                                case '05':
                                    $bulan = 'Mei';
                                    break;
                                case '06':
                                    $bulan = 'Juni';
                                    break;
                                case '07':
                                    $bulan = 'Juli';
                                    break;
                                case '08':
                                    $bulan = 'Agustus';
                                    break;
                                case '09':
                                    $bulan = 'September';
                                    break;
                                case '10':
                                    $bulan = 'Oktober';
                                    break;
                                case '11':
                                    $bulan = 'November';
                                    break;
                                case '12':
                                    $bulan = 'Desember';
                                    break;
                                default:
                                    $bulan = '-';
                                    break;
                            }
                            $tgl_catatan = $date_created[2][0].$date_created[2][1].' '.$bulan.' '.$date_created[0];
                            ?>                  
                            <div class="catatan-item" style="background-color:<?=$value->catatan_warna?>;<?=$border?>">
                                <div class="catatan-header"><?=$value->catatan_judul?></div>
                                <div class="catatan-content"><?=$value->catatan_isi?></div>
                                <div class="catatan-footer">
                                    <div class="right-align" style="font-size:12px;padding:8px;">
                                        <span><?=$tgl_catatan?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    <?php } ?>
                    <br>
                    <div id="jadwal-title" style="font-size: 16px; font-weight: 500; margin-top: 8px;"><?= $hari_ini_title ?></div>                                        
                    <div class="dragscroll">
                        <?php if ($jadwal == null) {
                            echo "<div class='jadwal-item center-align'>Tidak ada jadwal</div>";
                            echo '<script>setTimeout(() => {
                                document.querySelector(".dragscroll").style.height = "80px";
                            }, 1000);</script>';
                        } ?>
                        <?php 
                        foreach ($jadwal as $key => $value) { 
                                $asisten_1 = $this->db->where('asisten_id', $value->asisten_1)->get('t_asisten')->row();
                                $asisten_2 = $this->db->where('asisten_id', $value->asisten_2)->get('t_asisten')->row();
    
                                $nama1 = explode(' ', $asisten_1->asisten_nama);
                                if (sizeof($nama1) > 1) {
                                    if ($nama1[0] == 'M' || $nama1[0] == 'M.' || $nama1[0] == 'Muhamad' || $nama1[0] == 'Muhammad' || $nama1[0] == 'Mohamed' || $nama1[0] == 'Mohammed' || $nama1[0] == 'Moch' || $nama1[0] == 'Moch.' || $nama1[0] == 'Mochamad' || $nama1[0] == 'Mochammad' || $nama1[0] == 'Much' || $nama1[0] == 'Much.' || $nama1[0] == 'Muchamad' || $nama1[0] == 'Muchammad') {
                                        $panggilan_1 = $nama1[1];
                                    } else {
                                        $panggilan_1 = $nama1[0];
                                    }
                                } else {
                                    $panggilan_1 = $asisten_1->asisten_nama;
                                }
                                
    
                                $nama2 = explode(' ', $asisten_2->asisten_nama);
                                if (sizeof($nama2) > 1) {
                                    if ($nama2[0] == 'M' || $nama2[0] == 'M.' || $nama2[0] == 'Muhamad' || $nama2[0] == 'Muhammad' || $nama2[0] == 'Mohamed' || $nama2[0] == 'Mohammed' || $nama2[0] == 'Moch' || $nama2[0] == 'Moch.' || $nama2[0] == 'Mochamad' || $nama2[0] == 'Mochammad' || $nama2[0] == 'Much' || $nama2[0] == 'Much.' || $nama2[0] == 'Muchamad' || $nama2[0] == 'Muchammad') {
                                    $panggilan_2 = $nama2[1];
                                    } else {
                                        $panggilan_2 = $nama2[0];
                                    }
                                } else {
                                    $panggilan_2 = $asisten_2->asisten_nama;
                                }
                                
                            ?>
                            <div class="jadwal-item">
                                <span class="txt-hari"><?= $value->jadwal_hari ?></span>                                
                                <span class="txt-jam"><?= $value->jadwal_jam ?></span>                                                           
                                <span class="txt-kode"><?= strtoupper($value->jadwal_kode) ?></span>                                
                                <span class="txt-matkum"><?= ucwords($value->matkum) ?></span>                                
                                <span class="txt-asisten"><?= $panggilan_1 ?> & <?= $panggilan_2 ?></span>                       
                                <div class="line-dashed"></div>
                                <span class="txt-ruangan"><?= $value->ruangan_nama ?></span>
                            </div>
                        <?php } ?>
                    </div>
                    <span class="select2-wrapper" style="width: 140px;">
                        <select class="select2-no-search" style=" background-color; #FAE9DD;" id="jadwal-dashboard">
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
            </div>
        </div>        
    </div>
</div>

<!-- minigrid -->
 <script src="<?= base_url() ?>assets/library/minigrid/minigrid.min.js"></script>
 <script>
     function pinnedNote() {
        var grid;
        function init() {
            grid = new Minigrid({
            container: '.catatan-wrapper-pin',
            item: '.catatan-item',
            gutter: 12
            });
            grid.mount();            
        }
        
        // mount
        function update() {
            grid.mount();
        }

        document.addEventListener('DOMContentLoaded', init);
        window.addEventListener('resize', update);
    } pinnedNote();  
</script>