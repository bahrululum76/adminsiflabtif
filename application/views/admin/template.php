<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#07276e">    
    <link rel="apple-touch-icon" href="<?= base_url() ?>assets/images/icons/icon-96x96.png">
    <meta name="apple-mobile-web-app-status-bar" content="#07276e">
    <link rel="manifest" href="<?=base_url()?>manifest.json">  
    <title>SIFLABTIF - <?=$tabTitle?></title>
    <link href="<?= base_url() ?>assets/images/icons/icon-96x96.png" rel="shortcut icon">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/material-icons/material-icons.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/font/poppins.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/material-design-lite/material.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/materialize/materialize.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/dataTables/dataTables.material.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/admin.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/select2/select2.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/pace-loader/pace.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/summernote/summernote-lite-materialize.min.css">
    <script type="text/javascript" data-pace-options='{ "ajax": false }' src="<?= base_url() ?>assets/library/pace-loader/pace.min.js"></script>        
</head>
<body>
<div class="progress hide mypage-loader" style="position: fixed;z-index: 1000;top: 0;margin: 0;background-color:#bbdefb"><div class="indeterminate" style="background-color:#2979ff "></div></div>

<?php     
    // Get User
	$user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $asisten = $this->db->join('t_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id')->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $tgl_view = date_create($asisten->tgl_lahir);
    $jml_notif = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $this->session->userdata('jabatan').$this->session->userdata('uid')));
    $jmlTicket = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
	$jmlPesanMhs = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
    $nama = explode(' ', $asisten->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $asisten->asisten_nama;
    }
    $jmlPesanMhsHtml = 'hide';
    $jmlTicketHtml = 'hide';
    if ($jmlPesanMhs > 0) {
        $jmlPesanMhsHtml = '';
    }    
    if ($jmlTicket > 0) {
        $jmlTicketHtml = '';
    }    
