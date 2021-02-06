<?php 
    $user = $this->db->where('dosen_id', $this->session->userdata('dosen_id'))->get('t_dosen')->row();    
    $lab_url = strtolower(($this->input->get('ruangan')));
    $rid = $this->input->get('rid');   
    $lid = $this->input->get('lid');   
    $laporan = $this->input->get('laporan');   
?>

<style>
    .table-biasa td {
        vertical-align: middle;
    }    
</style>
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
                <a class="nav__link" href="#!" onclick="window.history.go(-1)"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                <span class="panel-title-2">Laporan <?=$this->input->get('laporan')?></span>
                <div class="line"></div>
                <div class="right-align">
                    <a target="_blank" href="<?=base_url()?>dosen/inventori/cetak_inventori?rid=<?=$rid?>&lid=<?=$lid?>&laporan=<?=$laporan?>" type="button" class="tombol tombol-sm tombol-primary"><i class="material-icons left">print</i><span>Cetak Laporan</span></a>
                </div>                
                <div class="table-wrapper">
                    <div class="panel-title-2 center-align grey-text" style="margin-bottom:12px">Laporan Barang</div>
                    <table class="table-biasa striped">
                        <thead>
                            <tr class="blue lighten-5">
                                <th rowspan="2" class="center blue-text text-accent-3" width="30">#</th>
                                <th rowspan="2" class="center">Nama Barang</th>
                                <th rowspan="2" class="center" width="80">Jumlah</th>
                                <th class="center" colspan="3">Kondisi</th>
                                <th rowspan="2" width="250" class="center">Keterangan</th>
                            </tr>                            
                            <tr>                                
                                <th class="center" width="80">Bagus</th>
                                <th class="center" width="80">Rusak</th>
                                <th class="center" width="80">Hilang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($barang->result() as $key => $brg) : 
                                if ($brg->keterangan == null) {
                                    $keterangan = '-';                                    
                                } else {
                                    $keterangan = $brg->keterangan;
                                }
                                $merek_brg = $brg->merek_nama;
                                if ($brg->merek_id == 0) {
                                    $merek_brg = $brg->kategori_nama.' - TANPA MEREK';
                                }
                                ?>  
                                <tr>
                                    <td class="center"><?=$key+1?></td>
                                    <td><?=$brg->kategori_nama.' - '.$merek_brg.' '.$brg->barang_tipe?></td>
                                    <td class="center"><?=$brg->barang_jumlah?></td>
                                    <td class="center"><?=$brg->kond_bagus?></td>
                                    <td class="center"><?=$brg->kond_rusak?></td>
                                    <td class="center"><?=$brg->kond_hilang?></td>                                                       
                                    <td><?=$keterangan?></td>                                                       
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-wrapper">
                    <div class="panel-title-2 center-align grey-text" style="margin-bottom:12px">Laporan PC</div>
                    <table class="table-biasa highlight">
                        <thead>
                            <tr class="blue lighten-5">
                                <th rowspan="2" class="center blue-text text-accent-3" width="30">#</th>
                                <th rowspan="2" class="center">Nama PC</th>
                                <th rowspan="2" class="center">Komponen</th>
                                <th class="center" colspan="3">Kondisi</th>
                                <th rowspan="2" width="250" class="center">Keterangan</th>
                            </tr>                            
                            <tr>                                
                                <th class="center" width="80">Bagus</th>
                                <th class="center" width="80">Rusak</th>
                                <th class="center" width="80">Hilang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pc->result() as $key => $pclab) : 
                                $komponen = $this->M_database->getLaporanKomponenPC($pclab->pc_id, $lid);
                                $jml_komponen = $this->M_database->countWhere('t_pc_komponen', array('pc_id' => $pclab->pc_id, 'pck_status' => 1)) + 1;
                                ?>
                                <tr>
                                    <td rowspan="<?=$jml_komponen?>"><?=$key+1?></td>
                                    <td rowspan="<?=$jml_komponen?>" class="center"><?=$pclab->pc_nama?></td>                                        
                                </tr>                                    
                                <?php foreach ($komponen->result() as $komp) : 
                                    if ($komp->keterangan == null) {
                                        $keterangan = '-';                                    
                                    } else {
                                        $keterangan = $komp->keterangan;
                                    }
                                    $merek_brg = $komp->merek_nama;
                                    if ($komp->merek_id == 0) {
                                        $merek_brg = 'TANPA MEREK';
                                    }
                                    $bagus = "";
                                    $rusak = "";
                                    $hilang = "";
                                    if ($komp->kondisi == 1) {
                                        $bagus = 'checked';
                                    } else if ($komp->kondisi == 2) {
                                        $rusak = 'checked';
                                    } else {
                                        $hilang = 'checked';
                                    }
                                    ?>
                                        <tr>
                                            <td><?=$komp->kategori_nama.' - '.$merek_brg.' '.$komp->barang_tipe?></td>
                                            <td class="center">
                                                <p>
                                                    <label>
                                                        <input class="check-radio" <?=$bagus?> disabled type="radio"/>
                                                        <span></span>
                                                    </label>
                                                </p>
                                            </td>
                                            <td class="center">
                                                <p>
                                                    <label>
                                                        <input class="check-radio" <?=$rusak?> disabled type="radio"/>
                                                        <span></span>
                                                    </label>
                                                </p>
                                            </td>
                                            <td class="center">
                                                <p>
                                                    <label>
                                                        <input class="check-radio" <?=$hilang?> disabled type="radio"/>
                                                        <span></span>
                                                    </label>
                                                </p>
                                            </td>
                                            <td><?=$keterangan?></td>                                                       
                                        </tr>
                                        <?php endforeach ?>
                                        <tr>
											<td colspan="7" class="indigo lighten-5"></td>
										</tr>                                                                                   
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>                                                                						                                                                                  
            </div>           
        </div>            
    </div>
</div>
