<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Master</a>                    
                <a class="breadcrumb">Mata Praktikum</a>                    
            </div>
        </div>        
    <div class="main-panel">                    
            <div class="searchbar-table right">
                <input type="text" placeholder="Cari Matkum" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                <i class="material-icons right">search</i>
            </div>                                                        
            <button class="tombol tombol-sm tombol-primary modal-trigger left" data-target="modalTambah"><i class="material-icons-outlined left">add</i><span>Tambah Matkum</span></button>
            <!-- Table data  -->
            <div class="table-wrapper">                                
                <table class="highlight datatable">                                    
                    <thead>
                        <tr>
                            <th class="blue-text text-accent-3">#</th>
                            <th>Mata Praktikum</th>
                            <th class="center">Status</th>
                            <th class="center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        foreach ($matkum->result() as $key => $value) { ?>                                            
                            <?php 
                                $jadwal = $this->M_database->cekdata('t_jadwal', 'matkum_id', $value->matkum_id);

                                $title = 'hapus';
                                $modal = 'modalHapus';

                                if ($jadwal != 0) {
                                        $title = 'tidak bisa hapus';
                                        $modal = 'modalAlert';
                                }

                                $status = '';
                                if ($value->matkum_status == 0){ 
                                    $status = '<span class="badge-status badge-not">Tidak aktif</span>';
                                } else { 
                                    $status = '<span class="badge-status badge-ok">Aktif</span>';
                                }
                            ?>
                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= ucwords($value->matkum) ?></td>
                                <td class="status-matkum td-data center" id="<?= $value->matkum_id ?>"><?= $status ?></td>
                                <td class="center">
                                    <button class="waves-effect mybtn transparent modal-trigger btn-edit-matkum" data-target="modalEdit"
                                        data-matkum_id="<?= $value->matkum_id ?>"
                                        data-matkum="<?= $value->matkum ?>"><i class="material-icons-outlined amber-text">edit</i>
                                    </button>
                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-matkum" data-target="<?= $modal ?>"
                                        data-matkum_id="<?= $value->matkum_id ?>"
                                        data-matkum="<?= $value->matkum ?>"><i class="material-icons red-text text-lighten-1">delete</i>
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

<!-- Modal tambah matkum -->
<div id="modalTambah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Tambah Matkum</span></div>
    <div class="modal-content-sm custom-modal-content">        
        <form action="<?= base_url() ?>admin/Matkum/tambahMatkum" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="add-matkum" type="text" class="" name="matkum">
                    <label for="add-matkum">Mata Praktikum</label>
                </div>                  
            </div>                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit matkum-->
<div id="modalEdit" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Edit Matkum</span></div>
    <div class="modal-content-sm custom-modal-content">        
        <form action="<?= base_url() ?>admin/Matkum/editMatkum" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="matkum_id" type="hidden" class=" matkum_id" name="matkum_id">
                    <input placeholder="" id="matkum" type="text" class=" matkum" name="matkum">
                    <label for="matkum">Mata Praktikum</label>
                </div>                  
            </div>                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus matkum-->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Matkum</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus mata praktikum <b><span class="txt-matkum"></span></b> ?</div>
            </div>
        </div>
        <form class="col s12" action="<?= base_url() ?>admin/Matkum/hapusMatkum" method="post">
            <input type="hidden" class=" matkum_id" name="matkum_id">
            <input type="text" hidden class="matkum" name="matkum" autocomplete="off">
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