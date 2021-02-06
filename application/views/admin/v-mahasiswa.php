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
                <a class="breadcrumb">Mahasiswa</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="main-panel">                    
                    <div class="row">
                        <div class="col s12">
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Mahasiswa" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>                                                        
                            <button class="tombol tombol-sm tombol-primary modal-trigger left" data-target="modalTambah"><i class="material-icons left">add</i><span>Tambah Mahasiswa</span></button>
                            <!-- Table data  -->
                            <div class="table-wrapper">
                                <table class="datatable-serverside-mhs">
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>
                                            <th>NPM</th>
                                            <th width="250">Nama</th>
                                            <th>Kelas</th>
                                            <th class="center">Status</th>
                                            <th class="center" width="160">Aksi</th>
                                        </tr>
                                    </thead>                                                                       
                                </table>                           
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Modal tambah mahasiswa -->
<div id="modalTambah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Tambah Mahasiswa</span></div>
    <div class="modal-content custom-modal-content">        
        <form class="form-tambah-mahasiswa" action="<?= base_url() ?>admin/Mahasiswa/tambahMahasiswa" method="post">
            <div class="row">
                <div class="col s12">
                    <div class="input-field col s12">
                        <input type="text" id="add-npm" name="npm" autocomplete="off" required>
                        <label for="add-npm">NPM</label>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" id="add-nama" name="nama" autocomplete="off" required>
                        <label for="add-nama">Nama</label>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper" style="max-width:1000px; box-shadow: none; border: 1px solid #cacaca">
                            <select class="select2" placeholder="pilih" name="kelas_kode" id="kelas_kode" required>
                                <option value="">PILIH KELAS</option>
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
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit mahasiswa -->
<div id="modalEdit" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Edit Mahasiswa</span></div>
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/Mahasiswa/editMahasiswa" method="post" id="form-edit-mahasiswa">
            <div class="row">
                <div class="col s12">
                    <div class="input-field col s12">
                        <input id="mhs_id" type="hidden" class="mhs_id" name="mhs_id">
                        <input type="text" class="npm" name="npm" required hidden>
                        <input placeholder="" id="npm_baru" type="text" class="npm" name="npm_baru" required>
                        <label for="npm_baru">NPM</label>
                    </div>
                    <div class="input-field col s12">
                        <input placeholder="" id="edit-nama" type="text" class="nama" name="nama" required>
                        <label for="edit-nama">Nama</label>
                    </div>
                    <div class="input-field col s12">                   
                        <span class="select2-wrapper" style="max-width:1000px; box-shadow: none; border: 1px solid #cacaca">
                            <select class="select2 kelas_kode" placeholder="pilih" name="kelas_kode" required>
                                <option value="">PILIH KELAS</option>
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
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus -->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Mahasiswa</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus data mahasiswa <b><span class="txt-nama"></span></b> ?</div>        
            </div>
        </div>
        <form class="col s12" action="<?= base_url() ?>admin/Mahasiswa/hapusMahasiswa" method="post">
            <input type="hidden" class="mhs_id" name="mhs_id">   
            <input type="hidden" class="nama" name="nama">   
            <input type="hidden" class="npm" name="npm">   
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
                <div class="panel panel-dark">Reset password mahasiswa <b><span class="txt-nama"></span></b> ?</div>        
            </div>
        </div>
        <form action="<?= base_url() ?>admin/mahasiswa/resetPassword" method="post">
            <input type="hidden" class="mhs_id" name="mhs_id">   
            <input type="hidden" class="nama" name="nama">   
    </div>    
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-dark right"><i class="material-icons left">autorenew</i><span>Reset</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>