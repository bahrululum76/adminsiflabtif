<?php 
$jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $this->input->get('idp')))->row();

$periode = $this->db->limit(1)->order_by('periode_id DESC')->get('t_periode')->row();
$idp = $this->input->get('idp');
?>                 
<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Registrasi</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="main-panel">                                      
                    <?php if (!isset($idp)) : ?>
                        <style>
                            .select2-selection__rendered {
                                background-color: #F3E4B2;
                            }
                            .select2-results__option[aria-selected=true] {
                                display: none
                            }
                        </style> 
                        <div class="center-align">
                            <span class="select2-wrapper">
                                <select class="select2" onchange="location = this.value;">
                                            <option value="<?= base_url() ?>admin/registrasi">PILIH ID PRAKTIKUM</option>
                                    <?php 
                                        foreach ($jadwal->result() as $key => $value): ?>
                                            <option value="<?= base_url() ?>admin/registrasi?idp=<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </span>
                        </div>                                                                                                   
                    <?php endif ?>
                    <?php if (isset($idp) && $jadwal_ini) : ?>                            	                           
                    <div class="head-panel">
                        <div class="row" style="margin: 0">
                            <div class="head-p" style="align-items:baseline;position:relative">                                                                  
                                <div style="align-items:center">
                                    <span>ID Praktikum</span>
                                    <span class="select2-wrapper">
                                        <select class="select2" onchange="location = this.value;">
                                            <?php 
                                                foreach ($jadwal->result() as $key => $value):
                                                    $selected = '' ; 
                                                    if ($idp== $value->jadwal_kode) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    } 
                                                    ?>
                                                    <option <?= $selected ?> value="<?= base_url() ?>admin/registrasi?idp=<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </span>
                                </div>
                                <div>
                                    <span>Praktikum</span>
                                    <span>:</span>
                                    <span class="isi"><?= ucwords($jadwal_ini->matkum) ?></span>
                                </div>                                
                            </div>
                            <br>
                            <div class="head-p">
                                <div>
                                    <span>Ruangan</span>
                                    <span>:</span>
                                    <span class="isi"><?= $jadwal_ini->ruangan_nama ?></span>
                                </div>  
                                <div>
                                    <span>Kelas</span>
                                    <span>:</span>
                                    <span class="isi"><?= $jadwal_ini->kelas_nama ?></span>
                                </div>  
                            </div>
                            <br>                                                      
                            <div class="head-p">
                                <div>
                                    <span>Jumlah PC</span>
                                    <span>:</span>
                                    <span class="isi"><?= $jadwal_ini->ruangan_kapasitas ?></span>
                                </div>                                  
                            </div>
                        </div>     
                    </div>                                                                                           
                    <div class="table-wrapper">
                        <div class="searchbar-table right">
                            <input type="text" placeholder="Cari Mahasiswa" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                            <i class="material-icons right">search</i>
                        </div>
                        <br>
                        <div class="table-wrapper">        
                            <br>                            
                            <form action="<?= base_url() ?>admin/registrasi/simpanRegistrasi" method="post" class="form-registrasi">	                                                                                                 
                            <table class="highlight datatable">                                    
                                <thead>
                                    <tr>
                                        <th class="blue-text text-accent-3 center">#</th>
                                        <th>NPM</th>
                                        <th>Nama</th>
                                        <th width="200">Kelas</th>                                                
                                        <th class="center" width="100">Tandai</th>
                                    </tr>
                                </thead>                
                                <tbody>
                                <?php
                                        foreach ($registrasi->result() as $key => $value) { ?>
                                        <?php 
                                            $cek = $this->db->query("SELECT COUNT(registrasi_id) AS exist FROM registrasi_praktikum WHERE jadwal_kode='$idp' AND npm='$value->npm'")->row();
                                
                                            $checked = '';
                                            $marker = '';
                                            $val = '1';

                                            if ($cek->exist == 1) {
                                                $checked = 'checked';
                                                $marker = 'green lighten-5';
                                                $val = '1';
                                            }
                                            else
                                            {
                                                $checked = '';
                                                $marker = '';
                                                $val = '2';
                                            }                                                   
                                        ?>  
                                            <tr class="<?= $marker ?> tr-<?=$value->npm?>">
                                                <td class="center"><?=$key+1?></td>
                                                <td><?= $value->npm ?></td>
                                                <td><?= $value->nama ?></td>
                                                <td><?= $value->kelas_nama ?></td>                                                                                                    
                                                <td class="center td-checkbox<?=$value->npm?>">                                               
                                                    <label>
                                                        <input type="checkbox" class="filled-in" data-nama="<?= $value->nama ?>" data-npm="<?= $value->npm ?>" <?= $checked ?>>
                                                        <span class="checkbox" style="left: 10px"></span>
                                                    </label>											
                                                </td>                                                                                                   
                                                <input type="hidden" class="jadwal_kode" name="jadwal_kode" value="<?= $idp ?>">                                                             
                                                <input type="hidden" name="id_praktikum" value="<?= $idp?>">                                                             
                                            </tr>
                                    <?php } ?>
                                    <?php if ($registrasi->result() != null) { ?>                                                
                                        <tr style="height: 50px; visibility: hidden">                                                       
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>                                            
                                            <td class="center">                                                                											
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                        foreach ($mahasiswa->result() as $key => $value) { ?>
                                        <?php 
                                            $cek = $this->db->query("SELECT COUNT(registrasi_id) AS exist FROM registrasi_praktikum WHERE jadwal_kode='$idp' AND npm='$value->npm'")->row();
                                
                                            $checked = '';
                                            $marker = '';
                                            $val = '1';

                                            if ($cek->exist == 1) {
                                                $checked = 'checked';
                                                $marker = 'hide';
                                                $val = '1';
                                            }
                                            else
                                            {
                                                $checked = '';
                                                $marker = '';
                                                $val = '2';
                                            }                                                  
                                        ?>  
                                            <tr class="<?= $marker ?> tr-<?=$value->npm?>"">
                                                <td class="center"><?=$key+1?></td>
                                                <td><?= $value->npm ?></td>
                                                <td><?= $value->nama ?></td>
                                                <td><?= $value->kelas_nama ?></td>                                            
                                                <td class="center td-checkbox<?=$value->npm?>">                                              
                                                    <label>
                                                        <input type="checkbox" class="filled-in" data-nama="<?= $value->nama ?>" data-npm="<?= $value->npm ?>" value="<?= $value->npm ?>" name="npm"  <?= $checked ?>>
                                                        <span class="checkbox" style="left: 10px"></span>
                                                    </label>											
                                                </td>                                                                                                   
                                                <input type="hidden" class="jadwal_kode" name="jadwal_kode" value="<?= $idp ?>">                                                             
                                                <input type="hidden" name="id_praktikum" value="<?= $idp?>">                                                             
                                            </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <br>
                            <br>
                            <button type="submit" class="btn-small blue hide btn-submit"><i class="material-icons left">save</i>Simpan Registrasi</button>
                            </form>
                        </div>                         	
                        <?php endif ?>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Modal hapus registrasi -->
<div id="modalHapusReg" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Hapus Registrasi</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel panel-danger">Apakah anda yakin ingin menghapus registrasi <b><span class="txt-nama"></span></b> dari praktikum <span class="txt-idp"></span> ?</div>
        <br>
        <div class="panel">*Aksi ini akan menghapus seluruh Absensi dan Nilai mahasiswa terkait dari praktikum <span class="txt-idp"></span>.</div>                
            <div class="row">
                <div class="input-field col s12">
                    <input type="hidden" class="checkbox-npm">
                    <input type="hidden" class="checkbox-nama">
                </div>
            </div>
        </div>
    <div class="modal-footer custom-modal-footer">
        <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-danger right" id="ok-hapus-reg"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>   
</div>