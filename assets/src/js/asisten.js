var urlLocation = window.location.pathname;
var path = window.location.pathname.split('/');
var currentPath = path[path.length - 1];

// alert
$('.alert-password').hide();

function showLoader(el) {
	$(el).html('<img src="../assets/images/loader/loader.gif" alt="loading" height="22">');
}

function showLoader2(el) {
	$(el).html('<img src="../assets/images/loader/loader.gif" alt="loading" height="22">');
}

// PENILAIAN ===========================================================================================================>
// penilaian praktikum
$(document).ready(function () {
	$('.input-nilai').hide();
	$('.td-nilai-ujian').on('click', function () {
		let id = $(this).data("npm");
		$('.txt-n-tugas' + id).hide();
		$('.inp-n-tugas' + id).show();
		$('.txt-n-ujian' + id).hide();
		$('.inp-n-ujian' + id).show();

		setTimeout(function () {
			$('.inp-n-tugas' + id).css('width', '50px');
			$('.inp-n-ujian' + id).css('width', '50px');
		}, 10);
	}).change(function () {
		let npm = $(this).data("npm");
		let jadwal_kode = $('.jadwal_kode').val();
		let p_kehadiran = $('.p_kehadiran').val();
		let p_tugas = $('.p_tugas').val();
		let p_ujian = $('.p_ujian').val();
		let kehadiran = $('.kehadiran' + npm).html();
		let text_tugas = $('.txt-n-tugas' + npm).text();
		let text_ujian = $('.txt-n-ujian' + npm).text();
		let tugas_total = $('#tugas' + npm).text();
		let nilai_tugas = $('.inp-n-tugas' + npm).val();
		let nilai_ujian = $('.inp-n-ujian' + npm).val();
		if (nilai_tugas > 100 || nilai_ujian > 100) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Maksimal nilai tugas atau ujian adalah 100');
			$('.inp-n-tugas' + npm).val(text_tugas);
			$('.inp-n-ujian' + npm).val(text_ujian);
		} else if (nilai_tugas < 0 || nilai_ujian < 0) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Oops! nilai tidak boleh minus');
			$('.inp-n-tugas' + npm).val(text_tugas);
			$('.inp-n-ujian' + npm).val(text_ujian);
		} else if (nilai_tugas < tugas_total) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Nilai tugas tidak boleh kurang dari total tugas');
			$('.inp-n-tugas' + npm).val(text_tugas);
			$('.inp-n-ujian' + npm).val(text_ujian);
		} else {
			showLoader('.txt-n-tugas' + npm);
			showLoader('.txt-n-ujian' + npm);
			let total = (kehadiran * p_kehadiran / 100) + (nilai_tugas * p_tugas / 100) + (nilai_ujian * p_ujian / 100);
			total = total.toString();
			$.ajax({
				type: "POST",
				url: "asisten_penilaian/penilaian",
				data: "npm=" + npm + "&jadwal_kode=" + jadwal_kode + "&nilai_tugas=" + nilai_tugas + "&nilai_ujian=" + nilai_ujian,
				success: function (data) {
					let respon = data.split('|');
					setTimeout(() => {
						$('.txt-n-tugas' + npm).html(respon[0]);
						$('.txt-n-ujian' + npm).html(respon[1]);
						$('.inp-n-tugas' + npm).val(respon[0]);
						$('.inp-n-ujian' + npm).val(respon[1]);
						$('.total' + npm).html(total);
					}, 1000);
				}
			});
		}
	});

	$(document).mouseup(function () {
		$('.hilang').hide();
		$('.muncul').show();
	});
});

// NILAI_PRAKTIKUM_DOSEN_
$(document).ready(function () {
	$('.pilih_kelas').change(function () {
		$('#kelas-kode').val($(this).val());
	});

	$(document).change(function () {
		if ($('#kelas-kode').val() == '' || $('#matkum-id').val() == '') {

		} else {
			$('#form-nilai').submit();
		}
	});

	$('.pilih_matkum').change(function () {
		$('#matkum-id').val($(this).val());
		$(".pilih_kelas").val(null).trigger('change');
	});
});

// MODUL_PRAKTIKUM_
$(document).ready(function (e) {
	$('.datatable, .datatable-nopage').on('click', '.status-modul', function () {
		let registrasi_id = $(this).attr('id');
		let status = $('#' + registrasi_id + ' .badge-status').html();
		showLoader('#' + registrasi_id);
		$.ajax({
			type: 'POST',
			url: 'modul_praktikum/statusModul',
			data: {
				registrasi_id: registrasi_id,
				status: status
			},
			success: function (data) {
				let respon = data.split('|');
				setTimeout(() => {
					$('#' + registrasi_id).html(respon[0]);
					$('#tgl' + registrasi_id).html(respon[1]);
				}, 1000);
			}
		});
		return false;
	});
});

// KRITIK_&_SARAN_
$(document).ready(function (e) {
	$('.datatable, .datatable-nopage').on('click', '.status-krisar', function () {
		let krisar_id = $(this).attr('id');
		let status = $('#' + krisar_id + ' .badge-status').html();
		showLoader('#' + krisar_id);
		$.ajax({
			type: 'POST',
			url: 'kritik_saran/statusKrisar',
			data: {
				krisar_id: krisar_id,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + krisar_id).html(data);
				}, 1000);
			}
		});
		return false;
	});
});

// PEMBAYARAN_BAK_
$(document).ready(function (e) {
	$('.datatable, .datatable-nopage').on('click', '.status-bayar', function () {
		let npm = $(this).attr('id');
		let status = $('#' + npm + ' .badge-status').html();
		let lunas = parseInt($('.lunas').html());
		let belum = parseInt($('.belum').html());
		showLoader('#' + npm);

		$.ajax({
			type: 'POST',
			url: 'pembayaran/statusBayar',
			data: {
				npm: npm,
				status: status
			},
			success: function (data) {
				let respon = data.split('|');
				setTimeout(() => {
					$('#' + npm).html(respon[1]);
					$('.tgl_bayar_' + npm).text(respon[2]);
					if (respon[0] === 'lunas') {
						$('.lunas').html(lunas + 1);
						$('.belum').html(belum - 1);
					} else {
						$('.lunas').html(lunas - 1);
						$('.belum').html(belum + 1);
					}
				}, 1000);
			}
		});
		return false;
	});
});

