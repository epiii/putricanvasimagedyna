<?php
include ("../conf.php");

$QryPrm = mysqli_query($con,"select * FROM parameter WHERE param1='type'");
echo "<table border=1 align=center>
	<tr style=background-color:#F8ED23>
		<td align=center> Nama </td>
		<td align=center> Param 1 </td>
	</tr>";
while ($data=mysqli_fetch_array($QryPrm))
echo "<tr>
		<td align=center> $data[nama] </td>
		<td align=center> $data[param1] </td>
		</tr>";
?>
<?php echo "</table>"; ?>
