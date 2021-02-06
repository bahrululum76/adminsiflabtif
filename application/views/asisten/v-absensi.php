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
    $periode = $this->db->limit(1)->order_by('periode_id DESC')->get('t_periode')->row();   
    $bulan = bulanIndonesia();
	$sekarang = date('d')." $bulan ".date('Y');
	
	if (isset($idp)) {
		$absen = $this->M_database->cekTanggal(array('jadwal_kode' => $idp, 'absen_tgl' => $sekarang));
	//echo var_dump($absen);
		if ($absen) {        
			$last_absen = $absen[0]['absen_tgl'];
			if ($last_absen == $sekarang) {
				$current_pertemuan = $absen[0]['pertemuan'];                            
			} else {
				$current_pertemuan = $absen[0]['pertemuan'] + 1;                       
			}
		} else {
			$last_absen = $this->db->select('absen_tgl, pertemuan')->order_by('pertemuan', 'DESC')->limit(1)->where(array('jadwal_kode' => $idp))->get('t_absensi_asisten')->row_array();
			if ($last_absen['absen_tgl'] == $sekarang) {
				$current_pertemuan = $last_absen['pertemuan'];                            
			} else {
				$current_pertemuan = $last_absen['pertemuan'] + 1;                       
			}
		}
	}
?>

