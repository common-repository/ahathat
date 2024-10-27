<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$prefix	= $wpdb->prefix ;
$id = sanitize_text_field($_POST['id']);
if($id != '')
{	
	require  dirname (dirname( __FILE__ ) )  .'/functions/aha_curl_function.php' ;
	$Curl = new Aha_curl_function;
	$user_ip  = $Curl->aha_getUserIP() ;
	$fb_d = 0;
	$twitter = 0;
	$linkedin = 0;

	if(sanitize_text_field($_POST['social']) == 'fb'){
		$fb_d = 1;
	}elseif(sanitize_text_field($_POST['social']) == 'twitter'){
		$twitter = 1;
	}elseif(sanitize_text_field($_POST['social']) == 'linkedin'){
		$linkedin = 1;
	}

	if(isset($_SESSION['user_data']) AND !empty($_SESSION['user_data']->name )){
		$user_id = $_SESSION['user_data']->user_id;
		$email   = $_SESSION['user_data']->email;
		$name    = $_SESSION['user_data']->name;
		$user    =  "AND user_id='" . $user_id.  "'";
	}else{
		$user_id = 0;
		$email= 'guest' ;
		$name= 'guest' ;
		$user = "";
	}

    $resultz = $wpdb->get_results( 'SELECT * FROM '.$prefix.'aha_api WHERE id='.$id);
	if(!empty($resultz)){
		$key = ($resultz[0]->api_key != '' ? $resultz[0]->api_key : '' );
		}
	$data = $wpdb->get_results( "SELECT * FROM ".$prefix."aha_report WHERE w_id = '".$id."' AND book_id = '".sanitize_text_field($_POST['fb'])."'" );
	$fb_d = 0;
	$twitter = 0;
	$linkedin = 0;
	if(sanitize_text_field($_POST['social']) == 'fb'){
		$fb_d = 1;
		$update = "fb='" . (isset($data[0]->fb) ? $data[0]->fb+1 : 1 ). "'";
	}elseif(sanitize_text_field($_POST['social']) == 'twitter'){
		$twitter = 1;
		$update = "twitter='" . (isset($data[0]->twitter) ? $data[0]->twitter+1 : 1 ) . "'";
	}elseif(sanitize_text_field($_POST['social']) == 'linkedin'){
		$linkedin = 1;
		$update = "linkedin='" . (isset($data[0]->linkedin) ? $data[0]->linkedin+1 : 1) . "'";
	}
	
	if(!empty($data)){
		$wpdb->query($wpdb->prepare("UPDATE ".$prefix."aha_report SET ".$update." WHERE w_id = '%d' AND book_id = '%d' ".$user , $id,sanitize_text_field($_POST['fb'])));
		$data_aha = $wpdb->get_results("SELECT * FROM ".$prefix."aha_stg WHERE id_wid = '".$id."'");
		unset( $_COOKIE['guest'] );
		if($data_aha != NULL AND $data_aha[0]->max_count != 0){
			$wpdb->update($prefix.'aha_stg', array(
			'max_count' => $data_aha[0]->max_count-1), 
			array('id_wid'=>$id));
		}
		$ip_address = $wpdb->get_results("SELECT * FROM `".$prefix."aha_order` WHERE `ip_address` =  '".$user_ip."' AND `w_id` = '".$id."' LIMIT 1") ;
		if(empty($ip_address)){
			$wpdb->insert($prefix.'aha_order', array(
			    'ip_address' => $user_ip,
				'order_no' => 1,
				'w_id' => $id,
				'share_limit' => 1
			));
		}else{
			$share_limit = $ip_address[0]->share_limit + 1;
			$wpdb->query($wpdb->prepare("UPDATE `".$prefix."aha_order` SET  `share_limit`= ".$share_limit." WHERE `ip_address`='". $user_ip."' AND `w_id` = '".$id."' "));
		}
	}else{
		$wpdb->insert($prefix.'aha_report', array(
			'fb' => $fb_d,
			'twitter' => $twitter,
			'linkedin' => $linkedin,
			'app_id' => $key,
			'book_id' => sanitize_text_field($_POST['fb']),
			'book_name' => sanitize_text_field($_POST['book_name']),
			'user_id' => $user_id,
			'email' => $email,
			'w_id' => $id,
			'name' => $name
		));
		$data_aha = $wpdb->get_results( "SELECT * FROM ".$prefix."aha_stg WHERE id_wid = '".$id."'" );
		if($data_aha != NULL AND $data_aha[0]->max_count != 0){
			$wpdb->update($prefix.'aha_stg', array(
				'max_count' => $data_aha[0]->max_count-1),
				 array('id_wid'=>$id));
			}
		 $ip_address = $wpdb->get_results("SELECT * FROM `".$prefix."aha_order` WHERE `ip_address` =  '".$user_ip."' AND `w_id` = '".$id."' LIMIT 1") ;
		 if(empty($ip_address)){
			$wpdb->insert($prefix.'aha_order', array(
				'ip_address' => $user_ip,
				'order_no' => 1,
				'w_id' => $id,
				'share_limit' => 1
			));
		}else{
			$share_limit = $ip_address[0]->share_limit +1;
			$wpdb->query($wpdb->prepare("UPDATE `".$prefix."aha_order` SET  `share_limit`= ".$share_limit." WHERE `ip_address`='". $user_ip."' AND `w_id` = '".$id."' "));
		}
	}
	$ip_address = $wpdb->get_results("SELECT * FROM `".$prefix."aha_order` WHERE `ip_address` =  '".$user_ip."' AND `w_id` = '".$id."' LIMIT 1") ; 
	if($ip_address[0]->share_limit >=   $data_aha[0]->maximum_share){
		echo "1" ; die;
	}
}
?>