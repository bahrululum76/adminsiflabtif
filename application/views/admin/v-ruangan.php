<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Master</a>                    
                <a class="breadcrumb">Ruangan</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel main-panel">                    
                    <div class="row">
                        <div class="col s12">
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Ruangan" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>                                                        
                            <button class="tombol tombol-sm tombol-primary modal-trigger left" data-target="modalTambah"><i class="material-icons-outlined left">add</i><span>Tambah Ruangan</span></button>
                            <!-- Table data  -->
                            <div class="table-wrapper">                                
                                <table class="highlight datatable">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>
                                            <th width="200">Ruangan</th>
                                            <th class="center">Jumlah PC</th>
                                            <th>Penanggung Jawab</th>
                                            <th class="center" width="100">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($ruangan->result() as $key => $value) { ?>
                                            <?php 
                                                $jadwal = $this->M_database->cekdata('t_jadwal', 'ruangan_id', $value->ruangan_id);

                                                $title = 'hapus';
                                                $modal = 'modalHapus';

                                                if ($jadwal) {
                                                     $title = 'tidak bisa hapus';
                                                     $modal = 'modalAlert';
                                                }
                                            ?>
                                            <tr>
                                                <td><?= $key+1 ?></td>
                                                <td><?= $value->ruangan_nama ?></td>
                                                <td class="center"><?= $value->ruangan_kapasitas ?></td>
                                                <td><?= $value->jabatan_nama ?></td>
                                                <td class="center">
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-edit-ruangan" data-target="modalEdit"
                                                        data-ruangan_id="<?= $value->ruangan_id ?>"
                                                        data-kapasitas="<?= $value->ruangan_kapasitas ?>"
                                                        data-ruangan_pj="<?= $value->ruangan_pj ?>"
                                                        data-ruangan_nama="<?= $value->ruangan_nama ?>"><i class="material-icons-outlined amber-text">edit</i>
                                                    </button>
                                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-ruangan" data-target="<?= $modal ?>"
                                                        data-ruangan_id="<?= $value->ruangan_id ?>"
                                                        data-ruangan_nama="<?= $value->ruangan_nama ?>"><i class="material-icons red-text text-lighten-1">delete</i>
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

<!-- Modal tambah ruangan -->
<div id="modalTambah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Tambah Ruangan</span></div>
    <div class="modal-content-sm custom-modal-content">        
        <form action="<?= base_url() ?>admin/Ruangan/tambahRuangan" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="add-ruangan_nama" type="text" name="ruangan_nama" autocomplete="off" required>
                    <label for="add-ruangan_nama">Nama Ruangan</label>
                </div>                  
                <div class="input-field col s12">
                    <input id="add-kapasitas" type="text" name="kapasitas" autocomplete="off" required>
                    <label for="add-kapasitas">Jumlah PC</label>
                </div>
                <div class="input-field col s12">
                    <span class="select2-wrapper" style="max-width:1000px; box-shadow: none; border: 1px solid #cacaca">
                        <select class="select2" name="ruangan_pj" required>
                            <option value="">PILIH PENANGGUNG JAWAB</option>
                            <?php foreach ($jabatan->result() as $key => $value): ?>
                                <option value="<?= $value->jabatan_id ?>"><?= $value->jabatan_nama ?></option>
                            <?php endforeach ?>
                        </select>
                    </span>
                </div>                  
            </div>                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit ruangan-->
<div id="modalEdit" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Edit Ruangan</span></div>
    <div class="modal-content-sm custom-modal-content">        
        <form action="<?= base_url() ?>admin/Ruangan/editRuangan" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input id="ruangan_id" type="hidden" class="ruangan_id" name="ruangan_id">
                    <input id="ruangan_nama" type="text" class="ruangan_nama" name="ruangan_nama" autocomplete="off" required>
                    <label for="ruangan_nama">Nama Ruangan</label>
                </div>                  
                <div class="input-field col s12">
                    <input id="kapasitas" type="text" class="kapasitas" name="kapasitas" autocomplete="off" required>
                    <label for="kapasitas">Jumlah PC</label>
                </div>
                <div class="input-field col s12">
                    <span class="select2-wrapper" style="max-width:1000px; box-shadow: none; border: 1px solid #cacaca">
                        <select class="select2 ruangan_pj" name="ruangan_pj" required>
                            <option value="">PILIH PENANGGUNG JAWAB</option>
                            <?php foreach ($jabatan->result() as $key => $value): ?>
                                <option value="<?= $value->jabatan_id ?>"><?= $value->jabatan_nama ?></option>
                            <?php endforeach ?>
                        </select>
                    </span>
                </div>                  
            </div>                    
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus ruangan-->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Ruangan</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus ruangan <b><span class="txt-ruangan"></span></b> ?</div>
            </div>
        </div>
        <form class="col s12" action="<?= base_url() ?>admin/Ruangan/hapusRuangan" method="post">
            <input type="hidden" class=" ruangan_id" name="ruangan_id">
            <input type="text" hidden class="ruangan_nama" name="ruangan_nama" autocomplete="off">
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