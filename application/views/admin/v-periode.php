<div class="col s12">
    <div class="main-wrapper">
            <div class="breadcrumb-box">
                <div class="col s12">
                    <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                    <a class="breadcrumb">Penjadwalan</a>                    
                </div>
            </div>
        <div class="row">
            <div class="col s12">
                <div class="main-panel">                    
                    <div class="row">
                        <div class="col s12">
                            <span class="panel-title-2">Periode Praktikum</span>
                            <div class="line-dashed"></div>	                        
                            <button class="tombol tombol-sm tombol-primary modal-trigger" data-target="modalTambah"><i class="material-icons-outlined left">add</i><span>Tambah Periode</span></button>                                                        
                            <div class="table-wrapper">                                
                                <table class="highlight datatable">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>             
                                            <th>Periode</th>
                                            <th class="center">Status Periode</th>
                                            <th class="center">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($periode->result() as $key => $value) { ?>
                                            <?php 
                                                $jadwal = $this->M_database->cekdata('t_jadwal', 'periode_id', $value->periode_id);

                                                $title = 'hapus';
                                                $modal = 'modalHapus';

                                                if ($jadwal != 0) {
                                                     $title = 'tidak bisa hapus';
                                                     $modal = 'modalAlert';
                                                }

                                                if ($value->status == 1) {
                                                    $status = '<span class="badge-status badge-ok">Aktif</span>';
                                                } else {
                                                    $status = '<span class="badge-status badge-not">Tidak aktif</span>';
                                                }
                                            ?>
                                            <tr>
                                                <td><?= $key+1 ?></td>                                               
                                                <td><a class="nav__link" href="<?= base_url() ?>admin/penjadwalan?periode_id=<?= $value->periode_id ?>" class="blue-text text-lighten-2"><?= $value->periode_nama ?></a></td>
                                                <td class="status-periode td-data center" id="<?= $value->periode_id ?>"><?= $status ?></td>
                                                <td class="center">
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-edit-periode" data-target="modalEdit"
                                                     	data-periode_id="<?= $value->periode_id ?>"
                                                        data-periode_nama="<?= $value->periode_nama ?>"><i class="material-icons-outlined amber-text">edit</i></button>
                                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-periode" data-target="<?= $modal ?>"
                                                        data-periode_id="<?= $value->periode_id ?>"
                                                        data-periode_nama="<?= $value->periode_nama ?>"><i class="material-icons red-text text-lighten-1 ">delete</i>
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

<!-- Modal tambah periode -->
<div id="modalTambah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Tambah Periode</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <form action="<?= base_url() ?>admin/periode/tambahPeriode" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="periode_nama" name="periode_nama" autocomplete="off">
                    <label for="periode_nama">Periode</label>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
      <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>    
</div>

<!-- Modal edit periode -->
<div id="modalEdit" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Edit Periode</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <form action="<?= base_url() ?>admin/periode/editPeriode" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="hidden" id="periode_id" class="periode_id" name="periode_id">
         			<input type="text" id="periode_nama" class="periode_nama" name="periode_nama" autocomplete="off">
                    <label for="periode_nama">Periode</label>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>    
</div>

<!-- Modal hapus periode -->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Hapus Periode</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel panel-danger">Hapus data <b><span class="txt-periode"></span></b> ?</div>        
        <form action="<?= base_url() ?>admin/periode/hapusPeriode" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="hidden" id="periode_id" class="validate periode_id" name="periode_id">
         			<input type="hidden" id="periode_nama" class="validate periode_nama" name="periode_nama" autocomplete="off">
                </div>
            </div>
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