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
	}else{
		$judul = 'Daftar Tagihan';
		$data_pgg = tagihan($con,$db,$usr,$halaman,$batas,$th1,$th2);
	}
}else{
	$judul ='';
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
			/* body {
				background-color: ivory;
			} */
			.outsideWrapper {
				width:125px;
				height:125px;
				/* margin:20px 60px; */
				/* border:1px solid blue; */

				border: 3px solid #73AD21;
			}
			.insideWrapper {
				width:100%;
				height:100%;
				position:relative;

				border: 3px solid #73AD21;
			}

			/* FB profile  */
			.coveredImage {
				width:100%;
				height:100%;
				top:0px;
				left:30px;
				position:absolute; /* overlay on other image */
				/* position:relative; */
				/* float: right; */

				border: 3px solid #73AD21;
			}
			.bottom-right {
			    position: absolute;
			    bottom: 8px;
			    right: 16px;
			}
			/* Centered text */
			.centeredText {
			    position: absolute;
			    top: 50%;
			    left: 50%;
			    transform: translate(-50%, -50%);

					border: 3px solid #73AD21;
			}
			.right-align {
			    position: absolute;
			    /* right: -50px; */
			    bottom: 0px;
					width: 100%;
			    /* right: 0px; */
			    /* width: 300px; */
			    border: 3px solid #73AD21;
			    /* padding: 10px; */
					color:white;
			}

			.right-float{
		    float: right;
		    width: 300px;
		    border: 3px solid #73AD21;
		    padding: 10px;
			}
			.coveringCanvas {
				width:100%;
				height:100%;
				position:absolute;
				top:0px;
				left:0px;
				background:url('SF1.png');
				/* background-color: rgba(255, 0, 0, .1); */
				/* background-color: rgba(255, 0, 0, .1); */
			}


			.divx {
			    border: 3px solid #4CAF50;
			    padding: 5px;
			}

			.img1 {
			    float: right;
			}

			.clearfix {
			    overflow: auto;
			}

			.img2 {
			    float: right;
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
													<?php echo $data_pgg;?>
                        </ul>
                        <div class="text-missing">
                            <h5 class="text-muted">Mari pantau gerakan digital marketing downline kita melalui facebook. Berteman dengan mereka dengan klik tombol facebook </h5>
                        </div>
                      </div>

											<div class="col-md-6 col-xs-12 text-center">
												<img id="canvasLoder" src="fb_loader.gif" alt="">
												<canvas id="canvasQ" xwidth="630" xheight="840"></canvas>
												<img id="canvasMirror">
												<a href="#" download="nama_file_ku.png" id="downloadBtn" class="btn btn-info"><i class="fa fa-download"></i> Download</a>
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
		        </ul>
		      </nav>
		      <div class="copyright pull-right">
		          &copy; DTS Jakarta
		      </div>
		  </div>
		</footer>

	</body>

	<script>
	// contoh 3
		// canvas related variables
			var canvas=document.getElementById("canvasQ");
			var mirror = document.getElementById('canvasMirror');

			var ctx=canvas.getContext("2d");
			var cw=canvas.width;
			var ch=canvas.height;

		// put the paths to your images in imageURLs[]
			var imageURLs	= [];
				var urlTemplate = 'SF1.png';
				var urlProfile = 'https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
				imageURLs.push(urlTemplate);
				imageURLs.push(urlProfile);

			// Do drawImage & fillText
			function imagesAreNowLoaded(){
				$('#canvasLoder').attr('style','display:none')
				// ctx.font="700px sans-serif";
				// ctx.fillStyle="#333333";
				ctx.font = "90px Comic Sans MS";
				ctx.fillStyle = "blue";
				ctx.textAlign = "center";

				wTemplate = imgs[0].naturalWidth;
				hTemplate = imgs[0].naturalHeight;
				canvas.width=mirror.width=wTemplate;
				canvas.height=mirror.height=hTemplate;

				ctx.drawImage(imgs[0],0,0);

				// roundedImage(10, 10, 120,120, 10);
				// ctx.clip();
				ctx.drawImage(imgs[1],490,330,120,120);
				ctx.fillText("<?php echo $nama_lengkap;?>", 250, 400);
				ctx.fillText("<?php echo $userByFbId['no_wa'];?>", 250,420);
				ctx.fillText("http://"+"<?php echo $userByFbId['username'];?>"+".sukses.family", 250,440);
				// ctx.fillText("http://"+"<?php echo $nama_lengkap;?>"+".sukses.family", 250,440);
			}

		// the loaded images will be placed in imgs[]
			var imgs=[];
			var imagesOK=0;
			startLoadingAllImages(imagesAreNowLoaded);

			function startLoadingAllImages(callback){ // When all images are loaded, run the callback (==imagesAreNowLoaded)
				for (var i=0; i<imageURLs.length; i++) { // iterate through the imageURLs array and create new images for each
					var img = new Image(); // create a new image an push it into the imgs[] array
					imgs.push(img);

					img.onload = function(){// when this image loads, call this img.onload
						imagesOK++; // this img loaded, increment the image counter
						if (imagesOK>=imageURLs.length ) { // if we've loaded all images, call the callback
							callback();
						}
					};

					img.onerror=function(){ // notify if there's an error
						alert("image load failed");
					}
					img.src = imageURLs[i]; // set img properties
				}
			}

		// rounded shape
			function roundedImage(x, y, width, height, radius) {
			    ctx.beginPath();
			    ctx.moveTo(x + radius, y);
			    ctx.lineTo(x + width - radius, y);
			    ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
			    ctx.lineTo(x + width, y + height - radius);
			    ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
			    ctx.lineTo(x + radius, y + height);
			    ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
			    ctx.lineTo(x, y + radius);
			    ctx.quadraticCurveTo(x, y, x + radius, y);
			    ctx.closePath();
			}

		/**
		 * Demonstrates how to download a canvas an image with a single
		 * direct click on a link.
		 */
		function doCanvas() {
		    /* draw something */
		    ctx.fillStyle = '#f90';
		    ctx.fillRect(0, 0, canvas.width, canvas.height);
		    ctx.fillStyle = '#fff';
		    ctx.font = '60px sans-serif';
		    // ctx.fillText('image doesn t exist :(', 10, canvas.height / 2 - 15);
		    ctx.font = '26px sans-serif';
		    // ctx.fillText('Click link below to save this as image', 15, canvas.height / 2 + 35);
		}

		/**
		 * This is the function that will take care of image extracting and
		 * setting proper filename for the download.
		 * IMPORTANT: Call it from within a onclick event.
		*/
		// function downloadCanvas(link, canvasId, filename) {
		//     link.href = document.getElementById(canvasId).toDataURL();
		//     link.download = filename;
		// }

		/**
		 * The event handler for the link's onclick event. We give THIS as a
		 * parameter (=the link element), ID of the canvas and a filename.
		*/
		mirror.addEventListener('contextmenu',function (e) {
			var dataURL = canvas.toDataURL('image/png');
			mirror.src = dataURL;
		})

		var button = document.getElementById('downloadBtn');
		button.addEventListener('click', function (e) {
		    var dataURL = canvas.toDataURL('image/png');
		    button.href = dataURL;
		});

		// document.getElementById('downloadBtn').addEventListener('click', function() {
		//     downloadCanvas(this, 'canvasQ', 'test.png');
		// 		alert('wkwkwkw');
		// }, false);

		/**
		 * Draw something to canvas
		 */
		// doCanvas();

	// contoh 1
		// var canvas = document.getElementById('myCanvas');
		// var context = canvas.getContext('2d');
		//
		// function loadImages(sources,callback) {
		// 	var images = {};
		// 	var loadedImages = 0;
		// 	var numImages = 0;
		//
		// 	for (var src in sources) {
		// 		numImages++;
		// 	}
		// 	for (var src in sources){
		// 		images[src]=new Image();
		// 		images[src].onload=function(){
		// 			if (++loadedImages >= numImages) {
		// 				callback(images);
		// 			}
		// 		};
		// 		images[src].src = sources[src];
		// 	}
		// }
		// var sources = {
		// 	backgroundx : 'SF1.png',
		// 	urlProfile : '<?php echo 'https://graph.facebook.com/'.$fbid.'/picture?type=large';?>',
		// 	// urlProfile : '../poto/299261963814087.jpg',
		// };
		// loadImages(sources, function (images) {
		// 	context.drawImage(images.backgroundx,0,0);
		// 	context.drawImage(images.urlProfile,415,330);
		// });

		// contoh 2
			// var canvas = document.getElementById('canvasKu');
			// var ctx = canvas.getContext('2d');
			//
			// var imgTemplate = new Image();
			// var imgProfile = new Image();
			// imgTemplate.src='SF1.png';
			// imgProfile.src='https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
			//
			// imgTemplate.onload = function(){
			// 	canvas.width = imgTemplate.naturalWidth
			// 	canvas.height = imgTemplate.naturalHeight
			// 	ctx.drawImage(imgTemplate, 0, 0);
			//
			// 	var fullName = 'bejo sugiantoro';
			// 	// var fullName = 'bejo sugiantoro';
			// 	ctx.font = '30px sans-serif';
			// 	ctx.fillText( fullName, canvas.width - (fullName.length * 15), canvas.height - 30 );
			// 	// ctx.fillText( 'test bos', canvas.width - (txt.length * 15), canvas.height - 30 );
			// 	// ctx.fillStyle = 'rgba(255, 255, 255, 0.25)';
			// 	// ctx.fillText( txt, canvas.width - (txt.length * 15) - 2, canvas.height - 32 )
			// }
			//
			// imgProfile.onload = function(){
			// 	canvas.width = imgProfile.naturalWidth
			// 	canvas.height = imgProfile.naturalHeight
			// 	ctx.drawImage(imgProfile, 30, 20);
			// }
			// img.src='https://graph.facebook.com/'+<?php echo $fbid; ?>+'/picture?type=large';
			// img.src = 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/130527/yellow-flower.jpg';


		// var canvas = document.getElementById('myCanvas');
    // var context = canvas.getContext('2d');
		//
    // var darthVader= 'http://www.html5canvastutorials.com/demos/assets/darth-vader.jpg';
    // var  yoda= 'http://www.html5canvastutorials.com/demos/assets/yoda.jpg';
		//
    // loadImages(sources, function(images) {
    //   context.drawImage(darthVader, 100, 30, 200, 137);
    //   context.drawImage(yoda, 350, 55, 93, 104);
    // });

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
		// Initialize the tour
		tour.init();
		// Start the tour
		tour.start();

		// (() => {
		// 	const mergeImage = ( id, imgsrc, txt, fbprof ) => {
		// 		const canvas = document.getElementById( id );
		//
		// 		if ( canvas.getContext ) {
		// 			const ctx = canvas.getContext( '2d' );
		// 			let img = new Image();
		// 			img.src = imgsrc;
		// 			img.onload = function () {
		// 				let imgWidth = img.width;
		// 				let imgHeight = img.height;
		// 				canvas.width = imgWidth;
		// 				canvas.height = imgHeight;
		//
		// 				ctx.drawImage( img, 0, 0 );
		// 				ctx.fillStyle = 'rgba(0, 0, 0, 0.25)';
		//
		// 				ctx.font = '30px sans-serif';
		// 				ctx.fillText( txt, canvas.width - (txt.length * 15), canvas.height - 30 );
		// 				ctx.fillStyle = 'rgba(255, 255, 255, 0.25)';
		// 				ctx.fillText( txt, canvas.width - (txt.length * 15) - 2, canvas.height - 32 )
		// 			}
		// 		}
		// 	}
		// 	mergeImage( 'mergedImageDisp', 'SF1.png', '<?php echo $nama_lengkap;?>', '<?php echo $nama_lengkap;?>');
		// 	// mergeImage( 'mergedImageDisp', 'SF1.png', 'ini nyoba bos' );
		// 	// mergeImage( 'canvas', '/staticImages/y.jpg', 'htmlstack.com' );
		// })()
		//
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
