<?php
include "../conf.php";
$QryPrm = mysqli_query($con,"select * FROM parameter WHERE param1='harga' and param2='pyt'");
while ($data=mysqli_fetch_array($QryPrm)){
echo "<option value='".$data[param2]."'>".$data[param3]." ".rp($data[nama])."</option>";
}
?>
