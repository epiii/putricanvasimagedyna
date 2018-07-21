<?php session_start();
include "../conf.php"; 
$q1 = mysqli_query($con,"SELECT `param1`,`param2` FROM `$db`.`parameter` WHERE `nama` = 'jml_user'");
$dno = mysqli_fetch_array($q1);
$seq = $dno['param2'];
$refer = $_SESSION['REF'];
//tulis seq di record
mysqli_query($con,"INSERT INTO `otp_code` (`id`, `otp`, `ref`, `tgl`, `stat`) VALUES (NULL, '$seq', '$refer', CURRENT_DATE(), NULL);");
//tambah 1 ke sequence parameter
		mysqli_query($con,"UPDATE `parameter` SET `param2` = `param2`+1 WHERE `nama` = 'jml_user'");
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
		
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body id="top">

		<!-- Header -->
			<section id="three" class="wrapper style2 special">
				<header class="major">
					<h2>Kode OTP Pendaftaran</h2>
					<ul class="actions">
					</li>
					<li><a href="#" class="button"><?php echo $seq ?></a></li>
				</ul>
					<p>Ingatlah kode <b>OTP Pendaftaran</b> ini untuk melanjutkan ke proses pendaftaran berikutnya. <br> Anda diwajibkan memiliki akun Facebook untuk memiliki web replika SuksesFamily</p>
				</header>
				<ul class="actions">
					<li><a href="./authe.php" class="button special icon fa-check-square">Selanjutnya</a></li>
				</ul>
			</section>

		
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