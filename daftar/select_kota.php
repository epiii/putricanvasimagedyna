<?php
if (!empty($_GET['q'])){
	if (ctype_digit($_GET['q'])) {
		include '../conf.php';
		$idk = $_GET['q'];
		$query = mysqli_query($con,"SELECT * FROM wilayah WHERE LEFT(kode,2)='$idk' AND CHAR_LENGTH(kode)='5' ORDER BY nama");
		echo"<option selected value=''>Pilih Kota/Kab</option>";
		while($d = mysqli_fetch_array($query)){
			echo "<option value='".$d['kode']."'>".$d['nama']."</option>";
		}


	}
}

if (empty($_GET['kel'])){

	if (!empty($_GET['kec'])){
		
			$idk = $_GET['kec'];
		include '../conf.php';
			$query = mysqli_query($con, "SELECT * FROM wilayah WHERE LEFT(kode,5)='$idk' AND CHAR_LENGTH(kode)='8' ORDER BY nama");
			echo"<option selected value=''>Pilih Kecamatan</option>";
			while($d = mysqli_fetch_array($query)){
				echo "<option value='".$d['kode']."'>".$d['nama']."</option>";
			}
		
	}
} else {
	
		include '../conf.php';
		$idk =$_GET['kel'];
			$query = mysqli_query($con,"SELECT * FROM wilayah WHERE LEFT(kode,8)='$idk' AND CHAR_LENGTH(kode)='13' ORDER BY nama");
			echo"<option selected value=''>Pilih Kelurahan/Desa</option>";
			while($d = mysqli_fetch_array($query)){
				echo "<option value='".$d['kode']."'>".$d['nama']."</option>";
			}
	
}
?>
