<?php
session_start();
ini_set('allow_url_fopen' , 1);
// added in v4.0.0
$refr ='mandiri';
if(isset($_GET['q'])){
	$refr = $_GET['q'];
}
$_SESSION['REF'] =$refr;
$s = $_GET['s'];
$_SESSION['STATUS'] =$s;
$lf = "?s=$s&q=$refr";
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication( '530503727291595','8b78fc10fb8fd9743d2b182f66cb5e68' );
// login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper('http://sukses.family/daftar/fbconfigb.php'.$lf );
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me?fields=id,name,gender,email' );
  $response = $request->execute();
  // get response
  $array = $response->getResponse();
  $pic = $array->picture->data->url;
  
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
		$fgen = $graphObject->getProperty('gender');
	/* ---- Session Variables -----*/
	    $_SESSION['FBID'] = $fbid;           
        $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;
		$_SESSION['GENDER'] = $fgen;
    /* ---- header location after session ----*/
	//print_r($graphObject);
	//echo $pic;
 header("Location: http://sukses.family/bisnis/register.php");
 //echo $_SESSION['FULLNAME'];
 //print_r($_SESSION);
} else {
  $loginUrl = $helper->getLoginUrl();
 header("Location: ".$loginUrl);
}
?>