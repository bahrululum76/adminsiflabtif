<style>
    .select2-results__option[aria-selected=true] {
        margin: 8px 0;
    }
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
        position: relative;
        opacity: 1;
        pointer-events: none;
        top: 2px;
        margin-right: 8px;
    }
</style>

<?php 
    if ($lab->mode_ujian) {
       $ujian = "checked";
       $status1 = "Aktif";
    } else {
        $ujian = "";
        $status1 = "Tidak aktif";
    }
    
    if ($lab->kuisioner_asisten) {
       $ksa = "checked";
       $status2 = "Aktif";
    } else {
        $ksa = "";
        $status2 = "Tidak aktif";
    }
    
    if ($lab->kuisioner_mhs) {
       $ksm = "checked";
       $status3 = "Aktif";
    } else {
        $ksm = "";
        $status3 = "Tidak aktif";
    }

?>

<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Aplikasi</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="main-panel center-align">
                    <a target="_blank" href="<?=base_url()?>admin/aplikasi/cetak_absensi_ujian" class="tombol-aplikasi teal lighten-5 black-text" style="font-weight:400"><i class="medium material-icons blue-text">event_note</i><br>Cetak Absensi Ujian</a>
                    <div class="tombol-aplikasi teal lighten-5 modal-trigger" data-target="modalCetakNilai"><i class="medium material-icons blue-text">assessment</i><br>Cetak Nilai Praktikum</div>
                    <div class="tombol-aplikasi red lighten-5 modal-trigger" data-target="modalHapusPesan"><i class="medium material-icons red-text">chat</i><br>Bersihkan Pesan</div>
                    <div class="tombol-aplikasi red lighten-5 modal-trigger" data-target="modalHapusTicketing"><i class="medium material-icons red-text">confirmation_number</i><br>Bersihkan E-Ticketing</div>                
                    <div class="tombol-aplikasi amber lighten-5 modal-trigger" data-target="modalResetPembayaran"><i class="medium material-icons amber-text">sync</i><br>Reset Pembayaran Praktikum</div>
                </div>    
                <div class="main-panel center-align">
                    <div class="tombol-aplikasi" style="text-align:left"><span style="font-weight:700">Status Ujian Praktikum</span><br><span style="font-weight:300;font-size:12px;width:300px;display:block;">Aktifkan untuk menampilkan status ujian praktikum</span></div>
                    <div class="tombol-aplikasi blue lighten-5" style="position:relative;bottom:12px">
                        <div class="switch">
                            <label>
                            Off
                            <input type="checkbox" <?=$ujian?> value="<?=$status1?>" class="status_ujian">
                            <span class="lever"></span>
                            On
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="tombol-aplikasi" style="text-align:left"><span style="font-weight:700">Kuisioner Asisten</span><br><span style="font-weight:300;font-size:12px;width:300px;display:block;">Aktifkan untuk menampilkan menu kuisioner di halaman asisten</span></div>
                    <div class="tombol-aplikasi blue lighten-5" style="position:relative;bottom:12px">
                        <div class="switch">
                            <label>
                            Off
                            <input type="checkbox" <?=$ksa?> value="<?=$status2?>" class="status_ksa">
                            <span class="lever"></span>
                            On
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="tombol-aplikasi" style="text-align:left"><span style="font-weight:700">Kuisioner Mahasiswa</span><br><span style="font-weight:300;font-size:12px;width:300px;display:block;">Aktifkan untuk menampilkan menu kuisioner asisten pada aplikasi client</span></div>
                    <div class="tombol-aplikasi blue lighten-5" style="position:relative;bottom:12px">
                        <div class="switch">
                            <label>
                            Off
                            <input type="checkbox" <?=$ksm?> value="<?=$status3?>" class="status_ksm">
                            <span class="lever"></span>
                            On
                            </label>
                        </div>
                    </div>
                </div>            
            </div>
            <div class="col s12 m12 l12">            
                <div class="main-panel">
                    <div class="row">
                        <div class="col s12">
                            <form action="<?=base_url()?>admin/aplikasi/peraturan" method="post">                            
                                <div class="input-field col s12">
                                    <div style="margin-bottom:8px;font-size:16px;font-weight:600">Prosedural Praktikum</div>
                                    <textarea class="summernote-tentang" name="prosedural"><?=$lab->lab_prosedural?></textarea>
                                </div>
                                <div class="input-field col s12">
                                    <div style="margin-bottom:8px;font-size:16px;font-weight:600">Tata Tertib Praktikum</div>
                                    <textarea class="summernote-tentang" name="tata_tertib"><?=$lab->lab_tata_tertib?></textarea>
                                </div>
                                <div class="input-field col s12">
                                    <div style="margin-bottom:8px;font-size:16px;font-weight:600">Sanksi Praktikum</div>
                                    <textarea class="summernote-tentang" name="sanksi"><?=$lab->lab_sanksi?></textarea>
                                </div>
                                <div class="input-field col s12">
                                    <button type="submit" class="tombol tombol-primary right">Simpan</button>
                                </div>
                            </form>                                                
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l8">            
                <div class="main-panel">
                    <div class="row">
                        <div class="col s12">                    
                            <div class="center-align">
                                <h6>Social Media</h6>
                                    <form action="<?=base_url()?>admin/aplikasi/social" method="post">
                                    <div class="input-field col s12">
                                        <input id="facebook-link" type="text" placeholder="Masukkan link facebook" name="facebook" value="<?=$lab->lab_fb?>">
                                        <label for="facebook-link" class="white">Facebook</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input id="instagram-link" type="text" placeholder="Masukkan link instagram" name="instagram" value="<?=$lab->lab_ig?>">
                                        <label for="instagram-link" class="white">Instagram</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input id="youtube-link" type="text" placeholder="Masukkan link youtube" name="youtube" value="<?=$lab->lab_yt?>">
                                        <label for="youtube-link" class="white">Youtube</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <button type="submit" class="tombol tombol-primary right">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>         
    </div>
