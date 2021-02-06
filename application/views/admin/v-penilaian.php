<!-- Get deskripsi jadwal dan periode terakhir  -->
<?php 
	$jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $this->input->get('idp')))->row();
	$periode = $this->db->limit(1)->order_by('periode_id DESC')->get('t_periode')->row();
	$idp = $this->input->get('idp');                                                                       
?>                 
<div class="col s12">
    <div class="main-wrapper">
    	<div class="breadcrumb-box">
            <div class="col s12">
                <a href="<?= base_url() ?>admin/dashboard" class="breadcrumb">Dashboard</a>
                <a class="breadcrumb">Penilaian Praktikum</a>                    
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
											<option value="<?= base_url() ?>admin/penilaian?idp=<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
									<?php endforeach ?>
								</select>
							</span>
						</div>
					<?php endif ?>                            
					<?php if (isset($idp)) : ?>                            	                           
					<div class="head-panel">
						<div class="row" style="margin: 0">
							<div class="head-p" style="align-items:baseline;position:relative">                                                                  
								<div style="align-items:center">
									<span>ID Praktikum</span>
									<span class="select2-wrapper">
										<select class="select2" onchange="location = this.value;">
											<?php 
												foreach ($jadwal->result() as $key => $value):
													$selected = '' ; 
													if ($idp == $value->jadwal_kode) {
														$selected = 'selected';
													} else {
														$selected = '';
													} 
													?>
													<option <?= $selected ?> value="<?= base_url() ?>admin/penilaian?idp=<?= $value->jadwal_kode ?>"><?= strtoupper($value->jadwal_kode) ?></option>
											<?php endforeach ?>
										</select>
									</span>
								</div>
								<div>
									<span>Praktikum</span>
									<span>:</span>
									<span class="isi"><?= ucwords($jadwal_ini->matkum) ?></span>
								</div>                                
							</div>
							<br>
							<div class="head-p">
								<div>
									<span>Kelas</span>
									<span>:</span>
									<span class="isi"><?= $jadwal_ini->kelas_nama ?></span>
								</div>  
							</div>                                                      
						</div>     
					</div>                                                              
					<div class="table-wrapper">								                                                                                          
						<table class="highlight">                                    
							<thead>
								<tr>
									<th width="50px"></th>
									<th width="150px"></th>
									<th></th>
									<th width="90px"></th>
									<th class="center-align" width="90px"><span class="p_penilaian"><?= $jadwal_ini->p_kehadiran ?> %</span></th>
									<th class="center-align" width="90px"><span class="p_penilaian"><?= $jadwal_ini->p_tugas ?> %</span></th>
									<th class="center-align" width="90px"><span class="p_penilaian"><?= $jadwal_ini->p_ujian ?> %</span></th>
									<th class="center-align" width="90px">
										<button class="waves-effect waves-light tombol tombol-center tombol-warning modal-trigger btn-edit-persentase" data-target="modalEdit"                                        
											data-p_kehadiran="<?= $jadwal_ini->p_kehadiran ?>"
											data-p_tugas="<?= $jadwal_ini->p_tugas ?>"
											data-p_ujian="<?= $jadwal_ini->p_ujian ?>">
											<i class="material-icons left">edit</i>
										</button>
									</th>											
								</tr>
								<tr>
									<th width="50px" class="blue-text text-accent-3">#</th>
									<th width="150px">NPM</th>
									<th>Nama Mahasiswa</th>
									<th class="center" width="90px">Total Tugas</th>
									<th class="center" width="90px">Kehadiran</th>
									<th class="center" width="90px">Tugas</th>
									<th class="center" width="90px">Ujian</th>
									<th class="center" width="90px">Nilai Akhir</th>
								</tr>
							</thead>
	
							<tbody>                                    
								<?php
									foreach ($mahasiswa->result() as $key => $value) { 

										$kehadiran = $this->M_database->kehadiran($idp, $value->npm);
										$nilai_tugas = $this->M_database->nilai_tugas($idp, $value->npm);
										$total_kehadiran = $kehadiran->kehadiran * 10;
										
										$total_nilai = 0;                                            
										foreach ($nilai_tugas->result() as $tt) {
											$total_nilai += $tt->nilai;              
										}

										$nilai = $total_nilai/10;

										$total = ($total_kehadiran*$jadwal_ini->p_kehadiran/100) + ($value->nilai_tugas*$jadwal_ini->p_tugas/100) + ($value->nilai_ujian*$jadwal_ini->p_ujian/100);
										?>                                            
										<tr id="<?= $value->npm ?>">                                                                        
											<td><?= $key+1 ?></td>
											<td><?= $value->npm ?></td>
											<td><?= $value->nama ?></td>
											<td class="center"><?= $nilai ?></td>
											<td class="center kehadiran<?= $value->npm ?>"><?= $total_kehadiran ?></td>                                                    
											<td class="center" id="td<?= $value->npm ?>">
												<span class="text-nilai txt-n-tugas<?= $value->npm ?> muncul"><?= $value->nilai_tugas ?></span>
												<input type="text" value="<?= $value->nilai_tugas ?>" data-npm="<?= $value->npm ?>" class="input-nilai inp-n-tugas<?= $value->npm ?> hilang browser-default" maxlength="3">
											</td>                                                    
											<td class="center">
												<span class="text-nilai txt-n-ujian<?= $value->npm ?> muncul"><?= $value->nilai_ujian ?></span>
												<input type="text" value="<?= $value->nilai_ujian ?>" class="input-nilai inp-n-ujian<?= $value->npm ?> hilang browser-default" maxlength="3">
												<input type="text" hidden="hidden" value="<?= $idp ?>" class="browser-default jadwal_kode">
												<input type="text" hidden="hidden" value="<?= $jadwal_ini->p_kehadiran ?>" class="browser-default p_kehadiran">
												<input type="text" hidden="hidden" value="<?= $jadwal_ini->p_tugas ?>" class="browser-default p_tugas">
												<input type="text" hidden="hidden" value="<?= $jadwal_ini->p_ujian ?>" class="browser-default p_ujian">
											</td>                                                    
											<td class="center total<?= $value->npm ?>"><?= $total ?></td>                                                    
										</tr>
								<?php } ?>
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

