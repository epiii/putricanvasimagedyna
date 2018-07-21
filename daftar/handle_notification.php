<?php
include "../conf.php";
require_once(dirname(__FILE__) . '/Veritrans.php');
Veritrans_Config::$isProduction = true;
Veritrans_Config::$serverKey = 'Mid-server-KTbscAPfafr37Q8wj3eMVzLO';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {


 try {
  $notif = new Veritrans_Notification();
} catch (Exception $e) {
  echo "Exception: ".$e->getMessage()."\r\n";
  echo "Notification received: ".file_get_contents("php://input");
  exit();
} 
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;
$rp = $notif -> gross_amount;
$wkt = $notif -> transaction_time;

/*
$transaction = "settlement";
$type = "atm";
$order_id = "10211507836106395_1";
$fraud = "ok";
$rp = "12000";
$wkt = "2018-02-27 13:44:24";
*/

//buat fungsi
$cbq = mysqli_query($con,"SELECT * FROM `pembayaran` WHERE `order_id` = '$order_id'");
$cek = mysqli_num_rows($cbq);


	$byr = mysqli_fetch_array($cbq);
	$tipe_byr =$byr['tipe_bayar'];
	$ms_sewa = $byr['ms_sewa'];
	function ubah_status($con,$cbq,$cek,$up_stat,$order_id,$type,$rp,$wkt,$transaction){
		if($cek == 0){
			mysqli_query($con,"INSERT INTO `pembayaran` (`id_bayar`, `time`, `status`, `payment_type`, `tipe_bayar`, `fb_id`, `amount`, `order_id`, `ms_sewa`) VALUES (NULL, '$wkt', '$transaction', '$type', 'new', 'new', '$rp', '$order_id', '6')");
			//echo mysqli_error();
		}else{
			mysqli_query($con,"UPDATE `pembayaran` SET `status` = '$up_stat', `time` = '$wkt', `payment_type`='$type' WHERE `order_id` = '$order_id'");
			//echo mysqli_error();
		}
	}

//fungsi update pengguna
function aktivasi($ms_sewa,$wkt,$con,$order_id,$tipe_byr){
	$idx = explode('_',$order_id);
	$idy = $idx[0];
	$tgl = date("Y-m-d", strtotime($wkt));
	if($tipe_byr == 'new'){
	$exp = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( $wkt ) ) . "+$ms_sewa month" ) );
	$que = "UPDATE `pengguna` SET `tgl_lunas` = '$tgl', `tgl_exp` ='$exp' WHERE `id_fb` = '$idy'";
	}else{
	
	$que = "UPDATE `pengguna` SET `tgl_lunas` = '$tgl', `tgl_exp` = DATE_ADD(`tgl_exp`, INTERVAL $ms_sewa month ) WHERE `id_fb` = '$idy'";
	mysqli_query($con,"UPDATE `upgrade` SET `tgl_lunas` = '$tgl' WHERE `idfb` = '$idy' AND `upgrade` ='$ms_sewa' AND `tgl_lunas` IS NULL");
	}
	
	mysqli_query($con,$que);
	//echo $que.'<br>'.$exp;
}

if ($transaction == 'capture') {
  // For credit card transaction, we need to check whether transaction is challenge by FDS or not
  if ($type == 'credit_card'){
    if($fraud == 'challenge'){
      // TODO set payment status in merchant's database to 'Challenge by FDS'
      // TODO merchant should decide whether this transaction is authorized or not in MAP
      //echo "Transaction order_id: " . $order_id ." is challenged by FDS";
	  ubah_status($con,$cbq,$cek,"challange CC",$order_id,$type,$rp,$wkt,$transaction);
      }
      else {
      // TODO set payment status in merchant's database to 'Success'
      //echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
	  ubah_status($con,$cbq,$cek,"KartuKredit Diterima",$order_id,$type,$rp,$wkt,$transaction);
	  aktivasi($ms_sewa,$wkt,$con,$order_id,$tipe_byr);
	  
      }
    }
  }
else if ($transaction == 'settlement'){
  // TODO set payment status in merchant's database to 'Settlement'
  //echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
	  ubah_status($con,$cbq,$cek,"aktifasi berhasil",$order_id,$type,$rp,$wkt,$transaction);
	  aktivasi($ms_sewa,$wkt,$con,$order_id,$tipe_byr);
  }
  else if($transaction == 'pending'){
  // TODO set payment status in merchant's database to 'Pending'
  //echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
  ubah_status($con,$cbq,$cek,"menunggu pembayaran",$order_id,$type,$rp,$wkt,$transaction);
  }
  else if ($transaction == 'deny') {
  // TODO set payment status in merchant's database to 'Denied'
  //echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
  ubah_status($con,$cbq,$cek,"pembayaran ditolak",$order_id,$type,$rp,$wkt,$transaction);
  }
  else if ($transaction == 'expire') {
  // TODO set payment status in merchant's database to 'expire'
  //echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
  ubah_status($con,$cbq,$cek,"pembayaran kadaluarsa",$order_id,$type,$rp,$wkt,$transaction);
  }
  else if ($transaction == 'cancel') {
  // TODO set payment status in merchant's database to 'Denied'
  //echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
  ubah_status($con,$cbq,$cek,"pembayaran dibatalkan",$order_id,$type,$rp,$wkt,$transaction);
}


} else {


    //
    // order_id=776981683&status_code=200&transaction_status=capture

    $order_id = $_GET['order_id'];
    $statusCode = $_GET['status_code'];
    $transaction  = $_GET['transaction_status'];


	if($transaction == 'capture') {
	  echo "<p>Transaksi berhasil.</p>";
	  echo "<p>Status transaksi untuk order id : " . $order_id;

	}
	// Deny
	else if($transaction == 'deny') {
	  echo "<p>Transaksi ditolak.</p>";
	  echo "<p>Status transaksi untuk order id .: " . $order_id;

	}
	// Challenge
	else if($transaction == 'challenge') {
	  echo "<p>Transaksi challenge.</p>";
	  echo "<p>Status transaksi untuk order id : " . $order_id;

	}
	// Error
	else {
	  echo "<p>Terjadi kesalahan pada data transaksi yang dikirim.</p>";
	  echo "<p>Status message: [$response->status_code] " . $transaction;
	}


}

?>