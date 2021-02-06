<?php 
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }
    $submenu = $this->input->get('sub');
    $lab_url = strtolower(($this->input->get('ruangan')));
    $rid = $this->input->get('rid');
    if ($submenu == 'barang') {
        $active1 = 'active';
        $active2 = '';
        $active3 = '';
    } else if ($submenu == 'kategori') {
        $active1 = '';
        $active2 = 'active';
        $active3 = '';        
    } else if ($submenu == 'merek') {
        $active1 = '';
        $active2 = '';
        $active3 = 'active';
    } else {
        $active1 = 'active';
        $active2 = '';
        $active3 = '';
    }
?>
<style>
    .select2-wrapper-barang {
        max-width: 1000px;
        box-shadow: none;
        border: 1px solid #cacaca;
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
                <a class="nav__link" href="<?=base_url()?>asisten/inventori"><i class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
                <span class="panel-title-2">Barang <?=ucwords(str_replace('-', ' ', $lab_url))?></span>
                <div class="line"></div>
                <div class="head-panel">
                    <div class="row" style="margin: 0">
                        <div class="head-p" style="align-items:center;position:relative">                                                                  
                            <div style="align-items:center">
                                <span>Ruangan</span>
                                <span class="select2-wrapper">
                                    <select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
                                    <option value="<?= base_url() ?>asisten/inventori?menu=barang">PILIH RUANGAN</option>
                                        <?php 
                                            foreach ($ruangan->result() as $key => $lab):
                                                $selected = '' ; 
                                                if ($lab_url == strtolower(str_replace(' ', '-', $lab->ruangan_nama))) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } 
                                                ?>
                                                <option <?= $selected ?> value="<?= base_url() ?>asisten/inventori?menu=barang&sub=barang&rid=<?=$lab->ruangan_id?>&ruangan=<?=strtolower(str_replace(' ', '-', $lab->ruangan_nama))?>"><?= strtoupper($lab->ruangan_nama) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </span>
                            </div>
                        </div>                                          
                    </div>     
                </div>
                <?php if (isset($rid)) : ?>
                <br>                                                						                                                                  
                <button type="button" class="waves-effect waves-light tombol tombol-sm tombol-primary modal-trigger btn-tambah-barang" data-target="modalBarang" data-action="<?=base_url()?>asisten/inventori/tambahBarang?rid=<?=$rid?>&ruangan=<?=$lab_url?>"><i class="material-icons left">add</i><span>Tambah Barang</span></button>
                <button class="waves-effect waves-light tombol tombol-sm tombol-warning modal-trigger" data-target="modalTambahKategori"><i class="material-icons left">category</i><span>Tambah Kategori</span></button>
                <button class="waves-effect waves-light tombol tombol-sm tombol-warning modal-trigger" data-target="modalTambahMerek"><i class="material-icons left">label</i><span>Tambah Merek</span></button>
                <br>
                <br>
                <ul class="tabs">
                    <li class="tab col"><a class="<?=$active1?>" href="#barang">Barang</a></li>
                    <li class="tab col"><a class="<?=$active2?>" href="#kategori">Kategori</a></li>
                    <li class="tab col"><a class="<?=$active3?>" href="#merek">Merek</a></li>
                </ul>
                <!-- BARANG -->
                <div id="barang" class="col s12">
                    <!-- Searchbar -->
                    <div class="searchbar-table right">
                        <input type="text" placeholder="Cari Barang" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                        <i class="material-icons right">search</i>
                    </div>
                    <br>
                    <br>
                    <div class="table-wrapper">
                        <table class="table-biasa striped datatable">
                            <thead>
                                <tr>
                                    <th class="blue-text text-accent-3">#</th>
                                    <th>Nama Barang</th>
                                    <th class="center">Jumlah</th>
                                    <th class="center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($barang->result() as $key => $brg) :
                                        $title = 'hapus';
                                        $modal = 'modalHapusBarang';
                                        $merek_brg = $brg->merek_nama;
                                        if ($brg->merek_id == 0) {
                                            $merek_brg = 'TANPA MEREK';
                                        }
                                    ?>                                    
                                    <tr>
                                        <td><?=$key+1?></td>
                                        <td><?=$brg->kategori_nama.' - '.$merek_brg.' '.$brg->barang_tipe?></td>
                                        <td class="center"><?=$brg->barang_jumlah?></td>
                                        <td class="center">
                                            <button class="waves-effect tombol-flat transparent modal-trigger btn-edit-barang" data-target="modalBarang" 
                                                data-action="<?=base_url()?>asisten/inventori/editBarang?rid=<?=$rid?>&ruangan=<?=$lab_url?>"
                                                data-barang_id="<?= $brg->barang_id ?>"
                                                data-merek_id="<?= $brg->merek_id ?>"
                                                data-ruangan_id="<?= $brg->ruangan_id ?>"
                                                data-barang_jumlah="<?= $brg->barang_jumlah ?>"
                                                data-kategori_id="<?= $brg->kategori_id ?>"
                                                data-barang_tipe="<?= $brg->barang_tipe ?>"><i class="material-icons-outlined amber-text">edit</i></button>
                                            <button title="<?= $title ?>" class="waves-effect tombol-flat transparent modal-trigger btn-hapus-barang" data-target="<?= $modal ?>"
                                                data-barang_id="<?= $brg->barang_id ?>"
                                                data-barang_nama="<?=$brg->kategori_nama.' - '.$brg->merek_nama.' '.$brg->barang_tipe?>"
                                                data-barang_tipe="<?= $brg->barang_tipe ?>"><i class="material-icons red-text text-lighten-1 ">delete</i>
                                            </button>
                                        </td>                                        
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- JENIS -->
                <div id="kategori" class="col s12">
                    <div class="table-wrapper">
                        <table class="table-biasa striped">
                            <thead>
                                <tr>
                                    <th class="blue-text text-accent-3">#</th>
                                    <th>Nama Kategori</th>
                                    <th class="center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kategori->result() as $key => $ktg) : 
                                        $cekKtg = $this->M_database->cekdata('t_inv_barang', 'kategori_id', $ktg->kategori_id);
                                        $title = 'hapus';
                                        $modal = 'modalHapusKategori';

                                        if ($cekKtg != 0) {
                                            $title = 'tidak bisa hapus';
                                            $modal = 'modalAlert';
                                        }
                                    ?>                                    
                                    <tr>
                                        <td><?=$key+1?></td>
                                        <td><?=$ktg->kategori_nama?></td>
                                        <td class="center">
                                            <button class="waves-effect tombol-flat transparent modal-trigger btn-edit-kategori" data-target="modalEditKategori"                                                
                                                data-kategori_id="<?= $ktg->kategori_id ?>"
                                                data-kategori_nama="<?= $ktg->kategori_nama ?>"><i class="material-icons-outlined amber-text">edit</i></button>
                                            <button title="<?= $title ?>" class="waves-effect tombol-flat transparent modal-trigger btn-hapus-kategori" data-target="<?= $modal ?>"
                                                data-kategori_id="<?= $ktg->kategori_id ?>"
                                                data-kategori_nama="<?= $ktg->kategori_nama ?>"><i class="material-icons red-text text-lighten-1 ">delete</i>
                                            </button>
                                        </td>                                       
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- MEREK -->
                <div id="merek" class="col s12">
                    <div class="table-wrapper">
                        <table class="table-biasa striped">
                            <thead>
                                <tr>
                                    <th class="blue-text text-accent-3">#</th>
                                    <th>Nama Merek</th>
                                    <th class="center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($merek->result() as $key => $mrk) : 
                                        $cekMrk = $this->M_database->cekdata('t_inv_barang', 'merek_id', $mrk->merek_id);
                                        $title = 'hapus';   
                                        $modal = 'modalHapusMerek';

                                        if ($cekMrk != 0) {
                                            $title = 'tidak bisa hapus';
                                            $modal = 'modalAlert';
                                        }
                                    ?>                                    
                                    <tr>
                                        <td><?=$key+1?></td>
                                        <td><?=$mrk->merek_nama?></td>
                                        <td class="center">
                                            <button class="waves-effect tombol-flat transparent modal-trigger btn-edit-merek" data-target="modalEditMerek"                                                
                                                data-merek_id="<?= $mrk->merek_id ?>"
                                                data-merek_nama="<?= $mrk->merek_nama ?>"><i class="material-icons-outlined amber-text">edit</i></button>
                                            <button title="<?= $title ?>" class="waves-effect tombol-flat transparent modal-trigger btn-hapus-merek" data-target="<?= $modal ?>"
                                                data-merek_id="<?= $mrk->merek_id ?>"
                                                data-merek_nama="<?= $mrk->merek_nama ?>"><i class="material-icons red-text text-lighten-1 ">delete</i>
                                            </button>
                                        </td>                                       
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif ?>
            </div>           
        </div>            
    </div>
