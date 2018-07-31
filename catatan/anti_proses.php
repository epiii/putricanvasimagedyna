<?php
  include '../conf.php';
  include 'fungsi.php';
  session_start();

  $data = $_POST['photo'];
  list($type, $data) = explode(';', $data);
  list(, $data)      = explode(',', $data);
  $data = base64_decode($data);

  $dir = '../uploads/profile_frame/';
  $file= 'profile_frame_'.$_SESSION['FBID'].'.png';
  // $file= 'profile_frame_'.time().'.png';
  $uplo = file_put_contents($dir.$file, $data);

  $dataForm=explode('=',$_POST['dataForm']);
  $idFrame=explode('&',$dataForm[1]);

  if ($uplo) {
    $s='UPDATE pengguna SET
        foto_profil ="'.$file.'",
        id_frame ='.$idFrame[0].'
        WHERE id_fb="'.$_SESSION['FBID'].'"';
    $e=mysqli_query($con,$s);
    $out=$e?'success save db':'failed save db';
    $_SESSION['foto_profil']=$file;
  }else{
    $out='failed upload';
  }
  echo json_encode($out);
  // pr($uplo?'success':'failed');
  // pr($uplo?'success':'failed');
  // file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/photos/".time().'.png', $data);
  // die;
?>
