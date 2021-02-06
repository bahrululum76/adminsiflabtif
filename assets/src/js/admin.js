var path = window.location.pathname.split('/');
var currentPath = path[path.length - 1];

function showLoader(el) {
	$(el).html('<img src="../assets/images/loader/loader.gif" alt="loading" height="22">');
}

// alert
$('.alert-password').hide();

// APLIKASI
$(document).ready(function () {
	// STATUS UJIAN
	$('.status_ujian').on('click', function () {
		let status = $(this).val();
		$.ajax({
			type: 'POST',
			url: 'aplikasi/statusUjian',
			data: {
				status: status
			},
			success: function (data) {
				$('.status_ujian').val(data);
			}
		});
	});
	// STATUS KUISIONER ASISTEN
	$('.status_ksa').on('click', function () {
		let status = $(this).val();
		$.ajax({
			type: 'POST',
			url: 'aplikasi/statusKsa',
			data: {
				status: status
			},
			success: function (data) {
				$('.status_ksa').val(data);
			}
		});
	});
	// STATUS KUISIONER MAHASISWA
	$('.status_ksm').on('click', function () {
		let status = $(this).val();
		$.ajax({
			type: 'POST',
			url: 'aplikasi/statusKsm',
			data: {
				status: status
			},
			success: function (data) {
				$('.status_ksm').val(data);
			}
		});
	});
	// HAPUS PESAN
	$('.form-hapus-pesan').submit(function () {
		let password = $('.password').val();

		$('button').prop('disabled', true).css('cursor', 'wait');
		$.ajax({
			type: "POST",
			url: "aplikasi/hapusPesan",
			data: "password=" + password,
			success: function (response) {
				if (response == 'salah') {
					$('.password').val('');
					$('.alert-password').slideDown(100);
					setTimeout(function () {
						$('.alert-password').slideUp(100);
					}, 3000);
				} else {
					$('.modal').modal('close');
					$('.alert-password').hide();
					$('.password').val('');
					tampilNotifikasiAjax('blue-gradient', 'info_outline', 'Data pesan berhasil dihapus');
				}
				$('label').removeClass('active');
				$('button').prop('disabled', false).css('cursor', 'pointer');
			}
		});

		return false;
	});

	// HAPUS PESAN
	$('.form-hapus-ticket').submit(function () {
		let password = $('.password2').val();

		$('button').prop('disabled', true).css('cursor', 'wait');
		$.ajax({
			type: "POST",
			url: "aplikasi/hapusTicketing",
			data: "password=" + password,
			success: function (response) {
				if (response == 'salah') {
					$('.password2').val('');
					$('.alert-password').slideDown(100);
					setTimeout(function () {
						$('.alert-password').slideUp(100);
					}, 3000);
				} else {
					$('.modal').modal('close');
					$('.alert-password').hide();
					$('.password2').val('');
					tampilNotifikasiAjax('blue-gradient', 'info_outline', 'Data E-ticketing berhasil dihapus');
				}
				$('label').removeClass('active');
				$('button').prop('disabled', false).css('cursor', 'pointer');
			}
		});

		return false;
	});

	// RESET PEMBAYARAN
	$('.form-reset-pembayaran').submit(function () {
		let password = $('.password1').val();

		$('button').prop('disabled', true).css('cursor', 'wait');
		$.ajax({
			type: "POST",
			url: "aplikasi/resetPembayaran",
			data: "password=" + password,
			success: function (response) {
				if (response == 'salah') {
					$('.password').val('');
					$('.alert-password').slideDown(100);
					setTimeout(function () {
						$('.alert-password').slideUp(100);
					}, 3000);
				} else {
					$('.modal').modal('close');
					$('.alert-password').hide();
					$('.password').val('');
					tampilNotifikasiAjax('blue-gradient', 'info_outline', 'Reset pembayaran praktikum berhasil');
				}
				$('label').removeClass('active');
				$('button').prop('disabled', false).css('cursor', 'pointer');
			}
		});

		return false;
	});
})

