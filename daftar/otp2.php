<?php session_start();

$form ='
<section id="three" class="wrapper style2 special">
				<header class="major">
				<form method="post">
					<h2>Kode OTP Pendaftaran</h2>
					<ul class="actions">
					</li>
					<li><input type="text" name="otp" value="" placeholder="kode otp" /></li>
				</ul>
					<p>Masukan kode <b>OTP Pendaftaran</b> anda.</p>
				</header>
				<ul class="actions">
					<li><button type="submit" class="button special icon fa-check-square" value="Selanjutnya">Selanjutnya</button></li>
				</ul>
				</form>
			</section>
';
$salah = '
<section id="three" class="wrapper style2 special">
				<header class="major">
					<h2>Kode OTP Salah</h2>
					<p>Silahkan tutup halaman ini dan ulangi pendaftaran! Ingat baik-baik kode <b>OTP Pendaftaran</b> anda.</p>
				</header>
				
			</section>
';
include "../conf.php"; 
//cek otp jika gagal muncul peringatan ulangi pendaftaran
if(isset($_POST['otp'])){
	$otp = $_POST['otp'];
	$ceko = mysqli_fetch_array(mysqli_query($con,"SELECT `ref` FROM `otp_code` WHERE `otp` = '$otp' ORDER BY `id` DESC limit 0,1"));
	if($ceko){
		//jika berhasil tulis sesi dan hapus record
		
		$_SESSION['REF'] = $ceko['ref'];
		
			mysqli_query($con,"DELETE FROM `otp_code` WHERE `otp` = '$otp'");
		
		//redirek lanjut
		
			header("Location: lanjut.php");
		
	}else{
		//redirek gagal
		$isi = $salah;
	}

}else{
	//redirek ke form
	$isi = $form;
}
?>
<html>
	<head>
		<title>Kode OTP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="apple-touch-icon" sizes="76x76" href="favicon-i.png">
		<link rel="icon" type="image/png" href="favicon.png">
		<meta name="theme-color" content="#4acaa8" />
		<meta name="keywords" content="daftar, sukses, family, sukses family, paytren, yusuf mansyur, UYM, bisnis paytren, bayar bayar, bayar">
		<meta http-equiv="content-language" content="In-Id"/>
		<meta name="geo.placename" content="Indonesia"/>
		
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body id="top">

		<!-- Header -->
			<?php
				echo $isi;
			?>
		<!-- Footer -->
			<footer id="footer">
				
				<p class="copyright">&copy; timDTS Jakarta</a></p>
			</footer>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>