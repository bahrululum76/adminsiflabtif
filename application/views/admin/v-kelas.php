<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Master</a>                    
                <a class="breadcrumb">Kelas</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel main-panel">                    
                    <div class="row">
                        <div class="col s12">
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Kelas" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>                                                        
                            <button class="tombol tombol-sm tombol-primary modal-trigger left" data-target="modalTambah"><i class="material-icons-outlined left">add</i><span>Tambah Kelas</span></button>
                            <!-- Table data  -->
                            <div class="table-wrapper">                                
                                <table class="highlight datatable-nopage">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>
                                            <th>ID Kelas</th>
                                            <th>Kelas</th>
                                            <th class="center">Status Kelas</th>
                                            <th class="center">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($kelas->result() as $key => $value) { ?>
                                            <?php 
                                                $jadwal = $this->M_database->cekdata('t_jadwal', 'kelas_kode', $value->kelas_kode);

                                                $title = 'hapus';
                                                $modal = 'modalHapus';

                                                if ($jadwal != 0) {
                                                     $title = 'tidak bisa hapus';
                                                     $modal = 'modalAlert';
                                                }

                                                $status = '';
                                                if ($value->kelas_status == 0){ 
                                                    $status = '<span class="badge-status badge-not">Tidak aktif</span>';
                                                } else { 
                                                    $status = '<span class="badge-status badge-ok">Aktif</span>';
                                                }
                                            ?>
                                            <tr>
                                                <td><?=$key+1?></td>
                                                <td><?= $value->kelas_kode ?></td>
                                                <td><?= $value->kelas_nama ?></td>
                                                <td class="status-kelas td-data center" id="<?= $value->kelas_id ?>"><?= $status ?></td>
                                                <td class="center">
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-edit-kelas" data-target="modalEdit"
                                                        data-kelas_id="<?= $value->kelas_id ?>"
                                                        data-kelas_kode="<?= $value->kelas_kode ?>"
                                                        data-kelas_nama="<?= $value->kelas_nama ?>"><i class="material-icons-outlined amber-text">edit</i>
                                                    </button>
                                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-kelas" data-target="<?= $modal ?>"
                                                        data-kelas_id="<?= $value->kelas_id ?>"
                                                        data-kelas_nama="<?= $value->kelas_nama ?>"><i class="material-icons red-text  text-lighten-1">delete</i>
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

<!-- Modal tambah kelas -->
<div id="modalTambah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Tambah Kelas</span></div>
    <div class="modal-content-sm custom-modal-content">                
        <form action="<?= base_url() ?>admin/Kelas/tambahKelas" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input placeholder="" id="kelas_kode" type="text" class="input-kode" readonly name="kelas_kode">
                    <span class="helper-text">*ID kelas otomatis</span>
                </div>
                <div class="input-field col s12">
                    <input id="add-nama" type="text" class="input-nama" name="kelas_nama" required autocomplete="off">
                    <label for="add-nama" style="width:fit-content; background-color:#fafafa">Kelas</label>
                    <span class="helper-text">*Contoh format IF A <?= date('Y') ?></span>
                </div>                                
            </div>                
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit kelas-->
<div id="modalEdit" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Edit Kelas</span></div>
    <div class="modal-content-sm custom-modal-content">        
        <form action="<?= base_url() ?>admin/Kelas/editKelas" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="hidden" class="kelas_id" name="kelas_id" readonly>
                    <input placeholder="" type="text" class="kelas_kode" name="kelas_kode" readonly>
                    <label for="kelas_id">ID Kelas</label>
                </div>
                <div class="input-field col s12">
                    <input placeholder="" id="edit-nama" type="text" class="kelas_nama input-nama" name="kelas_nama" autocomplete="off" required>
                    <label for="edit-nama">Kelas</label>
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
        <span class="modal-title">Hapus Kelas</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus kelas <b><span class="txt-kelas"></span></b> ?</div>
            </div>
        </div>
        <form class="col s12" action="<?= base_url() ?>admin/Kelas/hapusKelas" method="post">
            <input id="kelas_id" type="hidden" class="kelas_id" name="kelas_id">
            <input type="text" hidden class="kelas_nama" name="kelas_nama" autocomplete="off">
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