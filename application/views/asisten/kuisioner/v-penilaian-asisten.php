<!-- Get deskripsi jadwal dan periode terakhir  -->
<?php 
    $id_praktikum = $this->input->get('id_praktikum');
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }
    $nilai = array(5 => "Sangat Baik", 4 => "Baik", 3 => "Cukup", 2 => "Kurang", 1 => "Sangat Kurang");
?>
<style>
    [type=radio]:checked+span, [type=radio]:not(:checked)+span {
        padding-left: 26px;
        margin-right: 20px;
        margin-bottom: 8px;
    }
    [type=radio].with-gap:checked+span:after, [type=radio]:checked+span:after {
        background-color: #448aff;
    }
    [type=radio].with-gap:checked+span:after, [type=radio].with-gap:checked+span:before, [type=radio]:checked+span:after {
        border: 2px solid #448aff;
    }
</style>                 
<div class="col s12">
    <div class="main-wrapper">
        <div class="top-panel right-align">
            <span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Kuisioner</span>
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
                <a class="nav__link" href="<?=base_url()?>asisten/kuisioner?menu=penilaian-asisten"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                <span class="panel-title-2">Penilaian Asisten</span>
                <div class="line"></div>
                <div class="col s12">                        
                    <div class="panel amber lighten-5">
                        <div>Anda akan melakukan penilaian kepada <b><?= $asisten->asisten_nama ?></b>.</div>
                    </div>                                    
                    <form method="post" action="<?=base_url()?>asistenkuisioner/storePenilaianAsisten">
                        <input type="hidden" name="kd_asisten" value="<?= $asisten->username ?>">
                        <div class="table-wrapper">
                            <table class="striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="center">Uraian Kinerja Asisten</th>
                                        <th width="65%" class="center">Penilaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                for ($i = 0; $i < count($kategori); $i++) {
                                ?>
                                    <tr class="green lighten-5">
                                        <th>#</th>
                                        <th colspan="2"><?=$kategori[$i]['kategori']?></th>
                                    </tr>                                

                                <?php
                                    for ($j = 0; $j < count($uraian); $j++) {
                                        if ($uraian[$j]['id_kategori'] == $kategori[$i]['id']) {
                                ?>
                                    <tr>
                                        <td><?=$no++?></td>
                                        <td><?=$uraian[$j]['uraian']?>
                                        <input type="hidden" name="id_uraian[<?=$j?>]" value="<?=$uraian[$j]['id']?>"/></td>
                                        <td class="center">
                                <?php  
                                            foreach ($nilai as $key => $value){
                                ?>                                           
                                                <label>
                                                    <input class="with-gap" type="radio" name="nilai[<?=$j?>]" value="<?=$key?>" required />
                                                    <span><?=$value?></span>
                                                </label>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php 
                                        } 
                                    }
                                }
                                ?>
                                    <tr class="blue lighten-5">
                                        <th colspan="3"><span style="visibility:hidden">Akhir Penilaian</span></th>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="input-field col s12">
                            <div style="margin-bottom:4px;padding-left:12px">Tuliskan saran anda untuk <b><?= $asisten->asisten_nama ?></b> :</div style="margin-bottom:4px;padding-left:12px">
                            <textarea style="text-indent:0;padding:16px;font-size:14px;" class="materialize-textarea" name="komentar" required></textarea>
                            <div style="padding-left:12px;font-size:12px;color:cornflowerblue;margin-top:16px">* Identitas anda dirahasiakan dan terenkripsi.</div>
                        </div>
                        <div class="right-align">
                            <button class="tombol tombol-sm tombol-primary" name="sbmtPenilaian"><i class="material-icons left">send</i><span>Submit penilaian</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>            
    </div>
</div>