?>
<!-- Notifikasi flash data -->
<?php 
    if (!empty($this->session->flashdata('tambah'))) {
        $notif = $this->session->flashdata('tambah');
        $color = 'success-gradient';
        $icon = 'check_circle';
    } else if (!empty($this->session->flashdata('edit'))) {
        $notif = $this->session->flashdata('edit');
        $color = 'blue-gradient';
        $icon = 'info_outline';
    } else if (!empty($this->session->flashdata('hapus'))) {
        $notif = $this->session->flashdata('hapus');
        $color = 'red-gradient';
        $icon = 'remove_circle';
    } else if (!empty($this->session->flashdata('warning'))) {
        $notif = $this->session->flashdata('warning');
        $color = 'orange-gradient';
        $icon = 'warning';
    }     

    if (!empty($notif)) { ?>
        <div class="notifikasi <?=$color?>">
            <div><i class="notifikasi-icon material-icons"><?=$icon?></i></div>
            <div style="margin-right: 20px; width:100%"><?=$notif?></div>
            <button class="right transparent btn-flat" style="padding: 0;" onclick="closeNotifikasi()"><i class="material-icons white-text">close</i></button>
        </div>
<?php }  ?>

    <div class="notifikasi-ajax">
        <div><i class="notifikasi-icon material-icons">warning</i></div>
        <div style="margin-right: 20px; width:100%" class="notifikasi-msg">-</div>
        <button class="right transparent btn-flat" style="padding: 0;" onclick="closeNotifikasiAjax()"><i class="material-icons white-text">close</i></button>
    </div>

    <div class="mybrand-sidenav">
        <div class="mybrand">
            <img src="<?= base_url() ?>assets/images/icons/icon-96x96.png" alt="labtif logo" class="left" width="40px">
            <span class="logo-text">SIFLABTIF</span>
            <button class="btn transparent z-depth-0 btn-menu btn-menu-close" onclick="menuClose()"><i class="material-icons">menu_open</i></button>
        </div>      
    </div>

    <!-- sidebar -->
    <ul class="sidenav">          
        <li><a class="nav__link" href="<?= base_url() ?>admin/dashboard"><i class="material-icons-round text-white tooltiped" data-position="right" data-tooltip="Dashboard">dashboard</i><span>Dashboard</span></a></li>
        <li><a href="<?= base_url() ?>admin/periode" <?php if($this->uri->segment(2) == 'penjadwalan') { echo 'class="active nav__link"';} else { echo'class="nav__link"';} ?> ><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Penjadwalan">schedule</i><span>Penjadwalan</span></a></li>                                  
        <li><a class="nav__link" href="<?= base_url() ?>admin/pembayaran"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Pembayaran Praktikum">money</i><span>Pembayaran</span></a></li>    
        <li><a class="nav__link" href="<?= base_url() ?>admin/registrasi"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Registrasi Praktikum">how_to_reg</i><span>Registrasi</span></a></li>    
        <li><a class="nav__link" href="<?= base_url() ?>admin/pindah-praktikum"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Pindah Praktikum">swap_horizontal</i><span>Pindah Praktikum</span></a></li>    
        <li><a class="nav__link" href="<?= base_url() ?>admin/absensi"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Absensi">date_range</i><span>Absensi Praktikum</span></a></li>    
        <li><a class="nav__link" href="<?= base_url() ?>admin/penilaian"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Penilaian Praktikum">money</i><span>Penilaian Praktikum</span></a></li>    
        <li><a class="nav__link" href="<?= base_url() ?>admin/pengumuman"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Info Praktikum">announcement</i><span>Info Praktikum</span></a></li>
        <li><a class="nav__link" href="<?= base_url() ?>admin/ticketing"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="E - Ticketing">confirmation_number</i><span>E - Ticketing</span></a></li>
        <li><a class="nav__link" href="<?= base_url() ?>admin/chat"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Pesan">chat</i><span>Pesan</span></a></li>
        <li><a class="nav__link" href="<?= base_url() ?>admin/catatan"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Catatan">description</i><span>Catatan</span></a></li>
        <li class="no-padding">
            <ul class="collapsible expandable" id="collapsible-user">
                <li>
                    <a class="collapsible-header waves-effect waves-cyan"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Master">face</i><span>User</span><i class="material-icons right menu-arrow menu-arrow-1 grey-text text-lighten-1">keyboard_arrow_right</i></a>
                    <div class="collapsible-body">
                        <ul>
                            <li><a href="<?= base_url() ?>admin/asisten" class="nav__link collapsible-link-user" style="padding-left: 8px"><i class="tiny material-icons-round tooltiped" data-position="right" data-tooltip="Asisten">radio_button_unchecked</i><span>Asisten</span></a></li>        
                            <li><a href="<?= base_url() ?>admin/mahasiswa" class="nav__link collapsible-link-user" style="padding-left: 8px"><i class="tiny material-icons-round tooltiped" data-position="right" data-tooltip="Mahasiswa">radio_button_unchecked</i><span>Mahasiswa</span></a></li>                            
                            <li><a href="<?= base_url() ?>admin/dosen" class="nav__link collapsible-link-user" style="padding-left: 8px"><i class="tiny material-icons-round tooltiped" data-position="right" data-tooltip="Dosen">radio_button_unchecked</i><span>Dosen</span></a></li>                                                        
                        </ul>                                            
                    </div>
                </li>
            </ul>
        </li>
        <li class="no-padding">
            <ul class="collapsible expandable" id="collapsible-master">
                <li>
                    <a class="collapsible-header waves-effect waves-cyan"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Master">archive</i><span>Master</span><i class="material-icons right menu-arrow menu-arrow-2 grey-text text-lighten-1">keyboard_arrow_right</i></a>
                    <div class="collapsible-body">
                        <ul>                            
                            <li><a href="<?= base_url() ?>admin/kelas" class="nav__link collapsible-link-master" style="padding-left: 8px"><i class="tiny material-icons-round tooltiped" data-position="right" data-tooltip="Kelas">radio_button_unchecked</i><span>Kelas</span></a></li>                            
                            <li><a href="<?= base_url() ?>admin/ruangan" class="nav__link collapsible-link-master" style="padding-left: 8px"><i class="tiny material-icons-round tooltiped" data-position="right" data-tooltip="Ruangan">radio_button_unchecked</i><span>Ruangan</span></a></li>
                            <li><a href="<?= base_url() ?>admin/matkum" class="nav__link collapsible-link-master" style="padding-left: 8px"><i class="tiny material-icons-round tooltiped" data-position="right" data-tooltip="Mata Praktikum">radio_button_unchecked</i><span>Mata Praktikum</span></a></li>                       
                            <li><a href="<?= base_url() ?>admin/jabatan" class="nav__link collapsible-link-master" style="padding-left: 8px"><i class="tiny material-icons-round tooltiped" data-position="right" data-tooltip="Jabatan">radio_button_unchecked</i><span>Jabatan</span></a></li>                            
                        </ul>                                             
                    </div>
                </li>
            </ul>
        </li>
        <li><a class="nav__link" href="<?= base_url() ?>admin/aplikasi"><i class="material-icons-round tooltiped" data-position="right" data-tooltip="Aplikasi">app_settings_alt</i><span>Aplikasi</span></a></li>
        <br>
        <br>
    </ul>        

    <!-- Content Wrapper -->
    <div class="main-box">
        <div class="navbar-top">            
            <div class="top-panel right-align">
                <span class="panel-title-1 left" style="position: relative; top: 4px"><?=$halaman_title?></span>
                <a href="<?=base_url()?>admin/ticketing" class="nav__link tooltipped" data-position="bottom" 
                data-tooltip="<div class='tooltip-html'><div class='tooltip-html-title'>E-ticketing</div><div class='tooltip-html-item tooltip-html-item-first <?=$jmlPesanMhsHtml?>'><?=$jmlPesanMhs?> pesan belum dibaca</div><div class='tooltip-html-item <?=$jmlTicketHtml?>'><?=$jmlTicket?> tiket pending</div></div>">            
                    <span style="display: inline-block; margin-right: 24px; position: relative; top: 8px; cursor:pointer">
                        <span class="pulse-ticket hide" style="margin: 4px; animation: shadow-pulse 1s infinite;display:block; width: 6px; height: 6px; background-color: #0077ff; border-radius: 10px; position:absolute; right: -10px; top: -10px">
                        </span>
                        <i class="material-icons-outlined grey-text text-darken-2">confirmation_number</i>                    
                    </span>            
                </a>
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
                <span class="dd-nama">
                    <?= $this->session->userdata('nama') ?>
                    <i class="material-icons right" style="position:relative; bottom: 2px; right: 8px">arrow_drop_down</i>                    
                </span>
            </div>
            <div class="dd-body dd-body-menu">
                <a href="#!" class="modal-trigger" data-target="modalLihatAsisten"><i class="material-icons left">credit_card</i>Lihat Profil</a>
                <a class="nav__link" href="<?=base_url()?>asisten/profil"><i class="material-icons left">face</i>Edit Profil</a>
                <a class="nav__link" href="<?=base_url()?>asisten/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>
                <a class="nav__link" href="<?=base_url()?>asisten/dashboard"><i class="material-icons left">admin_panel_settings</i>Halaman Asisten</a>
                <div class="center-align">
                <button style="margin: 10px 0;" class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger" data-target="modalLogout" href="<?=base_url()?>auth/logout"><i class="material-icons left">exit_to_app</i><span>Logout</span></button>                    
                </div>
            </div>            
        </div>
        <div class="content-wrapper">
            <button class="btn btn-menu btn-menu-open transparent z-depth-0" onclick="menuOpen()"><i class="material-icons">menu</i></button>
            <button class="btn btn-menu-profil transparent z-depth-0"><i class="material-icons">arrow_drop_down</i></button>            
            <!-- Notifikasi pesan -->
            <input type="hidden" id="jml-notif" value="<?=$jml_notif?>">
            <input type="hidden" id="jml-ticketing" value="<?=$jmlTicket + $jmlPesanMhs?>">
            <input type="hidden" class="user_chat_id" value="nouser">
            <input type="hidden" class="chat_kanal" value="-">
            <div class="notif-pesan">
                <img src="" alt="profil" height="50" class="np-foto circle">
                <div style="margin-left:16px; width:100%">
                    <a class="np-link" style="cursor:pointer">        
                        <span class="np-pengirim">-</span>        
                        <span class="np-pesan">-</span>
                    </a>
                </div>
                <i class="material-icons white-text close-notif right">close</i>                    
            </div>
            <?= $content ?>
        </div>
    </div>

