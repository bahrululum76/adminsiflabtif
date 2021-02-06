<?php 
    $user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();    
    $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $this->input->get('id_praktikum')))->row();    
    $periode = $this->db->limit(1)->order_by('periode_id DESC')->get('t_periode')->row();
    $mp = $this->input->get('mp');    
    $kk = $this->input->get('kelas');    
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 34px; position: relative; top: 4px">Nilai Praktikum</span>
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
            <form action="<?=base_url()?>dosen/nilai_praktikum" method="get" id="form-nilai">
                <input type="text" hidden name="mp" id="matkum-id" value="<?=$mp?>" required>
                <input type="text" hidden name="kelas" id="kelas-kode" value="<?=$kk?>" required>
            </form>
                <?php if (!isset($mp)) : ?>
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
                            <select class="select2 pilih_matkum">
                            <option value="">PILIH MATA PRAKTIKUM</option>
                                <?php 
                                    foreach ($jadwal->result() as $key => $value):                                          
                                        ?>
                                        <option value="<?=$value->matkum_id?>"><?= strtoupper($value->matkum) ?></option>
                                <?php endforeach ?>
                            </select>
                        </span>
                        <span class="select2-wrapper">
                            <select class="select2 pilih_kelas">
                            <option value="">PILIH KELAS</option>
                                <?php 
                                    foreach ($kls->result() as $key => $value):                                          
                                        ?>
                                        <option value="<?=$value->kelas_kode?>"><?= strtoupper($value->kelas_nama) ?></option>
                                <?php endforeach ?>
                            </select>
                        </span>
                    </div>                    	
                <?php endif ?>	                                                                                 
                <?php if (isset($mp)) : ?>                            	                           
                <div class="head-panel">
                    <div class="row" style="margin: 0">
                        <div class="head-p">
                            <div style="align-items:center">
                                <span>Mata Praktikum</span>
                                <span class="select2-wrapper">
                                    <select class="select2 pilih_matkum">
                                    <option value="">PILIH MATA PRAKTIKUM</option>
                                        <?php 
                                            foreach ($jadwal->result() as $key => $value):
                                                $selected = '' ; 
                                                if ($mp == $value->matkum_id) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } 
                                                ?>
                                                <option <?= $selected ?> value="<?=$value->matkum_id?>"><?= strtoupper($value->matkum) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </span>
                            </div>                                                   
                            <div style="align-items:center">
                                <span>Kelas</span>
                                <span class="select2-wrapper">
                                    <select class="select2 pilih_kelas">
                                    <option value="">PILIH KELAS</option>
                                        <?php 
                                            foreach ($kls->result() as $key => $value):
                                                $selected = '' ; 
                                                if ($kk == $value->kelas_kode) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } 
                                                ?>
                                                <option <?= $selected ?> value="<?=$value->kelas_kode?>"><?= strtoupper($value->kelas_nama) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </span>
                            </div>                            
                        </div>                       
                    </div>     
                </div>
                <br>
                <div class="row">                
                    <div class="col s12">                
                        <ul class="tabs">
                        <?php                                                    
                            foreach ($kelas->result() as $key => $value) { ?>
                                <li class="tab col"><a href="#<?=$value->jadwal_kode?>"><?=$value->jadwal_kode?></a></li>
                            <?php } ?>                            
                        </ul>
                    </div>
                </div>
                    <?php                                                    
                        foreach ($kelas->result() as $kelas) {
                            $asisten_1 = $this->db->where('asisten_id', $kelas->asisten_1)->get('t_asisten')->row();
                            $asisten_2 = $this->db->where('asisten_id', $kelas->asisten_2)->get('t_asisten')->row();
                            ?>
                            <div id="<?=$kelas->jadwal_kode?>">
                                <div class="col s12"><span class="panel-title-2">Asisten</span></div>                                      
                                <div class="col s12" style="display:flex;flex-wrap:wrap">
                                    <div class="jadwal-item" style="margin:8px 16px 0 0; display:flex; align-items:center;background-color:ghostwhite;border:none;border-radius:12px">
                                        <img src="<?=base_url()?>assets/images/profil/<?=$asisten_1->foto?>" class="circle left" alt="" width="40">
                                        <span style="margin-left: 12px;font-weight:500"><?=$asisten_1->asisten_nama?></span>
                                    </div>
                                    <div class="jadwal-item" style="margin:8px 16px 0 0; display:flex; align-items:center;background-color:ghostwhite;border:none;border-radius:12px">
                                        <img src="<?=base_url()?>assets/images/profil/<?=$asisten_2->foto?>" class="circle left" alt="" width="40">
                                        <span style="margin-left: 12px;font-weight:500"><?=$asisten_2->asisten_nama?></span>
                                    </div>                                        
                                </div>
                                <div class="table-wrapper">
                                    <br>                                                                                                                       
                                    <table class="highlight">                                    
                                        <thead>                                    
                                            <tr>
                                                <th width="50px" class="blue-text text-accent-3">#</th>
                                                <th width="150px">NPM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th class="center" width="90px">Kehadiran<br><?= $kelas->p_kehadiran ?> %</th>
                                                <th class="center" width="90px">Tugas<br><?= $kelas->p_tugas ?> %</th>
                                                <th class="center" width="90px">Ujian<br><?= $kelas->p_ujian ?> %</th>
                                                <th class="center" width="90px">Nilai Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $mhs = $this->db->select('nama, t_mahasiswa.kelas_kode, kelas_nama, t_mahasiswa.npm, nilai_tugas, nilai_ujian')->order_by('nama ASC')->join('t_mahasiswa', 'registrasi_praktikum.npm=t_mahasiswa.npm')->join('t_kelas', 't_mahasiswa.kelas_kode=t_kelas.kelas_kode')->where('jadwal_kode', $kelas->jadwal_kode)->get('registrasi_praktikum');
                                                foreach ($mhs->result() as $key => $value) { 
                                                    $kehadiran = $this->M_database->kehadiran($kelas->jadwal_kode, $value->npm);
                                                    $nilai_tugas = $this->M_database->nilai_tugas($kelas->jadwal_kode, $value->npm);
                                                    $total_kehadiran = $kehadiran->kehadiran * 10;
                                                    $nilai = $value->nilai_tugas*$kelas->p_tugas/100;
                                                    $total = ($total_kehadiran*$kelas->p_kehadiran/100) + $nilai + ($value->nilai_ujian*$kelas->p_ujian/100);
                                                    $beda_kelas = "";
                                                    if ($value->kelas_kode != $kk) {
                                                        $beda_kelas = '<span class="badge-status badge-not left" style="margin-left:20px;">'.$value->kelas_nama.'</span>';
                                                    }
                                                    ?>
                                                    <tr>                                                                        
                                                        <td><?= $key+1 ?></td>
                                                        <td><?= $value->npm ?></td>
                                                        <td><span class="left"><?= $value->nama ?></span> <?= $beda_kelas ?></td>
                                                        <td class="center-align"><?= $total_kehadiran ?></td>
                                                        <td class="center-align"><?= $value->nilai_tugas ?></td>
                                                        <td class="center-align"><?= $value->nilai_ujian ?></td>
                                                        <td class="center-align"><?= $total ?></td>                                                                                                     
                                                    </tr>
                                                <?php } ?>                            
                                        </tbody>                                   
                                    </table>
                                    <br>
                                    <br>
                                </div>
                            </div>
                        <?php } ?>                                                                                                                
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>