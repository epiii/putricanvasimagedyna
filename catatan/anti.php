<?php

session_start();
include "../conf.php"; include "fungsi.php"; $notif_cl = '';
$fbid = $_SESSION['FBID'];
$nama_lengkap = $_SESSION['FULLNAME'];
$batas = 10;
$judul ='SuksesFamily'; $pencairan='';

$userByFbId = getUserByFbId($con,$fbid);
// print_r($dt);exit();

if(isset($_GET['h'])){
	$halaman = $_GET['h'];
}else{
	$halaman = 0;
}
$usr = cek($con,$db,$fbid);
//echo $usr;
$t_cair ='';
if($usr != 'no'){
	if(isset($_GET['t'])){
		$hala = $_GET['t'];

		if($hala == 'dp'){
			$dts = pembayaran($con,$db,$usr,$th1,$th2,$k_2th,$k_1th,$k_6bln);
			$data_pgg = $dts['tabel'];
			$cair = pencairan($con,$usr,$fbid);
			$pencairan = $cair['tabel_riwayat'];
			$judul = rp($dts['komisi']);
			if(date("d") == '13' OR date("d") == "14"){
				if($cair['norek'] == 'no'){
					if($dts['komisi'] >= 100000){
						$t_cair ='<a href="inisiasi.php" class="btn btn-danger btn-sm">Pencairan</a><small>tombol ini hanya muncul setiap tanggal 13 dan 14 </small>';
					}
				}
			}else{
				$t_cair='';
			}
		}
		if($hala == 'mt'){
			$dts = calon_mitra($con,$db,$usr,$halaman,$batas);
			$data_pgg = $dts;
			$judul = "Calon Mitra";
		}
		if($hala == 'kun'){
			//$cair = pencairan($con,$usr,$fbid);
			//$pencairan = $cair['tabel_riwayat'];
			$data_pgg = kunjungan($con,$db);
			$judul = "Dashboard Kunjungan";
		}
		if($hala == 'profmote'){
			// // var_dump($data_pgg);
			// $data_pgg = checkFbProfile($con,$fbid);
			$data_pgg = profmote($con,$fbid);
			// pr($data_pggx);
			$judul = "gambar";
		}
	}else{
		$judul = 'Daftar Tagihan';
		$data_pgg = tagihan($con,$db,$usr,$halaman,$batas,$th1,$th2);
	}
}else{
	$judul 		='';
	$data_pgg ='';
	header("Location: ganti_fb.php");
}

