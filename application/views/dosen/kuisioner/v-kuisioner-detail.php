<?php 
    $user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();  
    $uname = $this->input->get('asisten');    
    $prid = $this->input->get('prid');    
    $periodeP = $this->db->select('periode_nama')->where('periode_id', $prid)->get('t_periode')->row()->periode_nama;
    $asistenNama = $this->db->select('asisten_nama')->where('username', $uname)->get('t_asisten')->row()->asisten_nama;
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
                <a class="nav__link" href="<?= base_url() ?>dosen/kuisioner?prid=<?=$prid?>"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                <span class="panel-title-2">Detail Nilai Kuisioner <?= $periodeP ?></span>
                <div class="line"></div>
                <div class="head-panel">
                    <div class="row" style="margin: 0">
                        <div class="head-p" style="align-items:center;position:relative">                                                                  
                            <div style="align-items:center">
                                <span>Asisten</span>
                                <span class="select2-wrapper">
                                    <select class="select2-with-image" onchange="location = this.value;">
                                            <?php 
                                            foreach ($asisten->result() as $key => $value): 

                                                if ($uname == $value->username) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }

                                                ?>
                                                <option <?= $selected ?> class="black-text" value="<?= base_url() ?>dosen/kuisioner-detail?prid=<?=$prid?>&asisten=<?= $value->username ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>	
                                            <?php endforeach ?>             
                                    </select>
                                </span>
                            </div>
                        </div>                                          
                    </div>     
                </div>               
                <br>
                    <div class="panel blue lighten-5">
                        <div>Berikut adalah detail hasil penilaian kinerja <b><?= $asistenNama ?></b>.</div>
                    </div>
                <br>                
                <!-- PERINGKAT -->
                <div id="kinerja">                    
                    <div class="table-wrapper">                     
                        <table class="striped">
                            <thead>                                    
                                <tr>
                                    <th></th>
                                    <th class="center">Uraian Kinerja</th>
                                    <th class="center" width="200px">Nilai Rata-Rata</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            for ($i=0; $i < count($kategori); $i++) { 
                            ?>
                                <tr class="green lighten-5">
                                    <th>#</th>
                                    <th colspan="2"><strong><?=$kategori[$i]['kategori']?></strong></th>
                                </tr>
                            <?php
                                for ($j=0; $j < count($uraian); $j++) { 
                                    if ($uraian[$j]['kategori'] == $kategori[$i]['kategori']) {
                            ?>
                                <tr>
                                    <td><?=$no++?></td>
                                    <td><?=$uraian[$j]['uraian']?></td>
                                    <td class="center"><?=$uraian[$j]['nilai']?></td>
                                </tr>
                            <?php
                                    }
                                }
                            }
                            ?>                                    
                            </tbody>
                        </table>
                        <!-- PENILAIAN DIRI -->                                            
                        <br>
                        <br>
                        <div style="margin-bottom:4px;margin-left:12px"><strong>Deskripsi kelebihan dan kekurangan diri :</strong></div>
                        <div class="panel teal lighten-5" style="white-space:pre-line;padding-top:0">
                            <?=@$diri[0]['deskripsi1']?>
                        </div>
                        <br>
                        <div style="margin-bottom:4px;margin-left:12px"><strong>Solusi mengatasi kekurangan diri :</strong></div>                                
                        <div class="panel teal lighten-5" style="white-space:pre-line;padding-top:0">                            
                            <?=@$diri[0]['deskripsi2']?>
                        </div>
                        <br>
                        <div style="margin-bottom:4px;margin-left:12px"><strong>Memanfaatkan potensi kelebihan diri :</strong></div>                                
                        <div class="panel teal lighten-5" style="white-space:pre-line;padding-top:0">                            
                            <?=@$diri[0]['deskripsi2']?>
                        </div>  
                        <br>          
                    </div>
                </div>
            </div>           
        </div>            
    </div>
</div>