</div>

<!-- Modal barang -->
<div id="modalBarang" class="modal modal-sm modal-select2">
    <div class="modal-head">
        <span class="modal-title" id="modal-title-barang">Tambah Barang</span>        
    </div>
    <div class="modal-content custom-modal-content">        
        <div class="row">                                       
            <form action="<?=base_url()?>asisten/inventori/tambahBarang?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post" class="form-inv-barang">
                <input type="text" name="barang_id" class="barang_id" hidden>
                <div class="input-field col s12">
                    <span class="select2-wrapper select2-wrapper-barang">
                        <select name="kategori" class="select2 select2-barang barang_ktg" required>
                            <option value="">PILIH KATEGORI</option>
                            <?php foreach ($kategori->result() as $key => $ktg) : ?>                                    
                                <option value="<?=$ktg->kategori_id?>"><?=strtoupper($ktg->kategori_nama)?></option>
                            <?php endforeach ?>
                        </select>
                    </span>
                </div>
                <div class="input-field col s12">
                    <span class="select2-wrapper select2-wrapper-barang">
                        <select name="merek" class="select2 select2-barang barang_mrk" required>
                            <option value="">PILIH MEREK</option>
                            <option value="0">TANPA MEREK</option>
                            <?php foreach ($merek->result() as $key => $mrk) : ?>                                    
                                <option value="<?=$mrk->merek_id?>"><?=strtoupper($mrk->merek_nama)?></option>
                            <?php endforeach ?>
                        </select>
                    </span>
                </div>
                <div class="input-field col s12">
                    <input id="add-tipe" placeholder="" type="text" name="tipe" class="barang_tipe">
                    <label for="add-tipe" style="width:fit-content; background-color:#fafafa">Kapasitas/Tipe</label>
                    <span class="grey-text text-lighten-1" style="font-size:12px"> Tuliskan kapasitas atau tipe. Kosongkan jika tidak tahu.</span>
                </div>                  
                <div class="input-field col s12">
                    <input id="add-brg" placeholder="" type="text" name="barang" class="barang_nama" readonly>
                    <label for="add-brg" style="width:fit-content; background-color:#fafafa">Nama Barang</label>
                </div>                  
                <div class="input-field col s12">
                    <input id="add-jumlah" placeholder="" type="number" min="0" name="jumlah" class="barang_jumlah">
                    <label for="add-jumlah" style="width:fit-content; background-color:#fafafa">Jumlah</label>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus barang -->