// TUGAS_PRAKTIKUM_
$(document).ready(function () {
	// HAPUS & EDIT
	$(".btn-hapus-tugas, .btn-edit-tugas").on('click', function () {
		let tugas_deskripsi = $(this).data("tugas_deskripsi");
		$('.summernote').eq(1).summernote('code', tugas_deskripsi);
		let tugas_id = $(this).data("tugas_id");
		let tugas_judul = $(this).data("tugas_judul");
		let tugas_kode = $(this).data("tugas_kode");
		let tugas_status = $(this).data("tugas_status");
		let foto_lama = $(this).data("foto_lama");
		let tanggal = $(this).data("tanggal");
		let jam = $(this).data("jam");
		let tugas_foto = $(this).data("tugas_foto");

		$(".tugas_id").val(tugas_id);
		$(".tugas_judul").val(tugas_judul);
		$(".tugas_kode").val(tugas_kode);
		$(".foto_lama").val(foto_lama);
		$(".tugas_foto").val(tugas_foto);
		$(".tanggal").val(tanggal);
		$(".jam").val(jam);
		$(".tugas_status").val(tugas_status);
		$(".tugas_deskripsi").val(tugas_deskripsi);
		$(".tugas-deskripsi-p").text(tugas_deskripsi);
		$(".tugas-judul-p").text(tugas_judul);
		$(".tugas-kode-txt").text(tugas_kode);
		$(".status_tugas").val(tugas_status);
		M.textareaAutoResize($('.tugas_deskripsi'));
		M.textareaAutoResize($('.tugas_judul'));
		$(".preview-image").attr("src", tugas_foto);
		$(".select2-data").trigger('change');
	});

	$('.btn-lihat-tugas').on('click', function () {
		let tugas_kode = $(this).data('tugas_kode');
		let tugas_judul = $(this).data('tugas_judul');
		let tugas_deskripsi = $(this).data('tugas_deskripsi');
		fetchImageCarousel('#image-modal-wrapper', tugas_kode);
		$('#judul-tugas').html(tugas_judul);
		$('#deskripsi-tugas').html(tugas_deskripsi);
	})

	// NOTIFIKASI
	$('.btn-notifikasi-tugas').on('click', function () {
		let pesan = $(this).data('pesan');
		let jadwal_kode = $(this).data('jadwal_kode');
		$('#label-notif').addClass('active');
		$('.pesan_notifikasi').val(pesan);
		$('.jadwal_kode').val(jadwal_kode);
		setTimeout(() => {
			M.textareaAutoResize($('.pesan_notifikasi'));
		}, 400);
	});

	$('#form-notifikasi-tugas').submit(function () {
		let notif = $(this).serialize();
		$('button[type=submit]').prop('disabled', true).css('cursor', 'wait');
		$('.mymodal-loader').removeClass('hide');
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: notif,
			success: function (response) {
				let res = response.split('|');
				$('.mymodal-loader').addClass('hide');
				$('button[type=submit]').prop('disabled', false).css('cursor', 'pointer');
				$('.modal').modal('close');
				if (res[0] == 'sending') {					
					tampilNotifikasiAjax('success-gradient', 'check_circle', 'Notifikasi tugas berhasil dikirim. Terkirim : '+res[1]);
				} else {
					tampilNotifikasiAjax('red-gradient', 'warning', 'Notifikasi tugas gagal dikirim. Ekstensi GMP tidak terinstal di server');
				}
			}
		});
		return false;
	});

	$(".tugas_select").change(function () {
		let status = $(this).val()
		$('input.tugas_status').val(status);
	});

	$(".btn-buat-tugas").on('click', function () {
		let tugas_kode = $(this).data("tugas_kode");
		$("#kode-t").val(tugas_kode);
		$('#in-edit').val(0);
	});

	$('#btn-batal-tambah').on('click', function () {
		$('#form-tambah').trigger("reset");
	});

	$('.get-image').on('click', function () {
		let tugas_kode = $(this).data('tugas_kode');
		$('.file-upload-tugas').val(null);
		$('#kode-t').val(tugas_kode);
		$('#in-edit').val(1);
		fetchImage('#img-preview-edit', tugas_kode);
	});

	// PENILAIAN TUGAS	
	$('.input-nilai').hide();
	$('.td-data').on('click', function () {
		let id = $(this).attr("id");
		$('.text-nilai' + id).hide();
		$('.input-nilai' + id).show();
		setTimeout(function () {
			$('.input-nilai' + id).css('width', '50px');
		}, 10);
	}).change(function () {
		let npm = $(this).attr("id");
		let tugas_id = $('.tugas_id').val();
		let jadwal_kode = $('.jadwal_kode').val();
		let nilai_asal = $('.text-nilai' + npm).html();
		let nilai = $('.input-nilai' + npm).val();
		let tgl_upload = $('.tgl_upload' + npm).val();
		if (nilai >= 10 && nilai <= 100 || nilai == 0) {
			showLoader2('.text-nilai' + npm);
			$.ajax({
				type: "POST",
				url: "asisten_tugas/penilaian",
				data: "npm=" + npm + "&tugas_id=" + tugas_id + "&nilai=" + nilai + "&nilai_asal=" + nilai_asal + "&jadwal_kode=" + jadwal_kode + "&tgl_upload=" + tgl_upload,
				success: function (data) {
					setTimeout(() => {
						$('.text-nilai' + npm).html(data);
					}, 1000);
					$('.input-nilai' + npm).val(data);
				}
			});
		} else if (nilai > 100) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Nilai maksimal adalah 100');
			$('.input-nilai' + npm).val(nilai_asal);
		} else if (nilai < 10 && nilai > 0) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Masukkan nilai antara 10 - 100');
			$('.input-nilai' + npm).val(nilai_asal);
		} else if (nilai < 0) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Oops! nilai tidak boleh minus');
			$('.input-nilai' + npm).val(nilai_asal);
		}
	});

	// STATUS TUGAS
	$('.datatable, .datatable-nopage').on('click', '.status-tugas', function () {
		let tugas_id = $(this).data('tugas_id');
		let status = $('#status-tugas-' + tugas_id + ' .badge-status').html();
		showLoader('#status-tugas-' + tugas_id);
		$.ajax({
			type: 'POST',
			url: 'asisten_tugas/statusTugas',
			data: {
				tugas_id: tugas_id,
				status: status
			},
			success: function (data) {
				let respon = data.split('|');
				setTimeout(() => {
					$('#status-tugas-' + tugas_id).html(respon[0]);
					$("#btn-edit-tugas-" + tugas_id).data('tugas_status', respon[1]);
				}, 1000);
			}
		});
		return false;
	});
});

