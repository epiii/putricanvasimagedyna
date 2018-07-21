<!DOCTYPE HTML>
<?php
session_start();
include "../conf.php";
//notif e
$notif ='';
$notife ='';
$no_admin='';

if(isset($_SESSION['FBID'])){
	$id = $_SESSION['FBID'];
	$nm = $_SESSION['FULLNAME'];
	$mail = $_SESSION['EMAIL'];
	$gend = $_SESSION['GENDER'];
	$gret = "Selamat datang ".$nm;
	$ref = $_SESSION['REF'];

//tentukan harga
$q1 = mysqli_query($con,"select param1,param2 FROM parameter WHERE nama='jml_user'");
$dno = mysqli_fetch_array($q1);
$no = 0;
$no_admin =$dno['param2'];
$bln6 = $bln6+$no;
$th1 = $th1 + $no;
$th2 = $th2 + $no;

if(isset($_GET['er'])){
	function notif($er){
		if($er == '1'){
			$msg ='Anda belum memilih jenis keanggotaan!';
			$jenis = 'warning';
		}else if($er == '2'){
			$msg ='Apakah anda termasuk tim Jaguar? mohon jawab dengan mengklik kotak "Tim Jaguar?"';
			$jenis ='info';
		}else if($er == '3'){
			$msg = 'Anda belum memilih metode pembayaran pada kotak "Transfer Menggunakan"';
			$jenis ='danger';
		}else if($er == '5'){
			$msg = 'No HP yang anda masukan salah! mohon masukan no HP sesuai format yang valid';
			$jenis ='danger';
		}else if($er == '6'){
			$msg = 'Nama depan harus 1 kata tanpa spasi, angka, titik, koma dan tidak boleh singkatan';
			$jenis ='info';
		}else if($er == '7'){
			$msg = 'Koneksi Data bpk/ibu kurang stabil, pendaftaran tidak berhasil. Mohon ulangi beberapa saat lagi';
			$jenis ='danger';
		}
		else{
			$msg = 'Anda telah mendaftar dengan akun facebook ini sebelumnya, tagihan untuk http://'.$_GET['er_u'].'.sukses.family adalah '.rp($_GET['er_rp']).' anda tidak bisa melakukan pendaftaran baru. Silahkan menyelesaikan pendaftaran sebelumnya';
			$jenis ='success';
		}
		$hsl = ' <div class="alert-box '.$jenis.'"><span>error: </span>'.$msg.'</div>
		';
		return $hsl;
	}
	$err_d = str_split($_GET['er']);
	foreach ($err_d as $rno){
		$notif = $notif.notif($rno);
	}
	$notife = '
	<div id="notifications">'.$notif.'
	</div>
	';
}

}else{
	header("Location: index.php");
	$gend ='';
	$ref='';
	$mail='';
	$nm='';
	$id='';
	$gret='';
}
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
					<h1 id="logo"><a href="javascript:;">Formulir Pendaftaran</a></h1>
					<p>Komunitas SuksesFamily komunitas bisnis lintas jaringan.</p>
				</header>

				<footer>
					<ul class="icons">
						<li><a href="javascript:;" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="https://facebook.com/<?php echo $id;?>" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="javascript:;" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="javascript:;" class="icon fa-github"><span class="label">Github</span></a></li>
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
					<section>
						<h4>Bergabung Bersama Puluhan Ribu Anggota SuksesFamily Indonesia</h4>
						<?php echo $notife; ?>
						<div class="box alt">
							<div class="row 50% uniform">
								<div class="4u">
									<span class="image fit">
										<img src="../poto/<?php echo $id; ?>.jpg" alt="" />
									</span>
								</div>
								<div class="8u">
									<span class="image fit"> Anda mendaftar menggunakan akun medsos <br>
										FB : <b><small><?php echo $nm; ?></small></b>
									</span>
								</div>
							</div>
						</div>
						<form method="post" action="order.php">
							<div class="row uniform">
								<div class="6u 12u(xsmall)">
									<input type="text" name="nama1"  value="" placeholder="Nama Depan" />
								</div>
								<div class="6u 12u(xsmall)">
									<input type="text" name="nama2"  value="" placeholder="Nama Belakang" />
								</div>
							</div>
							<input type="hidden" name="fbid" value="<?php echo $id; ?>"/>
							<input type="hidden" name="nm" value="<?php echo $nm; ?>"/>
							<input type="hidden" name="mail" value="<?php echo $mail; ?>"/>
							<input type="hidden" name="gend" value="<?php echo $gend; ?>"/>
							<input type="hidden" name="admin" value="<?php echo $no_admin; ?>"/>
							<input type="hidden" name="ref" value="<?php echo $ref; ?>"/>
							<div class="row uniform">
								 <div class="4u 12u(xsmall)">
								 	<div class="select-wrapper">
								 		<select name="negara">
											<option value="neg">- Negara Tinggal -</option>
											<?php
											$QryPrm = mysqli_query($con,"select * FROM parameter WHERE param1='nomor' order by param2 asc");
											while ($data=mysqli_fetch_array($QryPrm)){
											echo "<option value='".$data[nama]."'>".$data[param2]."</option>";
											}
											?>
								 		</select>
								 	</div>
								 </div>
								 <div class="4u 12u(xsmall)">
										<input type="text" name="nope"  value="" placeholder="No WhatsApp 0812xxx" />
								 </div>
								<div class="4u 12u(xsmall)">
									<div class="select-wrapper">
										<select name="angg">
											<option value="no">- Pilih Keanggotaan -</option>
											<?php
											$QryPrm = mysqli_query($con,"select * FROM parameter WHERE param1='harga' and param2=(select mlm_type from pengguna where username='$ref')");
											while ($data=mysqli_fetch_array($QryPrm)){
											echo "<option value='".$data[nama]."'>".$data[param3]." ".rp($data[nama])."</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="row uniform">
									<div class="6u 12u(xsmall)">
										<input type="text" name="vp" value="" placeholder="ID Paytren" />
									</div>
									<div class="6u 12u(xsmall)">
										<div class="select-wrapper">
											<select name="jaguar" >
												<option value="">- Tim Jaguar ? -</option>
												<option value="bukan">Bukan</option>
												<option value="ya">Ya</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row uniform">
									<div class="12u">
										<ul class="feature-icons">
											<li class="fa-code">Pastikan semua formulir telah diisi untuk mengaktifkan tombol "Kirim Formulir"</li>
											<li class="fa-coffee">formulir harus diketik tidak boleh <i>copas<i/> agar terbaca oleh sistem</li>
											<li class="fa-bolt">Halaman ini bekerja dengan baik dengan browser <i class="icon fa-chrome"> </i> Chrome atau <i class="icon fa-safari"> </i> Safari di <i>smarthphone</i> anda</li>
										</ul>
									</div>
								</div>
								<div class="row uniform">
									<div class="12u">
										<ul class="actions fit">
											<li><input type="submit" class="button fit" value="Kirim Formulir" disabled /></li>
										</ul>
									</div>
								</div>
							</div>
						</form>
					</section>
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

		<script>
		$(document).ready(function() {

			$('.row input').keyup(function() {

	        var empty = false;
	        $('.row input').each(function() {
	            if ($(this).val().length == 0) {
	                empty = true;
	            }
	        });

	        if (empty) {
	            $('.actions input').attr('disabled', 'disabled');
	        } else {
	            $('.actions input').removeAttr('disabled');
	        }
	    });
/*
			$('#tipe').bind('change click keyup', function() {
				$('#tipex').val($('#tipe option:selected').text());
			});
			$('#angg').bind('change click keyup', function() {
				$('#anggx').val($('#angg option:selected').text());

				var empty = false;
				$('.row input').each(function() {
						if ($(this).val().length == 0) {
								empty = true;
						}
				});

				if (empty) {
						$('.actions input').attr('disabled', 'disabled');
				} else {
						$('.actions input').removeAttr('disabled');
				}
			});
*/
			/*
			$("#tipe").change(function() {
				var ambil = $(this).attr("value");
				$.ajax({
					type : "GET",
					url : "lanjut.php",
					data : ambil
				}).done(function(data) {
					$("#dapet").val(data);
				});
			});
			*/
	});
		</script>
	</body>
</html>