// E-TICKETING_
$(document).ready(function () {
	// DETAIL BUTTON
	$('.btn-pindah-shift').on('click', function () {
		let ticket_id = $(this).data("ticket_id");
		let ticket_kode = $(this).data("ticket_kode");
		let npm = $(this).data("npm");
		let nama = $(this).data("nama");
		let kelas = $(this).data("kelas");
		let matkum_id = $(this).data("matkum_id");
		let shift_asal = $(this).data("shift_asal").toUpperCase();
		let shift_tujuan = $(this).data("shift_tujuan");
		let ticket_deskripsi = $(this).data("ticket_deskripsi");
		let ticket_status = $(this).data("ticket_status");
		let date_created = $(this).data("date_created");

		$('#btnTabPesan').data('npm', npm);
		$('.tgl_ticket').text(": " + date_created);
		$('.ticket_kode').text(": " + ticket_kode);
		$('.npm').text(": " + npm);
		$('.nama').text(": " + nama);
		$('.kelas').text(": " + kelas);
		$('.ticket_deskripsi').text(": " + ticket_deskripsi);
		$('.shift_asal').html('<option>' + shift_asal + '</option>');
		fetch_jadwal(matkum_id, shift_tujuan, shift_asal);
		$('.ticket_id').val(ticket_id);
		$('.ticket_status').val(ticket_status);
		$(".select2, .select2-no-search, .select2-with-image").trigger('change');
	});

	// STATUS
	$('.btn-simpan-pindah').on('click', function () {
		let shift_tujuan = $('.shift_tujuan').val();
		let ticket_id = $('.ticket_id').val();
		let ticket_status = $('.ticket_status').val();
		showLoader('#status' + ticket_id);
		$.ajax({
			type: 'POST',
			url: 'ticketing/proses_ticket',
			data: {
				shift_tujuan: shift_tujuan,
				ticket_status: ticket_status,
				ticket_id: ticket_id
			},
			success: function (result) {
				let msg = result.split("|");
				$('#btn' + ticket_id).data("ticket_status", msg[1]);
				$('#btn' + ticket_id).data("shift_tujuan", msg[2]);
				$('.modal').modal('close');
				setTimeout(() => {
					$('#status' + ticket_id).html(msg[0]);
				}, 1000);
			}
		})
	});

	// TR PESAN 
	$('.tbody-pesan-mhs').on('click', '.tr-pesan', function () {
		sessionStorage.setItem('chat-scroll', 'go');
		$('.chat-content').html('<div class="progress"><div class="indeterminate"></div></div>');
		let npm = $(this).data("npm");
		let nama = $(this).data("nama");
		let kelas = $(this).data("kelas");
		$('.nama-pengirim').html(nama);
		$('.kelas-pengirim').html(npm + ' | ' + kelas);
		$('.npm').val(npm);
		$('.npm-mhs').val(npm);
		$('.nama').val(nama);
		fetch_pesan_ticket();	
		setTimeout(() => {
			$('.input-msg').focus();
		}, 100);
	});

	// BUTTON PESAN 1
	$('.btn-send').on('click', function () {
		sessionStorage.setItem('chat-scroll', 'go');
		let npm = $('.npm').val();
		let nama = $('.nama').val();
		let msg = $('.input-msg').val();
		if (msg == '') {
			$('.input-msg').focus();			
		} else {
			$('.send-loader').removeClass('hide');
			$('.btn-send').addClass('hide');
			$.ajax({
				type: 'POST',
				url: 'ticketing/balas_pesan',
				data: {
					npm: npm,
					nama: nama,
					pesan: msg
				},
				success: function () {
					fetch_pesan_ticket();
					$('.send-loader').addClass('hide');
					$('.btn-send').removeClass('hide');
					$('.input-msg').val('').css('height', '49px');
					kirimNotifikasiPesanTicket(npm, msg);
				}
			});
		}
	});

	// BUTTON PESAN 2
	$('.btn-send2').on('click', function () {
		sessionStorage.setItem('chat-scroll', 'go');		
		let npm = $('.npm').val();
		let nama = $('.nama').val();
		let msg = $('.input-msg2').val();
		if (msg == '') {
			$('.input-msg2').focus();
		} else {
			$('.send-loader').removeClass('hide');
			$('.btn-send2').addClass('hide');
			$.ajax({
				type: 'POST',
				url: 'ticketing/balas_pesan',
				data: {
					npm: npm,
					nama: nama,
					pesan: msg
				},
				success: function () {
					fetch_pesan_ticket();
					$('.send-loader').addClass('hide');
					$('.btn-send2').removeClass('hide');
					$('.input-msg2').val('').css('height', '49px');
					kirimNotifikasiPesanTicket(npm, msg);
				}
			});
		}
	});

	// TAB PINDAH SHIFT
	$('#btnTabTicket').on('click', function () {
		let scrollPos = sessionStorage.getItem('scroll-pos');
		setTimeout(() => {
			$(".modal-content").scrollTop(scrollPos);
		}, 10);
	});

	// TAB PESAN
	$('#btnTabPesan').on('click', function () {
		$('.chat-content').html('<div class="progress"><div class="indeterminate"></div></div>');
		let scrollPos = sessionStorage.getItem('scroll');
		sessionStorage.setItem('scroll-pos', scrollPos);
		let npm = $(this).data("npm");
		$('.npm').val(npm);
		$('.npm-mhs').val(npm);
		fetch_pesan_ticket();	
		setTimeout(() => {
			$('.input-msg2').focus();
		}, 100);
	});

	// GET SCROLL
	$('#modal-det').scroll(function () {
		let elmnt = document.getElementById("modal-det");
		let y = elmnt.scrollTop;
		sessionStorage.setItem('scroll', y);
	});

	// FETCH JADWAL
	function fetch_jadwal(matkum_id, shift_tujuan, shift_asal) {
		$.ajax({
			type: 'GET',
			url: 'ticketing/fetch_jadwal?matkum_id=' + matkum_id + '&shift_asal=' + shift_asal,
			success: function (result) {
				let res = result.split('|');
				$('.shift_tujuan').html(res[0]);
				$('.shift_tujuan').val(shift_tujuan);
				$('#jadwal-ticket').html(res[1]);
				$('#' + shift_tujuan).css('background', 'powderblue');
			}
		});
	}

	// FETCH PESAN
	function fetch_pesan_ticket() {
		let npm = $('.npm-mhs').val();
		$.ajax({
			type: 'GET',
			url: 'ticketing/fetch_pesan?npm=' + npm,
			dataType: 'json',
			success: function (result) {				
				let chatResult = '';
				let position = '';
				let status = '';
				let pesan = result[0];
				let datePesan = result[1]

				for (let index = 0; index < pesan.length; index++) {
					let userChat = pesan[index]['pengirim'];
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

					if (userChat == 'admin') {
						position = 'chat-text-right';
						if (pesan[index]['pesan_status'] == 1) {
							status = '<i class="material-icons right light-blue-text text-lighten-2 pesan-status" style="font-size:20px">done_all</i>';
						} else {
							status = '<i class="material-icons right grey-text text-lighten-1 pesan-status" style="font-size:20px">done_all</i>';
						}
					} else {
						position = 'chat-text-left';
						status = '';
					}

					if (tglChat[0] == tglPrev[0] && index > 0) {
						tgl = '';
					}

					chatResult += tgl + '<div class="' + position + '"><span class="pesan">' + pesan[index]['pesan'] + '<br>' + status + '<small>' + tglChat[1] + '</small></span></div>';
				}
				$('.chat-content').html(chatResult);
				if (sessionStorage.getItem('chat-scroll') == 'go') {					
					$(".modal-content").scrollTop($('.modal-content').height() * 100);
				}
				$('.tr-' + npm).removeClass('green');
				$('.jml-' + npm).html('<span class="badge-status badge-not">0</span>');
				if (pesan.length > 0) {					
					$('.last_msg_' + npm).text(pesan[pesan.length - 1]['pesan']);
				}
				sessionStorage.setItem('chat-scroll', 'stop');
				cek_pesan_ticket();
			}
		});
	}

	function cek_pesan_ticket() {
		$.ajax({
			type: 'GET',
			url: 'ticketing/cekPesanMhs',
			dataType: 'json',
			success: function (result) {
				let chatResult = '';
				let jmlPesan = result[0];
				let lastPesan = result[1];
				for (let index = 0; index < lastPesan.length; index++) {
					$('.no-' + lastPesan[index]['npm']).text(index + 1);
					$('.last_msg_' + lastPesan[index]['npm']).text(lastPesan[index]['pesan']);
					if (jmlPesan[index] < 1) {
						$('.jml-' + lastPesan[index]['npm']).html('<span class="badge-status badge-not">' + jmlPesan[index] + '</span>');
					} else {
						$('.jml-' + lastPesan[index]['npm']).html('<span class="badge-status badge-ok">' + jmlPesan[index] + '</span>');
						$('.jml-' + lastPesan[index]['npm']).parent().addClass('green lighten-5');
					}
				}
				let jmlPesanMhs = $('.jmlPesanMhs').val();
				if (lastPesan.length > jmlPesanMhs && jmlPesanMhs != 0) {
					$('.tbody-pesan-mhs').prepend(
						'<tr class="tr-data tr-pesan modal-trigger tr-' + lastPesan[0]['npm'] + ' green lighten-5 " data-target="modalPesan" data-kelas="' + lastPesan[0]['kelas_nama'] + '" data-npm="' + lastPesan[0]['npm'] + '" data-nama="' + lastPesan[0]['nama'] + '">' +
						'<td class="center no-' + lastPesan[0]['npm'] + '">1</td>' +
						'<td>' + lastPesan[0]['npm'] + '</td>' +
						'<td>' + lastPesan[0]['nama'] + '</td>' +
						'<td><span class="last_msg_' + lastPesan[0]['npm'] + '">' + lastPesan[0]['pesan'] + '</span></td>' +
						'<td class="jml-' + lastPesan[0]['npm'] + '">' + '<span class="badge-status badge-ok">' + jmlPesan[0] + '</span>' + '</td>' +
						'</tr>');
				}
				$('.jmlPesanMhs').val(lastPesan.length);
			}
		});
	}

	if (currentPath == 'ticketing') {
		cek_pesan_ticket();
		setInterval(() => {
			fetch_pesan_ticket();
		}, 10000);
	}
});