function fetchImage(elem, tugas_kode) {
	$(elem).html('<div class="progress"><div class="indeterminate"></div></div>');
	$.ajax({
		type: 'GET',
		url: 'asisten_tugas/fetch_image?tugas_kode=' + tugas_kode,
		success: function (imagedata) {
			$(elem).html(imagedata);
			$('.materialboxed').materialbox();
		}
	});
}

function fetchImageCarousel(elem, tugas_kode) {
	$(elem).html('<div class="progress"><div class="indeterminate"></div></div>');
	$.ajax({
		type: 'GET',
		url: 'asisten_tugas/fetch_image_carousel?tugas_kode=' + tugas_kode,
		success: function (imagedata) {
			$(elem).html(imagedata);
			setTimeout(() => {
				$('.carousel').carousel({
					fullWidth: true,
					indicators: true,
					noWrap: true,
					dist: 0,
					shift: 8
				});
			}, 500);
		}
	});
}

// ABSENSI_PRAKTIKUM_
// MAHASISWA
$(document).ready(function () {
	$('.table-absensi-mhs').on('click', '.kehadiran', function () {
		let npm = $(this).attr('id');
		let kehadiran = $('#' + npm + ' .badge-status').html();
		let jadwal_kode = $('.jadwal_kode').val();
		let absen_tgl = $('.absen_tgl').val();
		let pertemuan = $('.pertemuan').val();

		if (pertemuan.length != 0) {
			showLoader('#' + npm);
			$.ajax({
				type: "POST",
				url: "asisten_absensi/do_absen",
				data: "npm=" + npm + "&kehadiran=" + kehadiran + "&jadwal_kode=" + jadwal_kode + "&pertemuan=" + pertemuan + "&absen_tgl=" + absen_tgl,
				success: function (data) {
					setTimeout(() => {
						$('#' + npm).html(data);
					}, 1000);
				}
			});
		} else {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Pertemuan belum dipilih!');
		}

		return false;
	});
});

// ASISTEN
$(document).ready(function () {
	$('.form-absen').submit(function () {
		let asisten_id = $('.asisten_id').val();
		let password = $('.password').val();
		let kehadiran = $('#asisten' + asisten_id + ' .badge-status').html();
		let jadwal_kode = $('.jadwal_kode').val();
		let absen_tgl = $('.absen_tgl').val();
		let pertemuan = $('.pertemuan').val();

		if (pertemuan.length != 0) {
			showLoader('#asisten' + asisten_id);
			$.ajax({
				type: "POST",
				url: "asisten_absensi/do_absen_asisten",
				data: "asisten_id=" + asisten_id + "&kehadiran=" + kehadiran + "&jadwal_kode=" + jadwal_kode + "&pertemuan=" + pertemuan + "&password=" + password + "&absen_tgl=" + absen_tgl,
				success: function (data) {
					let respon = data.split('|');
					if (respon[0] == 'salah') {
						$('.password').val('');
						$('.alert-password').slideDown(100);
						$('#asisten' + asisten_id).html(respon[1]);
						setTimeout(function () {
							$('.alert-password').slideUp(100);
						}, 3000);
					} else {
						$('.modal').modal('close');
						$('.alert-password').hide();
						$('.password').val('');						
						setTimeout(() => {
							$('#asisten' + asisten_id).html(respon[1]);
						}, 1000);
					}
				}
			});
		} else {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Pertemuan belum dipilih!');
		}

		return false;
	});
});

// KONFIRMASI ABSEN
$(".btn-konfirmasi-absen").on('click', function () {
	let asisten_id = $(this).data("asisten_id");

	$(".asisten_id").val(asisten_id);
});

// PERSENTASE_PENILAIAN_
$(".btn-edit-persentase").on('click', function () {
	let p_kehadiran = $(this).data("p_kehadiran");
	let p_tugas = $(this).data("p_tugas");
	let p_ujian = $(this).data("p_ujian");

	$(".p_kehadiran").val(p_kehadiran);
	$(".p_tugas").val(p_tugas);
	$(".p_ujian").val(p_ujian);
});

// GANTI_PASSWORD_
$(document).ready(function () {
	$(".btn-password").on('click', function () {
		let newPass = $('#new_pass').val();
		let oldPass = $('#old_pass').val();
		let confPass = $('#conf_new_pass').val();
		if (newPass != confPass) {
			$('#conf_new_pass').val('').focus();
			$('#helper_conf_pass').removeClass('hide');
		} else {
			gantiPassword(newPass, oldPass);
		}
	});
	$('input').keyup(function () {
		$('.helper-text').addClass('hide');
	});
});

function gantiPassword(newPass, oldPass) {
	$('.myloader').removeClass('hide');
	$.ajax({
		type: 'POST',
		url: 'profil/gantiPassword',
		data: {
			password: newPass,
			old_pass: oldPass
		},
		success: function (response) {
			let res = response.split('|');
			setTimeout(() => {
				if (res[0] == 'sukses') {
					location.href = res[1];
				} else {
					$('.myloader').addClass('hide');
					$('#old_pass').val('').focus();
					$('#helper_old_pass').removeClass('hide');
				}
			}, 1000);
		}
	})
}

