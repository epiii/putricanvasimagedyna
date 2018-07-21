<!DOCTYPE HTML>
<?php session_start();
$ref ='';
if(isset($_GET['q'])){
$ref = "?q=".$_GET['q'];
$_SESSION['REF'] = $_GET['q'] ;
}else{
$_SESSION['REF'] = "mandiri";	
}
$refer = $_SESSION['REF'];
include "../conf.php"; include "../oge/oge.php";
$q1 = mysqli_query($con,"SELECT `param1`,`param3` FROM `$db`.`parameter` WHERE `nama` = 'jml_user'");
$dno = mysqli_fetch_array($q1);
$no = $dno['param1']+1;
$no_admin =ke_wa($dno['param3']);

$link_wa ="https://api.whatsapp.com/send?phone=$no_admin&text=saya%20mengetahui%20SuksesFamily%20dari%20$refer%20saya%20telah%20memiliki%20alamat%20replika%20SuksesFamily";
if(isset($_GET['q'])){
$judul ='Web Ini Mempermudah Promosi';
$desk = 'Bikin yuk? cukup klik tautan ini dan ikuti langkah-langkahnya.';
$alamat = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$oge = oge("http://sukses.family/daftar/oge.png",$judul,$desk,$alamat);	
}else{
$judul ='Pendaftaran Web SuksesFamily';
$desk = 'Hubungi '.nm($dno['param2']).' atau klik tautan ini untuk pendaftaran, bergabunglah bersama 40.000 member lainya';
$alamat = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$oge = oge("http://sukses.family/daftar/oge.png",$judul,$desk,$alamat);
}


?>
<html>
	<head>
		<title><?php echo $judul;?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="apple-touch-icon" sizes="76x76" href="favicon-i.png">
		<link rel="icon" type="image/png" href="favicon.png">
		<meta name="theme-color" content="#4acaa8" />
		<meta name="keywords" content="daftar, sukses, family, sukses family, paytren, yusuf mansyur, UYM, bisnis paytren, bayar bayar, bayar">
		<meta http-equiv="content-language" content="In-Id"/>
		<meta name="geo.placename" content="Indonesia"/>
		<?php echo $oge;?>
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body id="top">

		<!-- Header -->
			<header id="header">
				<div class="content">
					<h1><a href="#">SuksesFamily</a></h1>
					<p>Komunitas Bisnis Indonesia | Quantum Elite Camp <br/> Quantum Business Network | Elite Bizcamp</p>
					<ul class="actions">
						<li><a href="./authe.php<?php echo $ref;?>" class="button special icon fa-facebook">Daftar SuksesFamily</a></li>
						<li><a href="<?php echo $link_wa;?>" class="button icon fa-whatsapp">Cust Service</a></li>
					</ul>
				</div>
				<div class="image phone"><div class="inner"><img src="images/screen.jpg" alt="" /></div></div>
			</header>
			<section id="one" class="wrapper style2 special">
				<header class="major">
					<h2>Komunitas Bisnis
					Lintas Jaringan, Mengubah Kompetisi Menjadi Sinergi</h2>
				</header>
				<ul class="icons major">
					<li><span class="icon fa-cloud"><span class="label">Mudah</span></span></li>
					<li><span class="icon fa-refresh"><span class="label">Ekonomis</span></span></li>
					<li><span class="icon fa-area-chart"><span class="label">Menghasilkan</span></span></li>
					<input type="hidden" name="ref" value="<?php echo $_SESSION['REF'];?>"/>
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