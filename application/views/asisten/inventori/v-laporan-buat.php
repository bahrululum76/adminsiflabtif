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
<style>    
    input[type=number]:not(.browser-default) {
        height: 25px;
        width: 40px;
        text-indent:0;
        text-align:center;
        font-size: 14px;
    }

    textarea.materialize-textarea {
        height: 27px;
        min-height: 20px;
        text-indent: 0;
        font-family: 'Helvetica', sans-serif;
        padding: 4px 8px;
        font-size: 14px;
        margin:6px 0 0;
    }

    .table-biasa td {
        vertical-align: middle;
        padding: 0 5px;
    }
</style>
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
                <a class="nav__link" href="#!" onclick="window.history.go(-1)"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                <span class="panel-title-2">Buat Laporan Inventori</span>
                <div class="line"></div>
                <div class="head-panel">
                    <div class="row" style="margin: 0">
                        <div class="head-p" style="align-items:center;position:relative">                                                                  
                            <div style="align-items:center">
                                <span>Ruangan</span>
                                <span class="select2-wrapper">
                                    <select class="select2-no-search" onchange="location = this.value;">
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
                                                <option <?= $selected ?> value="<?= base_url() ?>asisten/inventori?menu=buat-laporan&rid=<?=$lab->ruangan_id?>&ruangan=<?=strtolower(str_replace(' ', '-', $lab->ruangan_nama))?>"><?= strtoupper($lab->ruangan_nama) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </span>
                            </div>
                            <div style="align-items:center">
                                <span style="width:200px">Laporan bulan</span>
                                <input type="text" class="datepicker-inventori tgl-laporan-inv" placeholder="bulan/tahun" style="height:38px;margin-bottom:0;background-color:#FFF" readonly>
                            </div>
                        </div>                                          
                    </div>     
                </div>
                <form action="<?=base_url()?>asisten/inventori/buatLaporan?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
                    <div class="table-wrapper">
                        <div class="panel-title-2 center-align grey-text" style="margin-bottom:12px">Barang <?=ucwords(str_replace('-', ' ', $lab_url))?></div>
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
                                <input type="text" name="tanggal" class="tanggal_laporan" hidden>                                  
                                <?php foreach ($barang->result() as $key => $brg) : 
                                        $merek_brg = $brg->merek_nama;
                                        if ($brg->merek_id == 0) {
                                            $merek_brg = 'TANPA MEREK';
                                        }
                                    ?>  
                                    <input type="text" name="barang_id[]" value="<?= $brg->barang_id ?>" hidden>                                  
                                    <tr>
                                        <td><?=$key+1?></td>
                                        <td><?=$brg->kategori_nama.' - '.$merek_brg.' '.$brg->barang_tipe?></td>
                                        <td class="center"><?=$brg->barang_jumlah?></td>
                                        <td class="center"><input type="number" name="bagus[]"></td>
                                        <td class="center"><input type="number" name="rusak[]"></td>
                                        <td class="center"><input type="number" name="hilang[]"></td>                                                       
                                        <td class="center"><textarea type="text" name="keterangan[]" class="materialize-textarea"></textarea></td>                                                       
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-wrapper">
                        <div class="panel-title-2 center-align grey-text" style="margin-bottom:12px">PC <?=ucwords(str_replace('-', ' ', $lab_url))?></div>
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
                                    $komponen = $this->M_database->getKomponenPC($pclab->pc_id);
                                    $jml_komponen = $this->M_database->countWhere('t_pc_komponen', array('pc_id' => $pclab->pc_id, 'pck_status' => 1)) + 1;                                    
                                    ?>
                                    <tr>
                                        <td rowspan="<?=$jml_komponen?>"><?=$key+1?></td>
                                        <td rowspan="<?=$jml_komponen?>" class="center"><?=$pclab->pc_nama?></td>                                        
                                    </tr>                                    
                                    <?php foreach ($komponen->result() as $komp) : 
                                        $merek_brg = $komp->merek_nama;
                                        if ($komp->merek_id == 0) {
                                            $merek_brg = 'TANPA MEREK';
                                        }
                                        ?>
                                            <input type="text" name="pc_id[]" value="<?= $komp->pc_id ?>" hidden>                                  
                                            <input type="text" name="pck_id[]" value="<?= $komp->pck_id ?>" hidden>                                  
                                            <input type="text" name="komp_id[]" value="<?= $komp->barang_id ?>" hidden>                                  
                                            <tr>
                                                <td><?=$komp->kategori_nama.' - '.$merek_brg.' '.$komp->barang_tipe?></td>
                                                <td class="center">
                                                    <p>
                                                        <label>
                                                            <input class="check-radio" name="kondisi<?=$komp->pck_id?>" type="radio" value="1" checked/>
                                                            <span></span>
                                                        </label>
                                                    </p>
                                                </td>
                                                <td class="center">
                                                    <p>
                                                        <label>
                                                            <input class="check-radio" name="kondisi<?=$komp->pck_id?>" type="radio" value="2" />
                                                            <span></span>
                                                        </label>
                                                    </p>
                                                </td>
                                                <td class="center">
                                                    <p>
                                                        <label>
                                                            <input class="check-radio" name="kondisi<?=$komp->pck_id?>" type="radio" value="3" />
                                                            <span></span>
                                                        </label>
                                                    </p>
                                                </td>
                                                <td class="center"><textarea type="text" name="keterangan_pc[]" class="materialize-textarea"></textarea></td>                                                       
                                            </tr>                                            
                                        <?php endforeach ?>
                                        <tr>
											<td colspan="7" class="indigo lighten-5" style="padding:5px"></td>
										</tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="right-align">                    
                        <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-primary btn-simpan-laporan"><i class="material-icons left">save</i><span>Simpan Laporan</span></button>
                    </div>
                </form>                                                  						                                                                                  
            </div>           
        </div>            
    </div>
</div>