// CATATAN_
$(document).ready(function () {
	// CATATAN HOVER
	$('.catatan-item').on({
		mouseenter: function () {
			$(this).children('.catatan-content').css('overflow', 'auto');
			$(this).children('.catatan-footer').children('.catatan-action').css('opacity', 1);
		},
		mouseleave: function () {
			$(this).children('.catatan-content').css('overflow', 'hidden').scrollTop(0);
			$(this).children('.catatan-footer').children('.catatan-action').css('opacity', 0);
		}
	});

	// PILIH WARNA
	$('.warna-item').on('click', function () {
		$('.catatan_warna').val($(this).data('warna'))
		$('.modal-head,.modal-content,.modal-footer').css('background-color', $(this).data('warna'));
		$('.warna-item').html('');
		$(this).html('<i class="material-icons warna-check">check</i>');
	});

	// ADD PIN
	$('.pin-btn').on('click', function () {
		let pin = $('.catatan_status').val();
		if (pin == 0) {
			$('.catatan_status').val(1);
			$(this).html('<i class="material-icons">push_pin</i>');
		} else {
			$('.catatan_status').val(0);
			$(this).html('<i class="material-icons-outlined">push_pin</i>');
		}
	});

	// TAMBAH CATATAN
	$('.btn-tambah-catatan').on('click', function () {
		$('.modal-head,.modal-content,.modal-footer').css('background-color', '#fafafa');
		$('.catatan_warna').val('#ffffff');
		$('.catatan_status').val(0);
		$('.pin-btn').html('<i class="material-icons-outlined">push_pin</i>');
		$('#default-warna').html('<i class="material-icons warna-check">check</i>');
	});

	// EDIT CATATAN
	$('.btn-edit-catatan').on('click', function () {
		let warna = $(this).data('catatan_warna');
		let is_pinned = $(this).data('catatan_status');

		$('.catatan_id').val($(this).data('catatan_id'));
		$('.catatan_judul').val($(this).data('catatan_judul'));
		$('.summernote-tentang').eq(1).summernote('code', $(this).data('catatan_isi'));
		$('.catatan_warna').val(warna);
		$('.catatan_status').val(is_pinned);

		$('.modal-head,.modal-content,.modal-footer').css('background-color', warna);
		$('.warna-item').html('');
		$(warna).html('<i class="material-icons warna-check">check</i>');

		if (is_pinned == 1) {
			$('.pin-btn').html('<i class="material-icons">push_pin</i>');
		} else {
			$('.pin-btn').html('<i class="material-icons-outlined">push_pin</i>');
		}
	});

	// HAPUS CATATAN
	$('.btn-hapus-catatan').on('click', function () {
		$('.modal-head,.modal-content,.modal-footer').css('background-color', '#fafafa');
		$('.catatan_id').val($(this).data('catatan_id'));
	});
});

$(document).ready(function () {
	if (currentPath == 'chat') {
		$('.p-link, .np-link').removeAttr('href').removeClass('nav__link');
		getAsisten();
		cek_pesan_fetch();
		setInterval(() => {
			fetch_pesan();
		}, 5000);
		$('.np-link, .p-link').on('click', function () {
			let user = $(this).data('user');
			toAsisten(user);
			$('.notif-pesan').css('right', '-500px');
		});

		$(document).on('click', '.p-link', function () {
			let user = $(this).data('user');
			toAsisten(user);
			$('.notif-pesan').css('right', '-500px');
		});
	} else {
		cek_pesan_fetch();
		setInterval(() => {
			cek_pesan_fetch();
		}, 10000);
	}

	// emoji
	for (let i = 128070; i <= 128080; i++) {
		$('.emoji-hand').append('<span class="emoji">&#' + i + ';&#127995;</span>');
	}

	for (let i = 129304; i <= 129311; i++) {
		$('.emoji-hand').append('<span class="emoji">&#' + i + ';&#127995;</span>');
	}

	$('.emoji-hand').append('<span class="emoji">&#129330;&#127995;</span>');
	$('.emoji-hand').append('<span class="emoji">&#128170;&#127995;</span>');
	$('.emoji-hand').append('<span class="emoji">&#128591;</span>');

	for (let i = 128512; i <= 128590; i++) {
		$('.emoji-smiley').append('<span class="emoji">&#' + i + ';</span>');
	}
	
	$('.emoji').on('click', function () {
		let emotes = $(this).text();
		let pesan = $('.chat-input-text').val();
		$('.chat-input-text').val(pesan + emotes);
	});

	$('.btn-emoji').on('click', function () {
		$('.emoji-container').toggle();
		let text = $(this).text();
		$(this).text(text == "insert_emoticon" ? "close" : "insert_emoticon");
	});

	// kontak
	$('.chat-user-box').on('click', function () {
		sessionStorage.setItem('chat-scroll', 'go');
		$('.chat-panel').show();
		$('.chat-start').html('<div class="progress"><div class="indeterminate"></div></div>');
		$('.notif-pesan').css('right', '-500px');
		$('.chat-user-box').css('background-color', '#fff');
		$(this).css('background-color', '#a8d6ff');
		setTimeout(() => {
			$('.chat-input').css('display', 'flex').addClass("fade-in-fwd");
		}, 500);
		let user_in = $(this).find('.chat-user-name').text();
		let u_token = $(this).find('.chat-user-name').data('u_token');
		let penerima = $(this).find('.chat-user-name').data('penerima');
		let kanal = $(this).find('.chat-user-name').data('kanal');
		let jabatan = $(this).find('.chat-user-name').data('jabatan');
		let foto = $(this).find('.chat-user-name').data('foto');
		$('#user-foto').attr('src', foto).removeClass('hide');
		$('#chat-with').text(user_in);
		$('#user-jabatan').text(jabatan);
		$('.kanal').val(kanal);
		$('.penerima').val(penerima);
		$('.u_token').val(u_token);
		$('.user_chat_id').val(penerima);
		$('.chat_kanal').val(kanal);
		fetch_pesan();
	});

	// kirim pesan
	$('.chat-btn').on('click', function () {
		kirimPesan();
		$('.send-loader').removeClass('hide');
		$('.chat-btn').addClass('hide');
	});

	// back chat
	$('.back-chat').on('click', function () {
		$('.chat-panel').hide();
	});
});

