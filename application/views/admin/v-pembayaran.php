<div class="col s12">
    <div class="main-wrapper">
        <div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Pembayaran</a>                    
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="main-panel">
                    <div class="row">                        
                        <div class="col s12">
                            <span class="panel-title-2">Status Pembayaran Praktikum</span>
                            <div class="line-dashed"></div>
                            <div class="col s12 m6" style="padding:0">
                                <div class="mini-counter blue lighten-3">
                                    <span class="badge-status blue-gradient left">Mahasiswa</span>
                                    <span class="badge-status right"><?=$jumlah_mhs?></span>
                                </div>
                                <div class="mini-counter green lighten-4">
                                    <span class="badge-status badge-ok left">Lunas</span>
                                    <span class="badge-status lunas right"><?=$jumlah_lunas?></span>
                                </div>
                                <div class="mini-counter blue-grey lighten-3">
                                    <span class="badge-status badge-not left">Belum</span>
                                    <span class="badge-status belum right"><?=$jumlah_belum?></span>
                                </div>                                
                            </div>
                            <div class="col s12 m6" style="padding:0">
                                <!-- Searchbar -->
                                <div class="searchbar-table right">
                                    <input type="text" placeholder="Cari Mahasiswa" class="browser-default global_filter" id="global_filter" autocomplete="off">                                                        
                                    <i class="material-icons right">search</i>
                                </div>                                                                
                            </div>
                            <!-- Table data  -->
                            <div class="col s12 table-wrapper">                                
                                <table class="highlight datatable">                                    
                                    <thead>
                                        <tr>
                                            <th class="blue-text text-accent-3">#</th>
                                            <th>NPM</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th class="center">Status Pembayaran</th>
                                        </tr>
                                    </thead>
            
                                    <tbody>
                                    <?php
                                        foreach ($mahasiswa->result() as $key => $value) { 

                                                $status = '';

                                                if ($value->status_bayar == 0){ 
                                                    $status = '<span class="badge-status badge-not">Belum</span>';
                                                } else { 
                                                    $status = '<span class="badge-status badge-ok">Lunas</span>';
                                                }
                                            ?>  

                                            <tr>
                                                <td><?= $key+1 ?></td>
                                                <td><?= $value->npm ?></td>
                                                <td><?= $value->nama ?></td>
                                                <td><?= $value->kelas_nama ?></td>                                               
                                                <td class="status-bayar td-data center" id="<?= $value->npm ?>"><?= $status ?></td>                                               
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
    </div>
</div>