<style>
	.select2-results__option[aria-selected=true] {
		margin: 8px 0;
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice {
		border: none;
		color: #000000;
		margin: 8px 0 0;
		padding: 4px 0 4px 10px;
		background: transparent;
		list-style: none;
		font-weight: 500;
	}

	.select2-selection__choice:not(:first-child)::before {
		content: ', ';
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice__remove,
	.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
		color: #fff;
		margin-right: 5px;
		display: none;
	}

	input[type=search]:not(.browser-default):focus:not([readonly]) {
		border: none;
		-webkit-box-shadow: none;
		box-shadow: none;
	}

</style>
<div class="col s12">
	<div class="main-wrapper">
		<div class="top-panel right-align">
			<span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Absensi Praktikum
			</span>
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
                                    if ($idp == $value->jadwal_kode) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    } 
                                    ?>
							<option class="nav__link" <?= $selected ?> value="<?= base_url() ?>asisten/absensi?idp=<?=$value->jadwal_kode?>"><?= strtoupper($value->jadwal_kode) ?></option>
							<?php endforeach ?>
						</select>
					</span>
				</div>
				<?php endif ?>
				<?php if (isset($idp)) : ?>
				<div style="font-weight:600;padding-right:8px" class="right-align absen-date-2">
					<i class="material-icons" style="font-size:1.2rem;margin-right:4px;position:relative;top:4px">today</i>
					<?= $sekarang ?>
				</div>
				<div class="head-panel">
					<div class="row" style="margin: 0">
						<div class="head-p" style="align-items:center;position:relative">
							<span class="absen-date-1" style="position:absolute;right:0;font-weight:600;margin-top:-4px"><i
									class="material-icons left"
									style="font-size:1.2rem;margin-right:8px">today</i><?= $sekarang ?>
							</span>
							<div style="align-items:center">
								<span>ID Praktikum</span>
								<span class="select2-wrapper">
									<select class="select2-no-search" onchange="location = this.value;document.querySelector('.mypage-loader').classList.remove('hide');">
										<?php 
                                                    foreach ($jadwal->result() as $key => $value):
                                                        $selected = '' ; 
                                                        if ($idp == $value->jadwal_kode) {
                                                            $selected = 'selected';
                                                        } else {
                                                            $selected = '';
                                                        } 
                                                        ?>
										<option class="nav__link" <?= $selected ?>
											value="<?= base_url() ?>asisten/absensi?idp=<?= $value->jadwal_kode ?>">
											<?= strtoupper($value->jadwal_kode) ?></option>
										<?php endforeach ?>
									</select>
								</span>
							</div>
							<div style="align-items:center">
								<span>Pertemuan</span>
								<?php 
                                        if ($current_pertemuan <= 10) { ?>
								<span class="select2-wrapper"
									style="max-width:100px;min-width:58px;width:fit-content; width:-moz-fit-content;">
									<select class="select2-no-search pertemuan" name="pertemuan[]" multiple="multiple"
										required>
										<?php
                                                    $lastPertemuan = $current_pertemuan; 
                                                    foreach ($absen as $key => $value) {
                                                        echo '<option selected value="'.$value['pertemuan'].'">'.$value['pertemuan'].'</option>';
                                                        $lastPertemuan = $value['pertemuan']+1;
                                                    }                                             
                                                    for ($i = $lastPertemuan; $i <= 10; $i++) { 
                                                        $selected = '' ; 
                                                            if ($i == $current_pertemuan) {
                                                                $selected = 'selected';
                                                            }
                                                    ?>
										<option <?=$selected?> value="<?=$i?>"><?= $i ?></option>
										<?php } ?>
									</select>
								</span>
								<?php } else { ?>
								<span>:</span>
								<span class="isi">-</span>
								<?php } ?>
							</div>
						</div>
						<br>
						<div class="head-p">
							<div>
								<span>Praktikum</span>
								<span>:</span>
								<span class="isi"><?= ucwords($jadwal_ini->matkum) ?></span>
							</div>
							<div>
								<span>Kelas</span>
								<span>:</span>
								<span class="isi"><?= $jadwal_ini->kelas_nama ?></span>
							</div>
						</div>
						<br>
						<div class="head-p">
							<div>
								<span>Jadwal</span>
								<span>:</span>
								<span class="isi"> <?= $jadwal_ini->jadwal_hari ?>,
									<?= $jadwal_ini->jadwal_jam ?></span>
							</div>
							<div>
								<span>Ruangan</span>
								<span>:</span>
								<span class="isi"><?= $jadwal_ini->ruangan_nama ?></span>
							</div>
						</div>
					</div>
				</div>				
				<div class="table-wrapper">
					<table class="highlight datatable-nopage">
						<thead>
							<tr>
								<th class="blue-text text-accent-3" width="50px">#</th>
								<th width="120px">Pengajar</th>
								<th>Nama Asisten</th>
								<th class="center" width="200px">Kehadiran</th>
							</tr>
						</thead>
						<tbody>
							<?php                                                                                               
                                $asisten= $this->db->where('jadwal_kode', $idp)->get('t_jadwal')->row();                                                
                                $asisten_1 = $this->db->where('asisten_id', $asisten->asisten_1)->get('t_asisten')->row();
                                $asisten_2 = $this->db->where('asisten_id', $asisten->asisten_2)->get('t_asisten')->row();
                                
                                $cek_asisten_1 = $this->M_database->cekdataAbsen('t_absensi_asisten', 'asisten_id', 'jadwal_kode', 'pertemuan', $asisten->asisten_1, $idp, $current_pertemuan);
                                $cek_asisten_2 = $this->M_database->cekdataAbsen('t_absensi_asisten', 'asisten_id', 'jadwal_kode', 'pertemuan', $asisten->asisten_2, $idp, $current_pertemuan);                                
                                
                                if ($current_pertemuan <= 10) {
									$modal = 'modal-trigger';
                                    if ($cek_asisten_1 != 0 ) {
                                        $kehadiran1 = '<span class="badge-status badge-ok">Hadir</span>';
                                        $button = ''; 
                                    } else {
                                        $kehadiran1 = '<span class="badge-status badge-not">Tidak Hadir</span>';
                                        $button = 'btn-konfirmasi-absen';    
                                    }

                                    if ($cek_asisten_2 != 0 ) {
                                        $kehadiran2 = '<span class="badge-status badge-ok">Hadir</span>';
                                        $button = '';                                                    
                                    } else {
                                        $kehadiran2 = '<span class="badge-status badge-not">Tidak Hadir</span>';
                                        $button = 'btn-konfirmasi-absen';                                                                                                       
                                    }
                                } else {
                                    $kehadiran1 = $this->M_database->kehadiranAsisten($idp, $asisten->asisten_1)->kehadiran * 10 . '%';
                                    $kehadiran2 = $this->M_database->kehadiranAsisten($idp, $asisten->asisten_2)->kehadiran * 10 . '%';
                                    $button = '';
									$modal = '';
                                }                                
                            ?>
							<tr>
								<td>1</td>
								<td>Pengajar 1</td>
								<td><?= $asisten_1->asisten_nama ?></td>
								<td class="td-data center <?= $modal ?> <?=$button?>"
									id="asisten<?= $asisten_1->asisten_id ?>" data-target="modalKonfirmasi"
									data-asisten_id="<?= $asisten_1->asisten_id ?>"><?= $kehadiran1 ?></td>
							</tr>
							<tr>
								<td>2</td>
								<td>Pengajar 2</td>
								<td><?= $asisten_2->asisten_nama ?></td>
								<td class="td-data center <?= $modal ?> <?=$button?>"
									id="asisten<?= $asisten_2->asisten_id ?>" data-target="modalKonfirmasi"
									data-asisten_id="<?= $asisten_2->asisten_id ?>"><?= $kehadiran2 ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="table-wrapper">
					<div class="line-table"></div>
					<table class="highlight datatable-nopage table-absensi-mhs">
						<thead>
							<tr>
								<th class="blue-text text-accent-3" width="50px">#</th>
								<th width="120px">NPM</th>
								<th>Nama Mahasiswa</th>
								<?php 
                                if ($current_pertemuan <= 10) {
                                    echo '<th class="center" width="200px">Kehadiran</th>';
                                } else {                                  
                                    echo '<th class="center" width="200px">Total Kehadiran</th>';
                                } ?>
							</tr>
						</thead>
						<tbody>
							<?php
                                foreach ($mahasiswa->result() as $key => $value) {
                                $cek_absen = $this->M_database->cekdataAbsen('t_absensi', 'npm', 'jadwal_kode', 'pertemuan', $value->npm, $idp, $current_pertemuan);  
                                if ($cek_absen != 0) {
                                    $kehadiran = '<span class="badge-status badge-ok">Hadir</span>';                                                   
                                } else {
                                    $kehadiran = '<span class="badge-status badge-not">Tidak Hadir</span>';                                                                                                       
                                }
                            ?>
							<tr>
								<input type="hidden" name="jadwal_kode" class="jadwal_kode" value="<?= $this->input->get('idp') ?>">
								<input type="hidden" name="absen_tgl" class="absen_tgl" value="<?= $sekarang ?>">
								<td><?= $key+1 ?></td>
								<td><?= $value->npm ?></td>
								<td><?= $value->nama ?></td>
								<?php 
                                        if ($current_pertemuan <= 10) {
                                            echo '<td class="btn-kehadiran kehadiran td-data center" id="'.$value->npm.'">'.$kehadiran.'</td>';
                                        } else {
                                            $kehadiran = $this->M_database->kehadiran($idp, $value->npm)->kehadiran * 10;
                                            echo '<td class="btn-kehadiran td-data center" id="'.$value->npm.'">'.$kehadiran.'%</td>';
                                        } ?>
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

<!-- Modal konfirmasi absensi -->
<div id="modalKonfirmasi" class="modal modal-sm">
	<div class="modal-head">
		<span class="modal-title">Konfirmasi absen</span>
	</div>
	<div class="modal-content modal-content-sm custom-modal-content">
		<div class="row">
			<div class="alert-password panel red-gradient white-text" style="padding: 16px 12px; margin: 0 9px 8px">
				<span>Password salah</span>
			</div>
			<form class="form-absen" method="post">
				<div class="input-field col s12">
					<input type="hidden" name="asisten_id" class="asisten_id">
					<input id="password" type="password" class="password" name="password">
					<label for="password" style="width:fit-content; background-color:#fafafa">Password</label>
				</div>
		</div>
	</div>
	<div class="modal-footer custom-modal-footer">
		<button type="submit"
			class="waves-effect waves-light tombol tombol-sm tombol-primary right btn-tambah right"><span>Konfirmasi</span></button>
		<button type="button" class="modal-close transparent tombol tombol-sm tombol-flat right">Batal</button>
	</div>
	</form>
</div>
