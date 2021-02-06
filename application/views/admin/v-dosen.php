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

    tbody {
	counter-reset: rowNumber;
    }

    tbody tr {
        counter-increment: rowNumber;
    }

    tbody tr td:first-child::before {
        content: counter(rowNumber);
    }
</style>
<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Dosen</a>                    
            </div>
        </div>     
        <div class="row">
            <div class="col s12">
                <div class="main-panel">                    
                    <div class="row">
                        <div class="col s12">                                                    
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Dosen" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>                               
                            <button class="tombol tombol-sm tombol-primary modal-trigger" data-target="modalTambah"><i class="material-icons left">add</i><span>Tambah Dosen</span></button>
                            <!-- Table data  -->
                            <div class="table-wrapper">                                                           
                                <table class="highlight datatable-nopage">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3" width="30">#</th>
                                            <th width="80"></th>
                                            <th width="200">Nama</th>
                                            <th>Jabatan</th>
                                            <th>NIDN</th>
                                            <th>Email</th>
                                            <th class="center">Status Mengajar</th>
                                            <th class="center" width="160">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($dosen->result() as $key => $value) { ?>
                                            <?php 
                                                $cek_dosen = $this->M_database->cekdata('t_jadwal', 'dosen_id', $value->dosen_id);

                                                $title = 'hapus';
                                                $modal = 'modalHapus';
                                                if ($cek_dosen) {
                                                     $title = 'tidak bisa hapus';
                                                     $modal = 'modalAlert';
                                                }

                                                $status = '';
                                                if ($value->dosen_status == 0){ 
                                                    $status = '<span class="badge-status badge-not">Tidak aktif</span>';
                                                } else { 
                                                    $status = '<span class="badge-status badge-ok">Aktif</span>';
                                                }
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td><img src="<?= base_url() ?>assets/images/profil/<?= $value->foto ?>" alt="profil foto" class="foto-profil-small"></td>
                                                <td><?= $value->dosen_nama ?></td>
                                                <td><?= $value->jabatan_nama ?></td>
                                                <td><?= $value->dosen_nidn ?></td>
                                                <td><?= $value->email ?></td>
                                                <td class="status-dosen td-data center" id="<?= $value->dosen_id ?>" data-nidn="<?=$value->dosen_nidn?>"><?= $status ?></td>
                                                <td class="center">                                                                                                        
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-password-dosen" data-target="modalPassword"
                                                        data-dosen_id="<?= $value->dosen_id ?>"
                                                        data-dosen_nama="<?= $value->dosen_nama ?>"
                                                        data-foto="<?= $value->foto ?>"><i class="material-icons-outlined grey-text">lock_open</i>
                                                    </button>                                                    
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-lihat-dosen" data-target="modalEdit"
                                                        data-dosen_id="<?= $value->dosen_id ?>"
                                                        data-dosen_nama="<?= $value->dosen_nama ?>"
                                                        data-jabatan_nama="<?= $value->jabatan_nama ?>"
                                                        data-jabatan_id="<?= $value->jabatan_id ?>"
                                                        data-nidn="<?= $value->dosen_nidn ?>"
                                                        data-email="<?= $value->email ?>"><i class="material-icons-outlined amber-text">edit</i>
                                                    </button>
                                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-dosen" data-target="<?= $modal ?>"
                                                        data-dosen_id="<?= $value->dosen_id ?>"
                                                        data-dosen_nama="<?= $value->dosen_nama ?>"
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

<!-- Modal hapus dosen -->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Dosen</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus dosen <b><span class="txt-dosen"></span></b> ?</div>
            </div>
        </div>
        <form action="<?= base_url() ?>admin/dosen/hapusdosen" method="post">
            <input type="hidden" class="dosen_id" name="dosen_id">
            <input type="text" hidden class="dosen_nama" name="dosen_nama" autocomplete="off">
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

<!-- Modal password -->
<div id="modalPassword" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Reset Password</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-dark">Reset password dosen <b><span class="txt-dosen"></span></b> ?</div>        
            </div>
        </div>
        <form action="<?= base_url() ?>admin/dosen/resetPassword" method="post">
            <input type="hidden" class="dosen_id" name="dosen_id">   
            <input type="hidden" class="dosen_nama" name="dosen_nama">   
    </div>    
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-dark right"><i class="material-icons left">autorenew</i><span>Reset</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal tambah dosen -->
<div id="modalTambah" class="modal modal-select2">
    <div class="modal-head">
        <span class="modal-title">Tambah Dosen</span>        
    </div>                                                
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/dosen/tambahdosen" method="post" class="form-dosen">
            <div class="row">
                <div class="col s12 m12 l10 offset-l1">
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">beenhere</i>
                        <input id="add-nidn" type="text" name="nidn" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="add-nidn" style="margin-left: 46px; width:fit-content; background-color:#fafafa">NIDN</label>
                    </div>                    
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">payment</i>
                        <input id="add-nama" type="text"  name="dosen_nama" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="add-nama" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Nama</label>
                    </div>
                    <!-- Foto -->
                    <div class="input-field col s12" style="margin:0 1rem; position:relative;bottom:4px;height:50px">
                        <input id="dosen_foto" class="hide" type="text" name="dosen_foto" required>
                        <img class="foto-dosen" src="<?=base_url()?>assets/images/profil/default-profil-l.jpg" alt="laki-laki" style="cursor:pointer;max-width:50px; transition: .5s; margin-right:10px; margin-left: 40px; background-color: lightskyblue; border-radius:5px" data-foto="default-profil-l.jpg">
                        <img class="foto-dosen" src="<?=base_url()?>assets/images/profil/default-profil-p.jpg" alt="perempuan" style="cursor:pointer;max-width: 50px; transition: .5s; background-color: lightpink;border-radius:5px" data-foto="default-profil-p.jpg">
                    </div>                    
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">event_seat</i>
                            <select class="select2" name="jabatan_id" id="jabatan_id" required>
                                <option>PILIH JABATAN</option>                           
                                <?php 
                                foreach ($jabatan->result() as $key => $value): ?>
                                <option value="<?= $value->jabatan_id ?>"><?= $value->jabatan_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>                                                                                 
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">email</i>
                        <input id="add-email" type="text"  name="email" required value="example@domain.com" autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="add-email" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Email</label>
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

<!-- Modal edit dosen -->
<div id="modalEdit" class="modal modal-select2">
    <div class="modal-head">
        <span class="modal-title">Edit dosen</span>        
    </div>                                                
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/dosen/editdosen" method="post" id="form-edit-dosen">
            <div class="row">
                <div class="col s12 m12 l10 offset-l1">
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">beenhere</i>
                        <input id="dosen_id" type="text" hidden class="dosen_id" name="dosen_id" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <input type="text" class="nidn" name="nidn" required autocomplete="off" hidden>
                        <input placeholder="" id="nidn_baru" type="text" class="nidn" name="nidn_baru" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="nidn_baru" style="margin-left: 46px; width:fit-content; background-color:#fafafa">NIDN</label>
                    </div>                    
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">payment</i>
                        <input placeholder="" id="dosen_nama" type="text" class="dosen_nama" name="dosen_nama" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="dosen_nama" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Nama</label>
                    </div>                    
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">event_seat</i>
                            <select class="select2 jabatan_id" name="jabatan_id" required>
                                <option>PILIH JABATAN</option>                           
                                <?php 
                                foreach ($jabatan->result() as $key => $value): ?>
                                <option value="<?= $value->jabatan_id ?>"><?= $value->jabatan_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>                                                                                 
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">email</i>
                        <input placeholder="" id="email" type="text" class=" email" name="email" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="email" style="margin-left: 46px; width:fit-content; background-color:#fafafa">Email</label>
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