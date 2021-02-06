<?php 
    $periode = $this->db->limit(1)->order_by('periode_id DESC')->get('t_periode')->row();
    $idp = $this->input->get('idp');                                                                       
?>                 
<div class="col s12">
    <div class="main-wrapper">
    	<div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Pindah Praktikum</a>                    
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
                        <br> 
                        <div class="center-align">
                            <span class="select2-wrapper">
                                <select class="select2" onchange="location = this.value;">
                                            <option>DARI PRAKTIKUM</option>
                                    <?php 
                                        foreach ($jadwal->result() as $key => $value): ?>
                                            <option value="<?= base_url() ?>admin/pindah-praktikum?idp=<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </span>
                        </div>                                                                                                   
                    <?php endif ?>                                             
                    <?php if (isset($idp)) : ?>                            	                           
                    <div class="head-panel">
                        <div class="row" style="margin: 0">
                            <div class="head-p" style="align-items:center;position:relative">                                   
                                <div style="align-items:center">
                                    <span>Dari Praktikum</span>
                                    <span class="select2-wrapper" style="margin-left:10px">
                                        <select class="select2" onchange="location = this.value;">
                                            <?php 
                                                foreach ($jadwal->result() as $key => $value):
                                                    $selected = '' ; 
                                                    if ($idp == $value->jadwal_kode) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    } 
                                                    ?>
                                                    <option <?= $selected ?> value="<?= base_url() ?>admin/pindah-praktikum?idp=<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </span>
                                </div>
                                <div style="align-items:center">
                                    <span>Ke Praktikum</span>
                                    <span class="select2-wrapper" style="margin-left:10px">
                                        <select class="select2" id="p_tujuan">
                                            <option value="">PILIH ID PRAKTIKUM</option>
                                            <?php 
                                                foreach ($jadwal->result() as $key => $value):?>
                                                    <option value="<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </span>
                                </div>                                        
                            </div>
                            <br>
                            <div class="head-p">
                                <div>
                                    <span>Pertemuan</span>
                                    <span>:</span>
                                    <span class="isi" style="font-weight: 600;" id="pertemuan1"><?= $praktikum1 ?></span>
                                </div>
                                <div>
                                    <span>Pertemuan</span>
                                    <span>:</span>
                                    <span class="isi" style="font-weight: 600;" id="pertemuan2">-</span>
                                </div>
                            </div>                                                                       
                        </div>                            
                    </div>
                    <br>
                    <div class="table-wrapper">                                                                                                                       	
                        <form action="<?= base_url() ?>admin/registrasi/pindahPraktikum" method="post" class="form-pindah-praktikum">
                            <input type="hidden" class="pertemuan1" value="<?= $pertemuan1 ?>">                                                                        
                            <input type="hidden" name="praktikum_awal" value="<?= $idp ?>">                                                                        
                            <input type="hidden" name="id_praktikum" value="<?= $idp ?>">               
                            <input type="hidden" name="praktikum_tujuan" class="praktikum_tujuan" value="0">               
                            <table class="highlight">                                    
                                <thead>                                    	
                                    <tr>
                                        <th width="50px" class="blue-text text-accent-3">#</th>
                                        <th width="150px">NPM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Kelas</th>
                                        <!-- <th class="center">Kehadiran (%)</th> -->
                                        <th class="center" width="90px">Tandai</th>                                            
                                    </tr>
                                </thead>        
                                <tbody>                                    
                                    <?php
                                        foreach ($mahasiswa->result() as $key => $value) { 

                                            $kehadiran = $this->M_database->kehadiran($idp, $value->npm);
                                            $nilai_tugas = $this->M_database->nilai_tugas($idp, $value->mhs_id);
                                            $total_kehadiran = $kehadiran->kehadiran * 10;
                                            
                                            ?>                                            
                                            <tr>                                                                                                                            
                                                <td><?= $key+1 ?></td>
                                                <td><?= $value->npm ?></td>
                                                <td><?= $value->nama ?></td>
                                                <td><?= $value->kelas_nama ?></td>
                                                <!-- <td class="center"><?= $total_kehadiran ?></td> -->
                                                <td class="center">
                                                    <label>                                                                
                                                        <input disabled="true" type="checkbox" value="<?= $value->npm ?>-<?= $value->nama ?>" name="terpilih[]" class="mhs filled-in" style="position: relative; left: 10px">
                                                        <span></span>
                                                    </label>
                                                </td>                                                                                                            
                                            </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <br>
                            <br>                                 
                            <button type="submit" class="tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>                                    
                        </form>                                                   
                    </div>
                    <br>
                    <br>
                    <?php endif ?>						    
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Modal konfirmasi pindah -->
<div id="modalPindah" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Pindah Praktikum</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel panel-danger">Apakah anda yakin ingin menghapus registrasi <b><span class="txt-nama"></span></b> dari praktikum <span class="txt-idp"></span> ?</div>
        <br>
        <div class="panel">*Aksi ini akan menghapus seluruh Absensi dan Nilai mahasiswa terkait dari praktikum <span class="txt-idp"></span>.</div>                
            <div class="row">
                <div class="input-field col s12">
                    <input type="hidden" class="checkbox-npm">
                </div>
            </div>
        </div>
    <div class="modal-footer custom-modal-footer">
        <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-danger right" id="ok-hapus-reg"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>   
</div>