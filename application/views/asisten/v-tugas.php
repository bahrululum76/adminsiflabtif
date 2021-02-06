<!-- Get deskripsi jadwal dan periode terakhir  -->
<?php
    $user = $this->db->where('asisten_id', $this->session->userdata('asisten_id'))->get('t_asisten')->row();
    $nama = explode(' ', $user->asisten_nama);
    if (sizeof($nama) > 3) {
        $panggilan = $nama[0]." ".$nama[1]." ".$nama[2];
    } else {
        $panggilan = $user->asisten_nama;
    }
    $idp = $this->input->get('idp');
    $jadwal_ini = $this->M_database->getJadwal(array('jadwal_kode' => $idp))->row();
    $nomor_tugas = $this->M_database->countWhere('t_tugas', array('jadwal_kode' => $idp)) + 1;

    if (isset($idp)) {
        $kode_tugas = "$jadwal_ini->jadwal_kode-$nomor_tugas";                            
    } else {
        $kode_tugas = "";
    }
?>

<style>
	.select2-results__option[aria-selected=true] {
		display: none
	}

	.input-field {
		margin: 0;
	}

	.input-field span.label {
		display: block;
		margin: 4px 0;
	}

	.btn-remove {
		position: absolute;
		background: rgba(0, 0, 0, 0.4);
		border: none;
		border-radius: 50px;
		width: 30px;
		height: 30px;
		right: 0;
		padding: 3px 2px;
		color: white;
		cursor: pointer;
		display: none;
	}

	[type="checkbox"]:not(:checked),
	[type="checkbox"]:checked {
		position: relative;
		opacity: 1;
		pointer-events: none;
		top: 2px;
		margin-right: 8px;
	}

</style>