function kirimPesan() {
	sessionStorage.setItem('chat-scroll', 'go');
	let pesan = $('.chat-input-text').val();
	let kanal = $('.kanal').val();
	let penerima = $('.penerima').val();
	let pengirim = $('.pengirim').val();
	let u_token = $('.u_token').val();

	if (pesan == '') {
		$('.chat-input-text').focus();
	} else {
		$.ajax({
			type: 'POST',
			url: 'chat/kirim_pesan',
			data: {
				pesan: pesan,
				kanal: kanal,
				penerima: penerima,
				pengirim: pengirim
			},
			success: function () {
				fetch_pesan();
				$('.send-loader').addClass('hide');
				$('.chat-btn').removeClass('hide');
				$('.chat-input-text').val('').css('height', '49px');
				kirimNotifikasiPesan(u_token, pesan, kanal);
			}
		});
	}
}

function kirimNotifikasiPesan(u_token, pesan, kanal) {
	$.ajax({
		type: 'POST',
		url: 'chat/kirimNotifikasi',
		data: {
			token: u_token,	
			pesan: pesan,			
			kanal: kanal			
		},
		success: function (response) {
			console.log(response);
		}
	});	
}

function fetch_pesan() {
	let user = $('.user_chat_id').val();
	let kanal = $('.chat_kanal').val();
	$.ajax({
		type: 'GET',
		url: 'chat/fetch_pesan?penerima=' + user + '&kanal=' + kanal,
		dataType: 'json',
		success: function (result) {
			let chatResult = '';
			let position = '';
			let status = '';
			let pengirim = result[0];
			let penerima = result[1];
			let pesan = result[3];
			let datePesan = result[4]

			if (user != 'nouser') {				
				for (let index = 0; index < pesan.length; index++) {
					let userChat = pesan[index]['pesan_user'].split('-');
					let userJabatan = userChat[0].split('_');
					let kanal = pesan[index]['pesan_kanal'];
					let color = '';
					let prevIndex = index - 1;
					if (prevIndex < 0) {
						prevIndex = 0;
					}
					let tglPrev = pesan[prevIndex]['date_created'].split('-');
					let tglChat = pesan[index]['date_created'].split('-');
					let tglPesan = tglChat[0];
					if (tglChat[0] == datePesan) {
						tglPesan = 'HARI INI';
					}
					let tgl = '<div class="center-align"><span class="chat-tanggal">' + tglPesan + '</span></div>';
	
					if (userJabatan[0] == 'dosen') {
						color = 'style="color:#3671dc;font-size:14px"';
					}
	
					let nama = '<div class="nama-pengirim" ' + color + '>' + pesan[index]['pesan_nama_pengirim'] + '</div>';
	
					if (userChat[0] == pengirim) {
						position = 'chat-text-right';
						nama = '';
					} else {
						position = 'chat-text-left';
					}
	
					if (tglChat[0] == tglPrev[0] && index > 0) {
						tgl = '';
					}
	
					if (kanal == '-') {
						nama = '';
						if (userChat[0] == pengirim) {
							if (pesan[index]['id'] == null) {
								status = '<i class="material-icons right light-blue-text text-lighten-2 pesan-status" style="font-size:20px">done_all</i>';
							} else {
								status = '<i class="material-icons right grey-text text-lighten-1 pesan-status" style="font-size:20px">done_all</i>';
							}
						} else {
							status = '';
						}
					}
	
					chatResult += tgl + '<div class="' + position + ' ' + pengirim + '"><span class="pesan">' + nama + pesan[index]['pesan_isi'] + '<br>' + status + '<small>' + tglChat[1] + '</small></span></div>';
				}
				
				$('.chat-start').html(chatResult);
				if (sessionStorage.getItem('chat-scroll') == 'go') {					
					$('.chat-start').scrollTop($('.chat-start').height() * 100);
				}
				$('.last_' + penerima).css('font-weight', '400');
				if (pesan.length > 0) {
					$('.last_' + penerima).text(pesan[pesan.length - 1]['pesan_isi']);
				}
				$('.jml_' + penerima).addClass('hide');
				$('.read_' + penerima).addClass('hide');
				sessionStorage.setItem('chat-scroll', 'stop');
			}
			cek_pesan_fetch();
		}
	});
}

function cek_pesan_fetch() {
	let unRead = $('#jml-notif').val();
	let jmlTicketing = $('#jml-ticketing').val();
	let penerima = $('.penerima').val();
	$.ajax({
		type: 'GET',
		url: 'chat/cek_pesan',
		dataType: 'json',
		success: function (result) {
			let jumlahPesan = result[0];
			let notifikasi = result[1];
			let jumlahTicket = result[2];
			let notifResult = '';

			for (let index = 0; index < notifikasi.length; index++) {
				notifResult +=
					'<div class="p-pesan">' +
					'<img src="' + notifikasi[index]['foto'] + '" alt="profil">' +
					'<div>' +
					'<a class="p-link nav__link" data-user="' + notifikasi[index]['pesan_pengirim'] + '" href="' + notifikasi[index]['link'] + '">' +
					'<span class="p-pengirim">' + notifikasi[index]['pesan_nama_pengirim'] + '</span>' +
					'<span class="p-jumlah-pesan">' + notifikasi[index]['jumlah'] + ' pesan masuk</span>' +
					'</a>' +
					'</div>' +
					'</div>';
			}

			$('#jml-notif').val(jumlahPesan);
			$('#counter').text(jumlahPesan);

			if (jumlahPesan > 0) {
				let npLink = result[3]['link'];
				let npPengirim = result[3]['pesan_pengirim'];
				let npPesan = result[3]['pesan_isi'];
				let npFoto = result[3]['foto'];
				let npNama = result[3]['pesan_nama_pengirim'];
				let npJumlah = result[3]['jumlah'];

				$('.pulse-pesan').removeClass('hide');
				$('#notif-pesan').html(notifResult);
				$('.read_' + npPengirim).addClass('hide');
				$('.last_' + npPengirim).css('font-weight', '600').text(npPesan);
				$('.jml_' + npPengirim).text(npJumlah).removeClass('hide');

				if (jumlahPesan > unRead) {
					if (npPengirim != penerima) {
						$('.notif-pesan').css('right', '-500px');
						setTimeout(() => {
							$('.np-link').attr('href', npLink);
							$('.np-link').data('user', npPengirim);
							$('.np-foto').attr('src', npFoto);
							$('.np-pengirim').text(npNama);
							$('.np-pesan').text(npJumlah + ' pesan masuk');
							$('.notif-pesan').css('right', '20px');
						}, 1000);
					}
				}
			} else {
				$('#notif-pesan').html('<div style="padding: 12px; text-align: center">Tidak ada pesan</div>');
				$('.pulse-pesan').addClass('hide');
			}

			if (jumlahTicket > jmlTicketing) {
				kirimNotifikasiTicket('pesan');
			}

			if (jumlahTicket > 0) {
				$('.pulse-ticket').removeClass('hide');
			} else {
				$('.pulse-ticket').addClass('hide');
			}

			$('#jml-ticketing').val(jumlahTicket);

			if (currentPath == 'chat') {
				$('.p-link, .np-link').removeAttr('href').removeClass('nav__link');			
			}

		}
	});
}

