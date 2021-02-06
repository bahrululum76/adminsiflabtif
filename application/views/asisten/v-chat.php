<?php 
    $grupKoorlab = pesanGrup('koorlab', $user->asisten_id);
    $grupAsisten = pesanGrup('asisten', $user->asisten_id);                        
?>
              
<div class="main-wrapper">
    <div class="top-panel right-align">
        <span class="panel-title-1 left" style="margin-left: 34px; position: relative; top: 4px">Pesan</span>
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
            <?= namaPendek($user->asisten_nama) ?>
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
    <div class="row" style="padding: 0 8px">
        <div class="chat-contact">
            <a id="dm-asisten" class="hide" href=""></a>
            <span class="panel-title-2" style="margin: 8px 0 12px">Public Channels</span>
            <!-- GRUP KOORLAB -->
            <div class="chat-user-box chat-public valign-wrapper row" id="koorlab">
                <div>
                    <img src="<?= base_url() ?>assets/images/icons/icon-96x96.png" alt="user" width="50">
                </div>
                <div>
                    <span class="chat-user-name" data-u_token="koorlab" data-kanal="koorlab" data-jabatan="Grup" data-penerima="koorlab" data-foto="<?= base_url() ?>assets/images/icons/icon-96x96.png">Koordinator Lab</span>
                    <span class="chat-last-message truncate"><span <?=$grupKoorlab['styleText']?> class="last-message last_koorlab"><?=$grupKoorlab['pesanText']?></span><span class="jumlah-pesan jml_koorlab <?=$grupKoorlab['pesanStatus']?>"><?=$grupKoorlab['unRead']?></span></span>
                </div>                    
            </div>
            
            <!-- GRUP ASISTEN -->
            <div class="chat-user-box chat-public valign-wrapper row" id="asisten">
                <div>
                    <img src="<?= base_url() ?>assets/images/icons/icon-96x96.png" alt="user" width="50">
                </div>
                <div>
                    <span class="chat-user-name" data-u_token="asisten" data-kanal="asisten" data-jabatan="Grup" data-penerima="asisten" data-foto="<?= base_url() ?>assets/images/icons/icon-96x96.png">ASLABTIF</span>
                    <span class="chat-last-message truncate"><span <?=$grupAsisten['styleText']?> class="last-message last_asisten"><?=$grupAsisten['pesanText']?></span><span class="jumlah-pesan jml_asisten <?=$grupAsisten['pesanStatus']?>"><?=$grupAsisten['unRead']?></span></span>
                </div>                    
            </div>

            <!-- DM BAK -->
            <?php if ($user->jabatan_id == 6 && $bak->row()) { ?>
                <span class="panel-title-2" style="margin: 28px 0 12px;">DM BAK</span>                 
            <?php
            foreach ($bak->result() as $key => $value) {                    
                $dmAsisten = pesanPribadi($user->asisten_id, $value->asisten_id);                                   
            ?>
                <div class="chat-user-box row valign-wrapper chat-private" id="asisten_<?=$value->asisten_id?>">
                    <div>
                        <img src="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>" class="circle" alt="user" width="50">
                    </div>
                    <div>
                        <span class="chat-user-name" data-kanal="-" data-jabatan="<?=$value->jabatan_nama?>" data-penerima="asisten_<?=$value->asisten_id?>" data-foto="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>"><?= $value->asisten_nama ?></span>
                        <span class="chat-last-message truncate"><span class="left read_asisten_<?=$value->asisten_id?>"><?= $dmAsisten['isRead'] ?></span><span <?= $dmAsisten['styleText'] ?> class="last-message last_asisten_<?=$value->asisten_id?>"><?= $dmAsisten['pesanText'] ?></span><span class="jumlah-pesan jml_asisten_<?=$value->asisten_id?> <?= $dmAsisten['pesanStatus']?> "><?=$dmAsisten['unRead']?></span></span>
                    </div>                    
                </div>
            <?php } } ?>

            <!-- DM ASISTEN -->
            <span class="panel-title-2" style="margin: 28px 0 12px;">DM Asisten</span>                
            <?php 
            foreach ($asisten->result() as $key => $value) {                                                            
                $dmAsisten = pesanPribadi($user->asisten_id, $value->asisten_id);                                   
            ?>                
                <div class="chat-user-box row valign-wrapper chat-private" id="asisten_<?=$value->asisten_id?>">
                    <div>
                        <img src="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>" class="circle" alt="user" width="50">
                    </div>
                    <div>
                        <span class="chat-user-name" data-kanal="-" data-jabatan="<?=$value->jabatan_nama?>" data-penerima="asisten_<?=$value->asisten_id?>" data-foto="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>"><?= namaPendek($value->asisten_nama) ?></span>
                        <span class="chat-last-message truncate"><span class="left read_asisten_<?=$value->asisten_id?>"><?= $dmAsisten['isRead'] ?></span><span <?= $dmAsisten['styleText'] ?> class="last-message last_asisten_<?=$value->asisten_id?>"><?= $dmAsisten['pesanText'] ?></span><span class="jumlah-pesan jml_asisten_<?=$value->asisten_id?> <?= $dmAsisten['pesanStatus']?> "><?=$dmAsisten['unRead']?></span></span>
                    </div>                    
                </div>
            <?php } ?>

            <!-- DM DOSEN -->
            <span class="panel-title-2" style="margin: 42px 0 12px">DM Dosen</span>
            <?php 
            foreach ($dosen->result() as $key => $value) {
                $dmDosen = pesanPribadiDosen($user->asisten_id, $value->dosen_id);                                                                                 
                ?>                
                <div class="chat-user-box row valign-wrapper" id="dosen_<?=$value->dosen_id?>">
                    <div>
                        <img src="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>" class="circle" alt="user" width="50">
                    </div>
                    <div>
                        <span class="chat-user-name" data-kanal="-" data-jabatan="<?=$value->jabatan_nama?>" data-penerima="dosen_<?=$value->dosen_id?>" data-foto="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>"><?= $value->dosen_nama ?></span>
                        <span class="chat-last-message truncate"><span class="left"><?= $dmDosen['isRead'] ?></span><span <?= $dmDosen['styleText'] ?> class="last-message last_dosen_<?=$value->dosen_id?>"><?= $dmDosen['pesanText'] ?></span><span class="jumlah-pesan jml_dosen_<?=$value->dosen_id?> <?= $dmDosen['pesanStatus'] ?>"><?= $dmDosen['unRead'] ?></span></span>
                    </div>                    
                </div>
            <?php } ?> 
        </div>

        <!-- CHAT PANEL -->
        <div class="chat-panel">
            <div class="chat-box">
                <div class="chat-head valign-wrapper white">
                <span class="back-chat"><i class="material-icons">arrow_back</i></span>
                    <img src="<?= base_url() ?>assets/images/profil/default-profil-l.jpg" class="circle hide" id="user-foto" alt="" width="50">
                    <div>                            
                        <span class="chat-user-name" id="chat-with"></span>                            
                        <span class="chat-last-message" id="user-jabatan"></span>
                    </div>
                </div>
                <div class="chat-start">
                        <button class="tombol tombol-info" style="height:50px; position:absolute; top:0; bottom:0; left:0; right:0; margin: auto"><i class="material-icons left">alternate_email</i><span>Pilih kontak untuk memulai percakapan</span></button>
                </div>
                <div class="chat-input">
                    <div class="emoji-container">
                        <div class="emoji-head">EMOJI</div>
                        <div class="emoji-hand"></div>
                        <div class="emoji-smiley"></div>
                    </div>
                    <i class="material-icons btn-emoji">insert_emoticon</i>
                    <textarea type="text" class="chat-input-text materialize-textarea" spellcheck="false" placeholder="Ketik pesan" style="box-shadow: 0 0 20px -15px;border:none; border-radius:25px;margin:0 8px 0 0; max-height: 140px; text-indent:0; padding: .8rem 1.2rem .8rem 3.1rem; overflow-y: auto; background-color:#fff; height: 45px"></textarea>
                    <input type="text" hidden class="kanal" value="">
                    <input type="text" hidden class="penerima" value="">
                    <input type="text" hidden class="pengirim" value="<?= $this->session->userdata('nama') ?>">
                    <input type="text" hidden class="u_token" value="">
                    <div class="valign-wrapper send-loader hide" style="margin-left:7px;margin-right:6px">
                        <div class="preloader-wrapper small active">
                            <div class="spinner-layer spinner-blue-only">
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
                    <button class="transparent chat-btn"><i class="material-icons">send</i></button>
                </div>
            </div>
        </div>

    </div>
</div>