<!-- Modal edit persentase -->
<div id="modalEdit" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Edit Persentase Penilaian</span>
	</div>
    <div class="modal-content modal-content-sm custom-modal-content">        
        <div class="row">
            <form class="col s12" action="<?= base_url() ?>admin/penilaian/editPersentase" method="post">
                <div class="row">
					<div class="input-field col s12">					
						<input type="hidden" name="jadwal_kode" value="<?= $idp ?>">
						<span>Praktikum :</span>
						<br>
						<span><b><?= $jadwal_ini->matkum ?></b></span>
					</div>
					<div class="input-field col s12">					
						<span>ID Praktikum :</span>
						<br>
						<span><b><?= $jadwal_ini->jadwal_kode ?></b></span>
					</div>					
					<br>						          		                    	
					<div class="input-field col s4">
						<span style="display:block; margin-bottom: 4px">Kehadiran</span>                        	
						<input type="text" name="p_kehadiran" id="p_kehadiran" class="validate p_kehadiran" required maxlength="3">
						<span style="position: relative; left: 44px; bottom: 46px">%</span>
					</div>
					<div class="input-field col s4">
						<span style="display:block; margin-bottom: 4px">Tugas</span>                        	
						<input type="text" name="p_tugas" id="p_tugas" class="validate p_tugas" required maxlength="3">
						<span style="position: relative; left: 44px; bottom: 46px">%</span>
					</div>
					<div class="input-field col s4">
						<span style="display:block; margin-bottom: 4px">Ujian</span>                        	
						<input type="text" name="p_ujian" id="p_ujian" class="validate p_ujian" required  maxlength="3">
						<span style="position: relative; left: 44px; bottom: 46px">%</span>
					</div>
                </div>        
        </div>
    </div>
    <div class="modal-footer custom-modal-footer">
      <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i class="material-icons left">save</i><span>Simpan</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>
</div>