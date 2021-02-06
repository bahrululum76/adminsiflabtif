<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Master</a>                    
                <a class="breadcrumb">Jam</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel main-panel">                    
                    <div class="row">
                        <div class="col s12">
                            <!-- btn tambah -->
                            <button class="tombol tombol-sm tombol-primary modal-trigger left" data-target="modalTambah"><i class="material-icons-outlined left">add</i><span>Tambah Jam</span></button>
                            <!-- Searchbar -->
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Jam" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>
                            <!-- Table data  -->
                            <div class="col s12 table-wrapper">
                            <br>                                
                                <table class="highlight datatable">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>
                                            <th>Nama</th>
                                            <th>Jam</th>
                                            <th class="center">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($jam->result() as $key => $value) { ?>                                     
                                            <tr>
                                                <td><?= $key+1 ?></td>
                                                <td>Jam ke - <?= $key+1 ?></td>
                                                <td><?= $value->jam ?></td>
                                                <td class="center">
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-edit-jam" data-target="modalEdit"
                                                        data-jam_id="<?= $value->jam_id ?>"
                                                        data-jam_nama="<?= $key+1 ?>"
                                                        data-jam_mulai="<?= $value->jam ?>"
                                                        data-jam_selesai="<?= $value->jam ?>"><i class="material-icons-outlined amber-text">edit</i>
                                                    </button>
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-hapus-jam" data-target="modalHapus"
                                                        data-jam_id="<?= $value->jam_id ?>"
                                                        data-jam_nama="<?= $key+1 ?>"
                                                        data-jam_lengkap="<?= $value->jam ?>"
                                                        data-jam_selesai="<?= $value->jam ?>"
                                                        data-jam_mulai="<?= $value->jam ?>"><i class="material-icons red-text text-lighten-1">delete</i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Modal tambah jam -->
<div id="modalTambah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Tambah Jam</span></div>
    <div class="modal-content-sm custom-modal-content">         
        <form class="col s12" action="<?= base_url() ?>admin/Jam/tambahJam" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="jam_mulai" type="text" class=" timepicker" name="jam_mulai" autocomplete="off" readonly>
                    <label for="jam_mulai">Jam Mulai</label>
                </div>
                <div class="input-field col s12">
                    <input id="jam_selesai" type="text" class=" timepicker" name="jam_selesai" autocomplete="off" readonly>
                    <label for="jam_selesai">Jam Selesai</label>
                </div>                             
            </div>        
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit jam-->
<div id="modalEdit" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Edit Jam</span></div>
    <div class="modal-content-sm custom-modal-content">         
        <form class="col s12" action="<?= base_url() ?>admin/Jam/editJam" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="jam_id" type="hidden" class=" jam_id" name="jam_id" readonly>
                    <input placeholder="" id="jam_nama" type="text" class=" jam_nama" name="jam_nama" readonly>
                    <label for="jam_nama">Nama</label>  	
                </div>
                <div class="input-field col s12">
                    <input id="jam_mulai" type="text" placeholder="" class=" timepicker jam_mulai" name="jam_mulai" autocomplete="off">
                    <label for="jam_mulai">Jam Mulai</label>
                </div>
                <div class="input-field col s12">
                    <input id="jam_selesai" type="text" placeholder="" class=" timepicker jam_selesai" name="jam_selesai" autocomplete="off">
                    <label for="jam_selesai">Jam Selesai</label>
                </div>                                        
            </div>                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus kelas-->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Jam</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel panel-danger">Apakah anda yakin ingin menghapus jam <b><span class="txt-jam"></span></b> ?</div>
        <form class="col s12" action="<?= base_url() ?>admin/Jam/hapusJam" method="post">
            <input id="jam_id" type="hidden" class=" jam_id" name="jam_id">
            <input type="text" hidden class="jam_lengkap" name="jam_lengkap" autocomplete="off">
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-light btn-flat">Batal</button>
      <button type="submit" class="waves-effect waves-light btn red">Hapus</button>
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