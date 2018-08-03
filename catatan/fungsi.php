<?php
	session_start();
// debug variable
	function pr($var){
		echo "<pre>";
			print_r($var);
		echo"</pre>";
		exit();
	}

// debug variable
	function vd($var){
		echo "<pre>";
			var_dump($var);
		echo"</pre>";
		exit();
	}

//VALIDASI
function cek($con,$db,$idfb){

	$lunas = TRUE; $blm_exp = TRUE;
	$qu = mysqli_query($con,"select username,tgl_exp,web_training,marketing,tgl_lunas,id_fb,nama_dpn,nama_blk from pengguna WHERE id_fb ='$idfb'");
	$data = mysqli_fetch_array($qu);
//`id_fb`, `nama_dpn`, `nama_blk`, `nama_fb`, `no_wa`, `gender`, `email`, `tgl_exp`, `nominal`, `tgl_join`, `username`, `tgl_lunas`, `id`
	if(isset($data['username'])){

		if(is_null($data['tgl_lunas'])){
			$lunas = FALSE;
		}
		$skr = strtotime("today");
		$exp = strtotime($data['tgl_exp']);
		if($skr >= $exp){
			$blm_exp = FALSE;
		}
		if($data['id_fb'] AND $lunas AND $blm_exp){
			$usr = $data['username'];
		}else{
			$usr ='no';
		}
	}else{
		$usr = 'no';
	}
	return $usr;
}
	function status($pay,$tglunas,$sewa){
		//BUY paket bisnis
		if(is_null($pay)){
			//jika blm lunas
			if(is_null($tglunas)){
			$stat = "Bisnis Tunggu Transfer";
			}else{
			$stat ="Daftar Paytren";
			}
		}else{
			if(is_null($tglunas)){
			$stat =$sewa."Replika Tunggu Transfer";
			}else{
			$stat =$sewa."Replika Aktif";
			}
		}
		return $stat;
	}

//LIHAT TAGIHAN REPLIKA
function tagihan($con,$db,$usr,$halaman,$batas,$th1,$th2){
	$q= "SELECT `username`,`no_wa`,`tgl_exp`,`id_fb`,`nama_fb`,`nama_dpn`,`paytren_id`,`nominal`,`tgl_lunas` FROM `$db`.`pengguna`  WHERE `referal` = '$usr' AND `tgl_lunas` IS NULL LIMIT $halaman,$batas";
	$quq = mysqli_query($con,$q);
	$jmldata    = mysqli_num_rows($quq);
	$jmlhalaman = ceil($jmldata/$batas);
	$hsl =''; $tabelh='';
	while($pgg = mysqli_fetch_array($quq)){
		$angg = $pgg['nominal'];
		if($angg + 40000 >= $th2){
			$sewa ='2th ';
		}else if($angg + 40000 >= $th1){
			$sewa ='1th ';
		}else{
			$sewa ='6bln ';
		}
		$hsl = $hsl.'
				<li>
					<div class="row">
						<div class="col-md-2 col-md-offset-1 col-xs-5 col-xs-offset-1">
							<img src="../poto/'.$pgg['id_fb'].'.jpg" alt="Circle Image" class="img-circle img-no-padding img-responsive">
						</div>
						<div class="col-md-6 col-xs-4 description">
							<h5>'.$pgg['nama_fb'].'<br /><small>'.status($pgg['paytren_id'],$pgg['tgl_lunas'],$sewa).'</small></h5>
						</div>
						<div class="col-md-2 col-xs-2">
							<a class="btn btn-icon btn-danger" href="https://api.whatsapp.com/send?phone='.ke_wa($pgg['no_wa']).'&text=Hallo%20'.$pgg['nama_dpn'].'%2C%20"><i class="fa fa-phone-square"></i></a>
							<a class="btn btn-icon btn-info" href="https://www.facebook.com/'.$pgg['id_fb'].'"><i class="fa fa-facebook"></i></a>
						</div>
					</div>
				</li>';
	}
	for($i=1;$i<=$jmlhalaman;$i++){
		if ($i != $halaman){
		 $a = '<li><a href="./anti.php?h='.$i.'">'.$i.'</a></li>';

		}
		else{
		 $a = '<li class="active"><a>'.$i.'</a></li>';
		}
		$tabelh=$tabelh.$a;
	}
	$q_u = mysqli_query($con,"SELECT `idfb`,`username`,`nominal_up`,`upgrade`,`bagi_hasil` FROM `upgrade` WHERE `referal` ='$usr' AND `tgl_lunas` IS NULL ");
	while($up = mysqli_fetch_array($q_u)){
		$hsl = $hsl.'
			<li>
				<div class="row">
					<div class="col-md-2 col-md-offset-1 col-xs-5 col-xs-offset-1">
						<img src="../poto/'.$up['idfb'].'.jpg" alt="Circle Image" class="img-circle img-no-padding img-responsive"/>
					</div>
					<div class="col-md-6 col-xs-4 description">
						<h5>'.$up['username'].'.sukses.family <br/><small>Upgrade Replika '.$up['upgrade'].' bulan</small></h5>
					</div>
					<div class="col-md-2 col-xs-2">
						<a class="btn btn-icon btn-info" href="https://www.facebook.com/'.$up['idfb'].'"><i class="fa fa-facebook"></i></a>
					</div>
				</div>
			</li>';
	}
	$halm = '<ul class="pagination pagination-danger">'.$tabelh.'</ul>';
	return $hsl.$halm;
	//return $q;
}