<!-- Modal logout-->
<div id="modalLogout" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Konfirmasi Logout</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">      
        <div class="panel orange-gradient white-text">Apakah anda yakin ingin logout dari aplikasi ?</div>        
    </div>
    <div class="modal-footer custom-modal-footer">
        <a href="<?=base_url()?>auth/logout" class="waves-effect waves-light tombol tombol-sm orange-gradient white-text right"><i class="material-icons left">exit_to_app</i><span>Logout</span></a>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
</div>

<!-- Modal lihat asisten -->
<div id="modalLihatAsisten" class="modal modal-card-id">
    <div class="modal-content">
        <div class="card-bg">
            <div class="row">
                <div class="card-foto-wrapper">
                    <img class="foto-modal circle" src="<?= base_url() ?>/assets/images/profil/<?= $asisten->foto ?>" alt="foto-profil">
                    <div class="card-foto-logo-wrapper">
                        <img src="<?= base_url() ?>assets/images/icons/icon-96x96.png" alt="logo" class="card-foto-logo">
                        <span class="card-foto-logo-name">ASLABTIF</span>
                    </div>
                    <div class="card-name">
                        <span class="asisten-nama"><?= $asisten->asisten_nama ?></span>                
                        <span class="jabatan-nama"><?= $asisten->jabatan_nama ?></span>
                    </div>               
                </div>                
            </div>            
        </div>
        <div class="row">                        
            <div class="card-id-item-wrapper">
                <div class="col s6 m4 l3">
                    <span class="title-id">Username</span>                    
                    <span class="isi-id txt-username"><?= $asisten->username ?></span>                                                            
                    <span class="title-id">Tanggal Lahir</span>                    
                    <span class="isi-id tgl-lahir"><?= date_format($tgl_view, 'd/m/Y') ?></span>                    
                </div>
                <div class="col s6 m4 l3 card-item">                                                            
                    <span class="title-id">Tahun Masuk</span>                    
                    <span class="isi-id tahun-masuk"><?= $asisten->thn_masuk ?></span>                                                            
                    <span class="title-id">Tahun Keluar</span>                    
                    <span class="isi-id tahun-keluar"><?= $asisten->thn_keluar ?></span>                    
                </div> 
                <div class="col s12 m4 l6 card-item">                    
                    <span class="title-id">Email</span>                    
                    <span class="isi-id txt-email"><?= $asisten->email ?></span>                                        
                    <span class="title-id">Alamat</span>                    
                    <span class="isi-id txt-alamat"><?= $asisten->alamat ?></span>                                       
                </div>                          
            </div>
        </div>
    </div>
