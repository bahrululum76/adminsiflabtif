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
?>
<div class="col s12">
	<div class="main-wrapper">
		<div class="top-panel right-align">
			<span class="panel-title-1 left" style="margin-left: 36px; position: relative; top: 4px">Modul Praktikum</span>
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
							<option <?= $selected ?>
								value="<?= base_url() ?>asisten/modul-praktikum?idp=<?= $value->jadwal_kode ?>">
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
                                                foreach ($jadwal->result() as $key => $value):
                                                    $selected = '' ; 
                                                    if ($idp == $value->jadwal_kode) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    } 
                                                    ?>
										<option <?= $selected ?>
											value="<?= base_url() ?>asisten/modul-praktikum?idp=<?= $value->jadwal_kode ?>">
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
					<table class="highlight datatable-nopage">
						<thead>
							<tr>
								<th class="blue-text text-accent-3">#</th>
								<th width="150">NPM</th>
								<th>Nama</th>
								<th class="center" width="150">Tanggal</th>
								<th class="center">Status Pengambilan</th>
							</tr>
						</thead>

						<tbody>
							<?php
                                foreach ($mahasiswa->result() as $key => $value) { 
                                $status = '';
                                if ($value->status_modul == 0){ 
                                    $status = '<span class="badge-status badge-not">Belum</span>';
                                } else { 
                                    $status = '<span class="badge-status badge-ok">Sudah</span>';
                                }
                            ?>
							<tr>
								<td><?= $key+1 ?></td>
								<td><?= $value->npm ?></td>
								<td><?= $value->nama ?></td>
								<td class="center" id="tgl<?= $value->registrasi_id ?>"><?= $value->tgl_modul ?></td>
								<td class="status-modul td-data center" id="<?= $value->registrasi_id ?>"><?= $status ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<br>
				</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>