//jika ada yg claim mitra
if(isset($_POST['claim'])){
		//analisa formatnya
	$mit = $_POST['claim'];
	$frm = explode('.',$mit);
	if(count($frm) == 3){
		if($frm[1] == 'sukses' and $frm[2]	== 'family'){
			//format benar
			$cl_user = $frm[0];
			$ada = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_lunas`,`referal` FROM `pengguna` WHERE `username`='$cl_user'"));
			if($ada){
				//ada

			if($ada['referal'] =='mandiri'){
				mysqli_query($con,"UPDATE `pengguna` SET `email` = NULL, `referal` = '$usr',`web_training`='tidak' WHERE `username` = '$cl_user'");

				$notif_cl = $notif_cl.'<div class="alert alert-success alert-with-icon" data-notify="container">
					<div class="container">
						<div class="alert-wrapper">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<i class="alert-icon fa fa-thumbs-up"></i>
							<div class="message"><b>Claim Berhasil! </b> Web Replika yang anda maksud sudah kami tambahkan ke /catatan ini refresh halaman ini dan cek di menu Home atau Komisi</div>
						</div>
					</div>
				</div>
				';
			}else{
				$notif_cl = $notif_cl.'<div class="alert alert-success alert-with-icon" data-notify="container">
					<div class="container">
						<div class="alert-wrapper">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
							<i class="alert-icon fa fa-lock"></i>
							<div class="message"><b>Claim Ditolak! </b> Web Replika yang anda maksud tidak mengalami gangguan sinyal/kendala teknis, replika tersebut mendaftar melalui replika lain (bukan replika anda). </div>
						</div>
					</div>
				</div>
				';
			}
		}else{
			//tdk ada
			$notif_cl = $notif_cl.'<div class="alert alert-info alert-with-icon" data-notify="container">
            <div class="container">
                <div class="alert-wrapper">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="alert-icon fa fa-eye-slash"></i>
                    <div class="message"><b>Web Replika Tidak Terdaftar! </b> Mungkin anda salah ketik, silahkan ulangi lagi</div>
                </div>
            </div>
        </div>
		';
		}

	}else{
		//format salah
		$notif_cl = $notif_cl.'
		<div class="alert alert-warning alert-with-icon" data-notify="container">
            <div class="container">
                <div class="alert-wrapper">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="alert-icon fa fa-bell"></i>
                    <div class="message"><b>Anda Salah Ketik! </b>ulangi sekali lagi, contoh : alamat web http://mitra.sukses.family maka masukan <b>mitra.sukses.family</b> dalam kotak "Claim Mitra" </div>
                </div>
            </div>
        </div>
		';
	}
}else{
	$notif_cl = $notif_cl.'
		<div class="alert alert-danger alert-with-icon" data-notify="container">
            <div class="container">
                <div class="alert-wrapper">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="alert-icon fa fa-exclamation-circle"></i>
                    <div class="message"><b>Format Salah! </b>contoh : alamat web http://mitra.sukses.family maka masukan <b>mitra.sukses.family</b> dalam kotak "Claim Mitra" </div>
                </div>
            </div>
        </div>
		';
	}
}

?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<link rel="icon" type="image/png" href="../home/img/favicon.ico">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>Catatan Sukses Family</title>

		<!-- Canonical SEO -->
		<link rel="canonical" href="https://sukses.family/catatan"/>

		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	    <meta name="viewport" content="width=device-width" />

	    <link href="../bootstrap3/css/bootstrap.css" rel="stylesheet" />
	    <link href="../assets/bootstrap-tour/css/bootstrap-tour.min.css" rel="stylesheet">
	    <link href="../home/css/ct-paper.css" rel="stylesheet"/>
	    <link href="../home/css/demo.css" rel="stylesheet" />
	    <link href="../home/css/examples.css" rel="stylesheet" />


			<script src="../bootstrap3/js/jquery.min.js"></script>
	    <script src="../bootstrap3/js/bootstrap.min.js"></script>
	    <script src="../assets/bootstrap-tour/js/bootstrap-tour.min.js"></script>

	    <!--     Fonts and icons     -->
	    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet">
	    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-WV7W67N');</script>

		<style media="screen">
			.imageOption{
				height: 150px;
				border: 5px dashed #CCC;
				/* border: 5px dashed #CCC; */
				cursor: pointer;
				opacity: .3;
			}
			.imageOption.active{
				border: 5px solid orange;
				/* border: 5px dashed #000; */
				opacity: 1;
			}
			.imageOption:hover {
				opacity: 1;
			}

			/* .container .btn { */
			.divCanvas .downloadBtn {
			  position: absolute;
			  top: 50%;
			  left: 50%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 12px 24px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
				opacity: 0.5;
			}
			.downloadBtn:hover {
				opacity: 1;
			}

	/* spinner / loader  */
			.pageLoader {
				position: fixed;
				left: 0px;
				top: 0px;
				width: 100%;
				height: 100%;
				z-index: 9999;
				/* background: url(fb_loader.gif) center no-repeat #fff; */
				background: url(../assets/img/loaderF.gif) center no-repeat #fff;
				background-size: 40px 40px;
				opacity: 0.7;
			}

		</style>
	</head>

	<body class="search">
		<?php echo $notif_cl; ?>
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WV7W67N" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
	    <nav class="navbar navbar-relative navbar-ct-transparent navbar-burger" role="navigation-demo">
	      <div class="container">
	        <!-- Brand and toggle get grouped for better mobile display -->
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
	            <span class="sr-only">Tombol</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="#"><?php echo $judul.'</a>'.$t_cair; ?>
	        </div>

	        <!-- Collect the nav links, forms, and other content for toggling -->
	        <div class="collapse navbar-collapse navbar-white-collapse" id="navigation-example-2">
	          <ul class="nav navbar-nav navbar-right">
                <li><a class="btn btn-simple btn-neutral">SuksesFamily</a></li>
                <li><a class="btn btn-simple btn-neutral">2017</a></li>
                <li><a  target="_blank" class="btn btn-simple btn-neutral"><i class="fa fa-twitter"></i> Twitter </a></li>
                <li><a  target="_blank" class="btn btn-simple btn-neutral"><i class="fa fa-facebook"></i> Facebook </a></li>
	           </ul>
	        </div><!-- /.navbar-collapse -->
	      </div><!-- /.container-->
	    </nav>

	    <div class="wrapper">
      	<div class="main">
        	<div class="section section-white section-search">
          	<div class="container">
          		<?php echo $pencairan;?>
						<div class="row">

							<div class="pageLoader"></div>

							<div class="col-md-6 col-xs-12 text-center" >
							<!-- <div class="col-md-12 text-center" > -->
								<div class="info info-horizontal" id="panel1">
									<div class="icon">
										<?php
										// destroy_session($_SESSION);
											if(isset($_SESSION['FOTO_PROFIL'])){
												echo '<img src="../uploads/frame_edit/'.$_SESSION['FOTO_PROFIL'].'">';
											}else{
												echo '<img src="https://graph.facebook.com/'.$fbid.'/picture?type=large"/>';
											}
										?>
									</div>
									<div class="description">
										<h4><?php echo $nama_lengkap;?> </h4>
										<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>
									</div>
								</div>

								<h6 class="text-muted">Followup Mitra</h6>
								<div class="progress">
									<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
										<span class="sr-only">Progress</span>
									</div>
								</div>

								<div class="overlay">
							    <div id="loading-img"></div>
								</div>

								<ul xid="dynaContent" class="list-unstyled follows">
									<?php echo $data_pgg;?>
								</ul>

								<div class="text-missing">
									<h5 class="text-muted">Mari pantau gerakan digital marketing downline kita melalui facebook. Berteman dengan mereka dengan klik tombol facebook </h5>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<footer class="footer-demo section-white-gray">
				<div class="container">
					<nav class="pull-left">
						<ul>
							<li>
								<a href="?t=dp" id="panel2">
									Komisi
								</a>
							</li>
							<li>
								<a href="?t=mt" id="panel3">
									CaMit
								</a>
							</li>
							<li>
								<a href="?b=" id="panel4">
									Home
								</a>
							</li>
							<li>
								<a href="../daftar/pembayaran.php" id="panel5">
									Upgrade
								</a>
							</li>
							<li>
								<a href="?t=kun" id="panel6">
									Kunjungan
								</a>
							</li>
							<li>
								<a href="?t=profmote" id="panel6">
									profile & promote
								</a>
							</li>
						</ul>
					</nav>
					<div class="copyright pull-right">
						&copy; DTS Jakarta
					</div>
				</div>
			</footer>

	</body>

	<script>
		$(document).ready(function(){
			console.log('ready');
			hideLoader();
			// setTimeout(function(){
			// 	$('.pageLoader').attr('style','display:none');
			// }, 700);
		});

		function hideLoader() {
			setTimeout(function(){
				$('.pageLoader').attr('style','display:none');
			}, 700);
		}

		function reloadPage() {
			location.reload();
		}

		function showLoader() {
			// setTimeout(function(){
				$('.pageLoader').removeAttr('style');
			// }, 700);
		}
// ---------
		function downloadCanvas(link, canvasId, filename) {
		    link.href = document.getElementById(canvasId).toDataURL();
		    link.download = filename;
		}
// ---------
		var pcanvas= document.getElementById('promoteCanvas');
		var pcontext= pcanvas.getContext('2d');
		var pimgs=[];
		var pimagesOK=0;
		var pimageURLs	= [];

		var pimgUrl1 = '';
		var pimgUrl2 = '';
		var pe,ptipe,pprofile_x,pprofile_y,ptext_x,ptext_y;
		var pObjProp=[];

		document.getElementById('promoteDownload').addEventListener('click', function() {
		    downloadCanvas(this, 'promoteCanvas',  'promote_<?php echo $fbid ?>.png');
		}, false);

		function promoteOptionClick(el) {
			$('#promoteButton').removeAttr('style');

			showLoader();
			pimageURLs=[];
			pObjProp=[];
			console.log('promoteOptionClick');
			$(".imageOption.active").removeClass("active");
			$(el).addClass("active");

			$('#id_promote').val(
				$(el).attr('promote_id')
			);

			pe = el;
			src = $(el).attr('src');
			pprofile_x = $(el).attr('profileX');
			pprofile_y	= $(el).attr('profileY');
			profile_w = $(el).attr('profileW');
			profile_h = $(el).attr('profileH');
			ptext_x = $(el).attr('textX');
			ptext_y = $(el).attr('textY');
			pObjProp.push(pprofile_x);
			pObjProp.push(pprofile_y);
			pObjProp.push(profile_w);
			pObjProp.push(profile_h);
			pObjProp.push(ptext_x);
			pObjProp.push(ptext_y);

			var profUrl = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
			var templUrl = src ;
			pimageURLs.push(templUrl); // layer 1 (bottom) : promote
			pimageURLs.push(profUrl); // layer 2 (top) : profile
			promoteLoadImage(promoteObjProp);
		}

		function promoteObjProp() {
			console.log('promoteObjProp');
			console.log(pObjProp);
			pcanvas.width = pimgs[0].naturalWidth;
			pcanvas.height = pimgs[0].naturalHeight;

		 	var imgX = pObjProp[0];
		 	var imgY = pObjProp[1];
		 	var imgW = pObjProp[2];
		 	var imgH = pObjProp[3];
		 	var txtX = pObjProp[4];
		 	var txtY = pObjProp[5];
			pcontext.drawImage(pimgs[0],0,0);
			pcontext.drawImage(pimgs[1],imgX,imgY,imgW,imgH);
			pcontext.fillText("<?php echo $nama_lengkap;?>", txtX, txtY); // 400
			pcontext.fillText("<?php echo $userByFbId['no_wa'];?>", txtX,parseFloat(txtY)+20); // 420
			pcontext.fillText("http://"+"<?php echo $userByFbId['username'];?>"+".sukses.family", txtX,parseFloat(txtY)+40); //440
		}

		function promoteLoadImage(promoteObjPropx) {
			console.log('promoteLoadImage');
			console.log('-- sebelum hapus ');
				console.log(pimgs);
				console.log(pimageURLs);
			pimgs=[];
			console.log('-- setelah hapus ');
				console.log(pimgs);
				console.log(pimageURLs);

			$('#promotePreview').attr('style','display:none');
			// context.clearRect(0, 0, canvas.width, canvas.height);

			console.log(pimageURLs.length);
			for (var i=0; i<pimageURLs.length; i++) { // iterate through the pimageURLs array and create new images for each
				var img = new Image(); // create a new image an push it into the pimgs[] array
				pimgs.push(img);

				img.crossOrigin = "anonymous";
				img.onload = function(){// when this image loads, call this img.onload
					pimagesOK++; // this img loaded, increment the image counter
					if (pimagesOK>=pimageURLs.length ) { // if we've loaded all images, call the callback
						promoteObjPropx();
						$('#promoteDownload').removeAttr('style');
						hideLoader();
					}
				};

				img.onerror=function(){ // notify if there's an error
					alert("failed load profile, check your network");
					hideLoader();
				}

				img.src = pimageURLs[i]; // set img properties
			}
		}

		function promoteResetForm () {
			$('#promoteCanvas').html('');
			$('#promotePreview').removeAttr('style');

			pimgs=[];
			pimagesOK=0;
			pimageURLs	= [];
			pimgUrl1 = '';
			pimgUrl2 = '';
			pe,ptipe='',pprofile_x='',pprofile_y='';
			pObjProp=[];
		}

		function promoteSave() {
			$.ajax({
				url:'anti_proses.php',
				dataType:'json',
		  	type:'post',
		  	data: {
					'photo':pcanvas.toDataURL('image/png'),
					'dataForm':$('#promoteForm').serialize()
				},beforeSend:function(){
					showLoader();
				},success:function(dt){
					setTimeout(function(){
						$('.pageLoader').attr('style','display:none');
					}, 700);
					setTimeout(function(){
						alert(dt.status);
					}, 700);
				}
			});
		}

// profile frame
		var fcanvas= document.getElementById('frameCanvas');
		var fcontext= fcanvas.getContext('2d');
		var fimgs=[];
		var fimagesOK=0;
		var fimageURLs	= [];

		var fimgUrl1 = '';
		var fimgUrl2 = '';
		var pe,ftipe,fprofile_x,fprofile_y,ftext_x,ftext_y;
		var fObjProp=[];

		function frameOptionClick(el) {
			// var frameDataURL = '';
			$('#frameButton').removeAttr('style');
			showLoader();
			fimageURLs=[];
			fObjProp=[];
			$('#dynaContent').addClass('spinner');
			console.log('frameOptionClick');
			$(".imageOption.active").removeClass("active");
			$(el).addClass("active");

			$('#id_frame').val(
				$(el).attr('frame_id')
			);

			fe = el;
			src = $(el).attr('src');
			fprofile_x = $(el).attr('profileX');
			fprofile_y	= $(el).attr('profileY');
			profile_w = $(el).attr('profileW');
			profile_h = $(el).attr('profileH');
			ftext_x = $(el).attr('textX');
			ftext_y = $(el).attr('textY');
			fObjProp.push(fprofile_x);
			fObjProp.push(fprofile_y);
			fObjProp.push(profile_w);
			fObjProp.push(profile_h);
			fObjProp.push(ftext_x);
			fObjProp.push(ftext_y);

			var profUrl = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
			var templUrl = src ;
			fimageURLs.push(profUrl); // layer 1 (bottom) : prof
			fimageURLs.push(templUrl); // layer 2 (top) : frame
			frameLoadImage(frameObjProp);
		}

		function frameObjProp() {
			console.log('frameObjProp');
			console.log(fObjProp);
			fcanvas.width = fimgs[0].naturalWidth;
			fcanvas.height = fimgs[0].naturalHeight;
			console.log(fcanvas.width);
			console.log(fimgs[1].naturalWidth);

			fcontext.drawImage(fimgs[0],0,0);
			fcontext.drawImage(fimgs[1],0,0,fcanvas.width,fcanvas.height);
		}

		function frameLoadImage(frameObjPropx) {
			console.log('frameLoadImage');
			console.log('-- sebelum hapus ');
				console.log(fimgs);
				console.log(fimageURLs);
			fimgs=[];
			console.log('-- setelah hapus ');
				console.log(fimgs);
				console.log(fimageURLs);

			$('#framePreview').attr('style','display:none');
			// context.clearRect(0, 0, canvas.width, canvas.height);

			console.log(fimageURLs.length);
			for (var i=0; i<fimageURLs.length; i++) { // iterate through the fimageURLs array and create new images for each
				var img = new Image(); // create a new image an push it into the fimgs[] array
				fimgs.push(img);

				img.crossOrigin = "anonymous";
				img.onload = function(){// when this image loads, call this img.onload
					fimagesOK++; // this img loaded, increment the image counter
					if (fimagesOK>=fimageURLs.length ) { // if we've loaded all images, call the callback
						frameObjPropx();
						$('#frameDownload').removeAttr('style');
						hideLoader();
					}
				};

				img.onerror=function(){ // notify if there's an error
					alert("failed load profile, check your network");
					hideLoader();
				}

				img.src = fimageURLs[i]; // set img properties
			}
		}

		function frameResetForm () {
			$('#frameCanvas').html('');
			$('#framePreview').removeAttr('style');

			fimgs=[];
			fimagesOK=0;
			fimageURLs	= [];
			fimgUrl1 = '';
			fimgUrl2 = '';
			fe,ftipe='',fprofile_x='',fprofile_y='';
			fObjProp=[];
		}

		document.getElementById('frameDownload').addEventListener('click', function() {
		    downloadCanvas(this, 'frameCanvas',  'frame_<?php echo $fbid ?>.png');
		}, false);

		function frameSave() {
			$.ajax({
				url:'anti_proses.php',
		  	type:'post',
				dataType:'json',
		  	data: {
					'photo':fcanvas.toDataURL('image/png'),
					'dataForm':$('#frameForm').serialize()
				},beforeSend:function(){
					showLoader();
				},success:function(dt){
					setTimeout(function(){
						$('.pageLoader').attr('style','display:none');
					}, 700);
					if(dt.status!='success'){
						alert(dt.status);
					} else {
						setTimeout(function(){
							reloadPage();
						}, 900);
					}
				}
			});
		}

// tour
		var tour = new Tour({
			backdrop:true,
			steps: [{
				element: "#panel1",
				title: "Nama User",
				content: "ini adalah nama yg anda gunakan"
			},{
				element: "#panel2",
				title: "button komis",
				content: "klik button komisi untuk mengetahui jumlah bonus anda"
			},{
				element: "#panel3",
				title: "Button Calon mitra",
				content: "klik button camit untuk calon mitra yg ingin bergabung"
			},{
				element: "#panel4",
				title: "Home",
				content: "kembali ke beranda/home"
			},{
				element: "#panel5",
				title: "button upgrade",
				content: "upgrade web replika untuk memperpanjang masa aktif "
			},{
				element: "#panel6",
				title: "button kunjungan",
				content: "untuk melihat total pengunjung web anda"

			}]
		});
		tour.init();	// Initialize the tour
		tour.start();	// Start the tour
	</script>

	<script src="../home/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="../home/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>

	<script src="../bootstrap3/js/bootstrap.js" type="text/javascript"></script>

	<!--  Plugins -->
	<script src="../home/js/ct-paper-checkbox.js"></script>
	<script src="../home/js/ct-paper-radio.js"></script>
	<script src="../home/js/ct-paper-bootstrapswitch.js"></script>

	<!--  for fileupload -->
	<script src="../home/js/jasny-bootstrap.min.js"></script>

	<script src="../home/js/ct-paper.js"></script>
</html>
