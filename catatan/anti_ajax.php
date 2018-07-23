<?php
require_once '../conf.php';
require_once 'fungsi.php';

$isRequest=false;

if (isset($_POST['mode'])) {
	$isRequest=true;
	$returns = [];
	$returns['getparam']=false;

	switch ($_POST['mode']) {
		case 'getImgTemplates':
			if (isset($_POST['tipe'])) {
				$returns['getparam']=true;
        $sql = ' SELECT * FROM parameter WHERE param1 = "'.$_POST['tipe'].'" ORDER BY nama ASC';
				$exe = mysqli_query($con,$sql);
			// pr($exe);

				if (!$exe) { // failed query
					$returns['queried'] = false;
				}else{ // success query
					$returns['queried'] = true;
					$returns['total']   = mysqli_num_rows($exe);

					// pr($res);
					while ($res=mysqli_fetch_assoc($exe)){
            // pr($res);
            $returns['data'][]=$res;
					}
				}
			}
		break;

		case 'view':
			// code here
		break;

		case 'create':
			$sql='INSERT INTO ajax SET
				nama   ="'.$_POST['nama'].'",
				no_tlp ="'.$_POST['no_tlp'].'",
				jenis  ="'.$_POST['jeniscombo'].'",
				harga  ="'.$_POST['hargacombo'].'"';
			$exe = mysqli_query($con,$sql);
			// pr($sql);
			$returns['success']=!$exe?false:true;
		break;

		case 'edit':
			// code here
		break;

		case 'delete':
			// code here
		break;

		default:
			// code here
		break;
	}

}

echo json_encode([
	'request' =>$isRequest,
	'returns' =>$returns
]);

?>