</div>

<!-- Kirim notifikasi -->
<div id="modalCetakNilai" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Cetak Nilai Praktikum</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">      
        <form class="col s12" action="<?= base_url() ?>admin/aplikasi/cetak_nilai_praktikum" method="post" target="_blank">
            <div class="row">                             
                <div class="input-field col s12" style="margin-top:4px">
                    <div style="margin-bottom:8px">Pilih Jadwal :</div>                 
                    <span class="select2-wrapper" style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
                        <select class="select2" name="jadwal_kode[]" required multiple="multiple">
                            <option value="all">SEMUA JADWAL</option>                                                                            
                            <?php foreach ($jadwal->result() as $key => $value): ?>
                            <option value="<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
                            <?php endforeach ?>                           
                        </select>
                    </span>
                </div>                
            </div>                                                                             
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">print</i><span>Cetak</span></button>
        <button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Tutup</button>
    </div>
    </form>
</div>

<!-- Modal hapus pesan -->
<div id="modalHapusPesan" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Pesan</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">
            <div class="panel">Aksi ini akan menghapus semua data perpesanan. Masukkan password dan konfirmasi untuk melanjutkan.</div>
            <div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
                <span>Password salah</span>
            </div>                            
            <form class="form-hapus-pesan" method="post">
                <div class="input-field col s12">
                    <input id="password" type="password" class="password" name="password">
                    <label for="password" style="width:fit-content; background-color:#fafafa">Password</label>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right btn-tambah right"><span>Konfirmasi</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus ticketing -->
<div id="modalHapusTicketing" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus E-ticketing</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">
        <div class="panel">Aksi ini akan menghapus semua data ticket permintaan pindah shift dan pesan mahasiswa. Masukkan password dan konfirmasi untuk melanjutkan.</div>
            <div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
                <span>Password salah</span>
            </div>                            
            <form class="form-hapus-ticket" method="post">
                <div class="input-field col s12">
                    <input id="password2" type="password" class="password2" name="password">
                    <label for="password2" style="width:fit-content; background-color:#fafafa">Password</label>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right btn-tambah right"><span>Konfirmasi</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal reset pembayaran -->
<div id="modalResetPembayaran" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Reset Pembayaran Praktikum</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">
        <div class="panel">Aksi ini akan mereset status pembayaran praktikum seluruh mahasiswa menjadi belum lunas. Masukkan password dan konfirmasi untuk melanjutkan.</div>
            <div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
                <span>Password salah</span>
            </div>                            
            <form class="form-reset-pembayaran" method="post">
                <div class="input-field col s12">
                    <input id="password1" type="password" class="password1" name="password">
                    <label for="password1" style="width:fit-content; background-color:#fafafa">Password</label>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right btn-tambah right"><span>Konfirmasi</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>