function kunjungan($con,$db){
	$s 			= "select count(nominal) from pengguna where date(tgl_lunas)=date(now())";
	$Qry2 	= mysqli_query($con,$s);
	$data2 	= mysqli_fetch_row($Qry2);
	// print_r($data2);
	// exit();
	$hsl = $hsl.'
		<div class="row">
			<div class="col-lg-12 col-xs-12">
				<div class="small-box" style="background-color:#af9c8b; color:#FFFFFF">
					<div class="inner" style="text-align:center">
						<h3>'.$data2[0].'<sup style="font-size: 18px"> Orang</sup></h3>
					</div>
					<div class="small-box-footer">
					  Jumlah Kunjungan Hari ini
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-xs-12">
				<div class="small-box" style="background-color:#af9c8b; color:#FFFFFF">
					<div class="inner" style="text-align:center">
						<h3>'.$data2[0].'<sup style="font-size: 18px"> Orang</sup></h3>
					</div>
					<div class="small-box-footer">
					  Jumlah Kunjungan Bulan ini
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-xs-12">
				<div class="small-box" style="background-color:#af9c8b; color:#FFFFFF">
					<div class="inner" style="text-align:center">
						<h3>'.$data2[0].'<sup style="font-size: 18px"> Orang</sup></h3>
					</div>
					<div class="small-box-footer">
					  Jumlah Total Kunjungan
					</div>
				</div>
			</div>
		</div>';
	return $hsl;
}

