<?php session_start();
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
		if($hala == 'img'){
			// // var_dump($data_pgg);
			// $data_pgg = checkFbProfile($con,$fbid);
			// $judul = "Dashboard Kunjungan";
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
				opacity: .5;
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

											<!-- panel : left -->
											<div class="col-md-6 col-xs-12 text-center" >
												<!-- profile pict. -->
												<div class="info info-horizontal" id="panel1">
													<div class="icon">
														<img src="<?php echo 'https://graph.facebook.com/'.$fbid.'/picture?type=large'; ?>">
													</div>
													<div class="description">
														<h4><?php echo $nama_lengkap;?> </h4>
														<p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></p>
													</div>
												</div>

												<!-- user -->
												<h6 class="text-muted">Followup Mitra</h6>
												<div class="progress">
												  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
													<span class="sr-only">Progress</span>
												  </div>
												</div>
                        <ul class="list-unstyled follows">
													<?php echo $data_pgg;?>
                        </ul>
                        <div class="text-missing">
                            <h5 class="text-muted">Mari pantau gerakan digital marketing downline kita melalui facebook. Berteman dengan mereka dengan klik tombol facebook </h5>
                        </div>
                      </div> <!-- end of :: panel : left -->

											<!-- panel : right -->
											<div id="imageDiv" class="col-md-6 col-xs-12 text-center">

													<!-- preview result image -->
													<div id="exTab1" class="containerx">
														<ul  class="nav nav-pills">
															<li class="active"> <a  href="#1a" data-toggle="tab">Promote</a></li>
															<li><a href="#2a" data-toggle="tab">Profile</a></li>
														</ul>

														<div class="tab-content xclearfix">

															<!-- promote content -->
														  <div class="tab-pane active" id="1a">
																<form id="image-form" onsubmit="promoteSave();return false;" enctype="multipart/form-data">
																	<label>Promote Picture</label>

																	<!-- preview options -->
																	<div id="imageOptions" class="form-group">
																		<?php
																			$sql = 'SELECT * FROM parameter WHERE param1 = "promote" ORDER BY nama ASC';
																			$exe = mysqli_query($con,$sql);
																			$i=0;
																			while ($res=mysqli_fetch_assoc($exe)) {
																				$param3s = explode(',',trim($res['param3'],' '));
																				$x= $param3s[0];
																				$y= $param3s[1];
																				$w= $param3s[2];
																				$h= $param3s[3];
																				$xx= $param3s[4];
																				$yy= $param3s[5];
																				// pr($x);
																					echo'<img onclick="promoteOptionClick(this);"
																						src="../uploads/promote_template/'.$res['nama'].'"
																						tipe="'.$res['param1'].'"
																						profileX="'.$x.'"
																						profileY="'.$y.'"
																						profileW="'.$w.'"
																						profileH="'.$h.'"
																						textX="'.$xx.'"
																						textY="'.$yy.'"
																						class="imageOption"
																						data-design="0"
																					/>';
																					// class="imageOption '.($i==0?'active':'').'"
																					$i++;
																			}
																		 ?>
																	</div>

																	<div class="form-group">
																		<div>
																			<img id="promotePreview" width="250" src="../uploads/no_preview.png" alt="" />
																		</div>

																		<div class="divCanvas">
																			<a id="promoteDownload" style="display:none;" href="#" class="downloadBtn">Download</a>
																			<canvas id="promoteCanvas"></canvas>
																		</div>
																	</div>

																	<button class="btn btn-primary" type="submit" name="submit" id="image-submit">Save</button>
																</form><!-- end of : promote FORM -->
															</div>

															<!-- profile content -->
															<div class="tab-pane" id="2a">
																<form id="image-form" onsubmit="promoteSave();return false;" enctype="multipart/form-data">
																	<label>Promote Picture</label>

																	<!-- preview options -->
																	<div id="imageOptions" class="form-group">
																		<?php
																			$sql2 = 'SELECT * FROM parameter WHERE param1 = "frame" ORDER BY nama ASC';
																			$exe2 = mysqli_query($con,$sql2);
																			$i=0;
																			while ($res2=mysqli_fetch_assoc($exe2)) {
																					echo'<img onclick="frameOptionClick(this);"
																						src="../uploads/frame_template/'.$res['nama'].'"
																						class="imageOption"
																						data-design="0"
																					/>';
																					$i++;
																			}
																		 ?>
																	</div>

																	<div class="form-group">
																		<div>
																			<img id="framePreview" width="250" src="../uploads/no_preview.png" alt="" />
																		</div>

																		<div class="divCanvas">
																			<a id="frameDownload" style="display:none;" href="#" class="downloadBtn">Download</a>
																			<canvas id="frameCanvas"></canvas>
																		</div>
																	</div>

																	<button class="btn btn-primary" type="submit" name="submit" id="image-submit">Save</button>
																</form><!-- end of : promote FORM -->
															</div>

														</div>
												  </div>


											</div> <!-- end of :: panel : left -->

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
												<!-- <li>
													<a href="?t=img" id="panel6">
														Image
													</a>
												</li> -->
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
		});

		var canvas= document.getElementById('promoteCanvas');
		var context= canvas.getContext('2d');
		var imgs=[];
		var imagesOK=0;
		var imageURLs	= [];

		var imgUrl1 = '';
		var imgUrl2 = '';
		var e,tipe,profile_x,profile_y,text_x,text_y;
		var objProp=[];

		function downloadCanvas(link, canvasId, filename) {
		    link.href = document.getElementById(canvasId).toDataURL();
		    link.download = filename;
		}
		document.getElementById('promoteDownload').addEventListener('click', function() {
		    downloadCanvas(this, 'promoteCanvas',  '<?php echo $fbid ?>.png');
		}, false);

		function promoteOptionClick(el) {
			imageURLs=[];
			objProp=[];
			console.log('promoteOptionClick');
			$(".imageOption.active").removeClass("active");
			$(el).addClass("active");

			e = el;
			src = $(el).attr('src');
			profile_x = $(el).attr('profileX');
			profile_y	= $(el).attr('profileY');
			profile_w = $(el).attr('profileW');
			profile_h = $(el).attr('profileH');
			text_x = $(el).attr('textX');
			text_y = $(el).attr('textY');
			objProp.push(profile_x);
			objProp.push(profile_y);
			objProp.push(profile_w);
			objProp.push(profile_h);
			objProp.push(text_x);
			objProp.push(text_y);

			var profUrl = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
			var templUrl = src ;
			imageURLs.push(templUrl); // layer 1 (bottom) : promote
			imageURLs.push(profUrl); // layer 2 (top) : profile
			loadImage(promoteObjProp);
			// $('#promoteDownload').removeAttr('style');
		}

		function promoteObjProp() {
			console.log('promoteObjProp');
			console.log(objProp);
			canvas.width = imgs[0].naturalWidth;
			canvas.height = imgs[0].naturalHeight;

		 	var imgX = objProp[0];
		 	var imgY = objProp[1];
		 	var imgW = objProp[2];
		 	var imgH = objProp[3];
		 	var txtX = objProp[4];
		 	var txtY = objProp[5];
			context.drawImage(imgs[0],0,0);
			context.drawImage(imgs[1],imgX,imgY,imgW,imgH);
			context.fillText("<?php echo $nama_lengkap;?>", txtX, txtY); // 400
			context.fillText("<?php echo $userByFbId['no_wa'];?>", txtX,parseFloat(txtY)+20); // 420
			context.fillText("http://"+"<?php echo $userByFbId['username'];?>"+".sukses.family", txtX,parseFloat(txtY)+40); //440
		}

		function loadImage(promoteObjPropx) {
			console.log('loadImage');
			console.log('-- sebelum hapus ');
				console.log(imgs);
				console.log(imageURLs);
			imgs=[];
			console.log('-- setelah hapus ');
				console.log(imgs);
				console.log(imageURLs);

			$('#promotePreview').attr('style','display:none');
			// context.clearRect(0, 0, canvas.width, canvas.height);

			console.log(imageURLs.length);
			for (var i=0; i<imageURLs.length; i++) { // iterate through the imageURLs array and create new images for each
				var img = new Image(); // create a new image an push it into the imgs[] array
				imgs.push(img);

				img.crossOrigin = "anonymous";
				img.onload = function(){// when this image loads, call this img.onload
					imagesOK++; // this img loaded, increment the image counter
					if (imagesOK>=imageURLs.length ) { // if we've loaded all images, call the callback
						promoteObjPropx();
						$('#promoteDownload').removeAttr('style');
					}
				};

				img.onerror=function(){ // notify if there's an error
					alert("failed load profile");
				}

				img.src = imageURLs[i]; // set img properties
			}
		}

		function resetForm () {
			$('#promoteCanvas').html('');
			$('#promotePreview').removeAttr('style');

			imgs=[];
			imagesOK=0;
			imageURLs	= [];
			imgUrl1 = '';
			imgUrl2 = '';
			e,tipe='',profile_x='',profile_y='';
			objProp=[];
		}

		function promoteSave() {

		}

	// -- profile frame
		var canvas= document.getElementById('frameCanvas');
		var context= canvas.getContext('2d');
		var imgs=[];
		var imagesOK=0;
		var imageURLs	= [];

		var imgUrl1 = '';
		var imgUrl2 = '';
		var e,tipe,profile_x,profile_y,text_x,text_y;
		var objProp=[];

		function downloadCanvas(link, canvasId, filename) {
				link.href = document.getElementById(canvasId).toDataURL();
				link.download = filename;
		}
		document.getElementById('promoteDownload').addEventListener('click', function() {
				downloadCanvas(this, 'promoteCanvas',  '<?php echo $fbid ?>.png');
		}, false);

		function promoteOptionClick(el) {
			imageURLs=[];
			objProp=[];
			console.log('promoteOptionClick');
			$(".imageOption.active").removeClass("active");
			$(el).addClass("active");

			e = el;
			src = $(el).attr('src');
			profile_x = $(el).attr('profileX');
			profile_y	= $(el).attr('profileY');
			profile_w = $(el).attr('profileW');
			profile_h = $(el).attr('profileH');
			text_x = $(el).attr('textX');
			text_y = $(el).attr('textY');
			objProp.push(profile_x);
			objProp.push(profile_y);
			objProp.push(profile_w);
			objProp.push(profile_h);
			objProp.push(text_x);
			objProp.push(text_y);

			var profUrl = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
			var templUrl = src ;
			imageURLs.push(templUrl); // layer 1 (bottom) : promote
			imageURLs.push(profUrl); // layer 2 (top) : profile
			loadImage(promoteObjProp);
			// $('#promoteDownload').removeAttr('style');
		}

		function promoteObjProp() {
			console.log('promoteObjProp');
			console.log(objProp);
			canvas.width = imgs[0].naturalWidth;
			canvas.height = imgs[0].naturalHeight;

			var imgX = objProp[0];
			var imgY = objProp[1];
			var imgW = objProp[2];
			var imgH = objProp[3];
			var txtX = objProp[4];
			var txtY = objProp[5];
			context.drawImage(imgs[0],0,0);
			context.drawImage(imgs[1],imgX,imgY,imgW,imgH);
			context.fillText("<?php echo $nama_lengkap;?>", txtX, txtY); // 400
			context.fillText("<?php echo $userByFbId['no_wa'];?>", txtX,parseFloat(txtY)+20); // 420
			context.fillText("http://"+"<?php echo $userByFbId['username'];?>"+".sukses.family", txtX,parseFloat(txtY)+40); //440
		}

		function loadImage(promoteObjPropx) {
			console.log('loadImage');
			console.log('-- sebelum hapus ');
				console.log(imgs);
				console.log(imageURLs);
			imgs=[];
			console.log('-- setelah hapus ');
				console.log(imgs);
				console.log(imageURLs);

			$('#promotePreview').attr('style','display:none');
			// context.clearRect(0, 0, canvas.width, canvas.height);

			console.log(imageURLs.length);
			for (var i=0; i<imageURLs.length; i++) { // iterate through the imageURLs array and create new images for each
				var img = new Image(); // create a new image an push it into the imgs[] array
				imgs.push(img);

				img.crossOrigin = "anonymous";
				img.onload = function(){// when this image loads, call this img.onload
					imagesOK++; // this img loaded, increment the image counter
					if (imagesOK>=imageURLs.length ) { // if we've loaded all images, call the callback
						promoteObjPropx();
						$('#promoteDownload').removeAttr('style');
					}
				};

				img.onerror=function(){ // notify if there's an error
					alert("failed load profile");
				}

				img.src = imageURLs[i]; // set img properties
			}
		}

		function resetForm () {
			$('#promoteCanvas').html('');
			$('#promotePreview').removeAttr('style');

			imgs=[];
			imagesOK=0;
			imageURLs	= [];
			imgUrl1 = '';
			imgUrl2 = '';
			e,tipe='',profile_x='',profile_y='';
			objProp=[];
		}


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