function getAsisten() {
	let asisten = location.href.split('?');
	if (asisten.length == 2) {
		setTimeout(() => {
			$('#' + asisten[1]).css('background-color', '#a8d6ff');
			$('.chat-contact').scrollTop($('#' + asisten[1]).offset().top - 190);
			$('#' + asisten[1]).click();
		}, 100);
	}
}

function toAsisten(asisten) {
	$('.chat-contact').scrollTop($('#' + asisten).offset().top - 190);
	$('#' + asisten).click();
}

// INVENTORI_
$(document).ready(function () {
	// TAMBAH BARANG
	$('.barang_ktg, .barang_mrk').on('change', function () {
		let ktg = $('.barang_ktg option:selected').text();
		let mrk = $('.barang_mrk option:selected').text();
		let tipe = $('.barang_tipe').val();
		let barang = $('.barang_nama').val();
		$('.barang_nama').val(ktg + ' - ' + mrk + ' ' + tipe);
	});
	$('.barang_tipe').on('keyup', function () {
		let ktg = $('.barang_ktg option:selected').text();
		let mrk = $('.barang_mrk option:selected').text();
		let tipe = $('.barang_tipe').val();
		let barang = $('.barang_nama').val();
		$('.barang_nama').val(ktg + ' - ' + mrk + ' ' + tipe);
	});

	$('.btn-tambah-barang').on('click', function () {
		let action = $(this).data('action');
		$('.form-inv-barang').attr('action', action);
		$('#modal-title-barang').text('Tambah Barang');
		$('.select2-barang').val(null).trigger('change');
		$('.barang_tipe').val(null);
		$('.barang_nama').val(null);
		$('.barang_jumlah').val(null);
	});

	// EDIT & HAPUS BARANG
	$('.btn-edit-barang, .btn-hapus-barang').on('click', function () {
		$('#modal-title-barang').text('Edit Barang');
		let action = $(this).data('action');
		$('.form-inv-barang').attr('action', action);
		let barang_id = $(this).data('barang_id');
		let barang_nama = $(this).data('barang_nama');
		let ktg = $(this).data('kategori_id');
		let mrk = $(this).data('merek_id');
		let tipe = $(this).data('barang_tipe');
		let jumlah = $(this).data('barang_jumlah');

		$('.barang_id').val(barang_id);
		$('.barang_ktg').val(ktg);
		$('.barang_mrk').val(mrk);
		$('.barang_jumlah').val(jumlah);
		$('.select2-barang').trigger('change');
		$('.barang_tipe').val(tipe);
		let ktg_txt = $('.barang_ktg option:selected').text();
		let mrk_txt = $('.barang_mrk option:selected').text();
		$('.barang_nama').val(ktg_txt + ' - ' + mrk_txt + ' ' + tipe);
		$('.barang_nama_hapus').val(barang_nama);
		$('.txt-barang').text(barang_nama);
	});

	// EDIT & HAPUS KATEGORI
	$('.btn-edit-kategori, .btn-hapus-kategori').on('click', function () {
		let kategori_id = $(this).data('kategori_id');
		let kategori_nama = $(this).data('kategori_nama');

		$('.kategori_id').val(kategori_id);
		$('.kategori_nama').val(kategori_nama);
		$('.txt-kategori').text(kategori_nama);
	});

	// EDIT & HAPUS MEREK
	$('.btn-edit-merek, .btn-hapus-merek').on('click', function () {
		let merek_id = $(this).data('merek_id');
		let merek_nama = $(this).data('merek_nama');

		$('.merek_id').val(merek_id);
		$('.merek_nama').val(merek_nama);
		$('.txt-merek').text(merek_nama);
	});

	// EDIT & HAPUS LAPORAN
	$('.btn-hapus-laporan').on('click', function () {
		let laporan_id = $(this).data('laporan_id');
		let laporan_nama = $(this).data('laporan_nama');

		$('.laporan_id').val(laporan_id);
		$('.laporan_nama').val(laporan_nama);
		$('.txt-laporan').text(laporan_nama);
	});

	// TANGGAL LAPORAN
	$('.tgl-laporan-inv').on('change', function () {
		$('.tanggal_laporan').val($(this).val());
		$('.btn-simpan-laporan').attr('type', 'submit');
	});

	$('.btn-simpan-laporan').on('click', function () {
		let tanggal = $('.tanggal_laporan').val();
		if (tanggal == '') {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Tanggal belum diisi!');
		}
	});

	// TAMBAH PC
	$('.btn-add-komponen').on('click', function () {
		$('.input-komponen').append('<div class="select2-wrapper"></div>');
		$('.select-komponen .select2-komponen:first-child').clone().appendTo($('.input-komponen').children().last());
		$('.select2-komponen').select2({
			minimumResultsForSearch: 10
		});
	});

	// EDIT & HAPUS PC
	$('.btn-pc').on('click', function () {
		let pc_id = $(this).data('pc_id');
		let pc_nama = $(this).data('pc_nama');
		let pck_nama = $(this).data('pck_nama');
		let pck_id = $(this).data('pck_id');
		let barang_id = $(this).data('barang_id');
		// let komponen = $(this).data("komponen").split(',');

		$('.pc_id').val(pc_id);
		$('.pck_id').val(pck_id);
		$('.pc_nama').val(pc_nama);
		$('.pck_nama').val(pck_nama);
		$('.txt-pc').text(pc_nama);
		$('.txt-komponen-pc').text(pck_nama);
		$('.pc_komp').val(barang_id);
		$('.pc_komp').trigger('change');
	})
});