function profmote($con,$db){
	$hsl = $hsl.'
		<div class="row">
			<div class="col-lg-12 col-xs-12">
				<div id="exTab1" class="containerx">
					<ul  class="nav nav-pills">
						<li class="active"> <a  href="#1a" data-toggle="tab">Promote</a></li>
						<li><a href="#2a" data-toggle="tab">Profile</a></li>
					</ul>

					<div class="tab-content xclearfix">

					  <div class="tab-pane active" id="1a">
							<br>
							<div id="frameAlert" style="display:none;" class="alert alert-success alert-dismissible fade in">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<strong>Success!</strong> ,profile has been set
							</div>
							<form id="promoteForm" onsubmit="promoteSave();return false;" enctype="multipart/form-data">
								<div id="imageOptions" class="form-group">';
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
											$hsl.='<img onclick="promoteOptionClick(this);"
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
												promote_id="'.$res['id_param'].'"

											/>';
											$i++;
										}
								$hsl.='</div>
								<div class="form-group">
									<div>
										<img id="promotePreview" width="250" src="../uploads/no_preview.png" alt="" />
									</div>
									<input type="hidden" name="tipe" id="tipe" value="promote" >
									<input type="hidden" name="id_promote" id="id_promote" >

									<div class="divCanvas">
										<a id="promoteDownload" style="display:none;" href="#" class="downloadBtn">Download</a>
										<canvas id="promoteCanvas"></canvas>
									</div>
								</div>

								<button class="btn btn-primary" type="submit" name="submit" id="image-submit">Save</button>
							</form>
						</div>

						<div class="tab-pane" id="2a">
							<br>
							<form id="frameForm" onsubmit="frameSave();return false;" method="post" enctype="multipart/form-data">
								<div id="imageOptions" class="form-group">';
										$getUserData= getUserByFbId($con,$_SESSION['FBID']);
										$frameId = $getUserData['id_frame'];
										$sql2 = 'SELECT * FROM parameter WHERE param1 = "frame" ORDER BY nama ASC';
										$exe2 = mysqli_query($con,$sql2);
										$i2=0;
										while ($res2=mysqli_fetch_assoc($exe2)) {
												$hsl.='<img onclick="frameOptionClick(this);"
													src="../uploads/frame_template/'.$res2['nama'].'"
													class="imageOption
													'.($res2['id_param']==$frameId?'active':'').'"
													tipe="'.$res2['param1'].'"
													data-design="0"
													frame_id="'.$res2['id_param'].'"
												/>';
												$i2++;
										}
								$hsl.='</div>
								<div class="form-group">
									<div>
										<img id="framePreview" width="250" src="../uploads/no_preview.png" alt="" />
									</div>
									<input type="hidden" name="tipe" id="tipe" value="frame" >
									<input type="hidden" name="id_frame" id="id_frame" >

									<div class="divCanvas">
										<a id="frameDownload" style="display:none;" href="#" class="downloadBtn">Download</a>
										<canvas id="frameCanvas"></canvas>
									</div>
								</div>

								<button class="btn btn-primary" type="submit" name="submit" xid="image-submit">Save</button>
							</form>
						</div>

					</div>
			  </div>
		  </div>
	  </div>
		';
		// pr($hsl);
	return $hsl;
}

