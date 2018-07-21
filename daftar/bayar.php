<?php
include "../conf.php";
$rp = $_POST['rp'];
$tr = $_POST['tr'];
$item = $_POST['item'];
$swa = $_POST['sw'];
$fbid = $_POST['id'];
$tipe = $_POST['tipe'];
$ke = $_POST['ke'];

if(isset($_POST['ulang'])){
	$tr = $fbid.'_'.$ke;
	mysqli_query($con,"INSERT INTO `pembayaran` (`id_bayar`, `time`, `status`, `payment_type`, `tipe_bayar`, `fb_id`, `amount`, `order_id`, `ms_sewa`) VALUES (NULL, NULL, NULL, NULL, '$tipe', '$fbid', '$rp', '$tr', '$swa')");
	
}
if(isset($_POST['upgrade'])){
	$tr = $fbid.'_'.$ke;
	//buat pembayaran
	mysqli_query($con,"INSERT INTO `pembayaran` (`id_bayar`, `time`, `status`, `payment_type`, `tipe_bayar`, `fb_id`, `amount`, `order_id`, `ms_sewa`) VALUES (NULL, NULL, NULL, NULL, 'upg', '$fbid', '$rp', '$tr', '$swa')");
	//jika baru upgrade
	if($_POST['status'] == "baru"){
		$username = $_POST['username'];
		$prov = $_POST['tprov'];
		$kab = $_POST['tkab'];
		$kec = $_POST['tkec'];
		$kel = $_POST['tkel'];
		$ref = $_POST['ref'];
		if($swa == 6){
			$basil = $k_6bln;
		}else if($swa == 12){
			$basil = $k_1th;
		}else{
			$basil = $k_2th;
		}
		//buat record upgrade
		mysqli_query($con,"INSERT INTO `upgrade` (`no`, `idfb`, `username`, `nominal_up`, `upgrade`, `tgl_permintaan`, `tgl_lunas`, `prov`, `kab`, `kec`, `kel`, `referal`, `bagi_hasil`, `basil_dibayar`) VALUES (NULL, '$fbid', '$username', '$rp', '$swa', CURRENT_TIMESTAMP, NULL, '$prov', '$kab', '$kec', '$kel', '$ref', '$basil', NULL)");
	}
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
					<h1 id="logo"><a href="#">Pembayaran</a></h1>
					<p>Komunitas SuksesFamily komunitas bisnis lintas jaringan.</p>
				</header>
				
				<footer>
					<ul class="icons">
						<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="https://facebook.com/10211507836106395" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
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
									<h4>Transaksi pembayaran!</h4> <p>Dapat dilakukan melalui metode Bank Transfer/Virtual Account menggunakan mobile banking/internet banking/ATM </p>
										<!-- <span class="image fit"><img src="bayar.png" alt="" /></span> -->

									</header>
									
									<div class="box alt">
										<div class="row 50% uniform">
											<h4>Tips Pembayaran!</h4>
											<p> Pembayaran anda secara otomatis akan diproses oleh sistem kami kurang dari 4 Jam jika anda melakuan prosedur dengan benar sesuai petunjuk pembayaran yang tertera. Klik tombol bayar dan <b>SCREENSHOT</b> petunjuk pembayaran sebagai arsip anda</p>
										</div>
										
									
									</div>	
								</div>
							</section>		
							<section id="two">
								<div class="container">	
									<div class="12u">
									<ul class="actions">
										<li><input type="submit" id="bayar" class="button fit special" value="Bayar" /></li>
									</ul>
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
			<script src="https://app.midtrans.com/snap/snap.js" data-client-key="Mid-client-cKm-6RR4sJP3IT7y"></script>
<script type="text/javascript">

  document.getElementById('bayar').onclick = function(){
    var requestBody = 
    {
      transaction_details: {
        gross_amount: <?php echo $rp; ?>,
        // as example we use timestamp as order ID
        order_id: '<?php echo $tr; ?>'
      },
	  expiry: {
		unit: "day",
		duration: 7
	  },
	
	customer_details: {
		first_name : "SuksesFamily",
		last_name: "Member", 
    },
	
	  item_details: [{
		id: "Sewa",
		price: <?php echo $rp; ?>,
		name: "<?php echo $item; ?>",
		quantity: 1
		
		}]
    }
    
    getSnapToken(requestBody, function(response){
      var response = JSON.parse(response);
      console.log("new token response", response);
      snap.pay(response.token);
    })
  };

  function getSnapToken(requestBody, callback) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
      if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        callback(xmlHttp.responseText);
      }
    }
    xmlHttp.open("post", "http://sukses.family/daftar/checkout.php");
    xmlHttp.send(JSON.stringify(requestBody));
  }
</script>
	</body>
</html>