<div id="modalHapusBarang" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Hapus Barang</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus <b><span class="txt-barang"></span></b> dari data barang?</div>        
        <form action="<?= base_url() ?>asisten/inventori/hapusBarang?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" class="barang_id" name="barang_id" hidden>
                    <input type="text" name="barang" class="barang_nama_hapus" hidden>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>    
</div>

<!-- Modal tambah kategori -->
<div id="modalTambahKategori" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Tambah Kategori</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">                                       
            <form action="<?=base_url()?>asisten/inventori/tambahKategori?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
                <div class="input-field col s12">
                    <input id="add-ktg" type="text" name="kategori">
                    <label for="add-ktg" style="width:fit-content; background-color:#fafafa">Nama Kategori</label>
                    <span class="helper-text"> Contoh: Hardisk, Mouse, Keyboard</span>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit kategori -->
<div id="modalEditKategori" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Edit Kategori</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">                                       
            <form action="<?=base_url()?>asisten/inventori/editKategori?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
                <div class="input-field col s12">
                    <input type="text" name="kategori_id" class="kategori_id" hidden>
                    <input placeholder="" type="text" name="kategori_nama" class="kategori_nama">
                    <label style="width:fit-content; background-color:#fafafa">Nama Kategori</label>
                    <span class="helper-text"> Contoh: Hardisk, Mouse, Keyboard</span>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus kategori -->
<div id="modalHapusKategori" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Hapus Kategori</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus kategori <b><span class="txt-kategori"></span></b> ?</div>        
        <form action="<?= base_url() ?>asisten/inventori/hapusKategori?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" class="kategori_id" name="kategori_id" hidden>
                    <input type="text" name="kategori_nama" class="kategori_nama" hidden>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>    
</div>

<!-- Modal tambah merek -->
<div id="modalTambahMerek" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Tambah Merek</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">                                      
            <form action="<?=base_url()?>asisten/inventori/tambahMerek?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
                <div class="input-field col s12">
                    <input id="add-mrk" type="text" name="merek" required>
                    <label for="add-mrk" style="width:fit-content; background-color:#fafafa">Nama Merek</label>
                    <span class="helper-text"> Contoh: Asus, LG, Logitech</span>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal edit merek -->
<div id="modalEditMerek" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Edit Merek</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">                                      
            <form action="<?=base_url()?>asisten/inventori/editMerek?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
                <div class="input-field col s12">
                    <input type="text" name="merek_id" class="merek_id" hidden>
                    <input placeholder="" type="text" name="merek_nama" class="merek_nama" required>
                    <label style="width:fit-content; background-color:#fafafa">Nama Merek</label>
                    <span class="helper-text"> Contoh: Asus, LG, Logitech</span>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal hapus merek -->
<div id="modalHapusMerek" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Merek</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">             
            <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus merek <b><span class="txt-merek"></span></b> ?</div>        
            <form action="<?=base_url()?>asisten/inventori/hapusMerek?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
                <div class="input-field col s12">
                    <input type="text" name="merek_id" class="merek_id" hidden>
                    <input type="text" name="merek_nama" class="merek_nama" hidden>
                </div>                  
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- Modal alert hapus -->
<div id="modalAlert" class="modal modal-sm">
    <div class="modal-content center-align">
        <i class="material-icons large red-text text-darken-1 center-align">warning</i>
        <p class="modal-title-alert">Oops!</p>
        <p>Data tidak dapat dihapus karena sudah digunakan oleh menu lain.</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-light tombol tombol-sm tombol-danger">X</button>
    </div>
</div>