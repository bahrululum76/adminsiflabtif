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
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Asisten</a>                    
            </div>
        </div>     
        <div class="row">
            <div class="col s12">
                <div class="main-panel">                    
                    <div class="row">
                        <div class="col s12">                            
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Asisten" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>                               
                            <button class="tombol tombol-sm tombol-primary modal-trigger" data-target="modalTambah"><i class="material-icons left">add</i><span>Tambah Asisten</span></button>
                            <!-- Table data  -->
                            <div class="table-wrapper">                                                           
                                <table class="highlight datatable-nopage">                                    
                                    <thead>
                                        <tr>
                                            <th width="50px" class="blue-text text-accent-3">#</th>
                                            <th width="80px"></th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th class="center">Status Mengajar</th>
                                            <th class="center" width="200">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($asisten->result() as $key => $value) { ?>
                                            <?php 
                                                $asisten_1 = $this->M_database->cekdata('t_jadwal', 'asisten_1', $value->asisten_id);
                                                $asisten_2 = $this->M_database->cekdata('t_jadwal', 'asisten_2', $value->asisten_id);

                                                $title = 'hapus';
                                                $modal = 'modalHapus';
                                                $tgl_view = date_create($value->tgl_lahir);

                                                if ($asisten_1 != 0 || $asisten_2 != 0) {
                                                     $title = 'tidak bisa hapus';
                                                     $modal = 'modalAlert';
                                                }

                                                $status = '';
                                                if ($value->status == 0){ 
                                                    $status = '<span class="badge-status badge-not">Tidak aktif</span>';
                                                } else { 
                                                    $status = '<span class="badge-status badge-ok">Aktif</span>';
                                                }                                               
                                            ?>
                                            <tr>
                                                <td><?=$key+1?></td>
                                                <td><img src="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>" alt="profil foto" class="foto-profil-small"></td>
                                                <td><?= $value->asisten_nama ?></td>
                                                <td><?= $value->jabatan_nama ?></td>
                                                <td class="status-asisten td-data center" id="<?= $value->asisten_id ?>" data-username="<?=$value->username?>"><?= $status ?></td>
                                                <td class="center">                                                    
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-lihat-asisten" data-target="modalLihat"
                                                        data-asisten_id="<?= $value->asisten_id ?>"
                                                        data-asisten_nama="<?= $value->asisten_nama ?>"
                                                        data-jabatan_nama="<?= $value->jabatan_nama ?>"
                                                        data-username="<?= $value->username ?>"
                                                        data-tgl_lahir="<?= $value->tgl_lahir ?>"
                                                        data-alamat="<?= $value->alamat ?>"
                                                        data-email="<?= $value->email ?>"
                                                        data-foto="<?= base_url() ?>assets/images/profil/<?=$value->foto ?>"
                                                        data-tahun_masuk="<?= $value->thn_masuk ?>"
                                                        data-tahun_keluar="<?= $value->thn_keluar ?>"><i class="material-icons-outlined blue-text text-accent-1">remove_red_eye</i>
                                                    </button>
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-password-asisten" data-target="modalPassword"
                                                        data-asisten_id="<?= $value->asisten_id ?>"
                                                        data-asisten_nama="<?= $value->asisten_nama ?>"
                                                        data-tgl_lahir="<?= $value->tgl_lahir ?>"
                                                        data-foto="<?= $value->foto ?>"><i class="material-icons-outlined grey-text">lock_open</i>
                                                    </button>                                                    
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-lihat-asisten" data-target="modalEdit"
                                                        data-asisten_id="<?= $value->asisten_id ?>"
                                                        data-asisten_nama="<?= $value->asisten_nama ?>"
                                                        data-jabatan_nama="<?= $value->jabatan_nama ?>"
                                                        data-jabatan_id="<?= $value->jabatan_id ?>"
                                                        data-username="<?= $value->username ?>"
                                                        data-tgl_lahir="<?= $value->tgl_lahir ?>"
                                                        data-alamat="<?= $value->alamat ?>"
                                                        data-email="<?= $value->email ?>"
                                                        data-tahun_masuk="<?= $value->thn_masuk ?>"
                                                        data-tahun_keluar="<?= $value->thn_keluar ?>"><i class="material-icons-outlined amber-text">edit</i>
                                                    </button>
                                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-asisten" data-target="<?= $modal ?>"
                                                        data-asisten_id="<?= $value->asisten_id ?>"
                                                        data-asisten_nama="<?= $value->asisten_nama ?>"
                                                        data-tgl_lahir="<?= $value->tgl_lahir ?>"
                                                        data-foto_nama="<?= $value->foto ?>"><i class="material-icons red-text text-lighten-1 ">delete</i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <br>
                                <div class="right">
                                    <?= $pagination ?>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Modal hapus -->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Asisten</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus data asisten <b><span class="txt-nama"></span></b> ?</div>        
            </div>
        </div>
        <form  action="<?= base_url() ?>admin/Asisten/hapusAsisten" method="post">
            <input type="hidden" class="asisten_id" name="asisten_id">
            <input type="hidden" class="asisten_nama" name="asisten_nama">   
            <input type="hidden" class="foto-lama" name="foto">   
    </div>    
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal alert hapus -->
<div id="modalAlert" class="modal modal-sm">
    <div class="modal-content center-align">
        <i class="material-icons large red-text text-darken-1 center-align">warning</i>
        <p class="modal-title-alert">Oops!</p>
        <p>Data tidak dapat dihapus karena sudah digunakan pada menu sebelumnya.</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-light tombol tombol-sm tombol-danger">X</button>
    </div>
</div>

<!-- Modal lihat asisten -->
<div id="modalLihat" class="modal modal-card-id">
    <div class="modal-content">
        <div class="card-bg">
            <div class="row">
                <div class="card-foto-wrapper">
                    <img class="foto-modal circle" src="" alt="foto-profil">
                    <div class="card-foto-logo-wrapper">
                        <img src="<?= base_url() ?>assets/images/icons/icon-96x96.png" alt="logo" class="card-foto-logo">
                        <span class="card-foto-logo-name">ASLABTIF</span>
                    </div>
                    <div class="card-name">
                        <span class="asisten-nama"></span>                
                        <span class="jabatan-nama"></span>
                    </div>               
                </div>                
            </div>            
        </div>
        <div class="row">                        
            <div class="card-id-item-wrapper">
                <div class="col s6 m4 l3">
                    <span class="title-id">Username</span>                    
                    <span class="isi-id txt-username"></span>                                                            
                    <span class="title-id">Tanggal Lahir</span>                    
                    <span class="isi-id tgl-lahir"></span>                    
                </div>
                <div class="col s6 m4 l3 card-item">                                                            
                    <span class="title-id">Tahun Masuk</span>                    
                    <span class="isi-id tahun-masuk"></span>                                                            
                    <span class="title-id">Tahun Keluar</span>                    
                    <span class="isi-id tahun-keluar"></span>                    
                </div> 
                <div class="col s12 m4 l6 card-item">                    
                    <span class="title-id">Email</span>                    
                    <span class="isi-id txt-email"></span>                                        
                    <span class="title-id">Alamat</span>                    
                    <span class="isi-id txt-alamat"></span>                                       
                </div>                          
            </div>
        </div>
    </div>
</div>

<!-- Modal password -->
<div id="modalPassword" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Reset Password</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-dark">Reset password asisten <b><span class="txt-nama"></span></b> ?</div>        
            </div>
        </div>
        <form action="<?= base_url() ?>admin/asisten/resetPassword" method="post">
            <input type="hidden" class="asisten_id" name="asisten_id">   
            <input type="hidden" class="asisten_nama" name="asisten_nama">   
    </div>    
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-dark right"><i class="material-icons left">autorenew</i><span>Reset</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal tambah asisten -->
<div id="modalTambah" class="modal modal-select2">
    <div class="modal-head">
        <span class="modal-title">Tambah Asisten</span>        
    </div>                                                
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/asisten/tambahAsisten" method="post" class="form-asisten">
            <div class="row">
                <div class="col s12">
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">beenhere</i>
                        <input id="add-a" type="text" name="username" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="add-a" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Username</label>
                    </div>                    
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">payment</i>
                        <input id="add-b" type="text" name="asisten_nama" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="add-b" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Nama</label>
                    </div>                    
                    <!-- Foto -->
                    <div class="input-field col s12" style="margin:0 1rem; position:relative;bottom:4px;height:50px">
                        <input id="asisten_foto" class="hide" type="text" name="asisten_foto" required>
                        <img class="foto-asisten" src="<?=base_url()?>assets/images/profil/default-profil-l.jpg" alt="laki-laki" style="cursor:pointer;max-width:50px; transition: .5s; margin-right:10px; margin-left: 40px; background-color: lightskyblue; border-radius:5px" data-foto="default-profil-l.jpg">
                        <img class="foto-asisten" src="<?=base_url()?>assets/images/profil/default-profil-p.jpg" alt="perempuan" style="cursor:pointer;max-width: 50px; transition: .5s; background-color: lightpink;border-radius:5px" data-foto="default-profil-p.jpg">
                    </div>                    
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">event_seat</i>
                            <select class="select2" name="jabatan_id" required>
                                <option value="">PILIH JABATAN</option>                           
                                <?php 
                                foreach ($jabatan->result() as $key => $value): ?>
                                <option value="<?= $value->jabatan_id ?>"><?= $value->jabatan_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>                    
                    <div class="input-field col s12 m4 l4">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">event_note</i>
                        <input id="add-c" type="text" name="tgl_lahir" required autocomplete="off" style="float:left;margin-left: 40px; background-color: #fafafa">
                        <label for="add-c" style="margin-left: 50px; width:fit-content; background-color:#fafafa;">Tanggal Lahir</label>
                    </div>
                    <div class="input-field col s11 offset-s1 m4 l5">                        
                        <span class="select2-wrapper" style="width: 100%;padding-left:2px">
                            <select class="select2" name="bulan_lahir" required>
                                <option value="">Bulan</option>                           
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
                        <input id="add-d" type="text" name="tahun_lahir" required autocomplete="off" style="background-color: #fafafa">
                        <label for="add-d" style="width:fit-content; background-color:#fafafa">Tahun</label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">maps</i>
                        <input id="add-f" type="text" name="alamat" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="add-f" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Alamat</label>
                    </div>                     
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">email</i>
                        <input id="add-e" type="text" name="email" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="add-e" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Email</label>
                    </div>
                    <div class="input-field col s12">
                        <div class="input-field col s12 m6 l6" style="margin:0">
                            <i class="material-icons-outlined prefix">sync_alt</i>
                            <input type="text" id="add_tahun_masuk" name="tahun_masuk" required autocomplete="off" value="<?= date('Y') ?>" style="float:left;margin-left: 40px; background-color: #fafafa">
                            <label for="add_tahun_masuk" style="margin-left: 48px; width:fit-content; background-color:#fafafa;">Tahun Masuk</label>
                        </div>                        
                        <div class="input-field col s12 m6 l6" style="margin:0">
                            <input type="text" id="add_tahun_keluar" name="tahun_keluar" value="" required autocomplete="off" style="background-color: #fafafa">
                            <label for="add_tahun_keluar">Tahun Keluar</label>                                                   
                        </div>
                    </div>
                </div>                                               	
            </div>                    			           		           			            
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit asisten -->
<div id="modalEdit" class="modal modal-select2">
    <div class="modal-head">
        <span class="modal-title">Edit Asisten</span>        
    </div>                                                
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/asisten/editAsisten" method="post" id="form-edit-asisten">
            <div class="row">
                <div class="col s12">
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">beenhere</i>
                        <input id="asisten_id" type="text" hidden class=" asisten_id" name="asisten_id" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <input type="text" class="username" name="username" required hidden>
                        <input placeholder="" id="username" type="text" class="username" name="username_baru" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="username" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Username</label>
                    </div>                    
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">payment</i>
                        <input placeholder="" id="asisten_nama" type="text" class=" asisten_nama" name="asisten_nama" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="asisten_nama" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Nama</label>
                    </div>                    
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">event_seat</i>
                            <select class="select2 jabatan_id" name="jabatan_id" required>
                                <option value="">PILIH JABATAN</option>
                                <?php 
                                foreach ($jabatan->result() as $key => $value): ?>
                                <option value="<?= $value->jabatan_id ?>"><?= $value->jabatan_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>                    
                    <div class="input-field col s12 m4 l4"">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">event_note</i>
                        <input placeholder="" id="tgl_lahir" type="text" class=" tgl_lahir" name="tgl_lahir" required autocomplete="off" style="float:left;margin-left: 40px; background-color: #fafafa">
                        <label for="tgl_lahir" style="margin-left: 50px; width:fit-content; background-color:#fafafa;">Tanggal Lahir</label>
                    </div>
                    <div class="input-field col s11 offset-s1 m4 l5">                        
                        <span class="select2-wrapper" style="width:100%;padding-left:2px">
                            <select class="select2 bulan_lahir" name="bulan_lahir" required>
                                <option value="">Bulan</option>                           
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
                        <input placeholder="" id="tahun_lahir" type="text" class=" tahun_lahir" name="tahun_lahir" required autocomplete="off" style="background-color: #fafafa">
                        <label for="tahun_lahir" style="width:fit-content; background-color:#fafafa">Tahun</label>
                    </div>                                       
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">maps</i>
                        <input placeholder="" id="alamat" type="text" class=" alamat" name="alamat" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="alamat" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Alamat</label>
                    </div>                     
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">email</i>
                        <input placeholder="" id="email" type="text" class=" email" name="email" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="email" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Email</label>
                    </div>
                    <div class="input-field col s12">
                        <div class="input-field col s12 m6 l6" style="margin:0">
                            <i class="material-icons-outlined prefix">sync_alt</i>
                            <input id="tahun_masuk" type="text" class="tahun_masuk" name="tahun_masuk" required autocomplete="off" value="<?= date('Y') ?>" style="float:left;margin-left: 40px; background-color: #fafafa">
                            <label for="tahun_masuk" style="margin-left: 48px; width:fit-content; background-color:#fafafa;">Tahun Masuk</label>
                        </div>                        
                        <div class="input-field col s12 m6 l6" style="margin:0">
                            <input id="tahun_keluar" type="text" class="tahun_keluar" name="tahun_keluar" value="-"  required autocomplete="off" style="background-color: #fafafa">
                            <label for="tahun_keluar">Tahun Keluar</label>                                                   
                        </div>
                    </div>                                               	
                </div>                                               	
            </div>                    			           		           			            
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>