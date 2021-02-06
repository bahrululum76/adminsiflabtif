// NOTIFICATION FLASHDATA
function closeNotifikasi() {
	$('.notifikasi').css("transform", 'translateY(400px)');
}

// NOTIFICATION AJAX
function closeNotifikasiAjax() {
	$('.notifikasi-ajax').css("transform", 'translateY(400px)');
}

// SHOW NOTIFICATION
function tampilNotifikasiAjax(bg, icon, msg) {
	$('.notifikasi-ajax').css("transform", 'translateY(400px)');
	var notifIn = setTimeout(() => {
		$('.notifikasi-ajax').css("transform", 'translateY(0px)').removeClass(sessionStorage.getItem('notif')).addClass(bg);
		$('.notifikasi-ajax .notifikasi-icon').text(icon);
		$('.notifikasi-ajax .notifikasi-msg').html(msg);
		sessionStorage.setItem('notif', bg);
	}, 500);
	var notifOut = setTimeout(() => {
		$('.notifikasi-ajax').css("transform", 'translateY(400px)');
		setTimeout(() => {
			$('.notifikasi-ajax').removeClass(bg);
		}, 500);
	}, 10000);
}

// SIDENAV
$(document).ready(function () {
	$('.sidenav').sidenav({
		isOpen: false
	});
});

// COLLAPSIBLE, CAROUSEL
$(document).ready(function () {
	$('.collapsible').collapsible({
		accordion: false
	});
	$('.carousel').carousel({
		fullWidth: true,
		indicators: true,
		noWrap: true,
		shift: 12
	});
});

// COLLAPSIBLE ARROW
$(document).ready(function () {
	$('#collapsible-user').click(function () {
		$('.menu-arrow-1').toggleClass('rotate-90');
	});
	$('#collapsible-master').click(function () {
		$('.menu-arrow-2').toggleClass('rotate-90');
	});
});


// ACTIVE SIDEMENU
$(document).ready(function () {
	let path = window.location.href.split('?');
	$("[href]").each(function () {
		if (this.href == path[0]) {
			$(this).addClass("active");
			if ($(this).hasClass('collapsible-link-master')) {
				$('#collapsible-master').collapsible('open');
				$('#collapsible-master').click();
			} else if ($(this).hasClass('collapsible-link-user')) {
				$('#collapsible-user').collapsible('open');
				$('#collapsible-user').click();
			}
		}
	});
});

$(document).ready(function () {
	setTimeout(function () {
		$('.notifikasi').css({
			"transform": 'translateY(0px)'
		});
	}, 100);

	setTimeout(function () {
		$('.notifikasi').css({
			"transform": 'translateY(400px)'
		});
	}, 10000);

	$("#jadwal-dashboard").on("change", function () {
		let hari = $(this).val();
		$.ajax({
			url: "asisten_dashboard/lihat_jadwal" + "?jadwal_hari=" + hari,
			success: function (result) {
				let msg = result.split("|");
				if (msg[0] === 'kosong') {
					$(".dragscroll").html(msg[1]);
					setTimeout(() => {
						$(".dragscroll").css('height', '80px');
					}, 1000);
				} else {
					$(".dragscroll").html(result);
					$(".dragscroll").css('height', 'auto');
				}
				$(".hari-ini").prop("disabled", "true");
				$("#jadwal-title").text("Jadwal hari " + hari + ",");
				addAnim();
			}
		});
	});
	
	$("#jadwal-admin-dashboard").on("change", function () {
		let hari = $(this).val();
		$.ajax({
			url: "dashboard/lihat_jadwal" + "?jadwal_hari=" + hari,
			success: function (result) {
				let msg = result.split("|");
				if (msg[0] === 'kosong') {
					$(".jadwal-dashboard-wrapper").html(msg[1]);					
				} else {
					$(".jadwal-dashboard-wrapper").html(result);
				}
				addAnimAdmin();
			}
		});
	});

	addAnim();
	addAnimAdmin();
});

// Views
function addAnim() {
	var item = $('.jadwal-item').length;
	var z = [];
	for (i = 1; i <= item; i++) {
		z.push(i);
	}
	z.forEach(animasiJadwal);
}

function animasiJadwal(item, index, arr) {
	setTimeout(function () {
		$('.jadwal-item:nth-child(' + item + ')').addClass('item-anim');
	}, arr[index] = item * 200);
}
function addAnimAdmin() {
	var item = $('.jadwal-item-dashboard').length;
	var z = [];
	for (i = 1; i <= item; i++) {
		z.push(i);
	}
	z.forEach(animasiJadwalAdmin);
}

function animasiJadwalAdmin(item, index, arr) {
	setTimeout(function () {
		$('.jadwal-item-dashboard:nth-child(' + item + ')').addClass('item-anim');
	}, arr[index] = item * 200);
}

