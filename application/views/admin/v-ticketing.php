<!-- <style>
    .tabs {
        height: 40px;
        /* border-radius: 5px; */
    }
    .tabs .tab {
        line-height: 40px;
        text-transform: none;
    }
    .tabs .tab a{
        color: rgba(17,92,179, 0.6);
        posistion: relative;
        font-weight: 600;
        z-index: 2;
    }
    .tabs .tab a.active{
        color: #fff;
    }
    .tabs .tab a:hover{
        color: rgba(17,92,179, 1);
    }

    .tabs .tab a:focus.active {
        background-color: rgba(38, 166, 154, 0);
        posistion: relative;
        z-index: 2;
        color: #fff;
        }
    .tabs .indicator {
        background-color:rgba(17,92,179, 0.8);
        height: 100%;
        z-index: 1;
        /* border-radius: 5px; */
    }
    ul:not(.browser-default)>li {
        list-style-type: none;
    }    
</style> -->

<div class="col s12">
    <div class="main-wrapper">        
        <div class="main-panel">
            <div class="row">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col"><a href="#pesanTab" class="active">Pesan Masuk</a></li>
                        <li class="tab col"><a href="#ticketTab">Permintaan Pindah Shift</a></li>
                    </ul>
                </div>
                <div id="pesanTab" class="col s12">
                    <br>                    
                    <!-- Table pesan  -->
                    <div class="col s12 table-wrapper">                                
                        <table class="highlight datatable-nopage">                                    
                            <thead>
                                <tr>
                                    <th class="blue-text text-accent-3 center" width="50">#</th>
                                    <th width="120">NPM</th>
                                    <th width="200">Nama</th>
                                    <th>Pesan</th>
                                    <th width="100"></th>
                                </tr>
                            </thead>
    
                            <tbody class="tbody-pesan-mhs">
                            <?php
                                foreach ($pesan_mhs->result() as $key => $value) { 
                                    
                                    $last_msg = $this->db->order_by('pesan_id DESC')->limit(1)->where(array('npm' => $value->npm))->get('t_pesan_mhs')->row();
                                    $pesan = $this->M_database->countWhere('t_pesan_mhs', array('npm' => $value->npm, 'pengirim' => 'mahasiswa', 'pesan_status' => 0));

                                    if ($pesan > 0) {
                                        $bg = 'green lighten-5';
                                        $pesan = '<span class="badge-status badge-ok">'.$pesan.'</span>';
                                    } else {
                                        $bg = '';
                                        $pesan = '<span class="badge-status badge-not">'.$pesan.'</span>';
                                    }
                                    
                                    ?>                                    
                                    <tr class="tr-data tr-pesan modal-trigger tr-<?= $value->npm ?> <?=$bg?>" data-target="modalPesan" data-kelas="<?= $value->kelas_nama ?>" data-npm="<?= $value->npm ?>" data-nama="<?= $value->nama ?>">
                                        <td class="center no-<?=$value->npm?>"><?= $key+1 ?></td>
                                        <td><?= $value->npm ?></td>
                                        <td><?= $value->nama ?></td>
                                        <td><span class="last_msg_<?= $value->npm ?>"><?= $last_msg->pesan ?></span></td>    
                                        <td class="jml-<?=$value->npm?>"><?=$pesan?></td>                                    
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="ticketTab" class="col s12">
                    <br>
                    <!-- Searchbar -->
                    <div class="searchbar-table right">
                        <input type="text" placeholder="Cari Ticket" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                        <i class="material-icons right">search</i>
                    </div> 
                    <!-- Table pindah shift  -->
                    <div class="col s12 table-wrapper">                                
                        <table class="highlight datatable">                                    
                            <thead>
                                <tr>
                                    <th class="blue-text text-accent-3">#</th>
                                    <th>Kode Tiket</th>
                                    <th>Tanggal</th>
                                    <th>NPM</th>
                                    <th>Nama</th>
                                    <th class="center">Status</th>
                                    <th class="center">Aksi</th>
                                </tr>
                            </thead>
    
                            <tbody>
                            <?php
                                foreach ($pindah_shift->result() as $key => $value) { 
                                    
                                    $status = '';
                                    if ($value->ticket_status == 0){ 
                                        $status = '<span class="badge-status yellow darken-1">Pending</span>';
                                    } else if ($value->ticket_status == 1) { 
                                        $status = '<span class="badge-status green accent-4 white-text">Diproses</span>';
                                    } else if ($value->ticket_status == 2) {
                                        $status = '<span class="badge-status blue white-text">Disetujui</span>';
                                    } else {
                                        $status = '<span class="badge-status red-gradient white-text">Dibatalkan</span>';
                                    }
                                    
                                    ?>                                    
                                    <tr>
                                        <td><?= $key+1 ?></td>
                                        <td><?= $value->ticket_kode ?></td>
                                        <td><?= $value->date_created ?></td>
                                        <td><?= $value->npm ?></td>
                                        <td><?= $value->nama ?></td>
                                        <td class="center" id="status<?=$value->ticket_id?>"><?= $status ?></td>
                                        <td class="center">
                                            <button id="btn<?=$value->ticket_id?>" class="waves-effect mybtn transparent blue-text text-accent-1 modal-trigger btn-pindah-shift" data-target="modalPindah"
                                                data-ticket_id="<?=$value->ticket_id?>"
                                                data-ticket_kode="<?=$value->ticket_kode?>"
                                                data-shift_asal="<?=$value->shift_asal?>"
                                                data-matkum_id="<?=$value->matkum_id?>"
                                                data-shift_tujuan="<?=$value->shift_tujuan?>"
                                                data-ticket_deskripsi="<?=$value->ticket_deskripsi?>"
                                                data-ticket_status="<?=$value->ticket_status?>"
                                                data-npm="<?=$value->npm?>"
                                                data-nama="<?=$value->nama?>"
                                                data-kelas="<?=$value->kelas_nama?>"
                                                data-date_created="<?=$value->date_created?>"
                                            ><i class="material-icons-outlined">visibility</i></button>
                                        </td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                
            </div>
        </div>
    </div>
</div>

<!-- jml pesan -->
<input type="text" class="jmlPesanMhs" value="0" hidden>
<input type="text" class="npm-mhs" value="mhs" hidden>

<!-- Modal Pindah -->
<div id="modalPindah" class="modal modal-tabs modal-select2" style="max-width: 720px">
    <div class="modal-head">
        <span class="modal-title">Permintaan Pindah Shift</span>
    </div>
    <div class="modal-content custom-modal-content" id="modal-det">
        <div class="row">
            <div class="col s12">
                <ul class="tabs" style="position: fixed;top: 50px;left: 0;border-top: 1px solid rgba(0,0,0,0.1);z-index:2">
                    <li class="tab col" id="detailbtn"><a href="#tabdetail" class="active" id="btnTabTicket">Detail Ticket</a></li>
                    <li class="tab col"><a href="#tabpesan" id="btnTabPesan">Pesan</a></li>
                </ul>
            </div>
            <div id="tabdetail" class="col s12" style="margin-top:32px">
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">Tanggal</span>
                </div>                
                <div class="col s12 m9 l9">            
                    <span style="display:inline-block;padding: 12px" class="tgl_ticket">: -</span>
                </div>                
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">Kode Ticket</span>
                </div>                
                <div class="col s12 m9 l9">            
                    <span style="display:inline-block;padding: 12px" class="ticket_kode">: -</span>
                </div>
                <div class="col s12">                
                    <div class="line-dashed" style="margin:8px 0"></div>
                </div>                
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">NPM</span>
                </div>                
                <div class="col s12 m9 l9">            
                    <span style="display:inline-block;padding: 12px" class="npm">: -</span>
                </div>                
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">Nama</span>
                </div>                
                <div class="col s12 m9 l9">            
                    <span style="display:inline-block;padding: 12px" class="nama">: -</span>
                </div>                
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">Kelas</span>
                </div>                
                <div class="col s12 m9 l9">            
                    <span style="display:inline-block;padding: 12px" class="kelas">: -</span>
                </div>                
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">Alasan Pindah</span>
                </div>                
                <div class="col s12 m9 l9">            
                    <span style="display:inline-block;padding: 12px; white-space:pre-wrap" class="ticket_deskripsi">: -</span>
                </div>
                <div class="col s12">                
                    <div class="line-dashed" style="margin:8px 0 16px"></div>
                </div>
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">Shift Asal</span>
                </div>                
                <div class="col s12 m9 l9">
                    <span class="left" style="display:inline-block;padding: 12px">:</span>            
                    <span class="select2-wrapper" style="max-width:200px">
                        <select class="select2-no-search shift_asal">

                        </select>
                    </span>
                </div>                
                <div class="col s12 m3 l3">            
                    <span style="display:inline-block;padding: 12px">Shift Tujuan</span>                    
                </div>                
                <div class="col s12 m9 l9">
                    <span class="left" style="display:inline-block;padding: 12px">:</span>            
                    <span class="select2-wrapper" style="max-width:200px">
                        <select class="select2 shift_tujuan">
                                   
                        </select>
                    </span>
                </div>
                <div class="col s12" id="jadwal-ticket" style="margin-top:20px;margin-bottom:20px">
                                       
                </div>                
                <div class="col s12 m3 l3">                   
                    <span style="display:inline-block;padding: 12px">Status</span>                    
                </div>                
                <div class="col s12 m9 l9">
                    <span class="left" style="display:inline-block;padding: 12px">:</span>            
                    <span class="select2-wrapper" style="max-width:200px">
                        <select class="select2 ticket_status">
                            <option value="0">PENDING</option>                            
                            <option value="2">SETUJUI</option>                            
                            <option value="3">BATALKAN</option>                           
                        </select>
                    </span>
                    <input type="text" hidden class="ticket_id">
                </div>
                <div class="modal-footer custom-modal-footer">
                    <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-simpan-pindah right"><i class="material-icons left">save</i><span>Simpan</span></button>
                    <button type="button" class="waves-effect waves-light transparent modal-close tombol tombol-sm tombol-flat right">Batal</button>
                </div>                
            </div>
            <div id="tabpesan" class="col s12">
                <div class="chat-content" style="background-color: #bbdefb; padding: 20px 20px 150px 20px; margin-top: 32px; border-radius:10px"></div>
                <div class="custom-modal-footer center-align" style="display:flex; justify-content:center; align-items:center">                    
                    <textarea class="materialize-textarea input-msg2" placeholder="Ketik pesan" style="border-radius:25px;border:none;margin:0; max-height: 100px; text-indent:0; padding: .8rem; overflow-y: auto; background-color:#eceff1" spellcheck="false"></textarea>
                    <div class="valign-wrapper send-loader hide" style="margin-left:16px;margin-right:16px">
                        <img src="<?=base_url()?>assets/images/loader/loader.gif" alt="loading">
                    </div>
                    <button class="btn-medium transparent btn-flat btn-send2"><i class="material-icons blue-text text-accent-4" style="font-size: 32px;">send</i></button>
                </div>
            </div>
        </div>
    </div>    
</div>

<input type="text" class="npm-mhs" hidden>

<!-- Modal Pesan -->
<div id="modalPesan" class="modal" style="max-width: 720px">
    <div class="modal-head" style="height:80px">
        <!-- <img src="<?=base_url()?>assets/images/profil/default-profil-l.jpg" alt="foto" width="50" class="circle"> -->
        <div class="modal-title-small">
            <div class="nama-pengirim" style="font-size:18px;font-weight:500">-</div>
            <div class="kelas-pengirim" style="font-weight:14px">-</div>
        </div>
    </div>
    <div class="modal-content custom-modal-content chat-content" style="background-color: #bbdefb; padding: 20px 20px 150px 20px;">
    </div>
    <div class="custom-modal-footer center-align" style="display:flex; justify-content:center; align-items:center">
        <input type="text" hidden class="npm">
        <input type="text" hidden class="nama">
        <input type="text" hidden class="date-pesan" value="<?=date('Y-m-d H:i:s')?>">
        <textarea class="materialize-textarea input-msg" placeholder="Ketik pesan" style="border-radius:25px;border:none;margin:0; max-height: 100px; text-indent:0; padding: .8rem; overflow-y: auto;background-color:#eceff1;height:45px" spellcheck="false"></textarea>        
        <div class="valign-wrapper send-loader hide" style="margin-left:7px;margin-right:6px">
            <div class="preloader-wrapper small active">
                <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
                </div>
            </div>
        </div>
        <button class="btn-medium transparent btn-flat btn-send"><i class="material-icons blue-text text-accent-4" style="font-size: 32px;">send</i></button>
    </div>
</div>