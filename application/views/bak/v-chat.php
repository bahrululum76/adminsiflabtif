<div class="main-wrapper">
    <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 34px; position: relative; top: 4px">Pesan</span>
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
                <a href="<?=base_url()?>dosen/profil"><i class="material-icons left">face</i>Edit Profil</a>
                <a href="<?=base_url()?>dosen/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>                   
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
            <span class="panel-title-2" style="margin: 8px 0 12px;">DM Admin Lab</span>                 
            <?php 
            foreach ($admin->result() as $key => $value) {
                $dmAsisten = pesanPribadi($user->asisten_id, $value->asisten_id);                                                   
                ?>                
                <div class="chat-user-box row valign-wrapper chat-private" id="asisten_<?=$value->asisten_id?>">
                    <div>
                        <img src="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>" class="circle" alt="user" width="50">
                    </div>
                    <div>
                        <span class="chat-user-name" data-u_token="<?=$value->username?>" data-kanal="-" data-jabatan="<?=$value->jabatan_nama?>" data-penerima="asisten_<?=$value->asisten_id?>" data-foto="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>"><?= namaPendek($value->asisten_nama); ?></span>
                        <span class="chat-last-message truncate"><span class="left read_asisten_<?=$value->asisten_id?>"><?= $dmAsisten['isRead'] ?></span><span <?= $dmAsisten['styleText'] ?> class="last-message last_asisten_<?=$value->asisten_id?>"><?= $dmAsisten['pesanText'] ?></span><span class="jumlah-pesan jml_asisten_<?=$value->asisten_id?> <?= $dmAsisten['pesanStatus']?> "><?=$dmAsisten['unRead']?></span></span>
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
                    <input type="text" hidden class="pengirim" value="<?= $user->asisten_nama ?>">
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