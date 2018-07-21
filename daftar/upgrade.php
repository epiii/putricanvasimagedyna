<!DOCTYPE HTML>
<?php 
session_start(); //perlu redirect klo ga ada post dari pembayaran
include "../conf.php";
	if(isset($_POST['bln6'])){
		$swa = 6;
		$rp = $bln6;
	}else if(isset($_POST['th1'])){
		$swa = 12;
		$rp = $th1;
	}else{
		$swa = 24;
		$rp = $th2;
	}	
$id = $_POST['id'];
$username = $_POST['username'];
$ref = $_POST['ref'];
$mail ='';
$notife ='';
$nm = $_POST['nm_fb'];

//Cek apakah ada yg blm dibayar
$que = mysqli_query($con,"SELECT * FROM `upgrade` WHERE `idfb` = '$id' AND `tgl_lunas` IS NULL AND `upgrade` ='$swa'");
$cek = mysqli_num_rows($que);
if($cek == 0){
	//order baru
	$status = "baru";
}else{
	//order lama
	$status ="lama";
}
//cek pembayaran

$ke = $_POST['ke'];
?>
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
					<h1 id="logo"><a href="#">Formulir Upgrade</a></h1>
					<p>Komunitas SuksesFamily komunitas bisnis lintas jaringan.</p>
				</header>
				
				<footer>
					<ul class="icons">
						<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="https://facebook.com/<?php echo $id;?>" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon fa-github"><span class="label">Github</span></a></li>
						<li><a href="<?php echo $mail; ?>" class="icon fa-envelope"><span class="label">Email</span></a></li>
					</ul>
				</footer>
			</section> 

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<section id="five">
								<div class="container">
										<h4>Terbukti lebih HEMAT dan EKONOMIS</h4>
										<?php echo $notife; ?>
										<div class="box alt">										
											<div class="row 50% uniform">
												<div class="4u"><span class="image fit"><img src="https://graph.facebook.com/<?php echo $id; ?>/picture?type=large" alt="" /></span></div>
												<div class="8u"><span class="image fit"> Anda mendaftar menggunakan akun medsos <br>
												FB : <b><small><?php echo $nm; ?></small></b>
												</span></div>
											</div>
										</div>	
										<form method="post" action="bayar.php">
											<!--<div class="row uniform">
												<div class="6u 12u(xsmall)">
													<input type="text" name="nama1"  value="" placeholder="Nama Depan" />
												</div>
												<div class="6u 12u(xsmall)">
													<input type="text" name="nama2"  value="" placeholder="Nama Belakang" />
												</div>
											</div> -->
										<div class="row uniform">
										<p></i>Data lokasi digunakan untuk penentuan lokasi Kopdar/Kegiatan</i> SuksesFamily, Lengkapi form berikut untuk melakukan upgrade :</p>
											<div class="6u 12u(xsmall)">
											<select name="prop" id="prop" onchange="ajaxkota(this.value)" >
												<option value="">Pilih Provinsi</option>
													<?php 
													$queryProvinsi=mysqli_query($con,"SELECT kode,nama FROM wilayah WHERE CHAR_LENGTH(kode)=2 ORDER BY nama");
													while ($dataProvinsi=mysqli_fetch_array($queryProvinsi)){
														echo '<option value="'.$dataProvinsi['kode'].'">'.$dataProvinsi['nama'].'</option>';
													}
													?>
											</select>
											</div>
											
											<div class="6u 12u(xsmall)">
											<select name="kota" id="kota" onchange="ajaxkec(this.value)" >
												<option value="">Pilih Kota</option>
											</select>
											</div>
										</div>
										<div class="row uniform">
											<div class="6u 12u(xsmall)">
											<select name="kec" id="kec" onchange="ajaxkel(this.value)" >
												<option value="">Pilih Kecamatan</option>
											</select>
											</div>
											
											<div class="6u 12u(xsmall)">
											<select name="kel" id="kel" >
												<option value="">Pilih Kelurahan/Desa</option>
											</select>
											</div>
										</div>	
											<input type="hidden" name="tprov" id="propx"/>
											<input type="hidden" name="tkab" id="kotax"/>
											<input type="hidden" name="tkec" id="kecx"/>
											<input type="hidden" name="tkel" id="kelx"/>
											<input type="hidden" name="upgrade" value="ok"/>
											<input type="hidden" name="tipe" value="upg"/>
											<?php 
											echo '
											<input type="hidden" name="id" value="'.$id.'"/>
											<input type="hidden" name="username" value="'.$username.'"/>
											<input type="hidden" name="rp" value="'.$rp.'"/>
											<input type="hidden" name="sw" value="'.$swa.'"/>
											<input type="hidden" name="ref" value="'.$ref.'"/>
											<input type="hidden" name="status" value="'.$status.'"/>
											<input type="hidden" name="ke" value="'.$ke.'"/>
											<input type="hidden" name="item" value="Upgrade web '.$swa.' bulan"/>
											';
											?>
											<div class="row uniform">
												<div class="12u">
													<ul class="actions fit">
														<li><input type="submit" class="button fit" value="Upgrade Akun SF" /></li>
													</ul>	
												</div>
											</div>
											</form>
								</div>
						</section>
						<!-- Footer -->
						<section id="footer">
							<div class="container">
								<ul class="copyright">
									<li>&copy; timDTS Jakarta</li>
								</ul>
							</div>
						</section>
					</div>
				</div>	

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.scrollzer.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="../assets/js/main.js"></script>
			<script src ="ajax_kota.js"></script>

			<script>
				$(document).ready(function() {
					$('#prop').bind('change click keyup', function() {
						$('#propx').val($('#prop option:selected').text());
					});
					$('#kota').bind('change click keyup', function() {
						$('#kotax').val($('#kota option:selected').text());
					});
					$('#kec').bind('change click keyup', function() {
						$('#kecx').val($('#kec option:selected').text());
					});
					$('#kel').bind('change click keyup', function() {
						$('#kelx').val($('#kel option:selected').text());
					});
				});	
			</script>
	</body>
</html>