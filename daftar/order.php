<?php
session_start();  date_default_timezone_set('Asia/Jakarta');
include ("../conf.php");
include ("ListDna.php");

	$usernm='';
	$wa_lengkap='';
$qsq = mysqli_query($con,"select param1 FROM parameter WHERE nama='jml_user'");
$no = mysqli_fetch_array($qsq);
$sq = $no[param1];

//CEK validasi
if(isset($_POST["fbid"])){
	$fbid = $_POST["fbid"];
	$id = $fbid;
	$nm1 = $_POST["nama1"];
	$nm2 = $_POST["nama2"];
	$nm = $_POST["nm"];
	$mail = $_POST["mail"]; $gret = "Selamat datang ".$nm;
	$gend = $_POST["gend"];
	$nope = $_POST["nope"];
	$angg =$_POST["angg"];
	$negara =$_POST["negara"];


	$ref = $_POST["ref"];
	$NO_ADMIN =$_POST["ADMIN"];
	$WA_ADMIN = KE_WA($NO_ADMIN);
	$vp = $_POST["vp"];
	$jaguar = $_POST["jaguar"];
/*
	function tipe_f($con,$db,$ref){
		if ($ref != "mandiri") {
			$query = mysqli_query($con, "select mlm_type from pengguna where username='$ref'");
			while($b=mysqli_fetch_row($query)){
				$tipe=$b[0];
			}
		}
		return $tipe;
	}

	echo "Ini ".tipe_f($tipe)." dan nominal : ".$angg;
*/

	function validasi($con,$db,$angg,$jaguar,$nope,$nm1,$id){
		$er =''; $er_rp=''; $er_u='';
		if($angg == "no" OR $angg== '-6500'){
			$er = $er.'1';
		}
		if($jaguar ==''){
			$er = $er.'2';
		}

		if($nope[0] !='0' OR strlen($nope) > 13){
			$er = $er.'5';
		}
		if(preg_match("/^[a-zA-Z]+$/", $nm1) == 0) {
		// string only contain the a to z , A to Z, 0 to 9
			$er = $er.'6';
		}


	//cek fb_id udah terdaftar blm
		$q19 = mysqli_query($con,"select nominal,username FROM pengguna WHERE id_fb ='$id'");
		$usr_cek9 = mysqli_fetch_array($q19);
		if($usr_cek9){
			$er_u = '&er_u='.$usr_cek9[username];
			$er_rp = '&er_rp='.$usr_cek9[nominal];
			$er = $er.'4';
		}
	//keluarkan EROR
		return 'er='.$er.$er_u.$er_rp;
	}

//cek USERNAME udah ada di DB blm
	function cek_u($con,$db,$nm1){
		$q1 = mysqli_query($con,"select nama_dpn FROM pengguna WHERE nama_dpn='$nm1'");
		$usr = mysqli_num_rows($q1);
		if($usr <= 0){
			$usernm = kecil($nm1);
		}else{
			$usernm = kecil($nm1.$usr);
		}
		return $usernm;
	}
	//tebak lama anggota
		if($angg + 40000 >= $th2){
			$sewa =24;
		}else if($angg + 40000 >= $th1){
			$sewa =12;
		}else{
			$sewa =6;
		}
	//kurangi nominal klo
		$notif = '';

	function clean($string) {
	   $string = str_replace("'", ' ', $string);
	   $string = str_replace(' ', '-', $string);
	   preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	   return str_replace('-', ' ', $string);
	}

	function tipe_f($con,$db,$ref){
		if ($ref != "mandiri") {
			$query = mysqli_query($con, "select mlm_type from pengguna where username='$ref'");
			while($b=mysqli_fetch_row($query)){
				$tipe=$b[0];
			}
		}
		return $tipe;
	}

	function dna($con,$db,$ref){
		if ($ref == "mandiri") {
			$Lvl="1";
			$Seq="Angka";
			$IdDna="AAA";
			$SeqOrtu="Huruf";
			$QryAnk = mysqli_query($con, "select count(dna_id) from pengguna where referal='$ref'");
		} else {
			$qry = mysqli_query($con, "select * from pengguna where username='$ref'");
			while($baris=mysqli_fetch_row($qry)){
				$Lvl=$baris[20]+1; //dari field dna_level
				$SeqOrtu=$baris[19]; //dari field dna_seq
				$IdDna=$baris[18]; //dari field dna_id
			}
			$QryAnk = mysqli_query($con, "select count(dna_id) from pengguna where left(dna_id,(select length(dna_id) from pengguna where username='$ref'))=(select dna_id from pengguna where username='$ref') and length(dna_id)=((select length(dna_id) from pengguna where username='$ref')+3)");
		}

		while($a=mysqli_fetch_row($QryAnk)){
			$BnykAnk=$a[0];
		}

		if ($Lvl % 2 == 1) {
			$Seq="Angka";
		} else {
			$Seq="Huruf";
		}

		$IdDna=$IdDna.dnaList($BnykAnk+1, $Seq);
/*
		if (strtolower($SeqOrtu) == "angka") {
			if ($BnykAnk == 0) {
				$IdDna=$IdDna."AAA";
			} else if ($BnykAnk == 1){
				$IdDna=$IdDna."BBB";
			} else if ($BnykAnk == 2){
				$IdDna=$IdDna."CCC";
			} else if ($BnykAnk == 3){
				$IdDna=$IdDna."DDD";
			}	else if ($BnykAnk == 4){
				$IdDna=$IdDna."EEE";
			} else if ($BnykAnk == 5){
				$IdDna=$IdDna."FFF";
			} else if ($BnykAnk == 6){
				$IdDna=$IdDna."GGG";
			} else if ($BnykAnk == 7){
				$IdDna=$IdDna."HHH";
			}	else if ($BnykAnk == 8){
				$IdDna=$IdDna."III";
			} else if ($BnykAnk == 9){
				$IdDna=$IdDna."JJJ";
			} else if ($BnykAnk == 10){
				$IdDna=$IdDna."KKK";
			} else if ($BnykAnk == 11){
				$IdDna=$IdDna."LLL";
			}	else if ($BnykAnk == 12){
				$IdDna=$IdDna."MMM";
			} else if ($BnykAnk == 13){
				$IdDna=$IdDna."NNN";
			} else if ($BnykAnk == 14){
				$IdDna=$IdDna."OOO";
			} else if ($BnykAnk == 15){
				$IdDna=$IdDna."PPP";
			}	else if ($BnykAnk == 16){
				$IdDna=$IdDna."QQQ";
			} else if ($BnykAnk == 17){
				$IdDna=$IdDna."RRR";
			}	else if ($BnykAnk == 18){
				$IdDna=$IdDna."SSS";
			} else if ($BnykAnk == 19){
				$IdDna=$IdDna."TTT";
			} else if ($BnykAnk == 20){
				$IdDna=$IdDna."UUU";
			} else if ($BnykAnk == 21){
				$IdDna=$IdDna."VVV";
			}	else if ($BnykAnk == 22){
				$IdDna=$IdDna."WWW";
			} else if ($BnykAnk == 23){
				$IdDna=$IdDna."XXX";
			} else if ($BnykAnk == 24){
				$IdDna=$IdDna."YYY";
			} else if ($BnykAnk == 25){
				$IdDna=$IdDna."ZZZ";
			} else if ($BnykAnk == 26){
				$IdDna=$IdDna."AAB";
			}	else if ($BnykAnk == 26){
				$IdDna=$IdDna."AAC";
			} else if ($BnykAnk == 27){
				$IdDna=$IdDna."AAD";
			} else if ($BnykAnk == 28){
				$IdDna=$IdDna."AAE";
			} else if ($BnykAnk == 29){
				$IdDna=$IdDna."AAF";
			} else if ($BnykAnk == 30){
				$IdDna=$IdDna."AAG";
			}
		} else {
			for ($loop=0; $loop < 30; $loop++) {
				if ($loop > 8) {
					if ($BnykAnk == $loop) {
						$IdDna=$IdDna."0".strval($loop+1);
					}
				} else {
					if ($BnykAnk == $loop) {
						$IdDna=$IdDna."00".strval($loop+1);
					}
				}
			}
		}
*/

		return array($Lvl,$Seq,$IdDna);
	}

	function simpan_no($con,$db,$nope,$negara){
		$digawal=substr($nope,1,1);
	  $digInti=substr($nope,1);
	  $nopeba=$negara.$digInti;
	  return $nopeba;
	}

//Simpan DATA $usernm = cek_u($con,$db,$nm1)
	function simpan($con,$db,$angg,$nm1,$fbid,$wa_admin,$nm2,$nm,$nope,$negara,$gend,$mail,$usernm,$vp,$jaguar,$ref,$sewa,$sq,$Seq,$Lvl,$IdDna,$tipe){
		$er_db='ok';
		// Object(mysqli), 'paytrwe2', 193512, 'lain', 'asdcoba', '12387', '6282156411621', 'asd', 'anes sd', '08123', 'male', '', 'asdcoba', 'asdw', 'bukan', 'iman'
		//INSERT INTO `paytrwe2`.`pengguna` (`id_fb`, `nama_dpn`, `nama_blk`, `nama_fb`, `no_wa`, `gender`, `email`, `tgl_exp`, `nominal`, `tgl_join`, `username`, `paytren_id`, `jaguar`,`referal`, `web_training`, `marketing`, `tgl_lunas`, `id`) VALUES ('12387', '12387', 'asd', 'anes sd', '08123', 'male', '', '$expired', '$angg', NOW(), '$usernm','$vp', '$jaguar','$ref', 'tidak', NULL, NULL, NULL);
		list($Lvl,$Seq,$IdDna)=dna($con,$db,$ref);
		$tipe=tipe_f($con,$db,$ref);
		$noba=simpan_no($con,$db,$nope,$negara);
	//buat timestamp exp
		$expired = date("Y-m-d h:i:s", strtotime( date( "Y-m-d h:i:s", strtotime( date("Y-m-d h:i:s") ) ) . "+".$sewa." month" ) );
		$fb1 = $fbid."_".$sq;

			$query_db ="insert into pengguna (id_fb, nama_dpn, nama_blk, nama_fb, no_wa, gender, email, tgl_exp, nominal, tgl_join, username, paytren_id, jaguar, referal, web_training, marketing, tgl_lunas, id, dna_id, dna_seq, dna_level, mlm_type)
								VALUES ('$fbid', '$nm1', '$nm2', '$nm', '$noba', '$gend', '$mail', '$expired', '$angg', NOW(), '$usernm','$vp', '$jaguar','$ref', 'tidak', NULL, now(), NULL, '$IdDna', '$Seq', '$Lvl', '$tipe')";
			mysqli_query($con,$query_db) or die($er_db=$query_db);
			mysqli_query($con,"insert into pembayaran (id_bayar, time, status, payment_type, tipe_bayar, fb_id, amount, order_id, ms_sewa) VALUES (NULL, NULL, NULL, NULL, 'new', '$fbid', '$angg', '$fb1','$sewa')");
			mysqli_query($con,"update parameter SET param1 = 'param1'+1 WHERE parameter.id_param = 1");


	//simpan foto
		/*$url = 'https://graph.facebook.com/'.$fbid.'/picture?type=large';*/

		$url = '../poto/'.$fbid.'.jpg';
		$img = '../poto/'.$fbid.'.jpg';
		file_put_contents($img, file_get_contents($url));

		return $er_db;

	}

//PROSESNYA
	$c_val = validasi($con,$db,$angg,$jaguar,$nope,$nm1,$id);

	if($c_val == 'er='){
		$usernm = cek_u($con,$db,$nm1);
		$tipe=tipe_f($con,$db,$ref);
		$simpan = simpan($con,$db,$angg,$nm1,$fbid,$wa_admin,clean($nm2),clean($nm),$nope,$negara,$gend,$mail,$usernm,clean($vp),$jaguar,$ref,$sewa,$sq,$Seq,$Lvl,$IdDna,$tipe);
		echo $simpan;
		$_SESSION['FBID']= $fbid;
		header("Location: pembayaran.php");
		//echo $simpan --> notif;
	}else{
		//ada error
		//echo $c_val;
		header("Location: lanjut.php?$c_val");
	}

}else{
	$fbid = '';
	$id = '';
	$nm1 = '';
	$nm2 = '';
	$nm = '';
	$mail = ''; $gret = "Selamat datang";
	$gend = '';
	$nope = '';
	$angg ='0';
	$bank = '';
	$ref = '';
	$no_admin ='';
	$wa_admin = '';
	$vp = '';
	$jaguar = '';
	header("Location: index.php");
	//echo "error";
}

?>