function pembayaran($con,$db,$usr,$th1,$th2,$k_2th,$k_1th,$k_6bln){
	$q= "SELECT `username`,`no_wa`,`tgl_exp`,`id_fb`,`nama_fb`,`nama_dpn`,`paytren_id`,`nominal`,`tgl_lunas`
		FROM `$db`.`pengguna`
		WHERE `referal` = '$usr' AND `tgl_lunas` IS NOT NULL AND `web_training` ='tidak' ";
	$quq = mysqli_query($con,$q);
	$hsl =''; $tabelh=''; $komisi_n =0;

	while($pgg = mysqli_fetch_array($quq)){
		$angg = $pgg['nominal'];
		if($angg + 40000 >= $th2){
			$sewa ='2th ';
			$komisi = $k_2th;
		}else if($angg + 40000 >= $th1){
			$sewa ='1th ';
			$komisi = $k_1th;
		}else{
			$sewa ='6bln ';
			$komisi = $k_6bln;
		}
		$komisi_n = $komisi_n+$komisi;
		$rp_nom = $komisi/1000;
		$hsl = $hsl.'
								<li>
                                    <div class="row">
                                        <div class="col-md-2 col-md-offset-1 col-xs-5 col-xs-offset-1">
										<span class="label label-success notification-bubble"> '.$rp_nom.'K </span>
                                            <img src="../poto/'.$pgg['id_fb'].'.jpg" alt="Circle Image" class="img-circle img-no-padding img-responsive"/>
                                        </div>
                                        <div class="col-md-6 col-xs-4 description">
                                            <h5>'.$pgg['nama_fb'].'<br /><small>'.status($pgg['paytren_id'],$pgg['tgl_lunas'],$sewa).'</small></h5>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <a class="btn btn-icon btn-danger" href="https://api.whatsapp.com/send?phone='.ke_wa($pgg['no_wa']).'&text=Hallo%20'.$pgg['nama_dpn'].'%2C%20"><i class="fa fa-phone-square"></i></a>
											<a class="btn btn-icon btn-info" href="https://www.facebook.com/'.$pgg['id_fb'].'"><i class="fa fa-facebook"></i></a>
                                        </div>
                                    </div>
                                </li>
			';
	}
	$q_u = mysqli_query($con,"SELECT `tgl_lunas`,`idfb`,`username`,`nominal_up`,`upgrade`,`bagi_hasil`,`basil_dibayar`
			FROM `upgrade`
			WHERE (`referal` ='$usr' AND `tgl_lunas` IS NOT NULL AND `basil_dibayar` = 'audit')
				OR (`referal` ='$usr' AND `tgl_lunas` IS NOT NULL AND `basil_dibayar` is null)");
		while($up = mysqli_fetch_array($q_u)){
			$k_up = $up['bagi_hasil'];
			$denom_k = $k_up/1000;
			$komisi_n = $komisi_n + $k_up;
			$hsl = $hsl.'
								<li>
                                    <div class="row">
                                        <div class="col-md-2 col-md-offset-1 col-xs-5 col-xs-offset-1">
										<span class="label label-success notification-bubble"> '.$denom_k.'K </span>
                                            <img src="../poto/'.$up['idfb'].'.jpg" alt="Circle Image" class="img-circle img-no-padding img-responsive"/>
                                        </div>
                                        <div class="col-md-6 col-xs-4 description">
                                            <h5>'.$up['username'].'.sukses.family <br/><small>Upgrade Replika '.$up['upgrade'].' bulan</small></h5>
                                        </div>
                                        <div class="col-md-2 col-xs-2">

											<a class="btn btn-icon btn-info" href="https://www.facebook.com/'.$up['idfb'].'"><i class="fa fa-facebook"></i></a>
                                        </div>
                                    </div>
                                </li>
			';
	}

	$hsl =$hsl.'
			<form role="search" class="form-inline" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control border-input" placeholder="Claim Mitra" name="claim">
                </div>
                <button type="submit" class="btn btn-icon btn-fill"><i class="fa fa-search"></i></button>
				<p><small>Terkadang karena sinyal yang kurang stabil menyebabkan mitra yang mendaftar replika lewat kita tidak tercatat, anda bisa claim mitra tersebut dengan memasukan alamat replika mitra tanpa "http://" </small></p>
            </form>
			';
	return array('tabel' => $hsl, 'komisi' => $komisi_n);
}

function calon_mitra($con,$db,$usr,$halaman,$batas){
	$hll = $halaman * $batas;
	$q= "SELECT `nama`,`nope`,`tgl_daftar` FROM `$db`.`pendaftar` WHERE `asal`='$usr' AND `nope` !='' GROUP BY `nope` LIMIT $hll,$batas";
	$qu = mysqli_query($con,$q); $hsl ='';$tabelh='';
	$jmldata    = mysqli_num_rows($qu);
	$jmlhalaman = ceil($jmldata/$batas);
	while($pgg = mysqli_fetch_array($qu)){
		$hsl = $hsl.'
								<li>
                                    <div class="row">
                                        <div class="col-md-2 col-md-offset-1 col-xs-5 col-xs-offset-1">
                                            <img src="../daftar/oge.png" alt="Circle Image" class="img-circle img-no-padding img-responsive">
                                        </div>
                                        <div class="col-md-6 col-xs-4 description">
                                            <h5>'.$pgg['nama'].' | '.$pgg['nope'].'<br /><small> '.$pgg['tgl_daftar'].'</small></h5>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <a class="btn btn-icon btn-danger" href="https://api.whatsapp.com/send?phone='.ke_wa($pgg['nope']).'&text=Hallo%20'.$pgg['nama'].'%2C%20"><i class="fa fa-phone-square"></i></a>

                                        </div>
                                    </div>
                                </li>
			';

	}
	for($i=0;$i<=$jmlhalaman;$i++){
		if ($i != $halaman){
		 $a = '<li><a href="./anti.php?t=mt&h='.$i.'">'.$i.'</a></li>';

		}
		else{
		 $a = '<li class="active"><a>'.$i.'</a></li>';
		}
		$tabelh=$tabelh.$a;

	}
	$hter = $jmlhalaman +1;
	if ($hter != $halaman){
		 $a = '<li><a href="./anti.php?t=mt&h='.$hter.'">'.$hter.'</a></li>';

		}
		else{
		 $a = '<li class="active"><a>'.$hter.'</a></li>';
		}
	$tabelh=$tabelh.$a;
	$halm = '<ul class="pagination pagination-info">'.$tabelh.'</ul>';
	return $hsl.$halm;

}
function pencairan($con,$usr,$fbid){
	//cek no_rek
	$nm = 'dbank_'.$usr;
	$akunb = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `parameter` WHERE `nama`='$nm'"));
	$akunbrek = explode('_',$akunb['param1']);
	$t_riwayat=''; $ada_norek='no'; $tabel=''; $no=1;
	if($akunb['nama']){
	$ada_norek ='ya';

	//tabel riwayat
	$qri = mysqli_query($con,"SELECT `nominal`,`status`,`group_pencairan`,`bukti_transfer` FROM `pencairan` WHERE `username`='$usr' ORDER BY `pencairan`.`id_pencairan` ASC");
	while($data = mysqli_fetch_array($qri)){
		$tabel = $tabel.'<tr>
		<td>'.$no.'</td>
		<td>'.$data['group_pencairan'].'</td>
		<td>'.rp($data['nominal']).'</td>
		<td>'.$data['status'].'</td>
		<td>'.$data['bukti_transfer'].'</td>
		</tr>';
		$no++;
	}
	$t_riwayat ='
	<div class="row">
                       <div class="col-md-6 col-xs-12 text-center" >
					   <div class="info info-horizontal">
                            <div class="icon">

                            </div>
                            <div class="description">
                                <p>'.$akunb['param2'].' | '.$akunbrek[1].' | a.n '.$akunb['param3'].'
                            </div>
                       </div>
						<div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Periode</th>
                                    <th>Pencairan</th>
                                    <th>Status</th>
									<th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
							'.$tabel.'

                             </tbody>
                        </table>
                        </div>
					   </div>
					</div>
	';
	}
	//tampilkan riwayat
	return array('norek' => $ada_norek, 'tabel_riwayat' => $t_riwayat);
}

function getUserByFbId($conn,$fbid){
	if (!isset($fbid) || !is_numeric($fbid)) {
		return 'invalid fb id';
	} else {
		$s = 'SELECT * FROM pengguna WHERE id_fb='.$fbid;
		$e = mysqli_query($conn,$s);
		$n = mysqli_num_rows($e);
		if($n<=0){
			return 'fb id is not found';
		} else {
			$r=mysqli_fetch_assoc($e);
			return $r;
		}
	}
}

function isFbProfileExist($conn,$fbid){
	if (!isset($conn) && !isset($fbid)) {
		return 'invalid parameter or undefined paramater isFbProfileExist';
	} else {
		$s = 'SELECT * FROM pengguna WHERE id_fb='.$fbid;
		$e = mysqli_query($conn,$s);
		$n = mysqli_num_rows($e);
		// vd($n);
		if($n<=0){
			return 'FB ID is not found';
		} else {
			$r=mysqli_fetch_assoc($e);
			return is_null($r['img_profile'])?'new user':'old user';
		}
		// return true;
	}
}

?>
