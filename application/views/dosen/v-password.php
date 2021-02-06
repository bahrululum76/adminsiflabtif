<?php
$user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();
?>

<div class="col s12">
    <div class="main-wrapper">
		<div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 34px; position: relative; top: 4px">Ganti Password</span>
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
                <div class="row">                    	
                    <div class="col s12 m12 l6 offset-l3">
                        <div class="row">
                            <div class="panel orange-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
                                <span>Login ulang diperlukan setelah berhasil mengganti password.</span>
                            </div>
                            <div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
                                <span>Password lama salah!</span>
                            </div> 							        
                            <div class="input-field col s12">
                                <input id="old_pass" type="password" name="old_pass" autocomplete="off">
                                <label for="old_pass" id="old_pass_lbl" style="width:fit-content; background-color:#ffffff">Password lama</label>
                                <span class="helper-text hide red-text" id="helper_old_pass">Password salah!</span>
                            </div>			                        
                            <div class="input-field col s12">
                                <input id="new_pass" type="password" class="" name="new_pass" autocomplete="off">
                                <label for="new_pass" style="width:fit-content; background-color:#ffffff">Password baru</label>
                            </div>
                            <div class="input-field col s12">
                                <input id="conf_new_pass" type="password" autocomplete="off">
                                <label for="conf_new_pass" style="width:fit-content; background-color:#ffffff">Konfirmasi password baru</label>
                                <span class="helper-text hide red-text" id="helper_conf_pass">Password tidak sesuai!</span>			                            
                            </div>
                            <div class="input-field col s12 center-align myloader hide">                                
                                <div class="preloader-wrapper small active">
                                    <div class="spinner-layer spinner-blue">
                                        <div class="circle-clipper left">
                                        <div class="circle"></div>
                                        </div><div class="gap-patch">
                                        <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                        </div>
                                    </div>
                                    <div class="spinner-layer spinner-red">
                                        <div class="circle-clipper left">
                                        <div class="circle"></div>
                                        </div><div class="gap-patch">
                                        <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                        </div>
                                    </div>
                                    <div class="spinner-layer spinner-yellow">
                                        <div class="circle-clipper left">
                                        <div class="circle"></div>
                                        </div><div class="gap-patch">
                                        <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                        </div>
                                    </div>
                                    <div class="spinner-layer spinner-green">
                                        <div class="circle-clipper left">
                                        <div class="circle"></div>
                                        </div><div class="gap-patch">
                                        <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                        </div>                            			                
                    </div>
                    <div class="col s12 m12 l12">                            
                    </div>			            
                    <div class="col s12 m12 l6 offset-l3">	
                        <button class="waves-effect waves-light tombol tombol-sm tombol-primary btn-password right"><i class="material-icons left">save</i><span>Ganti Password</span></button>	        	
                        <a href="<?=base_url()?>dosen/dashboard" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</a>
                    </div>			                       
                </div>			        
            </div>
        </div>            
    </div>
</div>