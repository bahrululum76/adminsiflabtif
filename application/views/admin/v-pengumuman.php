<style>
    .select2-results__option[aria-selected=true] {
        margin: 8px 0;
    }
    li.select2-selection__choice {
        list-style:none;
    }
    .select2-wrapper {
        box-shadow: none;
        border: 1px solid #cacaca;
        margin-bottom: 8px;
    }
    .select2-wrapper i {
        position:absolute;
        left: 10px;
        line-height:45px;
        color: #607d8b
    }      

    .input-field span.label {
        display: block;
        margin:4px 0;
    }       

    .btn-remove {
        position: absolute;
        background: rgba(0,0,0,0.4);
        border: none;
        border-radius: 50px;
        width: 30px;
        height: 30px;
        right: 0;
        padding: 3px 2px;
        color:white;
        cursor: pointer;
        display: none;
    }

    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
        position: relative;
        opacity: 1;
        pointer-events: none;
        top: 2px;
        margin-right: 8px;
    }    

   input[type=search]:not(.browser-default):disabled {
        border-bottom: none
    }
</style>

<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Info Praktikum</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="main-panel">
                    <div class="center-align">                
                        <button class="tombol waves-effect tombol-sm tombol-primary modal-trigger btn-buat-info" data-target="modalTambah" data-info_id="<?=$kode_info?>"><i class="material-icons-outlined left">add</i><span>Buat Info</span></button>
                        <button class="tombol waves-effect tombol-sm tombol-primary modal-trigger" data-target="modalNotifikasi1"><i class="material-icons left">campaign</i><span>Kirim Notifikasi</span></button>                                           
                    </div>                                                                                                        
                    <div class="card-info-wrapper">                    
                            <?php
                            foreach ($info->result() as $key => $value) { 
                                echo '<div class="card-info">';
                                $kelas_kode = ""; 
                                $image = $this->M_database->getWhere('t_info_image', null, null, array('info_id' => $value->info_id), 'info_image_id ASC');
                                $kelas_in = $this->M_database->getWhere('t_info_kelas', null, null, array('info_id' => $value->info_id), null);
                                if ($image->row()) {                                    
                                    echo '<div class="carousel carousel-slider" style="height:264px;overflow:unset">';
                                    foreach ($image->result() as $image) {
                                        echo '<div class="carousel-item">
                                                <img src="'.base_url().'assets/images/info/'.$image->info_image.'" height="264">
                                            </div>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<div class="no-image-box"></div>';                                   
                                }
                                foreach ($kelas_in->result() as $data_kelas) {
                                    $kelas_kode .= $data_kelas->kelas_kode.',';
                                }
                                
                                echo '<div class="left-align caption">'.$value->info_caption.'</div>';
                                echo '<div class="card-info-footer">
                                        <div class="left-align" style="font-size:12px;padding-left:8px;color:#9e9e9e">                                        
                                            <span>'.$value->date_created.'</span>
                                        </div>
                                        <div class="card-info-action">                                        
                                            <button class="waves-effect mybtn white modal-trigger get-image btn-kirim-notifikasi" data-target="modalNotifikasi"
                                                data-info_id="'.$value->info_id.'"
                                                data-kelas="'.$kelas_kode.'"
                                                data-info_caption="'.str_replace('"',"'",$value->info_caption).'"><i class="material-icons green-text text-lighten-1">campaign</i>
                                            </button>
                                            <button class="waves-effect mybtn white modal-trigger get-image btn-edit-info" data-target="modalEdit"
                                                data-info_id="'.$value->info_id.'"
                                                data-kelas="'.$kelas_kode.'"
                                                data-info_caption="'.str_replace('"',"'",$value->info_caption).'"><i class="material-icons-outlined amber-text">edit</i>
                                            </button>
                                            <button class="waves-effect white mybtn modal-trigger btn-hapus-info" data-target="modalHapus"
                                                data-info_id="'.$value->info_id.'"
                                                data-kelas="'.$kelas_kode.'"
                                                data-info_caption="'.str_replace('"',"'",$value->info_caption).'"><i class="material-icons red-text text-lighten-1">delete</i>
                                            </button>
                                        </div>                                    
                                    </div>';
                                echo '</div>';
                            } ?>                               
                    </div>                                                   
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- form upload -->
<form method="post" enctype="multipart/form-data" id="upload-form">    
    <input class="file-upload-info" hidden type="file" name="foto">
    <input hidden type="text" id="in-edit" value="0">
    <input hidden type="text" name="info_id" value="<?=$kode_info?>" id="info-id">
    <input hidden type="text" name="image_id" value="-" id="image-id">
    <input hidden type="text" name="image_edit" value="-" id="image-edit">
</form>

<!-- Modal buat info -->
<div id="modalTambah" class="modal modal-select2" style="max-width: 768px">
    <div class="modal-head"><span class="modal-title">Buat Info</span></div>
    <div class="modal-content custom-modal-content">
        <div class="row">
            <div class="col s12">                
                <div class="input-field col s12">
                    <img src="" alt="default-img" class="responsive-img hide" id="hidden-image">                        
                    <div class="img-preview" id="img-preview-tambah">
                    <?php foreach ($foto_info->result() as $key => $value) { ?>
                        <div class="img-wrapper" id="img-wrapper-<?=$value->info_image_id?>">
                            <!-- preloader -->
                            <div class="preloader-wrapper small active hide" id="myloader-<?=$value->info_image_id?>" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
                                <div class="spinner-layer spinner-teal-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div><div class="gap-patch">
                                        <div class="circle"></div>
                                    </div><div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>                                
                            <img src="<?=base_url()?>assets/images/info/<?=$value->info_image?>" alt="info-img" class="responsive-img img materialboxed" id="foto-info-<?=$value->info_image_id?>">
                            <div id="action-foto-<?=$value->info_image_id?>" style="position:absolute; bottom:0; width:100%">                                
                                <span class="material-icons red-text text-lighten-1 left delete-foto-info" data-gambar="<?=$value->info_image?>" data-id="<?=$value->info_image_id?>" id="hapus-<?=$value->info_image_id?>" style="cursor: pointer;" >close</span>
                                <span class="material-icons-outlined amber-text text-lighten-1 right edit-foto-info"  data-gambar="<?=$value->info_image?>" data-id="<?=$value->info_image_id?>" id="edit-<?=$value->info_image_id?>" style="cursor: pointer;">edit</span>
                            </div>
                        </div>                                                                                
                    <?php } ?>
                    </div>                                             
                    <div class="img-wrapper hide preview-wrapper">
                        <!-- preloader -->
                        <div class="preloader-wrapper small active myloader hide" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
                            <div class="spinner-layer spinner-teal-only">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>                           
                        <img src="<?=base_url()?>assets/images/add-tugas.png" alt="add-img" class="responsive-img img preview-image">
                    </div>                           			            	
                </div>
                <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-warning btn-upload-info" style="margin-left: 10px;"><i class="material-icons left">file_upload</i><span>Tambah Foto</span></button>
                <span class="tooltiped grey-text" style="margin-left:6px;position:relative;top:8px;cursor:pointer" data-position="bottom" data-tooltip="Max ukuran 2MB (1080x1080)"><i class="material-icons">info_outline</i></span>
            </div>    
        </div>
        <form action="<?= base_url() ?>admin/pengumuman/tambahInfo" method="post">
            <div class="row">
                <div class="col s12">                
                    <div class="input-field col s12">
                        <input type="text" name="info_id" value="<?=$kode_info?>" hidden>
                        <div class="label" style="margin-bottom:8px">Caption</div>                                                                                                   
                        <textarea name="info_caption" id="info_caption" class="materialize-textarea summernote-info" required style="text-indent:0; padding: 17px 16px 18px" spellcheck="false"></textarea>
                        <br>
                    </div>
                    <div class="input-field col s12" style="margin-top:4px">
                        <div style="margin-bottom:8px">Pilih Kelas :</div>                 
                        <span class="select2-wrapper" style="max-width: 1000px; border:1px solid #cacaca; box-shadow:none">
                            <select class="select2" name="kelas[]" required multiple="multiple">
                                <option value="all">SEMUA KELAS</option>                                                                            
                                <?php foreach ($kelas->result() as $key => $value): ?>
                                <option value="<?= $value->kelas_kode ?>"><?= $value->kelas_nama ?></option>
                                <?php endforeach ?>                           
                            </select>
                        </span>
                    </div>                    
                </div>
            </div>            
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit info -->
<div id="modalEdit" class="modal modal-select2" style="max-width: 768px">
    <div class="modal-head"><span class="modal-title">Edit Info</span></div>
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/pengumuman/editInfo" method="post">
            <div class="row">        
                <input type="text" class="info_id" hidden="hidden" name="info_id">
                <div class="input-field col s12">
                    <div class="img-preview" id="img-preview-edit">

                    </div>
                    <div class="img-wrapper hide preview-wrapper">
                        <!-- preloader -->
                        <div class="preloader-wrapper small active myloader hide" style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
                            <div class="spinner-layer spinner-teal-only">
                                <div class="circle-clipper left">
                                    <div class="circle"></div>
                                </div><div class="gap-patch">
                                    <div class="circle"></div>
                                </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>                           
                        <img src="<?=base_url()?>assets/images/add-tugas.png" alt="add-img" class="responsive-img img preview-image">
                    </div>
                </div>
                <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-warning btn-upload-info" style="margin-left: 10px;"><i class="material-icons left">file_upload</i><span>Tambah Foto</span></button>
                <span class="tooltiped grey-text" style="margin-left:6px;position:relative;top:8px;cursor:pointer" data-position="bottom" data-tooltip="Max ukuran 2MB (1080x1080)"><i class="material-icons">info_outline</i></span>
                <br>
                <br>                                                                   
                <div class="input-field col s12">
                    <div class="label" style="margin-bottom:8px">Caption</div>
                    <textarea name="info_caption" class="materialize-textarea info-caption summernote-info" placeholder="Caption" required style="text-indent:0; padding: 17px 16px 18px" spellcheck="false"></textarea>
                    <br>
                </div>
                <div class="input-field col s12" style="margin-top:4px">
                    <div style="margin-bottom:8px">Pilih Kelas :</div>                 
                    <span class="select2-wrapper" style="max-width: 1000px; border:1px solid #cacaca; box-shadow:none">
                        <select class="select2 kelas_kode" name="kelas[]" required multiple="multiple">
                            <option value="all">SEMUA KELAS</option>                                                                            
                            <?php foreach ($kelas->result() as $key => $value): ?>
                            <option value="<?= $value->kelas_kode ?>"><?= $value->kelas_nama ?></option>
                            <?php endforeach ?>                           
                        </select>
                    </span>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus info-->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Info</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus info yang dipilih?</div>
                <form class="col s12" action="<?= base_url() ?>admin/pengumuman/hapusInfo" method="post">
                    <input id="info_id" type="hidden" class="validate info_id" name="info_id">
                    <input type="text" class="foto-lama" hidden="hidden" name="info_image">
            </div>
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Kirim notifikasi -->
<div id="modalNotifikasi1" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Kirim Notifikasi</span>        
    </div>
    <div class="modal-content custom-modal-content">      
        <form class="form-notifikasi" action="<?= base_url() ?>admin/pengumuman/kirimNotifikasi" method="post">
            <div class="row">
                <div class="col s12">
                    <div class="input-field col s12">
                        <textarea name="pesan" class="materialize-textarea pesan-notifikasi" required style="text-indent:0; padding: 17px 16px 18px" spellcheck="false"></textarea>
                        <label for="judul">Pesan Notifikasi</label>
                    </div>               
                    <div class="input-field col s12" style="margin-top:4px">
                        <div style="margin-bottom:8px">Pilih Kelas :</div>                 
                        <span class="select2-wrapper" style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
                            <select class="select2" name="kelas[]" required multiple="multiple">
                                <option value="all">SEMUA KELAS</option>                                                                            
                                <?php foreach ($kelas->result() as $key => $value): ?>
                                <option value="<?= $value->kelas_kode ?>"><?= $value->kelas_nama ?></option>
                                <?php endforeach ?>                           
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12" style="margin-top:4px">
                        <span class="select2-wrapper" style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
                            <select class="select2" name="halaman_tujuan" required>                            
                                <option value="">PILIH HALAMAN</option>                                                                            
                                <option value="info-praktikum">Halaman Info</option>                           
                                <option value="auth">Halaman Beranda</option>                                                                            
                                <option value="tentang-lab">Halaman Tentang Labtif</option>                                                                            
                            </select>
                        </span>
                    </div>
                </div> 
            </div>
            <br>          
            <div class="center-align mymodal-loader hide">            
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
                <br>          
                <div id="counter-notif" style="margin-top: 8px;">Mengirim notifikasi<br>Harap tunggu ...</div>                    
            </div>                                                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">send</i><span>Kirim</span></button>
        <button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Kirim notifikasi -->
<div id="modalNotifikasi" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Kirim Notifikasi</span>        
    </div>
    <div class="modal-content custom-modal-content">      
        <form class="form-notifikasi" action="<?= base_url() ?>admin/pengumuman/kirimNotifikasi" method="post">
            <div class="row">
                <div class="col s12">
                    <div class="input-field col s12">
                        <textarea name="pesan" id="judul" class="materialize-textarea pesan-notifikasi" required style="text-indent:0; padding: 17px 16px 18px" spellcheck="false"></textarea>
                        <label for="judul">Pesan Notifikasi</label>
                    </div>               
                    <div class="input-field col s12" style="margin-top:4px">
                        <div style="margin-bottom:8px">Untuk Kelas :</div>                 
                        <span class="select2-wrapper" id="select2-disabled" style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
                            <select class="select2 kelas_kode" required multiple="multiple" disabled>
                                <option value="all">SEMUA KELAS</option>                                                                            
                                <?php foreach ($kelas->result() as $key => $value): ?>
                                <option value="<?= $value->kelas_kode ?>"><?= $value->kelas_nama ?></option>
                                <?php endforeach ?>                           
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12 hide" style="margin-top:4px">
                        <div style="margin-bottom:8px">Untuk Kelas :</div>                 
                        <span class="select2-wrapper" style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
                            <select class="select2 kelas_kode" name="kelas[]" required multiple="multiple">
                                <option value="all">SEMUA KELAS</option>                                                                            
                                <?php foreach ($kelas->result() as $key => $value): ?>
                                <option value="<?= $value->kelas_kode ?>"><?= $value->kelas_nama ?></option>
                                <?php endforeach ?>                           
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12" style="margin-top:4px">
                        <span class="select2-wrapper" style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
                            <select class="select2" name="halaman_tujuan" required>                            
                                <option value="">PILIH HALAMAN</option>                                                                            
                                <option value="info-praktikum">Halaman Info</option>                           
                                <option value="auth">Halaman Beranda</option>                                                                            
                                <option value="tentang-lab">Halaman Tentang Labtif</option>                                                                            
                            </select>
                        </span>
                    </div>                                                    
                </div>                 
            </div>    
            <div class="center-align mymodal-loader hide">            
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
                <br>          
                <div id="counter-notif" style="margin-top: 8px;">Mengirim notifikasi<br>Harap tunggu ...</div>                    
            </div>                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">send</i><span>Kirim</span></button>
        <button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal lihat info -->
<div id="modalLihat" class="modal modal-info-praktikum modal-fixed-footer">
    <div class="modal-content" style="padding: 0">
            <img src="<?= base_url() ?>assets/images/profil/profile1574333342-172009.png" alt="default-img" class="preview-image responsive-img">            
            <div class="input-field" style="margin: 0 20px">
            <br>                
                <p class="info-caption-p" style="font-size: 16px; white-space: pre-wrap;"></p>
            </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-light btn-flat">Tutup</button>
    </div>    
</div>

<!-- minigrid -->
 <script src="<?= base_url() ?>assets/library/minigrid/minigrid.min.js"></script>
 <script>
     function pinnedNote(){
        var grid;
        function init() {
            grid = new Minigrid({
            container: '.card-info-wrapper',
            item: '.card-info',
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