// UPLOAD FOTO PROFIL
$(document).ready(function () {
	// cekDimensiFoto();
	$('.btn-upload').on('click', function () {
		$('.file-upload').click();
	});

	$('.file-upload').change(function () {
		let config = cekConfigFoto(this);
		if (config == 1) {
			readURL(this);
		}
	});

	$('.cancel-profil').on('click', function () {
		let foto = $(this).data('foto');
		$('.preview-image').attr('src', foto);
		$('.hidden-image').attr('src', foto);
		$('.confirm-profil').addClass('hide');
		$('.file-upload').val('');
	});

	$(".ok-profil").on('click', function () {
		$('.confirm-profil').addClass('hide');
		$('.preview-image').css('filter', 'blur(10px)');
		$('.myloader-text').text('Mengupload . . .').removeClass('hide');
		$('.myloader').removeClass('hide');
		let fd = new FormData($('#upload-form')[0]);
		let files = $('.file-upload')[0].files[0];
		let foto_nama = $('#foto-nama').val();
		fd.append('file', files);
		fd.append('foto_nama', foto_nama);
		$.ajax({
			url: 'profil/uploadFoto',
			type: 'POST',
			data: fd,
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				$('.file-upload').val(null);
				if (response != 'gagal') {
					setTimeout(() => {
						$('#foto-nama').val(response);
						$('img.circle').attr('src', $('.preview-image').attr('src'));
					}, 2000);
				} else {
					tampilNotifikasiAjax('orange-gradient', 'warning', 'Upload foto gagal');
				}
				setTimeout(() => {
					$('.myloader-text').text('Loading . . .').addClass('hide');
					$('.myloader').addClass('hide');
					$('.preview-image').css('filter', 'none');
					tampilNotifikasiAjax('blue-gradient', 'info_outline', 'Foto profil berhasil diubah');
				}, 2000);
			},
		});
	});

	$('#toggle-profil').on('click', function () {
		if ($(this).hasClass('tombol-primary')) {
			$(this).css({
				'background-color': 'transparent'
			}).removeClass('tombol-primary').removeClass('waves-light');
			$(this).children('i').text('sync');
			$(this).children('span').text('Batal');
			$('.btn-tambah').removeClass('hide');
			$('.input-profil').prop('disabled', false);
		} else {
			$(this).css({
				'background-color': 'transparent'
			}).addClass('tombol-primary').addClass('waves-light');
			$(this).children('i').text('edit');
			$(this).children('span').text('Edit Profil');
			$('.btn-tambah').addClass('hide');
			$('.input-profil').prop('disabled', true);
			batalEditProfil();
		}
	});

	// UPLOAD FOTO TUGAS
	$('.btn-upload-tugas').on('click', function () {
		$('#image-id').val('-');
		$('#image-edit').val('-');
		$('.file-upload-tugas').click();
	});

	$('.file-upload-tugas').change(function () {
		let id = $('#image-id').val();
		let config = cekConfigFoto(this);
		if (config == 1) {
			if (id != '-') {
				$('#action-foto-' + id).hide();
				$('#foto-tugas-' + id).css('filter', 'blur(10px)');
				$('#myloader-' + id).removeClass('hide');
			} else {
				$('.preview-wrapper, .myloader').removeClass('hide');
				$('.preview-image').css('filter', 'blur(10px)');
			}
			readTugas(this);
		}
	});

	$(document).on('click', '.delete-foto-tugas', function () {
		let id = $(this).data('id');
		let gambar = $(this).data('gambar');
		$('#action-foto-' + id).hide();
		$('#foto-tugas-' + id).css('filter', 'blur(10px)');
		$('#myloader-' + id).removeClass('hide');
		$.ajax({
			url: 'asisten_tugas/hapusFoto',
			type: 'POST',
			data: {
				id: id,
				gambar: gambar
			},
			success: function (response) {
				if (response != 'gagal') {
					setTimeout(() => {
						$('#img-wrapper-' + id).remove();
					}, 1000);
				} else {
					tampilNotifikasiAjax('orange-gradient', 'warning', 'Hapus foto gagal');
					$('#action-tugas-' + id).show();
					$('#foto-tugas-' + id).css('filter', 'none');
					$('#myloader-' + id).addClass('hide');
				}
			},
		});
	});

	$(document).on('click', '.edit-foto-tugas', function () {
		let id = $(this).data('id');
		let gambar = $(this).data('gambar');
		$('#image-id').val(id);
		$('#image-edit').val(gambar);
		$('.file-upload-tugas').click();
	});
});

