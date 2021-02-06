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
	.select2-results__option[aria-selected=true] {
		margin: 8px 0;
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice {
		border: none;
		margin-top: 8px;
		padding: 3px 10px;
		list-style: none;
		clear: both;
		font-weight: 500;
		text-transform: initial;
	}
	.select2-wrapper {
		max-width: 1000px;
		box-shadow: none;
		border: 1px solid #cacaca;
	}

	.input-field {
		margin-bottom: 0;
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
				<span class="pulse-pesan hide"
					style="margin: 4px; animation: shadow-pulse-dots 1s infinite;display:block; width: 6px; height: 6px; background-color: #ff5252; border-radius: 10px; position:absolute; right: -10px; top: -10px">
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
					<a href="#!" class="modal-trigger" data-target="modalLihatAsisten"><i
							class="material-icons left">credit_card</i>Lihat Profil</a>
					<a href="<?=base_url()?>asisten/profil"><i class="material-icons left">face</i>Edit Profil</a>
					<a href="<?=base_url()?>asisten/password"><i class="material-icons left">vpn_key</i>Ganti
						Password</a>
					<?php if($this->session->userdata('jabatan_id') == 6) { echo '<a href="'.base_url().'admin/dashboard"><i class="material-icons left">admin_panel_settings</i>Halaman Admin</a>'; } ?>
					<div class="center-align">
						<button style="margin: 10px 0;"
							class="waves-effect waves-light tombol tombol-sm tombol-info center-align modal-trigger"
							data-target="modalLogout" href="<?=base_url()?>auth/logout"><i
								class="material-icons left">exit_to_app</i><span>Logout</span></button>
					</div>
				</div>
			</span>
			<div class="line"></div>
		</div>
		<div class="col s12">
			<div class="main-panel">
				<a class="nav__link" href="<?=base_url()?>asisten/inventori"><i
						class="material-icons-outlined left red-text text-darken-1">arrow_back</i></a>
				<span class="panel-title-2">PC <?= ucwords(str_replace('-', ' ', $lab_url)) ?></span>
				<div class="line"></div>
				<div class="head-panel">
					<div class="row" style="margin: 0">
						<div class="head-p" style="align-items:center;position:relative">
							<div style="align-items:center">
								<span>Ruangan</span>
								<span class="select2-wrapper"
									style="max-width:280px;box-shadow: 0 0 20px -8px rgba(0, 0, 0, .2);border:none;">
									<select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
										<option value="<?= base_url() ?>asisten/inventori?menu=laporan">PILIH RUANGAN
										</option>
										<?php 
                                            foreach ($ruangan->result() as $key => $lab):
                                                $selected = '' ; 
                                                if ($lab_url == strtolower(str_replace(' ', '-', $lab->ruangan_nama))) {
                                                    $selected = 'selected';
                                                } else {
                                                    $selected = '';
                                                } 
                                                ?>
										<option <?= $selected ?>
											value="<?= base_url() ?>asisten/inventori?menu=pclab&rid=<?=$lab->ruangan_id?>&ruangan=<?=strtolower(str_replace(' ', '-', $lab->ruangan_nama))?>">
											<?= strtoupper($lab->ruangan_nama) ?></option>
										<?php endforeach ?>
									</select>
								</span>
							</div>
						</div>
					</div>
				</div>
				<?php if (isset($rid)) : ?>				
				<br>
				<button class="waves-effect waves-light tombol tombol-sm tombol-primary modal-trigger" data-target="modalTambahPC"><i class="material-icons left">add</i><span>Tambah PC</span></button>
				<div class="table-wrapper">
                        <table class="table-biasa highlight">
                            <thead>
                                <tr class="blue lighten-5">
                                    <th class="center blue-text text-accent-3" width="30">#</th>
                                    <th class="center">Nama PC</th>
                                    <th class="center">Komponen</th>
                                    <!-- <th width="250" class="center">Aksi</th> -->
                                </tr>                                                            
                            </thead>
                            <tbody>
								<?php if (!$pc->result()) {
										echo '<tr><td class="center" colspan="4">No data available in this table</td></tr>';
									} ?>
                                <?php foreach ($pc->result() as $key => $pclab) : 
                                    $komponen = $this->M_database->getKomponenPC($pclab->pc_id);
									$jml_komponen = $this->M_database->countWhere('t_pc_komponen', array('pc_id' => $pclab->pc_id, 'pck_status' => 1)) + 1;
                                    ?>
                                    <tr>
                                        <td rowspan="<?=$jml_komponen + 1?>" class="center"><?=$key+1?></td>
                                        <td rowspan="<?=$jml_komponen?>" class="center" style="border:1px solid transparent;border-right:1px solid #cfd8dc"><?=$pclab->pc_nama?></td>
                                    </tr>									                                    									
                                    <?php foreach ($komponen->result() as $komp) : 
                                        $merek_brg = $komp->merek_nama;
                                        if ($komp->merek_id == 0) {
                                            $merek_brg = 'TANPA MEREK';
                                        }
                                        ?>                                                              
                                            <tr>
                                                <td>
													<?=$komp->kategori_nama.' - '.$merek_brg.' '.$komp->barang_tipe?>
													<span class="right modal-trigger btn-pc" data-target="modalHapusKomponen" 
														data-pc_id="<?= $komp->pc_id ?>"
														data-barang_id="<?= $komp->barang_id ?>"
														data-pck_id="<?= $komp->pck_id ?>"
														data-pck_nama="<?= $komp->kategori_nama.' - '.$merek_brg.' '.$komp->barang_tipe ?>"
														data-pc_nama="<?= $pclab->pc_nama ?>"><i class="waves-effect tiny material-icons-round red-text" style="padding:2px;cursor:pointer">clear</i></span>
													<span class="right modal-trigger btn-pc" data-target="modalEditKomponen" 
														data-pc_id="<?= $komp->pc_id ?>"
														data-barang_id="<?= $komp->barang_id ?>"
														data-pck_id="<?= $komp->pck_id ?>"
														data-pck_nama="<?= $komp->kategori_nama.' - '.$merek_brg.' '.$komp->barang_tipe ?>"
														data-pc_nama="<?= $pclab->pc_nama ?>"><i class="waves-effect tiny material-icons-outlined amber-text" style="padding:2px;margin-right:4px;cursor:pointer">edit</i></span>
												</td>                                                
                                            </tr>                                            
                                        <?php endforeach ?>
										<tr>
											<td class="center" style="border:1px solid transparent;border-right:1px solid #cfd8dc">
												<span class="modal-trigger btn-pc" data-target="modalEditPC"
													data-pc_id="<?= $pclab->pc_id ?>"
													data-pc_nama="<?= $pclab->pc_nama ?>"><i class="waves-effect material-icons-outlined amber-text" style="padding:2px;margin-right:4px;cursor:pointer">edit</i>
												</span>
												<span class="modal-trigger btn-pc" data-target="modalHapusPC" 
													data-pc_id="<?= $pclab->pc_id ?>"
													data-pc_nama="<?= $pclab->pc_nama ?>"><i class="waves-effect material-icons red-text" style="padding:2px;cursor:pointer">delete</i></span>
											</td>
											<td>
												<span class="right modal-trigger btn-pc" data-target="modalTambahKomponen" title="Tambah komponen" 
													data-pc_id="<?= $pclab->pc_id ?>"
													data-pc_nama="<?= $pclab->pc_nama ?>"><i class="waves-effect waves-light tiny material-icons-round white-text tombol-info" style="padding:4px;border-radius:4px;cursor:pointer">add</i></span>
											</td>
										</tr>										
										<tr>
											<td colspan="4" class="indigo lighten-5"></td>
										</tr>										
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>

<!-- Modal tambah PC -->
<div id="modalTambahPC" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Tambah PC</span>
	</div>
	<div class="modal-content custom-modal-content">
		<div class="row">
			<div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
				<span>Password salah</span>
			</div>
			<form action="<?=base_url()?>asisten/inventori/tambahPC?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
				<div class="input-field col s12">
					<input id="add-pc" type="text" name="pc_nama">
					<label for="add-pc" style="width:fit-content; background-color:#fafafa">Nama PC</label>
				</div>
				<div class="input-field col s12 input-komponen">
                    <div style="margin-bottom:8px">Komponen :</div>
					<span class="select2-wrapper">
						<select name="komponen[]" class="select2" multiple>
							<?php foreach ($barang->result() as $key => $brg) : 
								$merek_brg = $brg->merek_nama;
								if ($brg->merek_id == 0) {
									$merek_brg = 'TANPA MEREK';
								}	
							?>
							<option value="<?=$brg->barang_id?>">
								<?=$brg->kategori_nama.' - '.$merek_brg.' '.$brg->barang_tipe?></option>
							<?php endforeach ?>
						</select>
					</span>					
				</div>              
		</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
		<button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Modal edit PC -->
<div id="modalEditPC" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Edit PC</span>
	</div>
	<div class="modal-content modal-content-sm custom-modal-content">
		<div class="row">
			<div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
				<span>Password salah</span>
			</div>
			<form action="<?=base_url()?>asisten/inventori/editPC?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
				<div class="input-field col s12">
					<input type="text" name="pc_id" class="pc_id" hidden>
					<input placeholder="" type="text" name="pc_nama" class="pc_nama">
					<label for="edit-pc" style="width:fit-content; background-color:#fafafa">Nama PC</label>
				</div>				             
		</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
		<button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Modal tambah komponen PC -->
<div id="modalTambahKomponen" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Tambah Komponen PC</span>
	</div>
	<div class="modal-content custom-modal-content">
		<div class="row">
			<div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
				<span>Password salah</span>
			</div>
			<form action="<?=base_url()?>asisten/inventori/tambahKomponen?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
				<div class="input-field col s12">
					<input type="text" name="pc_id" class="pc_id" hidden>
					<input placeholder="" type="text" name="pc_nama" class="pc_nama" readonly>
					<label style="width:fit-content; background-color:#fafafa">Nama PC</label>
				</div>
				<div class="input-field col s12 input-komponen">
                    <div style="margin-bottom:8px">Komponen :</div>
					<span class="select2-wrapper">
						<select name="komponen[]" class="select2" multiple>
							<?php foreach ($barang->result() as $key => $brg) : 
								$merek_brg = $brg->merek_nama;
								if ($brg->merek_id == 0) {
									$merek_brg = 'TANPA MEREK';
								}	
							?>
							<option value="<?=$brg->barang_id?>">
								<?=$brg->kategori_nama.' - '.$merek_brg.' '.$brg->barang_tipe?></option>
							<?php endforeach ?>
						</select>
					</span>					
				</div>              
		</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
		<button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Modal edit komponen PC -->
<div id="modalEditKomponen" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Edit Komponen PC</span>
	</div>
	<div class="modal-content custom-modal-content">
		<div class="row">
			<div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
				<span>Password salah</span>
			</div>
			<form action="<?=base_url()?>asisten/inventori/editKomponen?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
				<div class="input-field col s12">
					<input type="text" name="pc_id" class="pc_id" hidden>
					<input type="text" name="pck_id" class="pck_id" hidden>
					<input placeholder="" type="text" name="pc_nama" class="pc_nama" readonly>
					<label style="width:fit-content; background-color:#fafafa">Nama PC</label>
				</div>
				<div class="input-field col s12 input-komponen">
                    <div style="margin-bottom:8px">Komponen :</div>
					<span class="select2-wrapper">
						<select name="komponen" class="select2 pc_komp">
							<?php foreach ($barang->result() as $key => $brg) : 
								$merek_brg = $brg->merek_nama;
								if ($brg->merek_id == 0) {
									$merek_brg = 'TANPA MEREK';
								}	
							?>
							<option value="<?=$brg->barang_id?>">
								<?=$brg->kategori_nama.' - '.$merek_brg.' '.$brg->barang_tipe?></option>
							<?php endforeach ?>
						</select>
					</span>					
				</div>              
		</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><i class="material-icons left">save</i><span>Simpan</span></button>
		<button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Modal hapus komponen -->
<div id="modalHapusKomponen" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Hapus Komponen PC</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus komponen <b><span class="txt-komponen-pc"></span></b> dari PC <span class="txt-pc"></span> ?</div>        
        <form action="<?= base_url() ?>asisten/inventori/hapusKomponen?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" class="pc_id" name="pc_id" hidden>
                    <input type="text" class="pck_id" name="pck_id" hidden>
                    <input type="text" name="pc_nama" class="pc_nama" hidden>
                    <input type="text" name="pck_nama" class="pck_nama" hidden>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>    
</div>

<!-- Modal hapus PC -->
<div id="modalHapusPC" class="modal modal-sm">
    <div class="modal-head"><span class="modal-title">Hapus PC</span></div>
    <div class="modal-content modal-content-sm custom-modal-content">
        <div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus PC <b><span class="txt-pc"></span></b> ?</div>        
        <form action="<?= base_url() ?>asisten/inventori/hapusPC?rid=<?=$rid?>&ruangan=<?=$lab_url?>" method="post">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" class="pc_id" name="pc_id" hidden>
                    <input type="text" name="pc_nama" class="pc_nama" hidden>
                </div>
            </div>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i class="material-icons left">delete</i><span>Hapus</span></button>
        <button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
    </div>
    </form>    
</div>