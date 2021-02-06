<?php 
    $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $this->input->get('idp')))->row();
    $idp = $this->input->get('idp');
    $current_pertemuan = $this->input->get('p');
    $id_praktikum = $this->input->get('idp');
    $absen = $this->db->where(array('jadwal_kode' => $idp, 'pertemuan' => $current_pertemuan))->get('t_absensi')->row();
    if ($absen) {
        $tgl_absen = $absen->absen_tgl;
    } else {
        $tgl_absen = "-";
    }   
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Absensi Praktikum</a>                    
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
                                        <option>PILIH ID PRAKTIKUM</option>
                                    <?php 
                                        foreach ($jadwal->result() as $key => $value): ?>
                                            <option value="<?= base_url() ?>admin/absensi?idp=<?= $value->jadwal_kode ?>&p=1"><?= strtoupper($value->jadwal_kode) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </span>
                        </div>   	
                    <?php endif ?>                                            
                    <?php if (isset($idp)) : ?>
                    <div style="font-weight:600;padding-right:8px" class="right-align absen-date-2">
                        <i class="material-icons" style="font-size:1.2rem;margin-right:4px;position:relative;top:4px">today</i>
                        <?= $tgl_absen ?>
                    </div>
                    <div class="head-panel">
                        <div class="row" style="margin: 0">
                            <div class="head-p" style="align-items:center;position:relative">                                   
                                <span class="absen-date-1" style="position:absolute;right:0;font-weight:600"><i class="material-icons left" style="font-size:1.2rem;margin-right:8px">today</i><?= $tgl_absen ?></span>                                
                                <div style="align-items:center">
                                    <span>ID Praktikum</span>
                                    <span class="select2-wrapper">
                                        <select class="select2" onchange="location = this.value;">
                                            <?php 
                                                foreach ($jadwal->result() as $key => $value):
                                                    $selected = '' ; 
                                                    if ($id_praktikum == $value->jadwal_kode) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    } 
                                                    ?>
                                                    <option <?= $selected ?> value="<?= base_url() ?>admin/absensi?idp=<?= $value->jadwal_kode ?>&p=<?=$current_pertemuan?>"><?= strtoupper($value->jadwal_kode) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </span>
                                </div>
                                <div style="align-items:center">
                                    <span>Pertemuan</span>
                                    <span class="select2-wrapper" style="width:80px">
                                        <select class="select2-no-search" onchange="location = this.value;">
                                            <?php
                                            $selected = '' ;
                                            for ($i=1; $i <= 10; $i++) {
                                                if ($i == $current_pertemuan){
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } ?>										  													  	
                                                <option <?= $selected ?> value="<?= base_url() ?>admin/absensi?idp=<?= $idp ?>&p=<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>	  
                                        </select>
                                    </span>
                                </div>                                        
                            </div>
                            <br>
                            <div class="head-p">
                                <div>
                                    <span>Praktikum</span>
                                    <span>:</span>
                                    <span class="isi"><?= ucwords($jadwal_ini->matkum) ?></span>
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
                                    <span>Jadwal</span>
                                    <span>:</span>
                                    <span class="isi"> <?= $jadwal_ini->jadwal_hari ?>, <?= $jadwal_ini->jadwal_jam ?></span>
                                </div>
                                <div>
                                    <span>Ruangan</span>
                                    <span>:</span>
                                    <span class="isi"><?= $jadwal_ini->ruangan_nama ?></span>
                                </div>
                            </div>                                
                        </div>                            
                    </div>                                                                                                                                                 
                    <div class="table-wrapper">                                                                                                                           
                        <table class="highlight">                                    
                            <thead>
                                <tr>
                                    <th class="blue-text text-accent-3" width="50px">#</th>
                                    <th width="200px">Pengajar</th>
                                    <th>Nama Asisten</th>
                                    <th class="center" width="200px">Kehadiran</th>
                                </tr>
                            </thead>            
                            <tbody>
                                <?php if (isset($absen)): ?>                                      
                                    <?php                                                                                               
                                        $asisten= $this->db->where('jadwal_kode', $idp)->get('t_jadwal')->row();                                                
                                        $asisten_1 = $this->db->where('asisten_id', $asisten->asisten_1)->get('t_asisten')->row();
                                        $asisten_2 = $this->db->where('asisten_id', $asisten->asisten_2)->get('t_asisten')->row();

                                            // cek absensi asisten
                                        $cek_asisten_1 = $this->M_database->cekdataAbsen('t_absensi_asisten', 'asisten_id', 'jadwal_kode', 'pertemuan', $asisten->asisten_1, $idp, $current_pertemuan);
                                        $cek_asisten_2 = $this->M_database->cekdataAbsen('t_absensi_asisten', 'asisten_id', 'jadwal_kode', 'pertemuan', $asisten->asisten_2, $idp, $current_pertemuan);

                                        $kehadiran1 = '';                                              
                                        $kehadiran2 = '';

                                        if ($cek_asisten_1 != 0 ) {
                                            $kehadiran1 = '<span class="badge-status badge-ok">Hadir</span>';
                                            $button = ''; 
                                        }
                                        else
                                        {
                                            $kehadiran1 = '<span class="badge-status badge-not">Tidak Hadir</span>';
                                            $button = 'btn-konfirmasi-absen';

                                        }

                                        if ($cek_asisten_2 != 0 ) {
                                            $kehadiran2 = '<span class="badge-status badge-ok">Hadir</span>';
                                            $button = '';                                                    
                                        }
                                        else
                                        {
                                            $kehadiran2 = '<span class="badge-status badge-not">Tidak Hadir</span>';
                                            $button = 'btn-konfirmasi-absen';                                                                                                       
                                        }
                                    ?>  
                                        <tr>                                            
                                            <td>1</td>
                                            <td>Pengajar 1</td>
                                            <td><?= $asisten_1->asisten_nama ?></td>
                                            <td class="td-data center kehadiran-asisten asisten<?= $asisten_1->asisten_id ?>" id="<?= $asisten_1->asisten_id ?>"><?= $kehadiran1 ?></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Pengajar 2</td>
                                            <td><?= $asisten_2->asisten_nama ?></td>
                                            <td class="td-data center kehadiran-asisten asisten<?= $asisten_2->asisten_id ?>" id="<?= $asisten_2->asisten_id ?>"><?= $kehadiran2 ?></td>
                                        </tr>
                                <?php endif ?>                            
                            </tbody>
                        </table>
                    </div>            
                    <div class="table-wrapper">                            	                                                                                          
                        <div class="line-table"></div>                        
                        <table class="highlight">                                    
                            <thead>
                                <tr>
                                    <th class="blue-text text-accent-3" width="50px">#</th>
                                    <th width="200px">NPM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th class="center" width="200px">Kehadiran</th>
                                </tr>
                            </thead>
    
                            <tbody>                                    
                                <?php
                                    if (isset($absen)) {                                        		                                        	
                                        foreach ($mahasiswa->result() as $key => $value) { ?>
                                        <?php                                                
                                            // cek absensi
                                            $cek_absen = $this->M_database->cekdataAbsen('t_absensi', 'npm', 'jadwal_kode', 'pertemuan', $value->npm, $idp, $current_pertemuan);
                                            $kehadiran = '';                                              
                                        
                                            if ($cek_absen != 0) {
                                                $kehadiran = '<span class="badge-status badge-ok">Hadir</span>';                                                   
                                            }
                                            else
                                            {
                                                $kehadiran = '<span class="badge-status badge-not">Tidak Hadir</span>';                                                                                                       
                                            }
                                        ?>  
                                            <tr>
                                                <input type="hidden" name="jadwal_kode" class="jadwal_kode" value="<?= $idp ?>">                                                             
                                                <input type="hidden" name="pertemuan" class="pertemuan" value="<?= $current_pertemuan ?>">                                                             
                                                <input type="hidden" name="tgl" class="tgl" value="<?= $absen->absen_tgl ?>">                                                             
                                                <td><?= $key+1 ?></td>
                                                <td><?= $value->npm ?></td>
                                                <td><?= $value->nama ?></td>
                                                <td class="kehadiran td-data center" id="<?= $value->npm ?>"><?= $kehadiran ?></td>
                                            </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                        <br>
                        <br>                                                   
                    </div>
                    <?php endif ?>                    
                </div>
            </div>            
        </div>
    </div>
</div>