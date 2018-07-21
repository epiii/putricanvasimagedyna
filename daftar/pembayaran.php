<?php
session_start(); date_default_timezone_set('Asia/Jakarta');
include "../conf.php";
if(isset($_SESSION['FBID'])){
    $fb_id = $_SESSION['FBID']; //jika sesi g da harus direct ke awal [BLM]
}else{
	header("Location: ../daftar");
}
$q = mysqli_query($con, "SELECT * FROM `pengguna` WHERE `id_fb`='$fb_id'");
$detil = mysqli_fetch_array($q); //jika record g ditemukan, dpt error tapi blm di handle [BLM]
$exp_tgl = $detil['tgl_exp'];
$nowa = $detil['no_wa'];
$lns = $detil['tgl_lunas'];
$usernm =$detil['username'];
$nm1 = $detil['nama_dpn'];
$ref = $detil['referal'];
$nm_fb = $detil['nama_fb'];
$angg = $detil['nominal'];
//simpan foto
	//	$url = 'https://graph.facebook.com/'.$fb_id.'/picture?type=large';
    $url = '../poto/'.$fb_id.'.jpg';
		$img = '../poto/'.$fb_id.'.jpg';
		file_put_contents($img, file_get_contents($url));

//cek tgihan
$form_tag ='';
$qt = mysqli_query($con,"SELECT 'tipe_bayar' FROM 'pembayaran' WHERE 'fb_id'= '$fb_id'");
$jml_p = mysqli_num_rows($qt);


