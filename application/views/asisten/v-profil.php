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

<style>
	.select2-container--default .select2-selection--multiple, .select2-container--default .select2-selection--single, .select2-container--default.select2-container--focus .select2-selection--multiple {
        height: 54px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 54px;        
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 54px;
    }
    .select2-results__option[aria-selected=true] {
        display: none
    }
    .select2-wrapper {
        width: calc(100% - 38px);
        max-width: 1000px;
        box-shadow: none;
        border: 1px solid #cacaca;
        float: right;
        margin-bottom: 8px;
    }
    .select2-wrapper i {
        position:absolute;
        left: 10px;
        line-height:45px;
        color: #607d8b
    }
</style>

<div class="col s12">
    <div class="main-wrapper">
		<div class="top-panel right-align">
			<span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Edit Profil</span>
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
            <img src="<?= base_url() ?>assets/images/profil/<?=$foto?>" alt="profil" height="32" class="circle">
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
                    <div class="row">
						<div class="col s12 m12 l4 center-align hide-on-large-only show-on-medium-and-down">
			            	<div class="hide-on-med-and-down show-on-large" style="margin-top: 12px"></div>
			            	<div class="box-foto-profil">
								<!-- preloader -->
								<div class="preloader-wrapper small active myloader hide" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
									<div class="spinner-layer spinner-yellow-only" style="border-color:white">
										<div class="circle-clipper left">
											<div class="circle"></div>
										</div><div class="gap-patch">
											<div class="circle"></div>
										</div><div class="circle-clipper right">
											<div class="circle"></div>
										</div>
									</div>
								</div>
								<p class="myloader-text hide" style="position:absolute; margin: auto; left:0; right:0; top:60%; color: #fff; z-index:10">Loading . . .</p>
			            		<img src="<?= base_url() ?>assets/images/profil/<?= $foto ?>" alt="default-img" class="responsive-img preview-image">			            		
			            		<img src="<?= base_url() ?>assets/images/profil/<?= $foto ?>" alt="default-img" class="responsive-img hide hidden-image">			            		
			            		<button type="button" class="waves-effect waves-light tombol tombol-sm tombol-warning btn-upload btn-upload-profil"><i class="material-icons left">file_upload</i></button>	            	<span class="tooltiped yellow-text text-lighten-2" style="position:absolute;bottom:4px;left:60px;cursor:pointer" data-position="bottom" data-tooltip="Max ukuran 2MB (1080x1080)"><i class="material-icons">info_outline</i></span>	
			            	</div>
							<div class="hide confirm-profil">							
								<button type="button" data-foto="<?=base_url()?>assets/images/profil/<?=$foto?>" class="waves-effect transparent tombol tombol-flat red-text cancel-profil"><i class="material-icons left">close</i></button>	
								<button type="button" class="waves-effect transparent tombol tombol-flat green-text ok-profil"><i class="material-icons left">check</i></button>	
							</div>
							<br>
			            </div>			            			                                           	
			            <div class="col s12 m12 l8">
			                <form class="col s12 m12 l12" action="<?= base_url() ?>asisten/profil/editProfil" method="post">
			                    <div class="row">							        
			                        <div class="input-field col s12">
			                        	<i class="material-icons-outlined prefix" style="color: #607d8b">beenhere</i>
			                            <input id="username" type="text" name="username" autocomplete="off" value="<?= $username ?>" disabled>
			                            <label for="username" style="margin-left: 55px; width:fit-content; background-color:#ffffff">Username</label>
			                        </div>			                        
			                        <div class="input-field col s12">
			                        	<i class="material-icons prefix" style="color: #607d8b">payment</i>			                        	
			                            <input id="asisten_nama" data-asisten_nama="<?= $asisten_nama ?>" type="text" class="input-profil" name="asisten_nama" autocomplete="off" value="<?= $asisten_nama ?>" disabled required>
			                            <label for="asisten_nama" style="margin-left: 55px; width:fit-content; background-color:#ffffff">Nama</label>
			                        </div>
			                        <div class="input-field col s12">
										<i class="material-icons-outlined prefix" style="color: #607d8b">event_seat</i>
										<input id="jabatan_nama" type="text" autocomplete="off" value="<?= $jabatan_nama ?>" disabled>
			                            <label for="jabatan_nama" style="margin-left: 55px; width:fit-content; background-color:#ffffff">Jabatan</label>			                            
									</div>
									<div class="input-field col s12 m4 l4">
										<i class="material-icons-outlined prefix" style="color: #607d8b">event_note</i>
										<input id="tgl_lahir" type="text" data-tgl_lahir="<?= $tanggal ?>" class="input-profil" name="tgl_lahir" required value="<?=$tanggal?>" autocomplete="off" spellcheck="false" disabled>
										<label for="tgl_lahir" style="margin-left: 55px; width:fit-content; background-color:#ffffff;">Tanggal Lahir</label>
									</div>
									<div class="input-field col s11 offset-s1 m4 l5">                        
										<span class="select2-wrapper" style="width: 100%;text-transform:uppercase">
											<select id="bulan_lahir" class="select2-no-search input-profil" name="bulan_lahir" required disabled data-bulan_lahir="<?= $n_bulan ?>">
												<option value="<?= $n_bulan ?>"><?= $bulan ?></option>                           
												<option value="01">Januari</option>                           
												<option value="02">Februari</option>                           
												<option value="03">Maret</option>                           
												<option value="04">April</option>                           
												<option value="05">Mei</option>                           
												<option value="06">Juni</option>                           
												<option value="07">Juli</option>                           
												<option value="08">Agustus</option>                           
												<option value="09">September</option>                           
												<option value="10">Oktober</option>                           
												<option value="11">November</option>                           
												<option value="12">Desember</option>             
											</select>
										</span>
									</div>
									<div class="input-field col s11 offset-s1 m4 l3">
										<input id="tahun_lahir" type="text" data-tahun_lahir="<?= $tahun ?>" class="input-profil" name="tahun_lahir" required value="<?=$tahun?>" autocomplete="off" spellcheck="false" disabled>
										<label for="tahun_lahir" style="width:fit-content; background-color:#ffffff">Tahun</label>
									</div>                        									
									<div class="input-field col s12">
										<i class="material-icons-outlined prefix" style="color: #607d8b">maps</i>	                        	
										<textarea id="alamat" type="text" class="materialize-textarea input-profil" data-alamat="<?= $alamat ?>" name="alamat" autocomplete="off" style="padding:16px .8rem; text-indent:0" disabled required><?= $alamat ?></textarea>
										<label for="alamat" style="margin-left: 55px; width:fit-content; background-color:#ffffff;">Alamat</label>
									</div>				                        
			                        <div class="input-field col s12">
			                        	<i class="material-icons-outlined prefix" style="color: #607d8b">email</i>			                        	
			                            <input id="email" type="text" data-email="<?= $email ?>" class="input-profil" name="email" autocomplete="off" value="<?= $email ?>" disabled required>
			                            <label for="email" style="margin-left: 55px; width:fit-content; background-color:#ffffff;">Email</label>
										<br>
			                        </div>									
									<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary btn-tambah right hide"><i class="material-icons left">save</i><span>Simpan</span></button>	
									<button type="button" class="waves-effect waves-light tombol tombol-sm tombol-primary right" id="toggle-profil" style="position:relative;transition:.5s;right:0;"><i class="material-icons left">edit</i><span>Edit Profil</span></button>		                        	
			                    </div>			                
			            </div>
			            <div class="col s12 m12 l4 center-align hide-on-med-and-down show-on-large">
			            	<div class="hide-on-med-and-down show-on-large" style="margin-top: 12px"></div>
			            	<div class="box-foto-profil">
								<!-- preloader -->
								<div class="preloader-wrapper small active myloader hide" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
									<div class="spinner-layer spinner-yellow-only" style="border-color:white">
										<div class="circle-clipper left">
											<div class="circle"></div>
										</div><div class="gap-patch">
											<div class="circle"></div>
										</div><div class="circle-clipper right">
											<div class="circle"></div>
										</div>
									</div>
								</div>
								<p class="myloader-text hide" style="position:absolute; margin: auto; left:0; right:0; top:60%; color: #fff; z-index:10">Loading . . .</p>
			            		<img src="<?= base_url() ?>assets/images/profil/<?= $foto ?>" alt="default-img" class="responsive-img preview-image">			            		
			            		<img src="<?= base_url() ?>assets/images/profil/<?= $foto ?>" alt="default-img" class="responsive-img hide hidden-image">			            		
								<button type="button" class="waves-effect waves-light tombol tombol-sm tombol-warning btn-upload btn-upload-profil"><i class="material-icons left">file_upload</i></button>	            	
								<span class="tooltiped yellow-text text-lighten-2" style="position:absolute;bottom:4px;left:60px;cursor:pointer" data-position="bottom" data-tooltip="Max ukuran 2MB (1080x1080)"><i class="material-icons">info_outline</i></span>
			            	</div>
							<div class="hide confirm-profil">							
								<button type="button" data-foto="<?=base_url()?>assets/images/profil/<?=$foto?>" class="waves-effect transparent tombol tombol-flat red-text cancel-profil"><i class="material-icons left">close</i></button>	
								<button type="button" class="waves-effect transparent tombol tombol-flat green-text ok-profil"><i class="material-icons left">check</i></button>	
							</div>
							<br>
			            </div>			            			                       
			        </div>			        
      				</form>       
					<div class="row">
						<form method="post" enctype="multipart/form-data" id="upload-form">
							<div class="input-field">
								<input type="hidden" id="foto-nama" name="foto_nama" value="<?= $foto ?>">	  
								<input type="file" class="file-upload" hidden="hidden" name="foto" value="<?= $foto ?>">	  
							</div>
						</form>
					</div>
                </div>
            </div>            
    </div>
</div>