<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Master</a>                    
                <a class="breadcrumb">Jabatan</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel main-panel">                    
                    <div class="row">
                        <div class="col s12">
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Jabatan" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>                                                        
                            <button class="tombol tombol-sm tombol-primary modal-trigger left" data-target="modalTambah"><i class="material-icons-outlined left">add</i><span>Tambah Jabatan</span></button>
                            <!-- Table data  -->
                            <div class="table-wrapper">
                            <br>                                
                                <table class="highlight datatable">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>
                                            <th>Jabatan</th>
                                            <th>Honor/Pertemuan</th>
                                            <th>Honor/Bulan</th>
                                            <th class="center">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($jabatan->result() as $key => $value) { ?>
                                            <?php 
                                                $jadwal = $this->M_database->cekdata('t_asisten', 'jabatan_id', $value->jabatan_id);

                                                $title = 'hapus';
                                                $modal = 'modalHapus';

                                                if ($jadwal != 0) {
                                                     $title = 'tidak bisa hapus';
                                                     $modal = 'modalAlert';
                                                }
                                            ?>
                                            <tr>
                                                <td><?= $key+1 ?></td>
                                                <td><?= $value->jabatan_nama ?></td>
                                                <td><?= "Rp. ". number_format($value->honor_pertemuan, 0, '.', '.') ?></td>
                                                <td><?= "Rp. ". number_format($value->honor_perbulan, 0, '.', '.') ?></td>
                                                <td class="center">
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-edit-jabatan" data-target="modalEdit"
                                                        data-jabatan_id="<?= $value->jabatan_id ?>"
                                                        data-honor_pertemuan="<?= $value->honor_pertemuan ?>"
                                                        data-honor_perbulan="<?= $value->honor_perbulan ?>"
                                                        data-jabatan_nama="<?= $value->jabatan_nama ?>"><i class="material-icons-outlined amber-text">edit</i>
                                                    </button>
                                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-jabatan" data-target="<?= $modal ?>"
                                                        data-jabatan_id="<?= $value->jabatan_id ?>"
                                                        data-jabatan_nama="<?= $value->jabatan_nama ?>"><i class="material-icons red-text text-lighten-1">delete</i>
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

<!-- Modal tambah jabatan -->
<div id="modalTambah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Tambah Jabatan</span></div>
    <div class="modal-content-sm custom-modal-content">
        <form action="<?= base_url() ?>admin/Jabatan/tambahJabatan" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="add-jabatan_nama" type="text" class="" name="jabatan_nama">
                    <label for="add-jabatan_nama">Nama Jabatan</label>
                </div>
                <div class="input-field col s12">
                    <input id="add-honor_pertemuan" type="number" min="0" class="" name="honor_pertemuan" placeholder="0">
                    <label for="add-honor_pertemuan">Honor Pertemuan</label>
                </div>
                <div class="input-field col s12">
                    <input id="add-honor_perbulan" type="number" min="0" class="" name="honor_perbulan" placeholder="0">
                    <label for="add-honor_perbulan">Honor Perbulan</label>
                </div>                                                            
            </div>                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit jabatan-->
<div id="modalEdit" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Edit Jabatan</span></div>
    <div class="modal-content-sm custom-modal-content">
        <form action="<?= base_url() ?>admin/Jabatan/editJabatan" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="jabatan_id" type="hidden" class=" jabatan_id" name="jabatan_id">
                    <input id="jabatan_nama" type="text" class=" jabatan_nama" name="jabatan_nama" placeholder="">
                    <label for="jabatan_nama">Nama Jabatan</label>
                </div>
                <div class="input-field col s12">
                    <input id="honor_pertemuan" type="number" min="0" class=" honor_pertemuan" name="honor_pertemuan" placeholder="0">
                    <label for="honor_pertemuan">Honor Pertemuan</label>
                </div>
                <div class="input-field col s12">
                    <input id="honor_perbulan" type="number" min="0" class=" honor_perbulan" name="honor_perbulan" placeholder="0">
                    <label for="honor_perbulan">Honor Perbulan</label>
                </div>                                                            
            </div>       
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus jabatan -->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Jabatan</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus jabatan <b><span class="txt-jabatan"></span></b> ?</div>
            </div>
        </div>
        <form class="col s12" action="<?= base_url() ?>admin/Jabatan/hapusJabatan" method="post">
            <input type="hidden" class=" jabatan_id" name="jabatan_id">
            <input type="text" hidden class="jabatan_nama" name="jabatan_nama" autocomplete="off">
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