<div class="main-wrapper">
	<div class="top-panel right-align">
		<span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Tugas Praktikum</span>
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
				<a href="<?=base_url()?>asisten/password"><i class="material-icons left">vpn_key</i>Ganti Password</a>
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
					<select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
						<option value="">PILIH ID PRAKTIKUM</option>
						<?php 
                            foreach ($jadwal->result() as $key => $value):
                            $selected = '' ; 
                            if ($idp== $value->jadwal_kode) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            } 
                        ?>
						<option <?= $selected ?> value="<?= base_url() ?>asisten/tugas?idp=<?= $value->jadwal_kode ?>">
							<?= strtoupper($value->jadwal_kode) ?></option>
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
								<select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
									<?php 
                                        foreach ($jadwal->result() as $key => $value) :
                                        $selected = '' ; 
                                        if ($idp == $value->jadwal_kode) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        } 
                                    ?>
									<option <?= $selected ?>
										value="<?= base_url() ?>asisten/tugas?idp=<?= $value->jadwal_kode ?>">
										<?= strtoupper($value->jadwal_kode) ?></option>
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
				<button class="tombol tombol-sm tombol-primary modal-trigger btn-buat-tugas" data-target="modalTambah"
					data-tugas_kode="<?=$kode_tugas?>"><i class="material-icons left">add</i><span>Buat
						Tugas</span></button>
				<br><br>
				<table class="highlight datatable-nopage">
					<thead>
						<tr>
							<th width="50px" class="blue-text text-accent-3">#</th>
							<th width="150px">ID Tugas</th>
							<th>Tugas</th>
							<th class="center" width="150px">Status</th>
							<th class="center" width="200px">Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php
                            foreach ($tugas->result() as $key => $value) { 
                            $nilai = $this->M_database->cekdata('t_tugas_nilai', 'tugas_id', $value->tugas_id);
                            $title = 'hapus';
                            $modal = 'modalHapus';

                            if ($nilai != 0) {
                                    $title = 'tidak bisa hapus';
                                    $modal = 'modalAlert';
                            }

                            $batas_waktu = explode(" ", $value->batas_waktu);
                            $tenggat = date_create($batas_waktu[0]." ".$batas_waktu[1]);
                            $hari = date_format($tenggat, 'D');
                            $tanggal = date_format($tenggat, 'd/m/Y');
                            $jam = date_format($tenggat, 'H:i');

                            switch($hari) {
                                case 'Sun':
                                    $hari = 'Minggu';
                                break;
                                case 'Mon':
                                    $hari = 'Senin';
                                break;
                                case 'Tue':
                                    $hari = 'Selasa';
                                break;
                                case 'Wed':
                                    $hari = 'Rabu';
                                break;
                                case 'Thu':
                                    $hari = 'Kamis';
                                break;
                                case 'Fri':
                                    $hari = 'Jumat';
                                break;
                                case 'Sat':
                                    $hari = 'Sabtu';
                                break;
                            }

                            if ($value->tugas_status == 1) {
                                $status = '<span class="badge-status badge-ok">Aktif</span>';
                            } else {
                                $status = '<span class="badge-status badge-not">Tidak aktif</span>';
                            }
                            ?>
						<tr>
							<td><?= $key+1 ?></td>
							<td>
								<a class="blue-text nav__link"
									href="<?= base_url() ?>asisten/tugas?idp=<?= $value->jadwal_kode ?>&t=<?= $value->tugas_id ?>&tk=<?= $value->tugas_kode ?>"><?= $value->tugas_kode ?></a>
							</td>
							<td><?= $value->tugas_judul ?></td>
							<td class="status-tugas td-data center" id="status-tugas-<?= $value->tugas_id ?>"
								data-tugas_id="<?=$value->tugas_id?>"><?= $status ?></td>
							<td class="center">
								<button class="waves-effect tombol-flat transparent modal-trigger btn-lihat-tugas"
									data-target="modalLihatTugas" data-tugas_id="<?= $value->tugas_id ?>"
									data-tugas_kode="<?= $value->tugas_kode ?>"
									data-tugas_judul="<?= $value->tugas_judul ?>"
									data-tugas_deskripsi='<?= $value->tugas_deskripsi ?>'><i
										class="material-icons blue-text text-accent-1">remove_red_eye</i>
								</button>
								<button class="waves-effect tombol-flat transparent modal-trigger btn-notifikasi-tugas"
									data-target="modalNotifikasi"
									data-pesan="Tugas praktikum baru <?= ucwords($jadwal_ini->matkum) ?> <?= $value->tugas_kode ?>, Yuk segera dikerjakan!"
									data-jadwal_kode="<?=$value->jadwal_kode?>"
									data-tugas_kode="<?= $value->tugas_kode ?>"><i
										class="material-icons green-text text-lighten-1">campaign</i>
								</button>
								<button
									class="waves-effect tombol-flat transparent modal-trigger get-image btn-edit-tugas"
									id="btn-edit-tugas-<?=$value->tugas_id?>" data-target="modalEdit"
									data-tugas_id="<?= $value->tugas_id ?>" data-tugas_kode="<?= $value->tugas_kode ?>"
									data-tugas_judul="<?= $value->tugas_judul ?>"
									data-tugas_deskripsi='<?= $value->tugas_deskripsi ?>' data-jam="<?= $jam ?>"
									data-tanggal="<?= $hari.', '.$tanggal ?>"
									data-tugas_status="<?= $value->tugas_status ?>"><i
										class="material-icons-outlined amber-text">edit</i>
								</button>
								<button title="<?= $title ?>"
									class="waves-effect tombol-flat transparent modal-trigger btn-hapus-tugas"
									data-target="<?= $modal ?>" data-tugas_id="<?= $value->tugas_id ?>"
									data-tugas_kode="<?= $value->tugas_kode ?>"><i
										class="material-icons red-text text-lighten-1 ">delete</i>
								</button>
							</td>
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

<!-- form upload -->
<form method="post" enctype="multipart/form-data" id="upload-form">
	<input class="file-upload-tugas" hidden type="file" name="foto">
	<input hidden type="text" id="in-edit" value="0">
	<input hidden type="text" name="kode_t" value="<?=$kode_tugas?>" id="kode-t">
	<input hidden type="text" name="image_id" value="-" id="image-id">
	<input hidden type="text" name="image_edit" value="-" id="image-edit">
</form>

<!-- Modal tambah tugas -->
<div id="modalTambah" class="modal modal-select2" style="max-width: 768px">
	<div class="modal-head">
		<span class="modal-title">Buat Tugas</span>
	</div>
	<div class="modal-content custom-modal-content">
		<div class="row">
			<div class="col s12">
				<div class="input-field col s12">
					<img src="" alt="default-img" class="responsive-img hide" id="hidden-image">
					<div class="img-preview" id="img-preview-tambah">
						<?php foreach ($foto_tugas->result() as $key => $value) { ?>
						<div class="img-wrapper" id="img-wrapper-<?=$value->tugas_image_id?>">
							<!-- preloader -->
							<div class="preloader-wrapper small active hide" id="myloader-<?=$value->tugas_image_id?>"
								style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
								<div class="spinner-layer spinner-teal-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div>
									<div class="gap-patch">
										<div class="circle"></div>
									</div>
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
							<img src="<?=base_url()?>assets/images/tugas/<?=$value->tugas_image?>" alt="tugas-img"
								class="responsive-img img materialboxed" id="foto-tugas-<?=$value->tugas_image_id?>">
							<div id="action-foto-<?=$value->tugas_image_id?>"
								style="position:absolute; bottom:0; width:100%">
								<span class="material-icons red-text text-lighten-1 left delete-foto-tugas"
									id="hapus-<?=$value->tugas_image_id?>" data-gambar="<?=$value->tugas_image?>"
									data-id="<?=$value->tugas_image_id?>" style="cursor: pointer;">close</span>
								<span class="material-icons-outlined amber-text text-lighten-1 right edit-foto-tugas"
									data-gambar="<?=$value->tugas_image?>" data-id="<?=$value->tugas_image_id?>"
									id="edit-<?=$value->tugas_image_id?>" style="cursor: pointer;">edit</span>
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="img-wrapper hide preview-wrapper">
						<!-- preloader -->
						<div class="preloader-wrapper small active myloader hide"
							style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
							<div class="spinner-layer spinner-teal-only">
								<div class="circle-clipper left">
									<div class="circle"></div>
								</div>
								<div class="gap-patch">
									<div class="circle"></div>
								</div>
								<div class="circle-clipper right">
									<div class="circle"></div>
								</div>
							</div>
						</div>
						<img src="<?=base_url()?>assets/images/add-tugas.png" alt="add-img"
							class="responsive-img img preview-image">
					</div>
				</div>
				<button type="button" class="waves-effect waves-light tombol tombol-sm tombol-warning btn-upload-tugas"
					style="margin-left: 10px;"><i class="material-icons left">file_upload</i><span>Tambah
						Foto</span></button>
						<span class="tooltiped grey-text" style="margin-left:6px;position:relative;top:8px;cursor:pointer" data-position="bottom" data-tooltip="Max ukuran 2MB (1080x1080)"><i class="material-icons">info_outline</i></span>
			</div>
		</div>
		<form action="<?= base_url() ?>asisten/asisten_tugas/tambahTugas" method="post">
			<div class="row">
				<input type="text" hidden="hidden" name="jadwal_kode" value="<?= $idp ?>">
				<input type="text" hidden="hidden" name="tugas_kode" value="<?=$kode_tugas?>">
				<div class="col s12">
					<div class="input-field col s12">
						<span class="label">Tugas</span>
						<textarea name="tugas_judul" class="materialize-textarea" required
							style="text-indent:0; padding: 17px 16px 18px;"></textarea>
					</div>
					<div class="input-field col s12">
						<span class="label">Deskripsi</span>
						<textarea name="tugas_deskripsi" class="summernote" required
							style="text-indent:0; padding: 17px 16px 18px;"></textarea>
						<br>
					</div>
					<div class="input-field col s12 m6">
						<span class="label">Batas waktu</span>
						<input type="text" placeholder="Tanggal" class="validate datepicker" name="tanggal"
							autocomplete="off" required readonly>
					</div>
					<div class="input-field col s12 m6">
						<span class="label" style="visibility:hidden">Jam</span>
						<input type="text" placeholder="Jam" class="validate timepicker" name="jam" autocomplete="off"
							required readonly>
					</div>
					<div class="input-field col s12 m6">
						<span class="label" style="margin-top:8px">Status tugas</span>
						<span class="select2-wrapper"
							style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
							<select class="select2" name="tugas_status" required>
								<option value="0">TIDAK AKTIF</option>
								<option value="1">AKTIF</option>
							</select>
						</span>
					</div>
				</div>
			</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary btn-tambah right"><i
				class="material-icons left">save</i><span>Simpan</span></button>
		<button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Modal edit tugas -->
<div id="modalEdit" class="modal modal-select2" style="max-width: 768px">
	<div class="modal-head">
		<span class="modal-title">Edit Tugas</span>
	</div>
	<div class="modal-content custom-modal-content">
		<form action="<?= base_url() ?>asisten/asisten_tugas/editTugas" method="post">
			<div class="row">
				<input type="text" hidden="hidden" name="tugas_id" class="tugas_id">
				<input type="text" hidden="hidden" name="tugas_kode" class="tugas_kode">
				<input type="text" hidden="hidden" name="total_file" class="total_file" value="0">
				<input id="jadwal_kode" type="hidden" name="jadwal_kode" value="<?= $idp ?>">
				<div class="col s12">
					<div class="input-field col s12">
						<div class="img-preview" id="img-preview-edit">

						</div>
						<div class="img-wrapper hide preview-wrapper">
							<!-- preloader -->
							<div class="preloader-wrapper small active myloader hide"
								style="position: absolute; margin: auto; top:0;bottom:0;left:0;right:0; z-index:10">
								<div class="spinner-layer spinner-teal-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div>
									<div class="gap-patch">
										<div class="circle"></div>
									</div>
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
							<img src="<?=base_url()?>assets/images/add-tugas.png" alt="add-img"
								class="responsive-img img preview-image">
						</div>
					</div>
					<button type="button"
						class="waves-effect waves-light tombol tombol-sm tombol-warning btn-upload-tugas"
						style="margin-left: 10px;"><i class="material-icons left">file_upload</i><span>Tambah
							Foto</span></button>
							<span class="tooltiped grey-text" style="margin-left:6px;position:relative;top:8px;cursor:pointer" data-position="bottom" data-tooltip="Max ukuran 2MB (1080x1080)"><i class="material-icons">info_outline</i></span>
					<br>
					<br>
					<div class="input-field col s12">
						<span class="label">Tugas</span>
						<textarea name="tugas_judul" id="tugas_judul" class="materialize-textarea tugas_judul" required
							style="text-indent:0; padding: 17px 16px 18px;"></textarea>
					</div>
					<div class="input-field col s12">
						<span class="label">Deskripsi</span>
						<textarea name="tugas_deskripsi" id="tugas_deskripsi" class="summernote tugas_deskripsi"
							required style="text-indent:0; padding: 17px 16px 18px;"></textarea>
						<br>
					</div>
					<div class="input-field col s6">
						<span class="label">Batas waktu</span>
						<input id="tanggal" type="text" placeholder="Tanggal" class="validate datepicker tanggal"
							name="tanggal" autocomplete="off" required readonly>
					</div>
					<div class="input-field col s6">
						<span class="label" style="visibility:hidden">Jam</span>
						<input id="jam" type="text" placeholder="Jam" class="validate timepicker jam" name="jam"
							autocomplete="off" required readonly>
					</div>
					<div class="input-field col s12 l6">
						<span class="label" style="margin-top:8px">Status tugas</span>
						<span class="select2-wrapper"
							style="max-width: 500px; border:1px solid #cacaca; box-shadow:none">
							<select class="select2 select2-data tugas_status" name="tugas_status" required>
								<option value="0">TIDAK AKTIF</option>
								<option value="1">AKTIF</option>
							</select>
						</span>
					</div>
				</div>
			</div>			
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary btn-tambah right"><i
				class="material-icons left">save</i><span>Simpan</span></button>
		<button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Modal hapus tugas-->
<div id="modalHapus" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Hapus Tugas</span>
	</div>
	<div class="modal-content modal-content-sm custom-modal-content">
		<div class="panel red-gradient white-text">Apakah anda yakin ingin menghapus tugas <span
				class="tugas-kode-txt"></span> ?</div>
		<form class="col s12" action="<?= base_url() ?>asisten/asisten_tugas/hapusTugas" method="post">
			<input id="tugas_id" type="hidden" class="validate tugas_id" name="tugas_id">
			<input id="tugas_kode" type="hidden" class="validate tugas_kode" name="tugas_kode">
			<input type="hidden" name="jadwal_kode" value="<?= $idp ?>">
			<input type="text" class="tugas_foto" hidden="hidden" name="tugas_foto">
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-danger right"><i
				class="material-icons left">delete</i><span>Hapus</span></button>
		<button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Kirim notifikasi -->
<div id="modalNotifikasi" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Kirim Notifikasi</span>
	</div>
	<div class="modal-content modal-content-sm custom-modal-content">
		<form class="col s12" action="<?= base_url() ?>asisten/asisten_tugas/kirimNotifikasi" method="post"
			id="form-notifikasi-tugas">
			<div class="row">
				<div class="input-field col s12">
					<input type="text" class="jadwal_kode" name="jadwal_kode" hidden>
					<textarea name="pesan" id="notif" class="materialize-textarea pesan_notifikasi" required
						style="text-indent:0; padding: 17px 16px 18px" spellcheck="false"></textarea>
					<label for="notif" id="label-notif" style="background-color: #fafafa;">Pesan Notifikasi</label>
				</div>
			</div>
			<br>
			<div class="center-align mymodal-loader hide">
				<div class="preloader-wrapper small active">
					<div class="spinner-layer spinner-blue-only">
						<div class="circle-clipper left">
							<div class="circle"></div>
						</div>
						<div class="gap-patch">
							<div class="circle"></div>
						</div>
						<div class="circle-clipper right">
							<div class="circle"></div>
						</div>
					</div>
				</div>
				<br>
				<div id="counter-notif" style="margin-top: 8px;">Mengirim notifikasi<br>Harap tunggu ...</div>
			</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit" class="waves-effect waves-light tombol tombol-sm tombol-primary right"><i
				class="material-icons left">send</i><span>Kirim</span></button>
		<button type="button" class="modal-close tombol tombol-sm transparent tombol-flat right">Batal</button>
	</div>
	</form>
</div>

<!-- Modal lihat Tugas -->
<div id="modalLihatTugas" class="modal modal-sm" style="max-width:375px">
	<div class="modal-content" style="overflow:hidden;padding:8px;position:relative">
		<div id="image-modal-wrapper"></div>
		<br>
		<div style="padding:8px 8px 0;max-height:200px;overflow:auto">
			<div><b>Tugas :</b></div>
			<div id="judul-tugas"></div>
			<div style="margin: 8px 0 12px;border-bottom: 1px dashed #bdbdbd;"></div>
			<div><b>Deskripsi :</b></div>
			<div id="deskripsi-tugas"></div>
			<br><br><br><br>
		</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="button" class="modal-close waves-effect transparent tombol tombol-sm tombol-flat">Tutup</button>
	</div>
</div>

<!-- Modal alert hapus -->
<div id="modalAlert" class="modal modal-sm">
	<div class="modal-content center-align">
		<i class="material-icons large red-text text-darken-1 center-align">warning</i>
		<p class="modal-title-alert">Oops!</p>
		<p>Data tidak dapat dihapus karena sudah digunakan pada menu sebelumnya.</p>
	</div>
	<div class="modal-footer">
		<button type="button" class="modal-close waves-effect waves-light tombol tombol-sm tombol-danger">X</button>
	</div>
</div>