$(document).ready(function () {
	// SELECT2
	$('.select2-in-modal').select2({
		minimumResultsForSearch: 10,
		dropdownParent: $('.modal')
	});
	$('.select2').select2();
	$('.select2-no-search').select2({
		minimumResultsForSearch: -1
	});
	$(".select2-with-image").select2({
		templateResult: formatState
	});

	function formatState(state) {
		let originalOption = state.element
		if (!state.id) {
			return state.text;
		}
		var $state = $(
			'<div style="display:flex; align-items:center"><img class="select2-img circle" src="../assets/images/profil/' + $(originalOption).data('image') + '" /><span style="display: block;">' + state.text + '</span></div>'
		);
		return $state;
	}
	// SUMMERNOTE
	$('.summernote').summernote({
		placeholder: 'Tuliskan deskripsi tugas',
		spellCheck: false,
		dialogsInBody: true,
		disableDragAndDrop: true,
		tabDisable: true,
		tabsize: 2,
		height: 200,
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['fontsize', ['fontsize']],
			['link', ['link']],
			['color', ['color']],
			['para', ['ul', 'ol']]
		]
	});
	$('.summernote-tentang').summernote({
		placeholder: 'Tuliskan disini ...',
		spellCheck: false,
		dialogsInBody: true,
		disableDragAndDrop: true,
		tabDisable: true,
		tabsize: 2,
		height: 200,
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['fontsize', ['fontsize']],
			['link', ['link']],
			['color', ['color']],
			['para', ['ul', 'ol']]
		]
	});
	$('.summernote-info').summernote({
		placeholder: 'Tuliskan caption informasi praktikum',
		spellCheck: false,
		tabDisable: true,
		tabsize: 2,
		dialogsInBody: true,
		disableDragAndDrop: true,
		height: 180,
		toolbar: [
			['style', ['bold', 'italic', 'underline', 'clear']],
			['fontsize', ['fontsize']],
			['link', ['link']],
			['color', ['color']],
			['para', ['ul', 'ol']]
		]
	});
});

// SEARCH CUSTOM DATATABLE
function filterGlobal() {
	$('.datatable, .datatable-nopage, .datatable-serverside-mhs').DataTable().search(
		$('#global_filter').val()
	).draw();
}

$(document).ready(function () {
	// DATATABLES
	// WITH PAGINATION
	var oTable1 = $('.datatable').DataTable({
		ordering: false,
		lengthChange: false,
		dom: 'trp',
		"pageLength": 100
	});

	// NO PAGINATION
	var oTable2 = $('.datatable-nopage').DataTable({
		ordering: false,
		lengthChange: false,
		dom: 'tr',
		'paging': false
	});

	// CHECKBOX IN DATATABLES
	$("form").on('submit', function (e) {
		let $form = $(this);
		// Iterate over all checkboxes in the table
		oTable1.$('input[type="checkbox"]').each(function () {
			// If checkbox doesn't exist in DOM
			if (!$.contains(document, this)) {
				// If checkbox is checked
				if (this.checked) {
					// Create a hidden element 
					$form.append(
						$('<input>')
						.attr('type', 'hidden')
						.attr('name', this.name)
						.val(this.value)
					);
				}
			}
		});

		oTable2.$('input[type="checkbox"]').each(function () {
			// If checkbox doesn't exist in DOM
			if (!$.contains(document, this)) {
				// If checkbox is checked
				if (this.checked) {
					// Create a hidden element 
					$form.append(
						$('<input>')
						.attr('type', 'hidden')
						.attr('name', this.name)
						.val(this.value)
					);
				}
			}
		});
	});

	// DATATABLES SEARCH
	$('input.global_filter').on('keyup click', function () {
		filterGlobal();
	});

	// MODAL
	$('.modal').modal({
		onOpenEnd: function () {
			$('.tabs').tabs();
		},
		onCloseStart: function () {
			$('.modal-content').scrollTop(0);
			$('.npm-mhs').val('mhs');
		}
	});

	//  TABS IN MODAL
	$('.modal-tabs').modal({
		onOpenEnd: function () {
			$('.tabs').tabs();
		}
	});

	// DATEPICKER
	$('.datepicker').datepicker({
		container: 'body',
		format: 'dddd, dd/mm/yyyy',
		yearRange: 25,
		i18n: {
			months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
			weekdays: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
			weekdaysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
			weekdaysAbbrev: ["M", "S", "S", "R", "K", "J", "S"]
		}
	});
	// DATEPICKER
	$('.datepicker-inventori').datepicker({
		container: 'body',
		format: 'mmmm/yyyy',
		yearRange: 25,
		i18n: {
			months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
			weekdays: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
			weekdaysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
			weekdaysAbbrev: ["M", "S", "S", "R", "K", "J", "S"]
		}
	});

	// TIMEPICKER
	$('.timepicker').timepicker({
		container: 'body',
		twelveHour: false,
		autoClose: false
	});

	// MATERIALBOXED
	$('.materialboxed').materialbox();

	// TABS
	$('.tabs').tabs();
	$('.tabs .tab a').css('position', 'relative');

	// SELECT & DROPDOWN
	$('.select').formSelect();
	$("select[required]").css({
		position: 'absolute',
		display: 'inline',
		height: 0,
		padding: 0,
		width: 0
	});
	$('.dropdown-trigger').dropdown({
		coverTrigger: false
	});
	$('.self-trigger').dropdown({
		coverTrigger: false,
		constrainWidth: false
	});
	$('.navbar-trigger').dropdown({
		coverTrigger: false,
		alignment: 'right',
		constrainWidth: false
	});

	// TOOLTIPS
	$('.tooltipped, .tooltiped').tooltip({
		enterDelay: 250,
		transitionMovement: 16,
		inDuration: 200,
		outDuration: 250
	});
	$('.tooltipped-closed').tooltip('close');

	// INPUT JADWAL KODE
	$(".input-jadwal-kode").keyup(function () {
		let jadwal_kode = $(this).val();

		$(".txt-kode").text(jadwal_kode.toUpperCase());
		$(".input-jadwal-kode").val(jadwal_kode.toUpperCase());


	});
});