// PENJADWALAN_
$(document).ready(function () {
	// TAMBAH
	$('.form-jadwal').submit(function () {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'sukses') {
					$('.datatable').prepend(msg[2]);
					$('.form-jadwal').trigger("reset");
					$(".select2, .select2-no-search, .select2-with-image").val(null).trigger('change');
					tampilNotifikasiAjax('success-gradient', 'check_circle', msg[1]);
					$('.modal-content').scrollTop(0);
				} else {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
					$('#jadwal_kode_tambah').focus();
				}
			}
		});
		return false;
	});

	// EDIT
	$('#form-edit-jadwal').submit(function () {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'gagal') {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
					$('#jadwal_kode_edit').focus();
					$('.modal-content').scrollTop(0);
				} else {
					location.reload();
				}
			}
		});
		return false;
	});

	// STATUS
	$('.datatable, .datatable-nopage').on('click', '.status-jadwal', function () {
		let jadwal_id = $(this).data('jadwal_id');
		let jadwal_kode = $(this).data('jadwal_kode');
		let status = $('#' + jadwal_id + ' .badge-status').html();
		showLoader('#' + jadwal_id);
		$.ajax({
			type: 'POST',
			url: 'penjadwalan/statusJadwal',
			data: {
				jadwal_id: jadwal_id,
				jadwal_kode: jadwal_kode,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + jadwal_id).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// EDIT & DELETE BUTTON
	$(document).on('click', '.btn-edit-jadwal, .btn-hapus-jadwal', function () {
		let jadwal_id = $(this).data("jadwal_id");
		let periode_id = $(this).data("periode_id");
		let jadwal_kode = $(this).data("jadwal_kode");
		let matkum_id = $(this).data("matkum_id");
		let kelas_kode = $(this).data("kelas_kode");
		let asisten_1 = $(this).data("asisten_1");
		let asisten_2 = $(this).data("asisten_2");
		let jadwal_hari = $(this).data("jadwal_hari");
		let jadwal_jam = $(this).data("jadwal_jam").split('-');
		let ruangan_id = $(this).data("ruangan_id");
		let dosen_id = $(this).data("dosen_id");

		$(".jadwal_id").val(jadwal_id);
		$(".jadwal_kode").val(jadwal_kode);
		$(".edit-label").addClass('active');
		$(".txt-jadwal").text(jadwal_kode);
		$(".matkum_id").val(matkum_id);
		$(".kelas_kode").val(kelas_kode);
		$(".asisten_1").val(asisten_1);
		$(".asisten_2").val(asisten_2);
		$(".jadwal_hari").val(jadwal_hari);
		$(".jam_mulai").val(jadwal_jam[0]);
		$(".jam_selesai").val(jadwal_jam[1]);
		$(".periode_id").val(periode_id);
		$(".ruangan_id").val(ruangan_id);
		$(".dosen_id").val(dosen_id);
		$(".custom-modal-title").text("Edit Jadwal");
		$(".select2, .select2-no-search, .select2-with-image").trigger('change');
	});
});

// MAHASISWA_
$(document).ready(function () {
	// TAMBAH
	$('.form-tambah-mahasiswa').submit(function () {
		let kelas_kode = $('#kelas_kode').val();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'sukses') {
					$('.datatable-serverside-mhs').DataTable().ajax.reload();
					$('#add-nama').val("");
					$(".select2, .select2-no-search, .select2-with-image").val(kelas_kode).trigger('change');
					tampilNotifikasiAjax('success-gradient', 'check_circle', msg[1]);
				} else {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
				}
				window.scrollTo({
					top: 0,
					behavior: 'smooth'
				});
			}
		});
		return false;
	});

	// EDIT
	$('#form-edit-mahasiswa').submit(function () {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'gagal') {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
					$('#npm_baru').focus();
					$('.modal-content').scrollTop(0);
				} else {
					location.reload();
				}
			}
		});
		return false;
	});

	// STATUS
	$('.datatable-serverside-mhs').on('click', '.status-mhs', function () {
		let npm = $(this).attr('id');
		let status = $('#' + npm + ' .badge-status').html();
		showLoader('#' + npm);

		$.ajax({
			type: 'POST',
			url: 'mahasiswa/statusMhs',
			data: {
				npm: npm,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + npm).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// FETCH
	$('.datatable-serverside-mhs').DataTable({
		oLanguage: {
			sProcessing: '<br><div class="center-align">' +
				'<div class="preloader-wrapper small active">' +
				'<div class="spinner-layer spinner-blue-only">' +
				'<div class="circle-clipper left">' +
				'<div class="circle"></div>' +
				'</div><div class="gap-patch">' +
				'<div class="circle"></div>' +
				'</div><div class="circle-clipper right">' +
				'<div class="circle"></div>' +
				'</div>' +
				'</div>' +
				'</div><br>' +
				'Memuat data ...</div>'
		},
		ordering: false,
		dom: 'trip',
		"pageLength": 100,
		order: [],
		processing: true,
		serverSide: true,
		ajax: {
			url: 'mahasiswa/fetch_mahasiswa',
			type: "POST"
		},
		columns: [{
			"aaData": "no"
		},
		{
			"aaData": "npm"
		},
		{
			"aaData": "nama"
		},
		{
			"aaData": "kelas_nama"
		},
		{
			"aaData": "status_mhs"
		},
		{
			"aaData": "aksi"
		}
		],
		"drawCallback": function () {
			editHapusMhs();
		},
		"createdRow": function (row, data) {
			if (data[0] == '<div class="hide"></div>') {
				$(row).addClass('lighten-5');
				$(row).addClass('green');
			}
		}
	});

	// EDIT & DELETE BUTTON
	function editHapusMhs() {
		$(".btn-edit-mhs, .btn-hapus-mhs, .btn-password-mhs").on('click', function () {
			let mhs_id = $(this).data("mhs_id");
			let npm = $(this).data("npm");
			let nama = $(this).data("nama");
			let kelas_kode = $(this).data("kelas_kode");

			$('.txt-nama').html(nama);
			$(".mhs_id").val(mhs_id);
			$(".npm").val(npm);
			$(".nama").val(nama);
			$(".kelas_kode").val(kelas_kode);
			$(".select2, .select2-no-search, .select2-with-image").trigger('change');
		});
	}
});


// PENILAIAN_PRAKTIKUM_
$(document).ready(function () {
	// PENILAIAN
	$('.input-nilai').hide();
	$('.tr-nilai-ujian').on('click', function () {
		let id = $(this).attr("id");
		$('.txt-n-tugas' + id).hide();
		$('.inp-n-tugas' + id).show();
		$('.txt-n-ujian' + id).hide();
		$('.inp-n-ujian' + id).show();
		setTimeout(function () {
			$('.inp-n-tugas' + id).css('width', '50px');
			$('.inp-n-ujian' + id).css('width', '50px');
		}, 10);
	}).change(function () {
		let npm = $(this).attr("id");
		let jadwal_kode = $('.jadwal_kode').val();
		let p_kehadiran = $('.p_kehadiran').val();
		let p_tugas = $('.p_tugas').val();
		let p_ujian = $('.p_ujian').val();
		let kehadiran = $('.kehadiran' + npm).html();
		let tugas = $('.tugas' + npm).html();
		let nilai_tugas = $('.inp-n-tugas' + npm).val();
		let nilai_ujian = $('.inp-n-ujian' + npm).val();
		showLoader('.txt-n-tugas' + npm);
		showLoader('.txt-n-ujian' + npm);
		let total = (kehadiran * p_kehadiran / 100) + (nilai_tugas * p_tugas / 100) + (nilai_ujian * p_ujian / 100);
		total = total.toString();
		$.ajax({
			type: "POST",
			url: "penilaian/penilaian",
			data: "npm=" + npm + "&jadwal_kode=" + jadwal_kode + "&nilai_tugas=" + nilai_tugas + "&nilai_ujian=" + nilai_ujian,
			success: function (data) {
				let respon = data.split('|');
				setTimeout(() => {
					$('.txt-n-tugas' + npm).html(respon[0]);
					$('.txt-n-ujian' + npm).html(respon[1]);
					$('.total' + npm).html(total);
				}, 1000);
			}
		});
	});

	$(document).mouseup(function () {
		$('.hilang').hide();
		$('.muncul').show();
	});

	// PERSENTASE PENILAIAN
	$(".btn-edit-persentase").on('click', function () {
		let p_kehadiran = $(this).data("p_kehadiran");
		let p_tugas = $(this).data("p_tugas");
		let p_ujian = $(this).data("p_ujian");

		$(".p_kehadiran").val(p_kehadiran);
		$(".p_tugas").val(p_tugas);
		$(".p_ujian").val(p_ujian);
	});
});


// ABSENSI_PRAKTIKUM_
$(document).ready(function () {
	// ABSENSI MAHASISWA
	$('.kehadiran').on('click', function () {
		let npm = $(this).attr('id');
		let kehadiran = $('#' + npm + ' .badge-status').html();
		let jadwal_kode = $('.jadwal_kode').val();
		let pertemuan = $('.pertemuan').val();
		let tgl = $('.tgl').val();
		showLoader('#' + npm);
		$.ajax({
			type: "POST",
			url: "absensi/do_absen",
			data: "npm=" + npm + "&kehadiran=" + kehadiran + "&jadwal_kode=" + jadwal_kode + "&pertemuan=" + pertemuan + "&tgl=" + tgl,
			success: function (data) {
				setTimeout(() => {
					$('#' + npm).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// ABSENSI ASISTEN
	$('.kehadiran-asisten').on('click', function () {
		let asisten_id = $(this).attr('id');
		let kehadiran = $('#' + asisten_id + ' .badge-status').html();
		let jadwal_kode = $('.jadwal_kode').val();
		let pertemuan = $('.pertemuan').val();
		let tgl = $('.tgl').val();
		showLoader('.asisten' + asisten_id);
		$.ajax({
			type: "POST",
			url: "absensi/do_absen_asisten",
			data: "asisten_id=" + asisten_id + "&kehadiran=" + kehadiran + "&jadwal_kode=" + jadwal_kode + "&pertemuan=" + pertemuan + "&tgl=" + tgl,
			success: function (data) {
				setTimeout(() => {
					$('.asisten' + asisten_id).html(data);
				}, 1000);
			}
		});
		return false;
	});
});

// PEMBAYARAN_PRAKTIKUM_
$(document).ready(function (e) {
	// STATUS BAYAR
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
					if (respon[0] === 'lunas') {
						$('#' + npm).html(respon[1]);
						$('.lunas').html(lunas + 1);
						$('.belum').html(belum - 1);
					} else {
						$('#' + npm).html(respon[1]);
						$('.lunas').html(lunas - 1);
						$('.belum').html(belum + 1);
					}
				}, 1000);
			}
		});
		return false;
	});
});

// ASISTEN_
$(document).ready(function (e) {
	// SET FOTO
	$('.foto-asisten').on('click', function () {
		$('#asisten_foto').val($(this).data('foto'));
		$('.foto-asisten').css('max-width', '30px');
		$(this).css('max-width', '50px');
	});

	// TAMBAH
	$('.form-asisten').submit(function () {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'sukses') {
					$('.modal').modal('close');
					$('.datatable-nopage').prepend(msg[2]);
					$('.form-asisten').trigger("reset");
					$(".select2, .select2-no-search, .select2-with-image").val(null).trigger('change');
					$('.modal-content').scrollTop(0);
					tampilNotifikasiAjax('success-gradient', 'check_circle', msg[1]);
					$('.foto-asisten').css('max-width', '50px');
				} else {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
				}
			}
		});
		return false;
	});

	// EDIT
	$('#form-edit-asisten').submit(function () {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'gagal') {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
					$('#username').focus();
					$('.modal-content').scrollTop(0);
				} else {
					location.reload();
				}
			}
		});
		return false;
	});

	// STATUS
	$('.datatable, .datatable-nopage').on('click', '.status-asisten', function () {
		let asisten_id = $(this).attr('id');
		let username = $(this).data('username');
		let status = $('#' + asisten_id + ' .badge-status').html();
		showLoader('#' + asisten_id);

		$.ajax({
			type: 'POST',
			url: 'asisten/statusAsisten',
			data: {
				asisten_id: asisten_id,
				username: username,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + asisten_id).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// EDIT & HAPUS & PASSWORD & LIHAT BUTTON
	$(document).on('click', ".btn-edit-asisten, .btn-hapus-asisten, .btn-password-asisten, .btn-lihat-asisten", function () {
		let asisten_id = $(this).data("asisten_id");
		let asisten_nama = $(this).data("asisten_nama");
		let username = $(this).data("username");
		let jabatan_id = $(this).data("jabatan_id");
		let jabatan_nama = $(this).data("jabatan_nama");
		let alamat = $(this).data("alamat");
		let email = $(this).data("email");
		let tahun_masuk = $(this).data("tahun_masuk");
		let tahun_keluar = $(this).data("tahun_keluar");
		let foto = $(this).data("foto");
		let foto_nama = $(this).data("foto_nama");
		let tgl_lahir = $(this).data("tgl_lahir").split("-");

		$(".txt-username").html(username);
		$(".txt-nama").html(asisten_nama);
		$(".asisten-nama").html(asisten_nama);
		$(".jabatan-nama").html(jabatan_nama);
		$(".tahun-masuk").html(tahun_masuk);
		$(".tahun-keluar").html(tahun_keluar);
		$(".tgl-lahir").html(tgl_lahir[2] + "/" + tgl_lahir[1] + "/" + tgl_lahir[0]);
		$(".txt-email").html(email);
		$(".txt-alamat").html(alamat);
		$(".foto-modal").attr('src', foto);

		$(".asisten_id").val(asisten_id);
		$(".username").val(username);
		$(".asisten_nama").val(asisten_nama);
		$(".jabatan_id").val(jabatan_id);
		$(".alamat").val(alamat);
		$(".email").val(email);
		$(".tgl_lahir").val(tgl_lahir[2]);
		$(".bulan_lahir").val(tgl_lahir[1]);
		$(".tahun_lahir").val(tgl_lahir[0]);
		$(".tahun_masuk").val(tahun_masuk);
		$(".tahun_keluar").val(tahun_keluar);
		$(".foto-lama").val(foto_nama);
		$(".select2, .select2-no-search, .select2-with-image").trigger('change');
	});
});

// DOSEN_
$(document).ready(function (e) {
	// SET FOTO
	$('.foto-dosen').on('click', function () {
		$('#dosen_foto').val($(this).data('foto'));
		$('.foto-dosen').css('max-width', '30px');
		$(this).css('max-width', '50px');
	});

	// TAMBAH
	$('.form-dosen').submit(function () {
		let jabatan_id = $('#jabatan_id').val();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'sukses') {
					$('.datatable-nopage').prepend(msg[2]);
					$('.form-dosen').trigger("reset");
					$(".select2, .select2-no-search, .select2-with-image").val(jabatan_id).trigger('change');
					$('.modal-content').scrollTop(0);
					tampilNotifikasiAjax('success-gradient', 'check_circle', msg[1]);
					$('.foto-dosen').css('max-width', '50px');
				} else {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
				}
			}
		});
		return false;
	});

	// EDIT
	$('#form-edit-dosen').submit(function () {
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function (data) {
				let msg = data.split('|');
				if (msg[0] == 'gagal') {
					tampilNotifikasiAjax('orange-gradient', 'warning', msg[1]);
					$('#nidn_baru').focus();
					$('.modal-content').scrollTop(0);
				} else {
					location.reload();
				}
			}
		});
		return false;
	});

	// STATUS
	$('.datatable, .datatable-nopage').on('click', '.status-dosen', function () {
		let dosen_id = $(this).attr('id');
		let nidn = $(this).data('nidn');
		let status = $('#' + dosen_id + ' .badge-status').html();
		showLoader('#' + dosen_id);

		$.ajax({
			type: 'POST',
			url: 'dosen/statusdosen',
			data: {
				dosen_id: dosen_id,
				nidn: nidn,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + dosen_id).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// EDIT & HAPUS & PASSWORD BUTTON
	$(document).on('click', ".btn-edit-dosen, .btn-hapus-dosen, .btn-password-dosen, .btn-lihat-dosen", function () {
		let dosen_id = $(this).data("dosen_id");
		let dosen_nama = $(this).data("dosen_nama");
		let nidn = $(this).data("nidn");
		let jabatan_id = $(this).data("jabatan_id");
		let email = $(this).data("email");
		let foto_nama = $(this).data("foto_nama");

		$(".txt-dosen").text(dosen_nama);
		$(".dosen_id").val(dosen_id);
		$(".nidn").val(nidn);
		$(".dosen_nama").val(dosen_nama);
		$(".foto-lama").val(foto_nama);
		$(".jabatan_id").val(jabatan_id);
		$(".email").val(email);
		$(".select2, .select2-no-search, .select2-with-image").trigger('change');
	});
});

// KELAS_
$(document).ready(function (e) {
	// GENERATE ID KELAS
	$(".input-nama").keyup(function () {
		let kelas_nama = $(this).val();
		filter = kelas_nama.replace(/\s/g, '').toLowerCase();

		$(".kelas_kode, .input-kode").val(filter);
		$(".kelas_nama, .input-nama").val(kelas_nama.toUpperCase());
	});

	// STATUS
	$('.datatable, .datatable-nopage').on('click', '.status-kelas', function () {
		let kelas_id = $(this).attr('id');
		let status = $('#' + kelas_id + ' .badge-status').html();
		showLoader('#' + kelas_id);

		$.ajax({
			type: 'POST',
			url: 'kelas/statusKelas',
			data: {
				kelas_id: kelas_id,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + kelas_id).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// EDIT & HAPUS BUTTON
	$(".btn-edit-kelas, .btn-hapus-kelas").on('click', function () {
		let kelas_id = $(this).data("kelas_id");
		let kelas_kode = $(this).data("kelas_kode");
		let kelas_nama = $(this).data("kelas_nama");

		$(".txt-kelas").text(kelas_nama);
		$(".kelas_id").val(kelas_id);
		$(".kelas_kode").val(kelas_kode);
		$(".kelas_nama").val(kelas_nama);
	});

});

// REGISTRASI_PRAKTIKUM_
$(document).ready(function () {
	// REGISTRASI
	$('.datatable, .datatable-nopage').on('click', '.checkbox', function () {
		let jadwal_kode = $(".jadwal_kode").val();
		let npm = $(this).parent().children('.filled-in').data("npm");
		let nama = $(this).parent().children('.filled-in').data("nama");
		let checkbox = $(this).parent().children('.filled-in').prop("checked");
		let row = $(this).parent().parent().parent();
		let td_checkbox = $(this).parent().parent();
		$(this).parent().children('.filled-in').click();
		$('.txt-nama').text(nama + ' | ' + npm);
		$('.txt-idp').text(jadwal_kode);
		$('.checkbox-npm').val(npm);
		$('.checkbox-nama').val(nama);
		showLoader(td_checkbox);
		let status;

		if (checkbox == true) {
			status = "delete";
		} else {
			status = "add";
		}

		$.ajax({
			type: 'POST',
			url: 'registrasi/simpanRegistrasi',
			data: {
				jadwal_kode: jadwal_kode,
				npm: npm,
				nama: nama,
				status: status
			},
			success: function (data) {
				let respon = data.split('|');
				setTimeout(() => {
					if (respon[0] == 'add') {
						row.addClass('green lighten-5');
						td_checkbox.html(respon[1]);
					} else if (respon[0] == 'confirm') {
						$('#modalHapusReg').modal('open');
					} else {
						row.removeClass('green lighten-5');
						td_checkbox.html(respon[1]);
					}
				}, 1000);
			}
		});
		return false;
	});

	// KONFIRMASI
	$('#modalHapusReg').modal({
		onCloseStart: function () {
			let npm = $('.checkbox-npm').val();
			let nama = $('.checkbox-nama').val();
			$('.td-checkbox' + npm).html('<label>' +
				'<input type="checkbox" class="filled-in" data-nama="' + nama + '" data-npm="' + npm + '" value="' + npm + '" name="npm" checked>' +
				'<span class="checkbox" style="left: 10px"></span>' +
				'</label>');
		}
	});

	// HAPUS
	$('#ok-hapus-reg').on('click', function () {
		$('#modalHapusReg').modal('close');
		let jadwal_kode = $(".jadwal_kode").val();
		let npm = $(".checkbox-npm").val();
		let status = 'confirm';
		showLoader('.td-checkbox' + npm);
		$.ajax({
			type: 'POST',
			url: 'registrasi/simpanRegistrasi',
			data: {
				jadwal_kode: jadwal_kode,
				npm: npm,
				status: status
			},
			success: function (data) {
				let respon = data.split('|');
				setTimeout(() => {
					$(".checkbox-npm").val('');
					$('.tr-' + npm).removeClass('green lighten-5');
					$('.td-checkbox' + npm).html(respon[1]);
				}, 1000);
			}
		});
		return false;
	});
});


// PINDAH_PRAKTIKUM_
$(document).ready(function () {
	// PILIH PRAKTIKUM TUJUAN
	$('.form-pindah-praktikum').submit(function () {
		let tujuan = $('.praktikum_tujuan').val();
		if (tujuan == 0) {
			tampilNotifikasiAjax('orange-gradient', 'warning', 'Praktikum tujuan belum dipilih');
			return false;
		}
	});

	// CEK PERTEMUAN
	$('#p_tujuan').on('change', function () {
		let jadwal_kode = $(this).val();
		let pertemuan1 = $('.pertemuan1').val();
		$('#pertemuan2').html('<img src="../assets/images/loader/loader.gif" alt="loading..." height="20">');
		$.ajax({
			type: 'POST',
			url: 'registrasi/cekPertemuan',
			data: 'jadwal_kode=' + jadwal_kode + '&pertemuan1=' + pertemuan1,
			success: function (data) {
				$('.praktikum_tujuan').val(jadwal_kode);
				let res = data.split('|');
				setTimeout(() => {
					$('#pertemuan2').html(res[1]);
					if (res[0] == 'sukses') {
						$('.mhs').prop('disabled', false);
					} else {
						$('.mhs').prop('disabled', true);
						tampilNotifikasiAjax('orange-gradient', 'warning', 'Pindah praktikum tidak bisa diproses. Jumlah Pertemuan tidak sama');
					}
				}, 1000);
			}
		});
	});
});

// INFO_PRAKTIKUM_
$(document).ready(function () {
	// KIRIM NOTIFIKASI
	$('.form-notifikasi').submit(function () {
		let notif = $(this).serialize();
		$('button[type=submit]').prop('disabled', true).css('cursor', 'wait');
		$('.mymodal-loader').removeClass('hide');
		setTimeout(() => {
			$('.modal-content').scrollTop(5000);
		}, 500);
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: notif,
			success: function (response) {
				let res = response.split('|');
				$('.mymodal-loader').addClass('hide');
				$('button[type=submit]').prop('disabled', false).css('cursor', 'pointer');
				$('.modal').modal('close');
				$('.form-notifikasi').trigger("reset");
				$(".select2, .select2-no-search, .select2-with-image").val(null).trigger('change');
				$('label').removeClass('active');
				if (res[0] == 'sending') {					
					tampilNotifikasiAjax('success-gradient', 'check_circle', 'Notifikasi info praktikum berhasil dikirim. Terkirim : '+res[1]);
				} else {
					tampilNotifikasiAjax('red-gradient', 'warning', 'Notifikasi tugas gagal dikirim. Ekstensi GMP tidak terinstal di server');
				}
			}			
		});
		return false;
	});

	// EDIT & HAPUS & NOTIFIKASI BUTTON
	$(".btn-hapus-info, .btn-kirim-notifikasi, .btn-edit-info").on('click', function () {
		let info_caption = $(this).data("info_caption");
		$('.summernote-info').eq(1).summernote('code', info_caption);
		let info_id = $(this).data("info_id");
		let info_image = $(this).data("info_image");
		let kelas = $(this).data("kelas").split(',');
		let foto_lama = $(this).data("foto_lama");
		let dataKelas = kelas.filter(item => item);

		$(".info_id").val(info_id);
		$(".info-caption").val(info_caption);
		$(".info-caption-p").text(info_caption);
		$(".foto-lama").val(foto_lama);
		M.textareaAutoResize($('.info-caption'));
		$(".preview-image").attr("src", info_image);
		$(".kelas_kode").val(dataKelas);
		$(".select2, .select2-no-search").trigger('change');
	});

	// TAMBAH
	$(".btn-buat-info").on('click', function () {
		let info_id = $(this).data("info_id");
		$("#info-id").val(info_id);
		$('#in-edit').val(0);
	});

	// FETCH IMAGE
	$('.get-image').on('click', function () {
		let info_id = $(this).data('info_id');
		$('#info-id').val(info_id);
		$('#in-edit').val(1);
		$('.file-upload-info').val(null);
		fetchImage('#img-preview-edit', info_id);
	});

	// CATATAN HOVER
	$('.card-info').on({
		mouseenter: function () {
			$(this).children('.caption').css('overflow', 'auto');
			$(this).children('.card-info-footer').children('.card-info-action').css('opacity', 1);
		},
		mouseleave: function () {
			$(this).children('.caption').css('overflow', 'hidden').scrollTop(0);
			$(this).children('.card-info-footer').children('.card-info-action').css('opacity', 0);
		}
	});

	function fetchImage(elem, info_id) {
		$(elem).html('<div class="progress"><div class="indeterminate"></div></div>');
		$.ajax({
			type: 'GET',
			url: 'pengumuman/fetch_image?info_id=' + info_id,
			success: function (imagedata) {
				$(elem).html(imagedata);
				$('.materialboxed').materialbox();
			}
		});
	}
});

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


// MATA_PRAKTIKUM_
$(document).ready(function () {
	// STATUS
	$('.datatable, .datatable-nopage').on('click', '.status-matkum', function () {
		let matkum_id = $(this).attr('id');
		let status = $('#' + matkum_id + ' .badge-status').html();
		showLoader('#' + matkum_id);
		$.ajax({
			type: 'POST',
			url: 'matkum/statusMatkum',
			data: {
				matkum_id: matkum_id,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + matkum_id).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// EDIT & HAPUS BUTTON
	$(".btn-edit-matkum, .btn-hapus-matkum").on('click', function () {
		let matkum_id = $(this).data("matkum_id");
		let matkum = $(this).data("matkum");

		$(".txt-matkum").text(matkum);
		$(".matkum_id").val(matkum_id);
		$(".matkum").val(matkum);
	});
});

// PERIODE_
$(document).ready(function () {
	// STATUS
	$('.datatable, .datatable-nopage').on('click', '.status-periode', function () {
		let periode_id = $(this).attr('id');
		let status = $('#' + periode_id + ' .badge-status').html();
		showLoader('#' + periode_id);
		$.ajax({
			type: 'POST',
			url: 'periode/statusPeriode',
			data: {
				periode_id: periode_id,
				status: status
			},
			success: function (data) {
				setTimeout(() => {
					$('#' + periode_id).html(data);
				}, 1000);
			}
		});
		return false;
	});

	// EDIT & HAPUS BUTTON
	$(".btn-edit-periode, .btn-hapus-periode, .btn-status-periode").on('click', function () {
		let periode_id = $(this).data("periode_id");
		let periode_nama = $(this).data("periode_nama");

		$(".txt-periode").html(periode_nama);
		$(".periode_id").val(periode_id);
		$(".periode_nama").val(periode_nama);
		M.updateTextFields();
	});
});

// RUANGAN_
$(document).ready(function () {
	// EDIT & HAPUS BUTTON
	$(".btn-edit-ruangan, .btn-hapus-ruangan").on('click', function () {
		let ruangan_id = $(this).data("ruangan_id");
		let ruangan_nama = $(this).data("ruangan_nama");
		let kapasitas = $(this).data("kapasitas");
		let ruangan_pj = $(this).data("ruangan_pj");

		$(".txt-ruangan").text(ruangan_nama);
		$(".ruangan_id").val(ruangan_id);
		$(".ruangan_nama").val(ruangan_nama);
		$(".kapasitas").val(kapasitas);
		$(".ruangan_pj").val(ruangan_pj);
		M.updateTextFields();
		$('.ruangan_pj').val(ruangan_pj);
		$('.ruangan_pj').trigger('change');
	});
});

// JAM_
$(document).ready(function () {
	// EDIT & HAPUS BUTTON
	$(".btn-edit-jam, .btn-hapus-jam").on('click', function () {
		let jam_id = $(this).data("jam_id");
		let jam_nama = $(this).data("jam_nama");
		let jam_lengkap = $(this).data("jam_lengkap");
		let jam_mulai = $(this).data("jam_mulai").slice(0, 5);
		let jam_selesai = $(this).data("jam_selesai").slice(8, 13);

		$(".txt-jam").text(jam_lengkap);
		$(".jam_lengkap").val(jam_lengkap);
		$(".jam_id").val(jam_id);
		$(".jam_nama").val('Jam ke - ' + jam_nama);
		$(".jam_mulai").val(jam_mulai);
		$(".jam_selesai").val(jam_selesai);
	});
});

$(document).ready(function () {
	// EDIT & HAPUS BUTTON
	$(".btn-edit-jabatan, .btn-hapus-jabatan").on('click', function () {
		let jabatan_id = $(this).data("jabatan_id");
		let jabatan_nama = $(this).data("jabatan_nama");
		let honor_perbulan = $(this).data("honor_perbulan");
		let honor_pertemuan = $(this).data("honor_pertemuan");

		$(".txt-jabatan").text(jabatan_nama);
		$(".jabatan_id").val(jabatan_id);
		$(".jabatan_nama").val(jabatan_nama);
		$(".honor_pertemuan").val(honor_pertemuan);
		$(".honor_perbulan").val(honor_perbulan);
	});
});

// UPLOAD_FOTO_INFO_PRAKTIKUM_
$(document).ready(function () {
	// BUTTON UPLOAD
	$('.btn-upload-info').on('click', function () {
		$('#image-id').val('-');
		$('#image-edit').val('-');
		$('.file-upload-info').click();
	});

	// FILE INPUT
	$('.file-upload-info').change(function () {
		let id = $('#image-id').val();
		let config = cekConfigFoto(this);
		if (config == 1) {
			if (id != '-') {
				$('#action-foto-' + id).hide();
				$('#foto-info-' + id).css('filter', 'blur(10px)');
				$('#myloader-' + id).removeClass('hide');
			} else {
				$('.preview-wrapper, .myloader').removeClass('hide');
				$('.preview-image').css('filter', 'blur(10px)');
			}
			readInfo(this);
		}
	});

	// BUTTON HAPUS
	$(document).on('click', '.delete-foto-info', function () {
		let id = $(this).data('id');
		let gambar = $(this).data('gambar');
		$('#action-foto-' + id).hide();
		$('#foto-info-' + id).css('filter', 'blur(10px)');
		$('#myloader-' + id).removeClass('hide');
		$.ajax({
			url: 'pengumuman/hapusFoto',
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
					$('#action-info-' + id).show();
					$('#foto-info-' + id).css('filter', 'none');
					$('#myloader-' + id).addClass('hide');
				}
			},
		});
	});

	// BUTTON EDIT
	$(document).on('click', '.edit-foto-info', function () {
		let id = $(this).data('id');
		let gambar = $(this).data('gambar');
		$('#image-id').val(id);
		$('#image-edit').val(gambar);
		$('.file-upload-info').click();
	});
});

function uploadFotoInfo() {
	let inEdit = $('#in-edit').val();
	let fd = new FormData($('#upload-form')[0]);
	let files = $('.file-upload-info')[0].files[0];
	$('.btn-upload-info').prop('disabled', true).css('cursor', 'wait');
	fd.append('file', files);
	$.ajax({
		url: 'pengumuman/uploadFoto',
		type: 'POST',
		data: fd,
		cache: false,
		contentType: false,
		processData: false,
		success: function (response) {
			$('.file-upload-info').val(null);
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
					$('#foto-info-' + res[1]).attr('src', res[2]);
					$('#foto-info-' + res[1]).css('filter', 'none');
					$('#action-foto-' + res[1]).show();
					$('#myloader-' + res[1]).addClass('hide');
				}, 1000);
			} else {
				$('#action-foto-' + res[1]).show();
				$('#myloader-' + res[1]).addClass('hide');
				tampilNotifikasiAjax('orange-gradient', 'warning', 'Upload foto gagal');
			}
			$('.btn-upload-info').prop('disabled', false).css('cursor', 'pointer');
		}
	});
}

function readInfo(input) {
	if (input.files && input.files[0]) {
		let reader = new FileReader();
		reader.onload = function (e) {
			$('#hidden-image').attr('src', e.target.result);
			$('.preview-image').attr('src', e.target.result);
			setTimeout(() => {
				cekDimensiFotoInfo(e.target.result);
			}, 500);
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

function cekDimensiFotoInfo(src) {
	let id = $('#image-id').val();
	const hidden = document.getElementById('hidden-image');
	if (hidden.naturalWidth !== hidden.naturalHeight) {
		tampilNotifikasiAjax('orange-gradient', 'warning', 'Lebar dan tinggi foto tidak sama. Foto terpilih = ' + hidden.naturalWidth + 'x' + hidden.naturalHeight);
		$('.file-upload-info').val(null);
		$('.preview-wrapper, .myloader').addClass('hide');
		$('#foto-info-' + id).css('filter', 'none');
		$('#action-foto-' + id).show();
		$('#myloader-' + id).addClass('hide');
	} else if (hidden.naturalWidth > 1080 || hidden.naturalHeight > 1080) {
		tampilNotifikasiAjax('orange-gradient', 'warning', 'Lebar dan tinggi foto terlalu besar. Maksimal 1080x1080');
		$('.file-upload-info').val(null);
		$('.preview-wrapper, .myloader').addClass('hide');
		$('#foto-info-' + id).css('filter', 'none');
		$('#action-foto-' + id).show();
		$('#myloader-' + id).addClass('hide');
	} else {
		uploadFotoInfo();
	}
}

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

function kirimNotifikasiPesanTicket(npm, pesan) {
	$.ajax({
		type: 'POST',
		url: 'ticketing/kirimNotifikasi',
		data: {
			npm: npm,
			pesan: pesan
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