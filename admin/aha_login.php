<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$nonce = isset($_REQUEST['submit_api_key']) ? sanitize_text_field($_REQUEST['submit_api_key']) : '';  
if(wp_verify_nonce($nonce, 'action_api_key'))
{ 
	$aha_post_process = sanitize_text_field($_POST['process']);
	if($aha_post_process == 'logout'){
		unset($_SESSION['user_data']); die;
	}
	require  dirname (dirname( __FILE__ ) )  .'/functions/aha_curl_function.php';
	$Curl = new Aha_curl_function;
	$books_data= $Curl->aha_active_key() ;
	if($aha_post_process == 'login'){
		$url ='https://www.ahathat.com/plugin-setup/login';
		$user_data = json_decode($Curl->aha_login($books_data->api_key , json_encode($_POST),$url)) ;
		if($user_data[0] != 'error')
		{
			if(session_id() == '' || !isset($_SESSION)) {
		        session_start();
			}
			$_SESSION['user_data'] = array();
			$_SESSION['user_data'] = $user_data[0] ;
			exit;
		}else{
			echo $user_data[0] ; exit;
		}
	}else{
		$url ='https://www.ahathat.com/plugin-setup/signup';
		$data = 'name='.sanitize_text_field($_POST['name']).'&email='.sanitize_email($_POST['email']).'&password='.sanitize_text_field($_POST['password']).'&cpassword='.sanitize_text_field($_POST['cpassword']).'&accept='.sanitize_text_field($_POST['accept']).'&website='.site_url().'&key='.$books_data->api_key;
		$user_data = $Curl->aha_signup($books_data->api_key , $data,$url) ;
		echo $user_data; exit;
	}
}








