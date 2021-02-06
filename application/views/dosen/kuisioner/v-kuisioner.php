<?php 
    $user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();    
    $prid = $this->input->get('prid');    
?>

<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Kuisoner Asisten</span>
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
                <span class="panel-title-2">Kuisioner Kinerja Asisten</span>
                <div class="line"></div>
                <div class="head-panel">
                    <div class="row" style="margin: 0">
                        <div class="head-p" style="align-items:center;position:relative">                                                                  
                            <div style="align-items:center">
                                <span>Periode</span>
                                <span class="select2-wrapper">
                                    <select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
                                    <option value="<?= base_url() ?>dosen/kuisioner">PILIH PERIODE</option>
                                        <?php 
                                            foreach ($periode->result() as $key => $prd):
                                                $selected = '' ; 
                                                if ($prid == $prd->periode_id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } 
                                                ?>
                                                <option <?= $selected ?> value="<?= base_url() ?>dosen/kuisioner?prid=<?=$prd->periode_id?>"><?= strtoupper($prd->periode_nama) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </span>
                            </div>
                        </div>                                          
                    </div>     
                </div>
                <?php if (isset($prid)) : ?> 
                <?php  $periodeP = $this->db->select('periode_nama')->where('periode_id', $prid)->get('t_periode')->row()->periode_nama; ?>               
                <br>
                    <div class="panel blue lighten-5">
                        <div>Berikut adalah nilai hasil kuisioner penilaian kinerja Asisten <?= $periodeP ?></div>
                    </div>
                <br>                                                                                                                                   
                <!-- PERINGKAT -->
                <div id="peringkat">                    
                    <div class="table-wrapper">                     
                        <table class="striped datatable-nopage">
                            <thead>
                                <tr>
                                    <th width="30" class="blue-text text-lighten-2 center">#</th>
                                    <th class="center"></th>
                                    <th>Kode Asisten</th>
                                    <th>Nama</th>
                                    <th class="center">Rerata Nilai</th>
                                    <th class="center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php                          
                                foreach($peringkat as $key => $value) { 
                                        $uraian = $this->M_kuisioner->getDetailRataAsisten($value['username']);
                                        $nilai = $this->M_kuisioner->getNilaiAsisten($value['username']);                                    
                                    ?>                                                                   
                                    <tr>
                                        <td class="center"><?=$key+1?></td>
                                        <td class="center"><img src="<?= base_url() ?>assets/images/profil/<?= $value['foto'] ?>" alt="profil foto" class="foto-profil-small"></td>
                                        <td><?=$value['username']?></td>
                                        <td><?=$value['asisten_nama']?></td>
                                        <td class="center"><?=$value['nilai']?></td>
                                        <td class="center"><a href="<?= base_url() ?>dosen/kuisioner-detail?prid=<?= $prid ?>&asisten=<?= $value['username'] ?>"><i class="material-icons-outlined">visibility</i></a></td>
                                    </tr>                       
                            <?php   } ?>                                    
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>                                
            </div>           
        </div>            
    </div>
</div>