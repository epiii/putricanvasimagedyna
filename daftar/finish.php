<?php include "../conf.php";
//baca order
if(isset($_GET['order_id'])){
//wa agen nya
$order = explode('_',$_GET['order_id']);
$fbid = $order[0];
$refx = mysqli_fetch_array(mysqli_query($con,"SELECT `referal`,`username` FROM `pengguna` WHERE `id_fb`='$fbid'"));
$ref = $refx['referal'];
$usr = $refx['username'];
$wax = mysqli_fetch_array(mysqli_query($con,"SELECT `no_wa` FROM `pengguna` WHERE `username`='$ref'"));
$wa = ke_wa($wax['no_wa']);
$msgx = "saya baru saja membuat pembayaran untuk web replika saya http://".$usr.".sukses.family mhn info lebih lanjut";
$msg = urlencode($msgx);
$link_wa ="https://api.whatsapp.com/send?phone=$wa&text=$msg";
header("Location: $link_wa");

}else{
	echo "Something Wrong!";
}
?>