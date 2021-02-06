<?php 
    $user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();    
    $lab_url = strtolower(($this->input->get('ruangan')));
    $rid = $this->input->get('rid');
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Inventori Laboratorium</span>
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
                <?= $user->dosen_nama ?>
                <i class="material-icons right" style="position:relative; bottom: 2px; right: 8px">arrow_drop_down</i>
                <div class="dd-body dd-body-profil">
                    <a href="<?=base_url()?>dosen/profil"><i class="material-icons left">face</i>Edit Profil</a>
                    <a href="<?=base_url()?>dosen/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>
                    <div class="center-align">
                    <button style="margin: 10px 0;" class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger" data-target="modalLogout" href="<?=base_url()?>auth/logout"><i class="material-icons left">exit_to_app</i><span>Logout</span></button>                    
                    </div>
                </div>
            </span>
            <div class="line"></div>
        </div> 
        <div class="col s12">
            <div class="main-panel">
                <span class="panel-title-2">Laporan Inventori</span>
                <div class="line"></div>
                <div class="head-panel">
                    <div class="row" style="margin: 0">
                        <div class="head-p" style="align-items:center;position:relative">                                                                  
                            <div style="align-items:center">
                                <span>Ruangan</span>
                                <span class="select2-wrapper">
                                    <select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
                                    <option value="<?= base_url() ?>dosen/inventori?menu=laporan">PILIH RUANGAN</option>
                                        <?php 
                                            foreach ($ruangan->result() as $key => $lab):
                                                $selected = '' ; 
                                                if ($lab_url == strtolower(str_replace(' ', '-', $lab->ruangan_nama))) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } 
                                                ?>
                                                <option <?= $selected ?> value="<?= base_url() ?>dosen/inventori?menu=laporan&rid=<?=$lab->ruangan_id?>&ruangan=<?=strtolower(str_replace(' ', '-', $lab->ruangan_nama))?>"><?= strtoupper($lab->ruangan_nama) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </span>
                            </div>
                        </div>                                          
                    </div>     
                </div>
                <?php if (isset($rid) && isset($lab_url)) : ?>                                             
                <div class="table-wrapper">                  
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
                                    <td><a class="nav__link" href="<?=base_url()?>dosen/inventori?menu=lihat-laporan&rid=<?=$rid?>&lid=<?=$value->laporan_id?>&laporan=<?=$value->laporan_nama?>"><?=$value->laporan_nama?></a></td>
                                    <td><?=$value->laporan_tgl?></td>
                                    <td><?=$value->asisten_nama?></td>
                                    <td class="center">
                                        <a target="_blank" href="<?=base_url()?>dosen/inventori/cetak_inventori?rid=<?=$rid?>&lid=<?=$value->laporan_id?>&laporan=<?=$value->laporan_nama?>" title="Cetak" class="waves-effect tombol-flat transparent" style="line-height:1.15;padding:0 6px"><i class="material-icons blue-text">assignment</i></a>                                        
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