$qsq = mysqli_query($con,"SELECT `param1` FROM `parameter` WHERE `nama`='jml_user'");
$no = mysqli_fetch_array($qsq);
$sq1 = $no['param1'];
$sq = $sq1 + $jml_p;
$tran = $fb_id.'_'.$sq;
//peralihan Tagihan
if($jml_p == 0 AND is_null($lns)){
	if($angg < 100000){
		$sewa = 6;
	}else if($angg < 300000){
		$sewa = 12;
	}else{
		$sewa = 24;
	}
	mysqli_query($con,"INSERT INTO `pembayaran` (`id_bayar`, `time`, `status`, `payment_type`, `tipe_bayar`, `fb_id`, `amount`, `order_id`,`ms_sewa`) VALUES (NULL, NULL, NULL, NULL, 'new', '$fb_id', '$angg', '$tran','$sewa')");
}
$qt1 = mysqli_query($con,"SELECT * FROM `pembayaran` WHERE `fb_id`= '$fb_id'");
while($tag = mysqli_fetch_array($qt1)){
	$swa = $tag['ms_sewa'];
	$rp = rp($tag['amount']);
	$rup = $tag['amount'];
	$tr = $tag['order_id'];
	$tipe = $tag['tipe_bayar'];
	if($tag['tipe_bayar'] == 'new'){
		$desk = "aktifasi ".$usernm.".sukses.family";
		$upd ='';
	}else{
		$desk = "upgrade ".$usernm.".sukses.family";
		$upd = '<input type="hidden" value="oke" name="upgrade" />';
	}
	if(is_null($tag['status'])){
		$btn = '<input type="submit" class="button fit special" value="Bayar" /></li>';
		$stt = "Belum Lunas";

	}else{
		if($tag['status'] != 'aktifasi berhasil'){
			$btn = '<input type="submit" class="button fit special" value="Bayar Ulang" name="ulang" /></li>';
			$stt = $tag['status'];
		}else{
			$stt = $tag['status'];
			$btn ='';
		}
	}
	$sq = $sq + 1;
	$item = $desk.' '.$swa.' bln';
	$tagi = '
										<ul class="alt">
										<form method="post" action="bayar.php">
											<li>'.$desk.'</li>
											<li>Masa Sewa '.$swa.' Bulan</li>
											<li>'.$rp.'</li>
											<li>'.$stt.'</li>
											<li>
											'.$upd.'
											<input type="hidden" value="'.$item.'" name="item" />
											<input type="hidden" value="'.$rup.'" name="rp" />
											<input type="hidden" value="'.$tr.'" name="tr" />
											<input type="hidden" value="'.$swa.'" name="sw" />
											<input type="hidden" value="'.$fb_id.'" name="id" />
											<input type="hidden" value="'.$tipe.'" name="tipe" />
											<input type="hidden" value="'.$sq.'" name="ke" />
											'.$btn.'
										</form>
										</ul>
	';
	$form_tag = $form_tag.$tagi;

}
//cek list upgrade
$qup = mysqli_query($con,"SELECT `username` FROM `upgrade` WHERE `idfb` ='$fb_id' AND `tgl_lunas` IS NULL");
$ckup = mysqli_num_rows($qup);
if($ckup == 0){
//buat tombol upgrade
$upgd = '
										<h5>Upgrade Web</h5>
										<ul class="actions">
										<form method="post" action="upgrade.php">
											<input type="hidden" value="Upgrade web" name="item" />
											<input type="hidden" value="0" name="rp" />
											<input type="hidden" value="0" name="tr" />
											<input type="hidden" value="0" name="sw" />
											<input type="hidden" value="'.$fb_id.'" name="id" />
											<input type="hidden" value="upg" name="tipe" />
											<input type="hidden" value="'.$sq.'" name="ke" />
											<input type="hidden" value="ok" name="upgrade" />
											<input type="hidden" value="'.$usernm.'" name="username" />
											<input type="hidden" value="'.$ref.'" name="ref" />
											<input type="hidden" value="'.$nm_fb.'" name="nm_fb" />
											<li><input type="submit" class="button special" value="6bln 75.000" name="bln6"/></li>
											<li><input type="submit" class="button special" value="1th 200.000" name="th1"/></li>
											<li><input type="submit" class="button special" value="2th 350.000" name="th2"/></li>
											</forrm>
										</ul>';
}else{
$upgd ='';
}
if(is_null($lns)){
	$gret = "1 Langkah Lagi! Web anda aktif";
	$upg = '';
}else{
	$gret = "Web Anda Aktif!";
	$upg = $upgd;
}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Welcome to SuksesFamily</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="apple-touch-icon" sizes="76x76" href="favicon-i.png">
		<link rel="icon" type="image/png" href="favicon.png">
		<meta name="theme-color" content="#4acaa8" />
		<meta name="keywords" content="daftar, sukses, family, sukses family, paytren, yusuf mansyur, UYM, bisnis paytren, bayar bayar, bayar">
		<meta http-equiv="content-language" content="In-Id"/>
		<meta name="geo.placename" content="Indonesia"/>
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../assets/css/notif.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body>

		<!-- Header -->

			<section id="header">
				<header>
					<span class="image"><img src="../daftar/favicon.png" alt="" /></span>
					<h1 id="logo"><a href="#">Halaman Kasir</a></h1>
					<p>Komunitas SuksesFamily komunitas bisnis lintas jaringan.</p>
				</header>

				<footer>
					<ul class="icons">
						<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="https://facebook.com/<?php echo $fb_id; ?>" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon fa-github"><span class="label">Github</span></a></li>
						<li><a href="" class="icon fa-envelope"><span class="label">Email</span></a></li>
					</ul>
				</footer>
			</section>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">


							<section id="one">
								<div class="container">

									<header class="major">
										<h2><?php echo $gret; ?></h2>

									</header>

									<div class="box alt">
										<div class="row 50% uniform">
												<div class="4u"><span class="image fit"><img src="https://graph.facebook.com/<?php echo $fb_id; ?>/picture?type=large" alt="" /></span></div>
												<div class="8u"><span class="image fit"> <?php echo $nowa; echo "| saya $nm1 ... <p>"; ?> http://<?php $nm_clean = preg_replace('/\s*/', '', $usernm); echo strtolower($usernm);?>.sukses.family
												</span>
												Kadaluarsa Tgl <?php echo $exp_tgl; ?></p>
												</div>
										</div>


									</div>
								</div>
							</section>
							<section id="two">
								<div class="container">
									<div class="12u">
									<h4>Tagihan Anda!</h4>
										<?php echo $form_tag; ?>
									</div>
								</div>
							</section>

							<section id="tri">
								<div class="container">
									<div class="12u">

										<?php echo $upg; ?>
									</div>
								</div>
							</section>
					</div>

				<!-- Footer -->
					<section id="footer">
						<div class="container">
							<ul class="copyright">
								<li>&copy; timDTS Jakarta</li>
							</ul>
						</div>
					</section>

			</div>

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.scrollzer.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="../assets/js/main.js"></script>

	</body>
</html>
