<?php 
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }     
?>
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Penggajian Praktikum</span>
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
                    <div class="head-panel">
                        <div class="row" style="margin: 0">
                            <div class="head-p-inline">                                                                
                                <div>
                                    <span>Pertemuan</span>
                                    <span class="select2-wrapper" style="width:60px">
                                        <select class="select2" disabled>
                                            <option selected value="1">1</option>                                            
                                        </select>
                                    </span>
                                </div>
                                <div>
                                    <span>Sampai</span>
                                    <span class="select2-wrapper" style="width:60px">
                                        <select class="select2-no-search" onchange="location = this.value;">
                                            <?php
                                            $selected = '' ;
                                            for ($i=1; $i <= 10; $i++) {
                                                if ($i == $sampai){
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } ?>										  													  	
                                                <option <?= $selected ?> value="<?= base_url() ?>asisten/penggajian?pertemuan=<?= $pertemuan ?>&sampai=<?= $i ?>&bulan=<?= $bulan ?>"><?= $i ?></option>
                                            <?php } ?>	  
                                        </select>
                                    </span>
                                </div>                                        
                                <div>
                                    <span>Bulan</span>
                                    <span class="select2-wrapper" style="width:60px">
                                        <select class="select2-no-search" onchange="location = this.value;">
                                            <?php
                                            $selected = '' ;
                                            for ($i=1; $i <= 4; $i++) {
                                                if ($i == $bulan){
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } ?>										  													  	
                                                <option <?= $selected ?> value="<?= base_url() ?>asisten/penggajian?pertemuan=<?= $pertemuan ?>&sampai=<?= $sampai ?>&bulan=<?= $i ?>"><?= $i ?></option>
                                            <?php } ?>	  
                                        </select>
                                    </span>
                                </div>                                        
                            </div>                                           
                        </div>                            
                    </div>
                    <br>
                    <div class="right-align">
                        <a target="_blank" href="<?=base_url()?>asisten/penggajian/cetak_penggajian?pertemuan=1&sampai=<?=$sampai?>&bulan=<?=$bulan?>" type="button" class="tombol tombol-sm tombol-primary"><i class="material-icons left">print</i><span>Cetak Penggajian</span></a>
                    </div>
                    <!-- Table data kritik saran  -->
                    <div class="table-wrapper">
                        <table class="table-biasa striped">                                    
                            <thead>
                                <tr>
                                    <th class="center blue-text text-accent-3" width="30">#</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th class="center">Jumlah</th>
                                    <th>Honor/Pertemuan</th>
                                    <th>Honor/Jabatan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
    
                            <tbody>
                            <?php
                                $totalJumlah = 0;
                                $totalHp = 0;
                                $totalHj = 0;
                                $totalSubTotal = 0;                                    
                                foreach ($asisten->result() as $key => $value) {                                    
                                $jumlah = 0;
                                for ($i=1; $i <= $sampai; $i++) {
                                    $totalPertemuan = $this->M_database->countWhere('t_absensi_asisten', array('pertemuan' => $i, 'asisten_id' => $value->asisten_id, 'status' => 1));
                                    $jumlah += $totalPertemuan;
                                }
                                
                                $hp = $value->honor_pertemuan * $jumlah;
                                $hj = $value->honor_perbulan * $bulan;
                                $subTotal = $hp + $hj;
                                $totalJumlah += $jumlah;                                   
                                $totalHp += $hp;
                                $totalHj += $hj;
                                $totalSubTotal += $subTotal;
                                ?>  
                                <tr>
                                    <td class="center"><?= $key+1 ?></td>
                                    <td><?= $value->asisten_nama ?></td>
                                    <td><?= $value->jabatan_nama ?></td>
                                    <td class="center"><?= $jumlah ?></td>
                                    <td><?= "Rp. ". number_format($hp, 0, '.', '.') ?></td>
                                    <td><?= "Rp. ". number_format($hj, 0, '.', '.') ?></td>
                                    <td><?= "Rp. ". number_format($subTotal, 0, '.', '.') ?></td>                                               
                                </tr>
                            <?php } ?>
                                <tr class="blue lighten-5">
                                    <th class="center" colspan="3">Total</th>                                    
                                    <td class="center"><?= $totalJumlah ?></td>                                    
                                    <td><?= "Rp. ". number_format($totalHp, 0, '.', '.') ?></td>
                                    <td><?= "Rp. ". number_format($totalHj, 0, '.', '.') ?></td>
                                    <td><?= "Rp. ". number_format($totalSubTotal, 0, '.', '.') ?></td>                                               
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <br>                                                   
                    </div>                    
                </div>
            </div>
        </div>            
    </div>
</div>