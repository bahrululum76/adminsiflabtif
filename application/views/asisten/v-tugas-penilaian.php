<?php 
    $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $this->input->get('idp')))->row();
    $tugas_kode = $this->input->get('tk');
    $jadwal_kode = $this->input->get('idp');
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }
    $current_tugas = $this->input->get('t');                                              
    $current_jadwal = $this->input->get('idp');    
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Tugas Praktikum</span>
            <?php if ($this->session->userdata('jabatan_id') == 6) : 
                $jmlPesanMhsHtml = 'hide';
                $jmlTicketHtml = 'hide';
                if ($jmlPesanMhs > 0) {
                    $jmlPesanMhsHtml = '';
                }    
                if ($jmlTicket > 0) {
                    $jmlTicketHtml = '';
                }    
            ?>                
            <a href="<?=base_url()?>admin/ticketing" class="tooltipped" data-position="bottom" 
            data-tooltip="<div class='tooltip-html'><div class='tooltip-html-title'>E-ticketing</div><div class='tooltip-html-item tooltip-html-item-first <?=$jmlPesanMhsHtml?>'><?=$jmlPesanMhs?> pesan belum dibaca</div><div class='tooltip-html-item <?=$jmlTicketHtml?>'><?=$jmlTicket?> tiket pending</div></div>">            
                <span style="display: inline-block; margin-right: 24px; position: relative; top: 8px; cursor:pointer">
                    <span class="pulse-ticket hide" style="margin: 4px; animation: shadow-pulse 1s infinite;display:block; width: 6px; height: 6px; background-color: #0077ff; border-radius: 10px; position:absolute; right: -10px; top: -10px">
                    </span>
                    <i class="material-icons-outlined grey-text text-darken-2">confirmation_number</i>                    
                </span>            
            </a>
            <?php endif ?>
            <span class="dd-pesan" style="display: inline-block; margin-right: 24px; position: relative; top: 8px">
                <span class="pulse-pesan hide" style="margin: 4px; animation: shadow-pulse-dots 1s infinite;display:block; width: 6px; height: 6px; background-color: #ff5252; border-radius: 10px; position:absolute; right: -10px; top: -10px">
                </span>
                <i class="material-icons-outlined grey-text text-darken-2">email</i>
                <div class="dd-body dd-body-pesan">
                    <div class="head">
                        <span>Pesan Masuk</span><span class="right counter" id="counter">0</span>
                    </div>
                    <div id="notif-pesan">
                        <div style="padding: 12px; text-align: center">Tidak ada pesan</div>
                    </div>                    
                </div>
            </span>
            <img src="<?= base_url() ?>assets/images/profil/<?=$user->foto?>" alt="profil" height="32" class="circle">
            <span class="dd-nama dd-trigger">
                <?= $panggilan ?>
                <i class="material-icons right" style="position:relative; bottom: 2px; right: 8px">arrow_drop_down</i>
                <div class="dd-body dd-body-profil">
                    <a href="#!" class="modal-trigger" data-target="modalLihatAsisten"><i class="material-icons left">credit_card</i>Lihat Profil</a>
                    <a href="<?=base_url()?>asisten/profil"><i class="material-icons left">face</i>Edit Profil</a>
                    <a href="<?=base_url()?>asisten/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>
                    <?php if($this->session->userdata('jabatan_id') == 6) { echo '<a href="'.base_url().'admin/dashboard"><i class="material-icons left">admin_panel_settings</i>Halaman Admin</a>'; } ?>
                    <div class="center-align">
                    <button style="margin: 10px 0;" class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger" data-target="modalLogout" href="<?=base_url()?>auth/logout"><i class="material-icons left">exit_to_app</i><span>Logout</span></button>                    
                    </div>
                </div>
            </span>
            <div class="line"></div>
        </div>       
            <div class="col s12">
                <div class="main-panel">
                    <div class="row">                        
                        <div class="col s12">
                            <div class="col s12">
                                <a class="nav__link" href="<?= base_url() ?>asisten/tugas?idp=<?= $jadwal_kode ?>"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                                <span class="panel-title-2">Penilaian Tugas</span>                               
                            </div>
                            <br>
                            <br>
                            <div class="col s12">
                                <div class="head-panel">
                                    <div class="head-p" style="align-items:baseline">
                                        <div style="align-items:center">
                                            <span>ID Tugas</span>
                                            <span class="select2-wrapper">
                                                <select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
                                                    <?php 
                                                        foreach ($tugas->result() as $key => $value) :
                                                        $selected = '' ; 
                                                        if ($tugas_kode == $value->tugas_kode) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        } 
                                                    ?>
                                                    <option <?= $selected ?>
                                                        value="<?= base_url() ?>asisten/tugas?idp=<?= $jadwal_kode ?>&t=<?= $current_tugas ?>&tk=<?= $value->tugas_kode ?>">
                                                        <?= strtoupper($value->tugas_kode) ?></option>
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
                                            <span>Kelas</span>
                                            <span>:</span>
                                            <span class="isi"><?= $jadwal_ini->kelas_nama ?></span>
                                        </div>                                    
                                        <div>
                                            <span>Tugas</span>
                                            <span>:</span>
                                            <span class="isi"><?= $tugas_ini->tugas_judul ?></span>
                                        </div>                                    
                                    </div>
                                </div>                                
                            </div>                
                            <!-- Table data tugas  -->
                            <div class="col s12">
                                <br>
                                <div class="right-align">
                                    <button class="tombol waves-effect tombol-sm tombol-danger modal-trigger" data-target="modalHapus"><i class="material-icons-outlined left">delete</i><span>Hapus File</span></button>                                                                     
                                </div>                              
                                <div class="table-wrapper">
                                <form action="<?= base_url() ?>asisten/asisten_tugas/hapusFileTugas" method="post" class="form-hapus-file" id="form-hapus-file">                             
                                    <table class="highlight datatable-nopage">                                    
                                        <thead>
                                            <tr>
                                                <th class="blue-text text-accent-3"></th>
                                                <th width="120">NPM</th>
                                                <th width="200">Nama Mahasiswa</th>
                                                <th class="center">Tanggal Upload</th>
                                                <th class="center">Nilai Tugas</th>
                                                <th class="center" width="80">Aksi</th>
                                            </tr>
                                        </thead>            
                                        <tbody>                                    
                                            <?php
                                                foreach ($mahasiswa->result() as $key => $value) { ?>
                                                <?php                                                                                                    
                                                    $file_tugas = $this->db->query("SELECT COUNT(file_id) AS exist FROM t_tugas_file WHERE tugas_id='$current_tugas' AND npm='$value->npm'")->row();
                                                    $nilai_tugas = $this->db->query("SELECT COUNT(nilai_id) AS exist FROM t_tugas_nilai WHERE tugas_id='$current_tugas' AND npm='$value->npm'")->row();
    
                                                    $nilais = $this->db->where(array('tugas_id' => $current_tugas, 'npm' => $value->npm))->get('t_tugas_nilai')->row();
                                                    $file = $this->db->where(array('tugas_id' => $current_tugas, 'npm' => $value->npm))->get('t_tugas_file')->row();
                                                                                                                                            
                                                    if ($file_tugas->exist == 1) {                                                    
                                                        $marker = 'green lighten-4';
                                                        $marker_2 = 'red lighten-4';
                                                        $color = 'green-text text-accent-4';
                                                        $file_name = $file->file_nama;
                                                        $disabled = "";
                                                    } else {                                                    
                                                        $marker = '';
                                                        $marker_2 = '';
                                                        $color = 'grey-text';
                                                        $file_name = "nofile";
                                                        $disabled = "disabled";
                                                    }

                                                    if ($nilai_tugas->exist == 1) {
                                                        $nilai = $nilais->nilai;
                                                        $tgl_upload = $nilais->tgl_upload;
                                                    } else {
                                                        $nilai = "0";
                                                        if ($file_tugas->exist == 1) {
                                                            $tgl_upload = $file->tgl_upload;
                                                        } else {
                                                            $tgl_upload = '-';
                                                        }
                                                    }
                                                ?>  
                                                    <tr>
                                                        <td>                                               
                                                            <label>
                                                                <input type="checkbox" class="filled-in" value="<?= $file_name ?>" <?= $disabled ?> name="file_tugas[]">
                                                                <span class="checkbox" style="left: 10px"></span>
                                                            </label>											
                                                        </td> 
                                                        <td><?= $value->npm ?></td>
                                                        <td><?= $value->nama ?></td>
                                                        <td class="center"><?=$tgl_upload?></td>
                                                        <td class="td-data center tugas" id="<?= $value->npm ?>">
                                                            <span class="text-nilai<?= $value->npm ?> muncul"><?= $nilai ?></span>
                                                            <input type="number" value="<?= $nilai ?>" class="input-nilai<?= $value->npm ?> hilang browser-default" min="0" max="100" title="Max nilai 100">
                                                            <input type="text" hidden="hidden" value="<?= $this->input->get('t'); ?>" name="tugas_id" class="tugas_id">
                                                            <input type="text" hidden="hidden" value="<?= $this->input->get('idp'); ?>" name="jadwal_kode" class="jadwal_kode">
                                                            <input type="text" hidden="hidden" value="<?= $this->input->get('tk'); ?>" name="tugas_kode" class="tugas_kode">
                                                            <input type="text" name="tgl_upload" value="<?=$tgl_upload?>" hidden class="tgl_upload<?=$value->npm?>">
                                                        </td>
                                                        <td class="center">
                                                            <button type="button" class="waves-effect btn-small z-depth-0 grey-text text-darken-1 <?= $marker ?>" <?= $disabled ?> style="padding: 0;">
                                                                <a data-senna-off="true" href="<?= base_url() ?>assets/tugas_file/<?= $file_name ?>" download class="<?= $color ?>" style="padding: 0 12px">
                                                                    <i class="material-icons-outlined">save_alt</i>
                                                                </a>
                                                            </button>                                                            
                                                        </td>
                                                    </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    </form>
                                </div>                          	                                                                                          
                                <br>
                                <br>                                                   
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
    </div>
</div>

<!-- Kirim notifikasi -->
<div id="modalNotifikasi" class="modal modal-small">
    <div class="modal-content">
        <i class="material-icons medium blue-text text-accent-3">volume_up</i>
        <h4 class="right modal-title">Kirim Notifikasi</h4>
        <br>
        <br>
        <br>
        <div class="row">
            <form class="col s12" action="<?= base_url() ?>admin/pengumuman/kirimNotifikasi" method="post">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="judul" type="text" class="validate" name="judul" autocomplete="off" required>
                        <label for="judul">Judul Info</label>
                        <span class="helper-text">Contoh : Jadwal ujian praktikum</span>
                    </div>                  
                </div>                    
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-light btn-flat">Batal</button>
      <button type="submit" class="waves-effect waves-light btn mybutton-save">Kirim</button>
    </div>
    </form>
</div>

<!-- Modal hapus tugas-->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus File</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus file tugas?</div>
        <form class="col s12" action="<?= base_url() ?>asisten/asisten_tugas/hapusTugas" method="post">
            <input id="tugas_id" type="hidden" class="validate tugas_id" name="tugas_id">
            <input id="tugas_kode" type="hidden" class="validate tugas_kode" name="tugas_kode">
            <input id="jadwal_kode" type="hidden" name="jadwal_kode" value="<?= $current_jadwal ?>">
            <input type="text" class="tugas_foto" hidden="hidden" name="tugas_foto">
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-danger right" onclick="submitForm()"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<script>
    function submitForm() {    
        document.getElementById("form-hapus-file").submit();
    }
</script>