<!-- Get periode nama -->
<?php 
    $periode_id = $this->input->get('periode_id');
    $periode_nama = $this->db->where('periode_id', $this->input->get('periode_id'))->get('t_periode')->row();
?> 
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
                <a href="<?= base_url() ?>admin/periode" class="breadcrumb">Penjadwalan</a>
                <a class="breadcrumb"><?= $periode_nama->periode_nama ?></a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="main-panel">
                    <div class="row">
                        <div class="col s12">
                            <a class="nav__link" href="<?= base_url() ?>admin/periode"><i class="material-icons left red-text">arrow_back</i></a>
                            <span class="panel-title-2">Penjadwalan Praktikum</span>	                        	
                            <div class="line-dashed"></div>	                        
                            <div class="searchbar-table right">
                                <input type="text" placeholder="Cari Jadwal" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                <i class="material-icons right">search</i>
                            </div>                                                                                                                
                            <button class="tombol tombol-sm tombol-primary modal-trigger" data-target="modalTambah"><i class="material-icons left">add</i><span>Tambah Jadwal</span></button>                            	
                            <!-- Table data  -->
                            <div class="table-wrapper">                                
                                <table class="highlight datatable">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>             
                                            <th>ID Praktikum</th>
                                            <th width="200">Mata Praktikum</th>
                                            <th width="120">Kelas</th>
                                            <th width="220">Pengajar</th>
                                            <th class="center" width="200">Hari/Jam</th>
                                            <th width="130">Ruangan</th>
                                            <th>Dosen</th>
                                            <th class="center">Status</th>
                                            <th class="center" width="150">Aksi</th>
                                        </tr>
                                    </thead>
            
                                    <tbody class="tbody-jadwal">
                                    <?php
                                        foreach ($jadwal->result() as $key => $value) { ?>
                                            <?php 
                                                $jadwal = $this->M_database->cekdata('registrasi_praktikum', 'jadwal_kode', $value->jadwal_kode);
                                                $asisten_1 = $this->db->where('asisten_id', $value->asisten_1)->get('t_asisten')->row();
                                                $asisten_2 = $this->db->where('asisten_id', $value->asisten_2)->get('t_asisten')->row();

                                                $title = 'hapus';
                                                $modal = 'modalHapus';                     

                                                if ($jadwal != 0) {
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
                                                <td></td>                                             
                                                <td><?= $value->jadwal_kode ?></td>
                                                <td><?= $value->matkum ?></td>
                                                <td><?= $value->kelas_nama ?></td>
                                                <td>
                                                    <ul class="browser-default" style="padding-left:18px">
                                                        <li><?= $asisten_1->asisten_nama ?></li>
                                                        <li><?= $asisten_2->asisten_nama ?></li>
                                                    </ul>
                                                </td>                                                                                                                                 
                                                <td class="center"><?= $value->jadwal_hari ?><br><?= $value->jadwal_jam ?></td>
                                                <td><?= $value->ruangan_nama ?></td>
                                                <td><?= $value->dosen_nama ?></td>
                                                <td class="status-jadwal td-data center" id="<?= $value->jadwal_id ?>" data-jadwal_id="<?=$value->jadwal_id?>" data-jadwal_kode="<?=$value->jadwal_kode?>"><?= $status ?></td>                                                
                                                <td class="center">
                                                    <button class="waves-effect mybtn transparent modal-trigger btn-edit-jadwal" data-target="modalEdit"
                                                    data-jadwal_id="<?= $value->jadwal_id ?>" 
                                                    data-jadwal_kode="<?= $value->jadwal_kode ?>"
                                                    data-jadwal_kode="<?= $value->periode_id ?>"
                                                    data-matkum_id="<?= $value->matkum_id ?>"
                                                    data-dosen_id="<?= $value->dosen_id ?>"
                                                    data-kelas_kode="<?= $value->kelas_kode ?>"
                                                    data-asisten_1="<?= $value->asisten_1 ?>"
                                                    data-asisten_2="<?= $value->asisten_2 ?>"
                                                    data-jadwal_hari="<?= $value->jadwal_hari ?>"
                                                    data-jadwal_jam="<?= str_replace(' ', '', $value->jadwal_jam) ?>"
                                                    data-ruangan_id="<?= $value->ruangan_id ?>"
                                                    ><i class="material-icons amber-text">edit</i></button>
                                                    <button title="<?= $title ?>" class="waves-effect mybtn transparent modal-trigger btn-hapus-jadwal" data-target="<?= $modal ?>" 
                                                        data-jadwal_id="<?= $value->jadwal_id ?>"
                                                        data-jadwal_kode="<?= $value->jadwal_kode ?>"
                                                        data-jadwal_jam="<?= str_replace(' ', '', $value->jadwal_jam) ?>"
                                                        data-periode_id="<?= $value->periode_id ?>"><i class="material-icons red-text text-lighten-1 ">delete</i>
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

<!-- Modal tambah jadwal -->
<div id="modalTambah" class="modal modal-select2">
    <div class="modal-head">
        <span class="modal-title">Tambah Jadwal</span>        
    </div>                                                
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/penjadwalan/tambahJadwal" method="post" class="form-jadwal">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">beenhere</i>
                        <input type="hidden" name="periode_id" value="<?= $periode_id ?>">
                        <input id="jadwal_kode_tambah" type="text" class="input-jadwal-kode" name="jadwal_kode" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label for="jadwal_kode_tambah" style="margin-left: 46px; width:fit-content; width:-moz-fit-content; background-color:#fafafa">ID Praktikum</label>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">book</i>
                            <select class="select2" name="matkum_id" required>
                                <option value="">PILIH MATA PRAKTIKUM</option>                           
                                <?php 
                                foreach ($matkum->result() as $key => $value): ?>
                                <option value="<?= $value->matkum_id ?>"><?= $value->matkum ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>                                     
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">label</i>
                            <select class="select2" name="kelas_kode" required>
                                <option value="">PILIH KELAS</option>                           
                                <?php 
                                foreach ($kelas->result() as $key => $value): ?>
                                <option value="<?= $value->kelas_kode ?>"><?= $value->kelas_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons prefix">date_range</i>
                            <select class="select2" name="jadwal_hari" required>
                                <option value="">PILIH HARI</option>                           
                                <option value="Senin">Senin</option>                           				                                
                                <option value="Selasa">Selasa</option>                           				                                
                                <option value="Rabu">Rabu</option>                           				                                
                                <option value="Kamis">Kamis</option>                           				                                
                                <option value="Jumat">Jumat</option>                           				                                
                                <option value="Sabtu">Sabtu</option>             
                            </select>
                        </span>                        
                    </div>
                    <div class="input-field col s7">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">schedule</i>
                        <input id="jam_mulai_tambah" type="text" class="timepicker" name="jam_mulai" autocomplete="off" readonly>
                        <label for="jam_mulai_tambah" style="margin-left: 52px; width:fit-content; width:-moz-fit-content; background-color:#fafafa"">Jam Mulai</label>
                    </div>                                        				                        
                    <div class="input-field col s5">
                        <input id="jam_selesai_tambah" type="text" class="timepicker" name="jam_selesai" autocomplete="off" readonly>
                        <label for="jam_selesai_tambah">Selesai</label>
                    </div>                    				                        
                </div>
                <div class="col s12 m12 l6">
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">home</i>
                            <select class="select2" name="ruangan_id" required>
                                <option value="">PILIH RUANGAN</option>                           
                                <?php 
                                foreach ($ruangan->result() as $key => $value): ?>
                                <option value="<?= $value->ruangan_id ?>"><?= $value->ruangan_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>                        
                    </div>                        
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons prefix">face</i>
                            <select class="select2-with-image" name="asisten_1" required>
                                <option value="">PILIH ASISTEN 1</option>
                                <?php 
                                foreach ($asisten->result() as $key => $value): ?>                                    
                                    <option class="black-text" value="<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons prefix">face</i>
                            <select class="select2-with-image" name="asisten_2" required>
                                <option value="">PILIH ASISTEN 2</option>
                                <?php 
                                foreach ($asisten->result() as $key => $value): ?>                                    
                                    <option class="black-text" value="<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">account_circle</i>
                            <select class="select2-with-image" name="dosen_id" required>
                                <option value="">PILIH DOSEN</option>                           
                                <?php 
                                foreach ($dosen->result() as $key => $value): ?>
                                <option value="<?= $value->dosen_id ?>" data-image="<?= $value->foto ?>"><?= $value->dosen_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
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

<!-- Modal edit jadwal -->
<div id="modalEdit" class="modal modal-select2">
    <div class="modal-head">
        <span class="modal-title">Edit Jadwal</span>        
    </div>                                                
    <div class="modal-content custom-modal-content">
        <form action="<?= base_url() ?>admin/penjadwalan/editJadwal" method="post" id="form-edit-jadwal">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field col s12">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">beenhere</i>
                        <input id="periode_id" type="hidden" class="" name="periode_id" value="<?= $periode_id ?>">
                        <input type="hidden" class="jadwal_id" name="jadwal_id">
                        <input type="hidden" class="jadwal_kode" name="kode_asal">
                        <input id="jadwal_kode_edit" type="text" class=" input-jadwal-kode jadwal_kode" name="jadwal_kode" required autocomplete="off" style="width:calc(100% - 38px); float:right; background-color: #fafafa">
                        <label class="edit-label" for="jadwal_kode" id="label-jadwal-kode" style="margin-left: 46px; width:fit-content; background-color:#fafafa">ID Praktikum</label>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">book</i>
                            <select class="select2 matkum_id" name="matkum_id" required>
                                <option value="">PILIH MATA PRAKTIKUM</option>                           
                                <?php 
                                foreach ($matkum->result() as $key => $value): ?>
                                <option value="<?= $value->matkum_id ?>"><?= $value->matkum ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>             
                        </select>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">label</i>
                            <select class="select2 kelas_kode" name="kelas_kode" required>
                                <option value="">PILIH KELAS</option>                           
                                <?php 
                                foreach ($kelas->result() as $key => $value): ?>
                                <option value="<?= $value->kelas_kode ?>"><?= $value->kelas_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons prefix">date_range</i>
                            <select class="select2 jadwal_hari" name="jadwal_hari" required>
                                <option value="">PILIH HARI</option>                           
                                <option value="Senin">Senin</option>                           				                                
                                <option value="Selasa">Selasa</option>                           				                                
                                <option value="Rabu">Rabu</option>                           				                                
                                <option value="Kamis">Kamis</option>                           				                                
                                <option value="Jumat">Jumat</option>                           				                                
                                <option value="Sabtu">Sabtu</option>             
                            </select>
                        </span>                        
                    </div>
                    <div class="input-field col s7">
                        <i class="material-icons-outlined prefix" style="color: #607d8b">schedule</i>
                        <input type="text" class="timepicker jam_mulai" name="jam_mulai" autocomplete="off" readonly>
                        <label class="edit-label" for="jam_mulai" style="margin-left: 52px; width:fit-content; width:-moz-fit-content; background-color:#fafafa"">Jam Mulai</label>
                    </div>                                        				                        
                    <div class="input-field col s5">
                        <input type="text" class="timepicker jam_selesai" name="jam_selesai" autocomplete="off" readonly>
                        <label class="edit-label" for="jam_selesai">Selesai</label>
                    </div>				                        
                </div>
                <div class="col s12 m12 l6">
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">home</i>
                            <select class="select2 ruangan_id" name="ruangan_id" required>
                                <option value="">PILIH RUANGAN</option>                           
                                <?php 
                                foreach ($ruangan->result() as $key => $value): ?>
                                <option value="<?= $value->ruangan_id ?>"><?= $value->ruangan_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>                        
                    </div>                        
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons prefix">face</i>
                            <select class="select2-with-image asisten_1" name="asisten_1" required>
                                <option value="">PILIH ASISTEN 1</option>
                                <?php 
                                foreach ($asisten->result() as $key => $value): ?>                                    
                                    <option class="black-text" value="<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons prefix">face</i>
                            <select class="select2-with-image asisten_2" name="asisten_2" required>
                                <option value="">PILIH ASISTEN 2</option>
                                <?php 
                                foreach ($asisten->result() as $key => $value): ?>                                    
                                    <option  class="black-text" value="<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="select2-wrapper">
                            <i class="material-icons-outlined prefix">account_circle</i>
                            <select class="select2-with-image dosen_id" name="dosen_id" required>
                                <option value="">PILIH DOSEN</option>                           
                                <?php 
                                foreach ($dosen->result() as $key => $value): ?>
                                <option value="<?= $value->dosen_id ?>" data-image="<?= $value->foto ?>"><?= $value->dosen_nama ?></option>	
                                <?php endforeach ?>             
                            </select>
                        </span>
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

<!-- Modal hapus jadwal -->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Jadwal</span>
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="row">
            <div class="col s12">
                <div class="panel panel-danger">Apakah anda yakin ingin menghapus jadwal <b><span class="txt-jadwal"></span></b> ?</div>
            </div>
        </div>
        <form action="<?= base_url() ?>admin/penjadwalan/hapusJadwal" method="post">
            <input type="hidden" class="" name="periode_id" value="<?= $periode_id ?>">
            <input type="hidden" class="jadwal_id" name="jadwal_id">
            <input type="text" hidden class="jadwal_kode" name="jadwal_kode" autocomplete="off">
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