function uploadFotoTugas() {
	let inEdit = $('#in-edit').val();
	let fd = new FormData($('#upload-form')[0]);
	let files = $('.file-upload-tugas')[0].files[0];
	$('.btn-upload-tugas').prop('disabled', true).css('cursor', 'wait');
	fd.append('file', files);
	$.ajax({
		url: 'asisten_tugas/uploadFoto',
		type: 'POST',
		data: fd,
		cache: false,
		contentType: false,
		processData: false,
		success: function (response) {
			$('.file-upload-tugas').val(null);
			let res = response.split('|');
			if (res[0] == 'tambah') {
				$('.preview-wrapper, .myloader').addClass('hide');
				$('.preview-image').css('filter', 'none');
				if (inEdit == 1) {
					$('#img-preview-edit').append(res[1]);
				} else {
					$('#img-preview-tambah').append(res[1]);
				}
				$('.materialboxed').materialbox();
			} else if (res[0] == 'edit') {
				$('#image-id').val('-');
				$('#image-edit').val('-');
				$('#edit-' + res[1]).data('gambar', res[3]);
				$('#hapus-' + res[1]).data('gambar', res[3]);
				setTimeout(() => {
					$('#foto-tugas-' + res[1]).attr('src', res[2]);
					$('#foto-tugas-' + res[1]).css('filter', 'none');
					$('#action-foto-' + res[1]).show();
					$('#myloader-' + res[1]).addClass('hide');
				}, 1000);
			} else {
				$('#action-foto-' + res[1]).show();
				$('#myloader-' + res[1]).addClass('hide');
				tampilNotifikasiAjax('orange-gradient', 'warning', 'Upload foto gagal');
			}
			$('.btn-upload-tugas').prop('disabled', false).css('cursor', 'pointer');
		}
	});
}

function batalEditProfil() {
	$('#asisten_nama').val($('#asisten_nama').data('asisten_nama'));
	$('#dosen_nama').val($('#dosen_nama').data('dosen_nama'));
	$('#tgl_lahir').val($('#tgl_lahir').data('tgl_lahir'));
	$('#bulan_lahir').val($('#bulan_lahir').data('bulan_lahir'));
	$('#tahun_lahir').val($('#tahun_lahir').data('tahun_lahir'));
	$('#alamat').val($('#alamat').data('alamat'));
	$('#email').val($('#email').data('email'));
	$('.select2').trigger('change');
}

function readTugas(input) {
	if (input.files && input.files[0]) {
		let reader = new FileReader();
		reader.onload = function (e) {
			$('#hidden-image').attr('src', e.target.result);
			$('.preview-image').attr('src', e.target.result);
			setTimeout(() => {
				cekDimensiFotoTugas(e.target.result);
			}, 1000);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function readURL(input) {
	if (input.files && input.files[0]) {
		let reader = new FileReader();
		reader.onload = function (e) {
			$('.myloader-text').removeClass('hide');
			$('.myloader').removeClass('hide');
			$('.hidden-image').attr('src', e.target.result);
			$('.preview-image').attr('src', e.target.result);
			$('.preview-image').css('filter', 'blur(10px)');
			setTimeout(() => {
				cekDimensiFoto(e.target.result);
				$('.preview-image').css('filter', 'none');
				$('.myloader-text').addClass('hide');
				$('.myloader').addClass('hide');
			}, 1000);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

function cekConfigFoto(input) {
	let fileExtension = ['jpeg', 'jpg', 'png', 'webp'];
	if ($.inArray($(input).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
		tampilNotifikasiAjax('orange-gradient', 'warning', 'Format foto tidak didukung. Pastikan foto berformat ' + fileExtension.join(', '));
		return 0;
	} else {
		if (input.files[0].size > 2000000 || input.files[0].fileSize > 2000000) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Ukuran foto terlalu besar. Maksimal 2MB');
			return 0;
		} else {
			return 1;
		}
	}
}

function cekDimensiFotoTugas(src) {
	let id = $('#image-id').val();
	const hidden = document.getElementById('hidden-image');
	if (hidden.naturalWidth !== hidden.naturalHeight) {
		tampilNotifikasiAjax('orange-gradient', 'warning', 'Lebar dan tinggi foto tidak sama. Foto terpilih = ' + hidden.naturalWidth + 'x' + hidden.naturalHeight);
		$('.file-upload-tugas').val(null);
		$('.preview-wrapper, .myloader').addClass('hide');
		$('#foto-tugas-' + id).css('filter', 'none');
		$('#action-foto-' + id).show();
		$('#myloader-' + id).addClass('hide');
	} else if (hidden.naturalWidth > 1080 || hidden.naturalHeight > 1080) {
		tampilNotifikasiAjax('orange-gradient', 'warning', 'Lebar dan tinggi foto terlalu besar. Maksimal 1080x1080');
		$('.file-upload-tugas').val(null);
		$('.preview-wrapper, .myloader').addClass('hide');
		$('#foto-tugas-' + id).css('filter', 'none');
		$('#action-foto-' + id).show();
		$('#myloader-' + id).addClass('hide');
	} else {
		uploadFotoTugas();
	}
}

function cekDimensiFoto(src) {
	const hidden = document.getElementsByClassName('hidden-image');
	if (hidden.naturalWidth !== hidden.naturalHeight) {
		tampilNotifikasiAjax('orange-gradient', 'warning', 'Lebar dan tinggi foto tidak sama. Foto terpilih = ' + hidden.naturalWidth + 'x' + hidden.naturalHeight);
		$('.file-upload').val(null);
		$('.cancel-profil').click();
	} else if (hidden.naturalWidth > 1080 || hidden.naturalHeight > 1080) {
		tampilNotifikasiAjax('orange-gradient', 'warning', 'Lebar dan tinggi foto terlalu besar. Maksimal 1080x1080');
		$('.file-upload').val(null);
		$('.cancel-profil').click();
	} else {
		$('.preview-image').attr('src', src);
		$('.confirm-profil').removeClass('hide');
	}
}

function kirimNotifikasiTicket(kanal) {
	$.ajax({
		type: 'POST',
		url: 'ticketing/notifikasiTicketing',
		data: {
			kanal: kanal
		},
		success: function (response) {
			console.log(response);
		}
	});	
}

// CHATTING_
var pusher = new Pusher('bf535588d7d38b92fa01', {
	cluster: 'ap1'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function () {

	let penerima = $('.penerima').val();
	let kanal = $('.kanal').val();

	if (currentPath != 'chat') {
		cek_pesan_fetch();
	} else {
		if (penerima == '') {
			cek_pesan_fetch();
		} else {
			fetch_pesan();
		}
	}
});