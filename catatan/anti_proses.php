<?php
  include '../conf.php';
  include 'fungsi.php';
  session_start();
  
  $imgData = $_POST['photo'];
  $out='invalid_request';
  
  // check type png or not 
  if (strpos($imgdata, 'data:image/png;base64') == 0) {
  // upload file --------------------
    
    // file submitted
    $imgData = str_replace('data:image/png;base64,', '', $imgData);
    $imgData = str_replace(' ', '+', $imgData);
    $imgData = base64_decode($imgData);

    // data submitted
    $dataForm = explode('&',$_POST['dataForm']);
    
      $types  = explode('=',$dataForm[0]);
      $type   = $types[1];
      
      $idTemplates  = explode('=',$dataForm[1]);
      $idTemplate   = $idTemplates[1];
      
      $dir = '../uploads/'.$type.'_edit/';
      $file= $type.'_'.$_SESSION['FBID'].'.png';
      $fullpath = $dir.$file;
      // vd($dir);
    
    // delete image if exist
    if (file_exists($fullpath)) {
      unlink($fullpath);
    }

    // upload file 
    $uplo = file_put_contents($fullpath, $imgData, LOCK_EX);

    // if success upload file then "save data" to db
    if ($uplo) { 
    // save to db --------------------
      $s='UPDATE pengguna SET
            foto_'.$type.' ="'.$file.'",
            id_'.$type.'='.$idTemplate.'
          WHERE id_fb ="'.$_SESSION['FBID'].'"';
      $e=mysqli_query($con,$s);
      $out=$e?'success':'failed save db';
      
      // update profile picture with new framed - profile picture 
      if($type=='frame'){
        unset($_SESSION['FOTO_PROFIL']);
        $_SESSION['FOTO_PROFIL']=$file;
      }
    }else{
      $out='failed upload';
    }
  }  echo json_encode(['status'=>$out]);
?>
