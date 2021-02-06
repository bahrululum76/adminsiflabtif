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
    <link href="<?= base_url() ?>assets/images/icons/icon-96x96.png" rel="shortcut icon" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/material-icons/material-icons.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/font/poppins.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/material-design-lite/material.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/materialize/materialize.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/dataTables/dataTables.material.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/asisten.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/select2/select2.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/pace-loader/pace.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/library/summernote/summernote-lite-materialize.min.css">
    <script type="text/javascript" data-pace-options='{ "ajax": false }' src="<?= base_url() ?>assets/library/pace-loader/pace.min.js"></script>    
</head>
<body>
<?php 
// Get User
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $asisten = $this->db->join('t_jabatan', 't_asisten.jabatan_id = t_jabatan.jabatan_id')->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $tgl_view = date_create($asisten->tgl_lahir);
    $jml_notif = $this->M_database->countWhere('t_pesan_status', array('pesan_penerima' => $this->session->userdata('jabatan').$this->session->userdata('uid')));
    $menu_inventori = $this->M_database->getWhere('t_ruangan', null, null, array('ruangan_pj' => $this->session->userdata('jabatan_id')))->row();
    $jmlTicket = $this->M_database->countWhere('t_ticket', array('ticket_status' => 0));
    $jmlPesanMhs = $this->M_database->countWhere('t_pesan_mhs', array('pengirim' => 'mahasiswa', 'pesan_status' => 0));
    $menu_kuisioner = $this->db->select('kuisioner_asisten')->get('t_lab')->row();
    
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
?>
<div class="progress hide mypage-loader" style="position: fixed;z-index: 10;top: 0;margin: 0;background-color:#bbdefb"><div class="indeterminate" style="background-color:#2979ff "></div></div>
<div class="main-box">
    <?php          
    if (!empty($notif)) : ?>
        <div class="notifikasi <?=$color?>">
            <div><i class="notifikasi-icon material-icons"><?=$icon?></i></div>
            <div style="margin-right: 20px; width:100%"><?=$notif?></div>
            <button class="right transparent btn-flat" style="padding: 0;" onclick="closeNotifikasi()"><i class="material-icons-round white-text">close</i></button>
        </div>
    <?php endif  ?>

    <div class="notifikasi-ajax">
        <div><i class="notifikasi-icon material-icons">warning</i></div>
        <div style="margin-right: 20px; width:100%" class="notifikasi-msg">-</div>
        <button class="right transparent btn-flat" style="padding: 0;" onclick="closeNotifikasiAjax()"><i class="material-icons white-text">close</i></button>
    </div>
        
        <div class="sidenav-custom">
            <div class="sidenav-head">
                <img src="<?= base_url() ?>assets/images/icons/icon-96x96.png" alt="logo" width="30"><span class="app-name">SIFLABTIF</span>
                <button class="btn btn-menu btn-menu-close transparent z-depth-0" onclick="menuClose();"><i class="material-icons">menu_open</i></button>           
                <div class="line" style="border-radius: 5px; border-bottom: 2px solid rgba(203, 223, 236, 0.1); margin: 18px 0 20px -6px"></div>
            </div>                                         
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/dashboard"><span class="menu-link"><i class="material-icons-outlined left">dashboard</i>Dashboard</span></a></div>
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/absensi"><span class="menu-link"><i class="material-icons-outlined left">event_available</i>Absensi Praktikum<span class="menu-link"></a></div>
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/penilaian"><span class="menu-link"><i class="material-icons-outlined left">filter_7</i>Penilaian Praktikum</span></a></div>
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/tugas"><span class="menu-link"><i class="material-icons-outlined left">code</i>Tugas Praktikum</span></a></div>
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/modul-praktikum"><span class="menu-link"><i class="material-icons-outlined left">book</i>Modul Praktikum</span></a></div>
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/kehadiran"><span class="menu-link"><i class="material-icons-outlined left">date_range</i>Kehadiran Asisten</span></a></div>
            <?php if ($menu_inventori) : ?>              
                <div><a class="link nav__link" href="<?= base_url() ?>asisten/inventori"><span class="menu-link"><i class="material-icons-outlined left">kitchen</i>Inventori Lab</span></a></div>
            <?php endif ?>
            <?php if ($this->session->userdata('jabatan_id') == 4 || $this->session->userdata('jabatan_id') == 5) { ?>                
                <div><a class="link nav__link" href="<?= base_url() ?>asisten/penggajian?pertemuan=1&sampai=1&bulan=1" id="link-penggajian"><?php if($this->uri->segment(2) == 'penggajian') { echo '<div class="link-active"></div><div class="link-active-child"></div>';} ?><span class="menu-link"><i class="material-icons-outlined left">money</i>Penggajian</span></a></div>                        
            <?php }?>
            <?php if ($this->session->userdata('jabatan_id') == 2) { ?>                
                <div><a class="link nav__link" href="<?= base_url() ?>asisten/kritik_saran"><span class="menu-link"><i class="material-icons-outlined left">how_to_vote</i>Kritik & Saran</span></a></div>                        
            <?php }?>
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/chat"><span class="menu-link"><i class="material-icons-outlined left">chat</i>Pesan</span></a></div>            
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/catatan"><span class="menu-link"><i class="material-icons-outlined left">description</i>Catatan</span></a></div>
            <?php if ($menu_kuisioner->kuisioner_asisten == 1) { ?>            
            <div><a class="link nav__link" href="<?= base_url() ?>asisten/kuisioner"><span class="menu-link"><i class="material-icons left">people_alt</i>Kuisioner</span></a></div>
            <?php } ?>            
        </div>
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <button class="btn btn-menu btn-menu-open transparent z-depth-0" onclick="menuOpen();"><i class="material-icons">menu</i></button>           
            <button class="btn btn-menu-profil transparent z-depth-0"><i class="material-icons">arrow_drop_down</i></button>
            <div style="position:sticky;z-index:10;top:0">
                <div class="dd-body dd-body-menu">
                    <a href="#!" class="modal-trigger" data-target="modalLihatAsisten"><i class="material-icons left">credit_card</i>Lihat Profil</a>
                    <a class="nav__link" href="<?=base_url()?>asisten/profil"><i class="material-icons left">face</i>Edit Profil</a>
                    <a class="nav__link" href="<?=base_url()?>asisten/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>
                    <?php if($this->session->userdata('jabatan_id') == 6) { echo '<a class="nav__link" href="'.base_url().'admin/dashboard"><i class="material-icons left">admin_panel_settings</i>Halaman Admin</a>'; } ?>
                    <div class="center-align">
                    <button style="margin: 10px 0;" class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger" data-target="modalLogout" href="<?=base_url()?>auth/logout"><i class="material-icons left">exit_to_app</i><span>Logout</span></button>                    
                    </div>
                </div>           
            </div>
            <!-- Notifikasi pesan -->
            <input type="hidden" id="jml-notif" value="<?=$jml_notif?>">
            <input type="hidden" id="jml-ticketing" value="<?=$jmlTicket + $jmlPesanMhs?>">
            <input type="hidden" class="user_chat_id" value="nouser">
            <input type="hidden" class="chat_kanal" value="-">
            <div class="notif-pesan">
                <img src="" alt="profil" height="50" class="np-foto circle">
                <div style="margin-left:16px; width:100%">
                    <a class="np-link nav__link" style="cursor:pointer">        
                        <span class="np-pengirim">-</span>        
                        <span class="np-pesan" style="font-weight:300">-</span>
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
<script src="<?= base_url() ?>assets/library/select2/select2.min.js"></script>
<script src="<?= base_url() ?>assets/library/summernote/summernote-lite.min.js"></script>
<script src="<?= base_url() ?>assets/dist/js/init.min.js"></script>
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
<script src="<?= base_url() ?>assets/dist/js/asisten.min.js"></script>
<script>
    $(document).ready(function () {            
        $(".main-panel").addClass("fade-in-bottom");
        $(".chat-contact").addClass("fade-in-bottom");
        $(".chat-panel").addClass("fade-in-bottom");
        $("a.tooltipped").addClass("nav__link");
        $("table").css('width', '100%');
        $("form").attr('autocomplete', 'off');
        $("input, textarea").attr('spellcheck', 'false');
        $(".dataTables_paginate").css('margin-top', '24px');
        $('.modal').removeAttr('tabIndex');            
        $('.dd-nama, .btn-menu-profil').on('click', function () {
            $('.dd-body-menu').toggle();
            $('.dd-body-menu').toggleClass('fade-in-bottom');
        });                                                                                
        $('.dd-pesan').on('click', function () {
            $('.dd-body-pesan').toggle();
            $('.dd-body-pesan').toggleClass('fade-in-bottom');
        });                
    
        let link = '<div class="link-active"></div><div class="link-active-child"></div>';                                 
        let path = window.location.href.split('?');            
        let uri = path[0].split('/');            
        let segment = uri[uri.length-1];
        if (segment === 'penggajian') {
            setTimeout(() => {
                $("#link-penggajian").css('color', 'rgba(0,0,0,0.87)');            
            }, 300); 
        }
        $(".link").each(function () {               
            if (this.href == path[0]) {
                $(this).append(link);                
                setTimeout(() => {
                    $(this).css('color', 'rgba(0,0,0,0.87)');            
                }, 500);
            } 
        });       
        
        $(document).on('click', '.nav__link', function () {
            $('.mypage-loader').removeClass('hide');
            menuClose();
        });

        $('.close-notif').on('click', function () {
            $('.notif-pesan').css('right', '-500px');
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
        $('.sidenav-custom').addClass('menu-open');
        $('.btn-menu-close').css('display', 'block');           
    }
    
    function menuClose() {
        $('.sidenav-custom').removeClass('menu-open');          
        $('.btn-menu-close').css('display', 'none');           
    }                                 
</script>
</body>
</html>