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
				cursor: pointer;
			}
			.imageOption.active{
				border: 5px dashed #000;
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
											<div class="col-md-6 col-xs-12 text-center" >
												<div class="info info-horizontal" id="panel1">
													<div class="icon">
														<img src="<?php echo 'https://graph.facebook.com/'.$fbid.'/picture?type=large'; ?>">
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
                        <ul class="list-unstyled follows">
													<!-- nah -->
													<?php echo $data_pgg;?>
                        </ul>
                        <div class="text-missing">
                            <h5 class="text-muted">Mari pantau gerakan digital marketing downline kita melalui facebook. Berteman dengan mereka dengan klik tombol facebook </h5>
                        </div>
                      </div>

										<!-- image (profile & promote) -->
											<div id="imageDiv" class="col-md-6 col-xs-12 text-center">
												<form id="image-form" enctype="multipart/form-data">
													<!-- tipe -->
													<div class="form-group">
									          <label>Mode</label>
														<select onchange="showAllImages(this.value);"  class="form-control" name="tipe" id="mode">
															<option value="">-Select Mode-</option>
															<option value="frame">Frame</option>
															<option value="promote">Promote</option>
														</select>
									        </div>

													<!-- preview options -->
									        <div id="imageOptions" class="form-group"></div>

													<!-- preview result image -->
													<div class="form-group">
														<img id="imagePreview" width="250" src="../uploads/no_preview.png" alt="" />
														<canvas id="canvasQ">
														</canvas>
									        </div>

													<!-- <input type="hidden" id="tempImagePreview"> -->
									        <button  class="btn btn-primary" type="submit" name="submit" id="image-submit">Save</button>
									      </form>

												<!-- <img id="canvasLoder" src="fb_loader.gif" alt=""> -->
												<!-- <canvas id="canvasQ" xwidth="630" xheight="840"></canvas> -->
												<!-- <img id="canvasMirror">
												<a href="#" download="nama_file_ku.png" id="downloadBtn" class="btn btn-info"><i class="fa fa-download"></i> Download</a> -->
											</div>
										<!-- end of : image (profile & promote) -->

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

		});
		var canvas= document.getElementById('canvasQ');
		var context= canvas.getContext('2d');
		var imgs=[];
		var imagesOK=0;
		var imageURLs	= [];
		// var imgUrl1 = '../uploads/promote_template/SF1.png';
		// var imgUrl2 = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
		var imgUrl1 = '';
		var imgUrl2 = '';
		var e,tipe,koordx,koordy;
		// imageURLs.push(imgUrl1);
		// imageURLs.push(imgUrl2);

		function showAllImages(tipe) {
			console.log('showAllImages');

			resetForm();
			$.ajax({
				url:'anti_ajax.php',
				data:{
					'mode':'getImgTemplates',
					'tipe':tipe
				},type:'post',
				dataType:'json',
				beforeSend:function () {
					$('#imageOptions').html('<img src="fb_loader.gif" alt="">');
				},success:function(ret){
					setTimeout(function(){
						$('#imageOptions').html('');

						var opt='';
						if(ret.total==0) opt+='<p>template list is empty</p>';
						else{
							console.table(ret.returns.data);
							$.each(ret.returns.data, function  (id,val) {
								if (val.param3!=null) {
									var koords = val.param3.split(",");
									x = koords[0];
									y = koords[1];
								} else {
									x = y = 0;
								}

								opt+='<img onclick="imageOptionClick(this);" '
												+'src="../uploads/'+tipe+'_template/'+val.nama+'" '
												+'tipe="'+val.param1+'" '
												+'koordX="'+(x)+'" '
												+'koordY="'+(y)+'" '
												+'class="imageOption '+(id==0?'active':'')+'" '
												+'data-design="0" '
										+'/>';
							});
						}$('#imageOptions').html(opt);
					}, 700);
				}, error : function (xhr, status, errorThrown) {
					$('#imageOptions').html('');
							alert('error : ['+xhr.status+'] '+errorThrown);
					}
			});
		}

		function imageOptionClick(el) {
			console.log('imageOptionClick');
			$(".imageOption.active").removeClass("active");
			$(el).addClass("active");

			e = el;
			tipe = $(el).attr('tipe'); // frame vs promote
			src = $(el).attr('src'); // frame vs promote
			koordx = $(el).attr('koordX'); // 100
			koordy = $(el).attr('koordY'); // 150
			// loadCanvas(e,tipe,koordx,koordy);
			var profUrl = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
			var templUrl = src ;
			// var templUrl = '../uploads/'+tipe+'_template/'+;
			if (tipe=='frame') {
				imageURLs.push(profUrl); // layer 1 (bottom) -> frame (profil fb) vs koord
				imageURLs.push(templUrl); // layer 2 (top) -? promote :
			} else {
				imageURLs.push(templUrl); // layer 1 (bottom) -> frame (profil fb) vs koord
				imageURLs.push(profUrl); // layer 2 (top) -? promote :
			}
			loadImage(objectProperty);
		}


		function objectProperty() {
			console.log('objectProperty');

			canvas.width = imgs[0].naturalWidth;
			canvas.height = imgs[0].naturalHeight;
			console.log(imgs[0]);
			console.log(imgs[1]);
			console.log(context.width);
			console.log(context.height);

		 context.drawImage(imgs[0],0,0);
		 context.drawImage(imgs[1],10,10);
		 // context.drawImage(this,0,0);

		 // context.drawImage(this,koordx,koordy);
		 context.fillText("<?php echo $nama_lengkap;?>", 250, 400);
		 context.fillText("<?php echo $userByFbId['no_wa'];?>", 250,420);
		 context.fillText("http://"+"<?php echo $userByFbId['username'];?>"+".sukses.family", 250,440);
		}


		function loadImage(objectPropertyx) {
			console.log('loadImage');
			console.log('-- sebelum hapus ');
				console.log(imgs);
				console.log(imageURLs);
			imgs=[];
			console.log('-- setelah hapus ');
				console.log(imgs);
				console.log(imageURLs);
			// console.log(imageURLs);
			// console.log(imgs.length);

		// function loadCanvas(e,tipex,koordx,koordy) {
			$('#imagePreview').attr('style','display:none');

			// context.clearRect(0, 0, canvas.width, canvas.height);

			for (var i=0; i<imageURLs.length; i++) { // iterate through the imageURLs array and create new images for each
				var img = new Image(); // create a new image an push it into the imgs[] array
				imgs.push(img);

				img.onload = function(){// when this image loads, call this img.onload
					imagesOK++; // this img loaded, increment the image counter
					if (imagesOK>=imageURLs.length ) { // if we've loaded all images, call the callback
						objectPropertyx();
					}
				};
				img.onerror=function(){ // notify if there's an error
					alert("failed load image");
				}
				img.src = imageURLs[i]; // set img properties
			}
		}

		function resetForm () {
			$('#canvasQ').html('');
			$('#imagePreview').removeAttr('style');

			imgs=[];
			imagesOK=0;
			imageURLs	= [];
			imgUrl1 = '';
			imgUrl2 = '';
			e,tipe='',koordx='',koordy='';

		}




		// // statis canvas
		// 	var canvas=document.getElementById("canvasQx");
		// 	var mirror = document.getElementById('canvasMirror');
		//
		// 	var ctx=canvas.getContext("2d");
		// 	var cw=canvas.width;
		// 	var ch=canvas.height;
		//
		// // put the paths to your images in imageURLs[]
		// 	var imageURLs	= [];
		// 		var urlTemplate = '../uploads/promote_template/SF1.png';
		// 		var urlProfile = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
		// 		imageURLs.push(urlTemplate);
		// 		imageURLs.push(urlProfile);
		//
		// 	// Do drawImage & fillText
		// 	function imagesAreNowLoaded(){
		// 		$('#canvasLoder').attr('style','display:none')
		// 		// ctx.font="700px sans-serif";
		// 		// ctx.fillStyle="#333333";
		// 		ctx.font = "90px Comic Sans MS";
		// 		ctx.fillStyle = "blue";
		// 		ctx.textAlign = "center";
		//
		// 		wTemplate = imgs[0].naturalWidth;
		// 		hTemplate = imgs[0].naturalHeight;
		// 		canvas.width	= mirror.width=wTemplate;
		// 		canvas.height	= mirror.height=hTemplate;
		//
		// 		// roundedImage(10, 10, 120,120, 10);
		// 		// ctx.clip();
		// 		ctx.drawImage(imgs[0],0,0);
		// 		ctx.drawImage(imgs[1],490,330,120,120);
		// 		ctx.fillText("<?php echo $nama_lengkap;?>", 250, 400);
		// 		ctx.fillText("<?php echo $userByFbId['no_wa'];?>", 250,420);
		// 		ctx.fillText("http://"+"<?php echo $userByFbId['username'];?>"+".sukses.family", 250,440);
		// 	}
		//
		// // the loaded images will be placed in imgs[]
		// 	var imgs=[];
		// 	var imagesOK=0;
		// 	startLoadingAllImages(imagesAreNowLoaded);
		//
		// 	function startLoadingAllImages(callback){ // When all images are loaded, run the callback (==imagesAreNowLoaded)
		// 		for (var i=0; i<imageURLs.length; i++) { // iterate through the imageURLs array and create new images for each
		// 			var img = new Image(); // create a new image an push it into the imgs[] array
		// 			imgs.push(img);
		//
		// 			img.onload = function(){// when this image loads, call this img.onload
		// 				imagesOK++; // this img loaded, increment the image counter
		// 				if (imagesOK>=imageURLs.length ) { // if we've loaded all images, call the callback
		// 					callback();
		// 				}
		// 			};
		//
		// 			img.onerror=function(){ // notify if there's an error
		// 				alert("failed load image");
		// 			}
		// 			img.src = imageURLs[i]; // set img properties
		// 		}
		// 	}

		// rounded shape
			// function roundedImage(x, y, width, height, radius) {
			//     ctx.beginPath();
			//     ctx.moveTo(x + radius, y);
			//     ctx.lineTo(x + width - radius, y);
			//     ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
			//     ctx.lineTo(x + width, y + height - radius);
			//     ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
			//     ctx.lineTo(x + radius, y + height);
			//     ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
			//     ctx.lineTo(x, y + radius);
			//     ctx.quadraticCurveTo(x, y, x + radius, y);
			//     ctx.closePath();
			// }

		//
		// mirror.addEventListener('contextmenu',function (e) {
		// 	var dataURL = canvas.toDataURL('image/png');
		// 	mirror.src = dataURL;
		// })
		//
		// var button = document.getElementById('downloadBtn');
		// button.addEventListener('click', function (e) {
		//     var dataURL = canvas.toDataURL('image/png');
		//     button.href = dataURL;
		// });
		//
		// document.getElementById('downloadBtn').addEventListener('click', function() {
		//     downloadCanvas(this, 'canvasQ', 'test.png');
		// 		alert('wkwkwkw');
		// }, false);

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
