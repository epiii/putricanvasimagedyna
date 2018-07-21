<?php
if (!empty($_GET['q'])){
	if (ctype_digit($_GET['q'])) {
		include '../conf.php';
		$idk = $_GET['q'];
		$query = mysqli_query($con,"select * from parameter WHERE param1='harga' and param2='$idk'");
    echo"<option selected value=''>- Pilih Keanggotaan -</option>";
		while($d = mysqli_fetch_array($query)){
			echo "<option value='".$d[param2]."'>".rp($d[nama])."</option>";
		}
	}
}
?>
