<style>
    .input-field {
        margin: 0;
    }
    
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
        position: relative;
        opacity: 1;
        pointer-events: none;
        top: 2px;
        margin-right: 8px;
    }
</style>
<div class="col s12">
    <div class="main-wrapper">
    	<div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Catatan</a>                    
            </div>
        </div>       
        <div class="row">
            <div class="col s12">
                <div class="main-panel">                    
                    <br>
                    <div class="center-align">
                        <button class="tombol tombol-sm tombol-primary modal-trigger btn-tambah-catatan" data-target="modalTambah"><i class="material-icons left">add</i><span>Buat Catatan</span></button>
                    </div>
                    <?php 
                    if ($catatanPin) { ?>                            
                        <div class="catatan-wrapper catatan-wrapper-pin">                
                            <?php foreach ($catatanPin as $key => $value) { 
                                $border = '';
                                if ($value->catatan_warna == '#ffffff') {
                                    $border = 'border: 1px solid #e0e0e0;';
                                }

                                $date_created = explode('-', $value->created_at);
                                switch ($date_created[1]) {
                                    case '01':
                                        $bulan = 'Januari';
                                        break;
                                    case '02':
                                        $bulan = 'Februari';
                                        break;
                                    case '03':
                                        $bulan = 'Maret';
                                        break;
                                    case '04':
                                        $bulan = 'April';
                                        break;
                                    case '05':
                                        $bulan = 'Mei';
                                        break;
                                    case '06':
                                        $bulan = 'Juni';
                                        break;
                                    case '07':
                                        $bulan = 'Juli';
                                        break;
                                    case '08':
                                        $bulan = 'Agustus';
                                        break;
                                    case '09':
                                        $bulan = 'September';
                                        break;
                                    case '10':
                                        $bulan = 'Oktober';
                                        break;
                                    case '11':
                                        $bulan = 'November';
                                        break;
                                    case '12':
                                        $bulan = 'Desember';
                                        break;
                                    default:
                                        $bulan = '-';
                                        break;
                                }
                                $tgl_catatan = $date_created[2][0].$date_created[2][1].' '.$bulan.' '.$date_created[0];
                                ?>
                                <div class="catatan-item catatan-item-pin" style="background-color:<?=$value->catatan_warna?>;<?=$border?>">
                                    <div class="catatan-header"><?=$value->catatan_judul?></div>
                                    <div class="catatan-content"><?=$value->catatan_isi?></div>
                                    <div class="catatan-footer">
                                        <div class="catatan-action">
                                            <button class="waves-effect tombol-flat transparent modal-trigger btn-edit-catatan" data-target="modalEdit" 
                                                data-catatan_id="<?=$value->catatan_id?>"
                                                data-catatan_judul="<?=$value->catatan_judul?>"
                                                data-catatan_isi='<?=$value->catatan_isi?>'
                                                data-catatan_warna="<?=$value->catatan_warna?>"
                                                data-catatan_status="<?=$value->catatan_status?>"
                                                ><i class="material-icons">edit</i>
                                            </button>
                                            <button class="waves-effect tombol-flat transparent modal-trigger btn-hapus-catatan" data-target="modalHapus" data-catatan_id="<?=$value->catatan_id?>"><i class="material-icons">delete</i>
                                            </button>
                                        </div>
                                        <div class="right-align" style="font-size:12px;padding-right:8px;">                                        
                                            <span><?=$tgl_catatan?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>   
                        </div>
                        <br>                   
                    <?php } 
                    if ($catatan) { ?>                        
                    <div class="catatan-wrapper catatan-wrapper-unpin">                
                        <?php foreach ($catatan as $key => $value) { 
                            $border = '';
                            $catatanTitle = '';
                            if ($value->catatan_warna == '#ffffff') {
                                $border = 'border: 1px solid #e0e0e0;';
                            }
                            if ($catatanPin) {
                                $catatanTitle = 'catatan-unpin-title';
                            }
                            $date_created = explode('-', $value->created_at);
                                switch ($date_created[1]) {
                                    case '01':
                                        $bulan = 'Januari';
                                        break;
                                    case '02':
                                        $bulan = 'Februari';
                                        break;
                                    case '03':
                                        $bulan = 'Maret';
                                        break;
                                    case '04':
                                        $bulan = 'April';
                                        break;
                                    case '05':
                                        $bulan = 'Mei';
                                        break;
                                    case '06':
                                        $bulan = 'Juni';
                                        break;
                                    case '07':
                                        $bulan = 'Juli';
                                        break;
                                    case '08':
                                        $bulan = 'Agustus';
                                        break;
                                    case '09':
                                        $bulan = 'September';
                                        break;
                                    case '10':
                                        $bulan = 'Oktober';
                                        break;
                                    case '11':
                                        $bulan = 'November';
                                        break;
                                    case '12':
                                        $bulan = 'Desember';
                                        break;
                                    default:
                                        $bulan = '-';
                                        break;
                                }
                                $tgl_catatan = $date_created[2][0].$date_created[2][1].' '.$bulan.' '.$date_created[0];
                            ?>
                            <div class="catatan-item catatan-item-unpin <?=$catatanTitle?>" style="background-color:<?=$value->catatan_warna?>;<?=$border?>">
                                <div class="catatan-header"><?=$value->catatan_judul?></div>
                                <div class="catatan-content"><?=$value->catatan_isi?></div>
                                <div class="catatan-footer">
                                    <div class="catatan-action">
                                        <button class="waves-effect tombol-flat transparent modal-trigger btn-edit-catatan" data-target="modalEdit" 
                                            data-catatan_id="<?=$value->catatan_id?>"
                                            data-catatan_judul="<?=$value->catatan_judul?>"
                                            data-catatan_isi='<?=$value->catatan_isi?>'
                                            data-catatan_warna="<?=$value->catatan_warna?>"
                                            data-catatan_status="<?=$value->catatan_status?>"
                                            ><i class="material-icons">edit</i>
                                        </button>
                                        <button class="waves-effect tombol-flat transparent modal-trigger btn-hapus-catatan" data-target="modalHapus" data-catatan_id="<?=$value->catatan_id?>"><i class="material-icons">delete</i>
                                        </button>
                                    </div>
                                    <div class="right-align" style="font-size:12px;padding-right:8px;">                                        
                                        <span><?=$tgl_catatan?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>   
                    </div>
                    <br>                     
                    <?php } ?>                                     
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Modal tambah catatan -->
<div id="modalTambah" class="modal modal-select2" style="max-width: 768px">
    <div class="modal-head">
        <div class="modal-title">Buat Catatan</div>
        <button class="tombol-flat transparent pin-btn"><i class="material-icons-outlined">push_pin</i></button>
    </div>
    <div class="modal-content custom-modal-content">        
        <form action="<?= base_url() ?>admin/catatan/tambahCatatan" method="post">
            <div class="row">                              
                <div class="col s12">
                    <div class="input-field col s12">
                        <span class="label">Judul</span>
                        <textarea name="catatan_judul" class="materialize-textarea" required style="text-indent:0; padding: 17px 16px 18px;margin-top:4px;"></textarea>
                    </div>
                    <div class="input-field col s12">
                        <div class="label" style="margin-bottom:4px">Catatan</div>
                        <textarea name="catatan_isi" class="summernote-tentang" required style="text-indent:0; padding: 17px 16px 18px;"></textarea>
                        <br>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" name="catatan_warna" class="catatan_warna" value="#ffffff" hidden>
                        <input type="text" name="catatan_status" class="catatan_status" value="0" hidden>
                        <div class="warna-wrapper">
                            <div class="warna-item circle" id="default-warna" style="background-color:#ffffff;" data-warna="#ffffff"><i class="material-icons warna-check">check</i></div>
                            <div class="warna-item circle" style="background-color:#F28B82;" data-warna="#F28B82"></div>
                            <div class="warna-item circle" style="background-color:#FBBC04;" data-warna="#FBBC04"></div>
                            <div class="warna-item circle" style="background-color:#FFF475" data-warna="#FFF475"></div>
                            <div class="warna-item circle" style="background-color:#CCFF90" data-warna="#CCFF90"></div>
                            <div class="warna-item circle" style="background-color:#A7FFEB" data-warna="#A7FFEB"></div>
                            <div class="warna-item circle" style="background-color:#CBF0F8" data-warna="#CBF0F8"></div>
                            <div class="warna-item circle" style="background-color:#AECBFA" data-warna="#AECBFA"></div>
                            <div class="warna-item circle" style="background-color:#D7AEFB" data-warna="#D7AEFB"></div>
                            <div class="warna-item circle" style="background-color:#FDCFE8" data-warna="#FDCFE8"></div>
                            <div class="warna-item circle" style="background-color:#E6C9A8" data-warna="#E6C9A8"></div>
                            <div class="warna-item circle" style="background-color:#E8EAED" data-warna="#E8EAED"></div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="modal-footer custom-modal-footer">
            <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
            <button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
        </div>
    </form>
</div>

<!-- Modal edit catatan -->
<div id="modalEdit" class="modal modal-select2" style="max-width: 768px">
    <div class="modal-head">
        <div class="modal-title">Edit Catatan</div>
        <button class="tombol-flat transparent pin-btn"><i class="material-icons-outlined">push_pin</i></button>        
    </div>
    <div class="modal-content custom-modal-content">        
        <form action="<?= base_url() ?>admin/catatan/editCatatan" method="post">
            <input type="text" class="catatan_id" name="catatan_id" hidden>
            <div class="row">                              
                <div class="col s12">
                    <div class="input-field col s12">
                        <span class="label">Judul</span>
                        <textarea name="catatan_judul" class="materialize-textarea catatan_judul" required style="text-indent:0; padding: 17px 16px 18px;margin-top:4px;"></textarea>
                    </div>
                    <div class="input-field col s12">
                        <div class="label" style="margin-bottom:4px">Catatan</div>
                        <textarea name="catatan_isi" class="summernote-tentang catatan_isi" required style="text-indent:0; padding: 17px 16px 18px;"></textarea>
                        <br>
                    </div> 
                    <div class="input-field col s12">
                        <input type="text" name="catatan_warna" class="catatan_warna" hidden>
                        <input type="text" name="catatan_status" class="catatan_status" hidden>
                        <div class="warna-wrapper">
                            <div class="warna-item circle" id="ffffff" style="background-color:#ffffff;" data-warna="#ffffff"></div>
                            <div class="warna-item circle" id="F28B82" style="background-color:#F28B82;" data-warna="#F28B82"></div>
                            <div class="warna-item circle" id="FBBC04" style="background-color:#FBBC04;" data-warna="#FBBC04"></div>
                            <div class="warna-item circle" id="FFF475" style="background-color:#FFF475" data-warna="#FFF475"></div>
                            <div class="warna-item circle" id="CCFF90" style="background-color:#CCFF90" data-warna="#CCFF90"></div>
                            <div class="warna-item circle" id="A7FFEB" style="background-color:#A7FFEB" data-warna="#A7FFEB"></div>
                            <div class="warna-item circle" id="CBF0F8" style="background-color:#CBF0F8" data-warna="#CBF0F8"></div>
                            <div class="warna-item circle" id="AECBFA" style="background-color:#AECBFA" data-warna="#AECBFA"></div>
                            <div class="warna-item circle" id="D7AEFB" style="background-color:#D7AEFB" data-warna="#D7AEFB"></div>
                            <div class="warna-item circle" id="FDCFE8" style="background-color:#FDCFE8" data-warna="#FDCFE8"></div>
                            <div class="warna-item circle" id="E6C9A8" style="background-color:#E6C9A8" data-warna="#E6C9A8"></div>
                            <div class="warna-item circle" id="E8EAED" style="background-color:#E8EAED" data-warna="#E8EAED"></div>
                        </div>
                    </div>                                       
                </div>
            </div>
        </div>
        <div class="modal-footer custom-modal-footer">
            <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
            <button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
        </div>
    </form>
</div>

<!-- Modal hapus catatan-->
<div id="modalHapus" class="modal modal-sm">
    <div class="modal-head">
        <span class="modal-title">Hapus Catatan</span>        
    </div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel red-gradient white-text">Apakah kamu yakin ingin menghapus catatan yang dipilih?</div>
        <form class="col s12" action="<?= base_url() ?>admin/catatan/hapusCatatan" method="post">
            <input type="text" class="catatan_id" name="catatan_id" hidden>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>

<!-- minigrid -->
 <script src="<?= base_url() ?>assets/library/minigrid/minigrid.min.js"></script>
 <script>
     function pinnedNote(){
        var grid;
        function init() {
            grid = new Minigrid({
            container: '.catatan-wrapper-pin',
            item: '.catatan-item-pin',
            gutter: 12
            });
            grid.mount();
        }
        
        // mount
        function update() {
            grid.mount();
        }

        document.addEventListener('DOMContentLoaded', init);
        window.addEventListener('resize', update);
    } pinnedNote();

    function unpinnedNote(){
        var grid2;
        function init2() {
            grid2 = new Minigrid({
            container: '.catatan-wrapper-unpin',
            item: '.catatan-item-unpin',
            gutter: 12
            });
            grid2.mount();
        }
        
        // mount
        function update2() {
            grid2.mount();
        }

        document.addEventListener('DOMContentLoaded', init2);
        window.addEventListener('resize', update2);
    } unpinnedNote();        
 </script>