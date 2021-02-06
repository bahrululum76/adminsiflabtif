<div class="col s12">
    <div class="main-wrapper">
    	<div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Kehadiran Asisten</a>                    
            </div>
        </div>       
        <div class="row">
            <div class="col s12">
                <div class="main-panel">
                <?php 
                	// Get user
                	$user = $this->db->where('asisten_id', $this->input->get('asisten_id'))->get('t_asisten')->row();
                 ?>	                                 
                    <div class="row">                        
                        <div class="col s12">
                            <?php if(isset($asistenkosong)){?>
                                <div class="head-panel">
                                    <div class="row" style="margin:0">
                                        <div class="col" style="padding: 9.5px">
                                            <span style="font-weight: bold;">Kehadiran praktikum asisten</span></span>
                                        </div>
                                        <div class="col s12 m5">
                                            <span class="select2-wrapper">
                                                <select class="select2-with-image" onchange="location = this.value;">
                                                    <?php 
                                                    foreach ($asisten->result() as $key => $value): 
        
                                                        if ($user->asisten_nama == $value->asisten_nama) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        }
        
                                                        ?>
                                                        <option <?= $selected ?> class="black-text" value="<?= base_url() ?>admin/kehadiran?asisten_id=<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>	
                                                    <?php endforeach ?>             
                                                </select>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <br>                                                                                                   					    
						    <?php if (!isset($asistenkosong)): ?>						    
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
                                        <select class="select2-with-image" onchange="location = this.value;">
                                                <option value="">PILIH ASISTEN</option>
                                            <?php 
                                              foreach ($asisten->result() as $key => $value): ?>
                                                  <option value="<?= base_url() ?>admin/kehadiran?asisten_id=<?= $value->asisten_id ?>" data-image="<?= $value->foto ?>"><?= $value->asisten_nama ?></option>
                                          <?php endforeach ?>
                                      </select>
                                    </span>
								</div>                                         		                                            	
                            <?php endif ?> 
                            <?php if (isset($asistenkosong)) { ?>                                                                                                                                 
                            <!-- Table data kehadiran  -->
                            <div>                                                        
                                <!-- Table data kehadiran  -->                        
                                <table>                                    
                                    <thead>
                                        <tr>
                                            <th class="center" rowspan="2" style="background-color: #e3f2fd; border-top-left-radius: 12px;"><span style="position:relative; top: 26px">ID Praktikum</span></th>                                            
                                            <th class="center" colspan="10" style="background-color: #e3f2fd; border-top-right-radius: 12px;">Pertemuan</th>                                            
                                        </tr>
                                        <tr class="center">											
                                            <?php 
                                            for ($i=1; $i <= 10; $i++) { ?> 
                                                <td class="center" style="border: 2px solid #fff;border-radius:5px; font-weight: 600; background-color: #e3f2fd"><?= $i ?></td>										    												
                                            <?php } ?>										 										    
                                            </tr>
                                    </thead>        
                                    <tbody>                                    
                                        <?php
                                            foreach ($jadwal->result() as $key => $value) { ?>
                                                <tr class="center">
                                                    <td class="center grey-text text-darken-2" style="border-top: 2px solid #fff;border-right: 2px solid #fff;font-weight:500; background-color: #e3f2fd"><?= strtoupper($value->jadwal_kode) ?></td>
                                            <?php
                                                for ($i=1; $i <= 10; $i++) { 
                                                    $cek_asisten = $this->M_database->cekdataAbsen('t_absensi_asisten', 'asisten_id', 'jadwal_kode', 'pertemuan', $this->input->get('asisten_id'), $value->jadwal_kode, $i);

                                                    $color= 'red lighten-4';
                                                    $icon = '<i class="material-icons red-text text-accent-4">close</i>';

                                                    if ($cek_asisten == 1) {
                                                        $color= 'green lighten-4';
                                                        $icon = '<i class="material-icons green-text text-accent-4">check</i>';
                                                    } 

                                                    ?>
                                                    <td class="center"><div class="<?= $color ?>" style="padding: 8px 8px 6px; border-radius: 5px"><?= $icon ?></div></td>                                                	
                                                <?php } ?>                                            
                                                </tr>                                               
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <br>
                                <br>                                                   
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Modal konfirmasi absensi -->
<div id="modalKonfirmasi" class="modal modal-small">
    <div class="modal-content">
        <i class="material-icons medium blue-text text-accent-3">lock</i>
        <h4 class="right modal-title">Konfirmasi Absen</h4>
        <br>
        <br>
        <div class="alert-danger alert-password">
            <span>Password yang anda masukan salah!</span>
        </div>
        <br>
        <div class="row">
            <form class="col s12 form-absen" method="post">
                <div class="row">
                    <div class="input-field col s12">
                        <input type="hidden" name="asisten_id" class="asisten_id">
                        <input id="password" type="password" class="password" name="password" autofocus placeholder="Masukan Password">
                        <label for="password">Password</label>
                    </div>                  
                </div>                    
        </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-close waves-effect waves-light btn-flat">Batal</button>
      <button type="submit" class="waves-effect waves-light btn mybutton-save btn-sm">Oke</button>
    </div>
    </form>
</div>

<script>

</script>