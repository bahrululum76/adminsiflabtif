<?php 
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }
    $lab_url = strtolower(($this->input->get('ruangan')));
    $rid = $this->input->get('rid');
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Inventori Laboratorium</span>
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
                <a class="nav__link" href="<?=base_url()?>asisten/inventori"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                <span class="panel-title-2">Laporan Inventori</span>
                <div class="line"></div>
                <div class="head-panel">
                    <div class="row" style="margin: 0">
                        <div class="head-p" style="align-items:center;position:relative">                                                                  
                            <div style="align-items:center">
                                <span>Ruangan</span>
                                <span class="select2-wrapper">
                                    <select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
                                    <option value="<?= base_url() ?>asisten/inventori?menu=laporan">PILIH RUANGAN</option>
                                        <?php 
                                            foreach ($ruangan->result() as $key => $lab):
                                                $selected = '' ; 
                                                if ($lab_url == strtolower(str_replace(' ', '-', $lab->ruangan_nama))) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } 
                                                ?>
                                                <option <?= $selected ?> value="<?= base_url() ?>asisten/inventori?menu=laporan&rid=<?=$lab->ruangan_id?>&ruangan=<?=strtolower(str_replace(' ', '-', $lab->ruangan_nama))?>"><?= strtoupper($lab->ruangan_nama) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </span>
                            </div>
                        </div>                                          
                    </div>     
                </div>
                <?php if (isset($rid) && isset($lab_url)) : ?>                                             
                <div class="table-wrapper">
                    <button onclick="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');" value="<?=base_url()?>asisten/inventori?menu=buat-laporan&rid=<?=$rid?>&ruangan=<?=$lab_url?>" class="tombol tombol-sm tombol-primary"><i class="material-icons-outlined left">add</i><span>Buat Laporan</span></button>
                    <br>
                    <br>
                    <table class="highlight datatable">
                        <thead>                            
                            <tr>
                                <th class="blue-text text-accent-3">#</th>
                                <th>Nama Laporan</th>
                                <th>Tanggal Buat</th>
                                <th>Oleh</th>                                   
                                <th class="center" width="120">Aksi</th>                                   
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($laporan->result() as $key => $value) : ?>  
                                <tr>
                                    <td><?=$key+1?></td>
                                    <td><a class="nav__link" href="<?=base_url()?>asisten/inventori?menu=lihat-laporan&rid=<?=$rid?>&lid=<?=$value->laporan_id?>&laporan=<?=$value->laporan_nama?>"><?=$value->laporan_nama?></a></td>
                                    <td><?=$value->laporan_tgl?></td>
                                    <td><?=$value->asisten_nama?></td>
                                    <td class="center">
                                        <a target="_blank" href="<?=base_url()?>asisten/inventori/cetak_inventori?rid=<?=$rid?>&lid=<?=$value->laporan_id?>&laporan=<?=$value->laporan_nama?>" title="Cetak" class="waves-effect tombol-flat transparent" style="line-height:1.15;padding:0 6px"><i class="material-icons blue-text">assignment</i></a>
                                        <button title="hapus" class="waves-effect tombol-flat transparent modal-trigger btn-hapus-laporan" data-target="modalHapus"
                                            data-laporan_id="<?= $value->laporan_id ?>"
                                            data-laporan_nama="<?= $value->laporan_nama ?>"><i class="material-icons red-text text-lighten-1 ">delete</i>
                                        </button>
                                    </td>                                                                                              
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <?php endif ?>                                
            </div>           
        </div>            
    </div>
</div>

<!-- Modal hapus laporan -->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Hapus Laporan</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus laporan <b><span class="txt-laporan"></span></b> ?</div>        
        <form action="<?= base_url() ?>asisten/inventori/hapusLaporan?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" class="laporan_id" name="laporan_id" hidden>
                    <input type="text" name="laporan_nama" class="laporan_nama" hidden>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>    
</div>
