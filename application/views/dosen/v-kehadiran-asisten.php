<?php 
    $user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();    
    $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $this->input->get('id_praktikum')))->row();
    $asisten_id = $this->input->get('asisten_id');
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 34px; position: relative; top: 4px">Kehadiran Asisten</span>
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
                <div class="col s12">
                    <?php if(isset($asistenkosong)) {?>
                        <div class="head-panel">
                            <div class="row" style="margin:0">
                                <div class="col" style="padding: 9.5px">
                                    <span>Kehadiran praktikum asisten</span>
                                </div>
                                <div class="col s12 m5">
                                    <span class="select2-wrapper">
                                        <select class="select2-with-image" onchange="location = this.value;">
                                            <?php 
                                            foreach ($asisten->result() as $key => $value): 

                                                if ($asisten_id == $value->asisten_id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                }

                                                ?>
                                                <option <?= $selected ?> class="black-text" value="<?= base_url() ?>dosen/kehadiran_asisten?asisten_id=<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>	
                                            <?php endforeach ?>             
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>                                                                                                                              					    
                    <?php if (!isset($asistenkosong)) : ?>						    
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
                                <select class="select2-with-image" onchange="location = this.value;">
                                        <option value="">PILIH ASISTEN</option>
                                    <?php 
                                    foreach ($asisten->result() as $key => $value): ?>
                                        <option value="<?= base_url() ?>dosen/kehadiran_asisten?asisten_id=<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>
                                    <?php endforeach ?>
                                </select>
                            </span>
                        </div>                                         		                                            	
                    <?php endif ?> 
                    <?php if (isset($asistenkosong)) { ?>
                        <div class="table-wrapper">
                            <br>                        
                            <table>                                    
                                <thead>
                                    <tr>
                                        <th class="center" rowspan="2" style="background-color: #e3f2fd; border-top-left-radius: 12px;"><span style="position:relative; top: 26px">ID Praktikum</span></th>                                            
                                        <th class="center" colspan="10" style="background-color: #e3f2fd; border-top-right-radius: 12px;">Pertemuan</th>                                            
                                    </tr>
                                    <tr class="center">											
                                        <?php 
                                        for ($i=1; $i <= 10; $i++) { ?> 
                                            <td class="center" style="border: 2px solid #fff;border-radius:5px; font-weight: 600; background-color: #e3f2fd"><?= $i ?></td>										    												
                                        <?php } ?>										 										    
                                        </tr>
                                </thead>        
                                <tbody>                                    
                                    <?php
                                        foreach ($jadwal->result() as $key => $value) { ?>
                                            <tr class="center">
                                                <td class="center grey-text text-darken-2" style="border-top: 2px solid #fff;border-right: 2px solid #fff;font-weight:500; background-color: #e3f2fd"><?= strtoupper($value->jadwal_kode) ?></td>
                                        <?php
                                            for ($i=1; $i <= 10; $i++) { 
                                                $cek_asisten = $this->M_database->cekdataAbsen('t_absensi_asisten', 'asisten_id', 'jadwal_kode', 'pertemuan', $this->input->get('asisten_id'), $value->jadwal_kode, $i);
    
                                                $color= 'red lighten-4';
                                                $icon = '<i class="material-icons red-text text-accent-4">close</i>';
    
                                                if ($cek_asisten == 1) {
                                                    $color= 'green lighten-4';
                                                    $icon = '<i class="material-icons green-text text-accent-4">check</i>';
                                                } 
    
                                                ?>
                                                <td class="center"><div class="<?= $color ?>" style="padding: 8px 8px 6px; border-radius: 5px"><?= $icon ?></div></td>                                                	
                                            <?php } ?>                                                                                                      
                                            </tr>                                                
                                    <?php } ?>
                                </tbody>
                            </table>
                            <br>
                            <br>                                                   
                        </div>                    
                    <?php } ?>
                </div>                    
            </div>
        </div>
    </div>
</div>