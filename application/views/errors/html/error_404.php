<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="<?=config_item('base_url')?>/assets/images/icons/icon-96x96.png" rel="shortcut icon" />
<link rel="stylesheet" href="<?= config_item('base_url') ?>/assets/library/material-icons/material-icons.css">
<link rel="stylesheet" href="<?= config_item('base_url') ?>/assets/font/poppins.css">
<title>404 Page Not Found</title>
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
	text-align: center;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}

.img-404 {
	width: 100%;
	max-width: 800px;
	margin-top: 48px;
	-webkit-animation: slide-top 2s ease-in-out infinite alternate both;
	        animation: slide-top 2s ease-in-out infinite alternate both;
}

.msg-head {
	font-family: 'Poppins';
	margin-top: 24px;
	font-size: 3em;
	font-weight: 600;
	text-transform: uppercase;
	border: none;
}

.msg {
	font-family: 'Poppins';
	margin-top: 12px;
	font-size: 1.2em;
	color: #888;
}

.btn-back {
	font-family: 'Poppins';
	font-size: 1.4em;
	border: none;
	margin-top: 32px;
	padding: 12px 24px;
	border-radius: 2px;
	cursor: pointer;
	background-color: #536DFE;
	color: #fff;
}

.btn-back span {
	margin-right: 12px;
}

button:focus {
	outline: none;
}

@-webkit-keyframes slide-top {
  0% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
  }
  100% {
    -webkit-transform: translateY(-30px);
            transform: translateY(-30px);
  }
}

@keyframes slide-top {
  0% {
    -webkit-transform: translateY(0);
            transform: translateY(0);
  }
  100% {
    -webkit-transform: translateY(-30px);
            transform: translateY(-30px);
  }
}

.left {
	float: left;
}
</style>
</head>
<body>
	<img src="<?=config_item('base_url')?>/assets/images/access/404.svg" alt="404 not found" class="img-404">
	<h1 class="msg-head">Page not found</h1>
	<p class="msg">Halaman yang anda minta tidak tersedia</p>
	<button type="button" class="btn-back" onclick="location.href = '<?= config_item('base_url') ?>'"><i class="material-icons left" style="line-height: 1.1">home</i><span class="left" style="margin-left: 12px">Beranda</span></button>
	<!-- <div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div> -->
</body>
</html>