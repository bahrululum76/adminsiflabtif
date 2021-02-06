<?php 
function cek_session()
{
	$CI =& get_instance();
	$session = $CI->session->userdata('jabatan_id');

	if ($session != '6' || $session == null) {
		redirect('auth');
	}
}
function cek_session_bak()
{
	$CI =& get_instance();
	$session = $CI->session->userdata('jabatan_id');

	if ($session != '12' || $session == null) {
		redirect('auth');
	}
}
function cek_session_dosen()
{
	$CI =& get_instance();
	$session = $CI->session->userdata('jabatan_id');

	if ($session == '13' || $session == '1') {
	} else {
		redirect('auth');
	}
}

function cek_session_null()
{
	$CI =& get_instance();
	$session = $CI->session->userdata('jabatan_id');
	
	if ($session == null) {
		redirect('auth');
	}
}

?>