</div>
<!--Modal Permission  -->
<div id="modalPermission" class="modal modal-sm">	
	<div class="modal-content modal-content-sm custom-modal-content">
		<div class="row">
			<div class="panel teal lighten-5">
                <div style="font-size: 16px;font-weight:600;margin-bottom:4px">Halo, <?=$this->session->userdata('panggilan')?></div>                
                <div>Selamat datang di SIFLABTIF.</div>
                <div>Aplikasi ini memerlukan izin untuk menerima notifikasi pesan. Klik mengerti kemudian izinkan untuk memberikan akses notifikasi.</div>
            </div>
		</div>
	</div>
	<div class="modal-footer custom-modal-footer">
        <div class="center-align">        
		    <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-primary modal-close btn-notif"><span>Mengerti</span></button>
        </div>
	</div>
</div> 
    <script src="<?= base_url() ?>assets/library/jquery/jquery-3.5.1.min.js"></script>
    <script src="<?= base_url() ?>assets/library/materialize/materialize.min.js"></script>
    <script src="<?= base_url() ?>assets/library/dataTables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/library/dataTables/dataTables.material.min.js"></script>
    <!-- select 2 -->
    <script src="<?= base_url() ?>assets/library/select2/select2.min.js"></script>
    <!-- summernote -->
    <script src="<?= base_url() ?>assets/library/summernote/summernote-lite.min.js"></script>
    <!-- init js -->
    <script src="<?= base_url() ?>assets/dist/js/init.min.js"></script>
    <!-- pusher -->
    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
    <!-- proses data js -->
    <script src="<?= base_url() ?>assets/dist/js/admin.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".main-panel").addClass("fade-in-bottom");
            $(".chat-contact").addClass("fade-in-bottom");
            $(".chat-panel").addClass("fade-in-bottom");
            $("table").css('width', '100%');
            $("form").attr('autocomplete', 'off');
            $("input[type=text]").attr('spellcheck', 'false');
            $(".dataTables_paginate").css('margin-top', '24px');
            $('.modal').removeAttr('tabIndex');
            $('.close-notif').on('click', function () {
                $('.notif-pesan').css('right', '-500px');
            });
            $('.dd-nama, .btn-menu-profil').on('click', function () {
                $('.dd-body-menu').toggle();
                $('.dd-body-menu').toggleClass('fade-in-bottom');
            });                                                                                
            $('.dd-pesan').on('click', function () {
                $('.dd-body-pesan').toggle();
                $('.dd-body-pesan').toggleClass('fade-in-bottom');
            });

            $(document).on('click', '.nav__link', function () {
                $('.mypage-loader').removeClass('hide');
                menuClose();
            });

            sessionStorage.setItem('asiflabtif-app', 'open');
            const permissionToggle = $(".btn-notif");
            permissionToggle.click(function() {
                Notification.requestPermission(status => {
                    $('#modalPermission').modal('close');                   
                });
            });

            if (Notification.permission === 'default') {
                setTimeout(() => {                
                    $('#modalPermission').modal('open');
                }, 1000);               
            } else if (Notification.permission === 'granted') {
                $('.btn-notif').click();
            }                    
        });
        function menuOpen() {
            $('.sidenav, .mybrand-sidenav').addClass('menu-open');
            $('.btn-menu-close').css('display', 'block');           
        }
        
        function menuClose() {
            $('.sidenav, .mybrand-sidenav').removeClass('menu-open');          
            $('.btn-menu-close').css('display', 'none');           
        }                                               
    </script>   

</body>
</html>
