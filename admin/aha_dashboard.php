<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$prefix	= $wpdb->prefix ;
require  dirname (dirname( __FILE__ ) ) .'/functions/aha_curl_function.php' ;
$Curl = new Aha_curl_function;
$nonce = isset($_REQUEST['submit_api_key']) ? sanitize_text_field($_REQUEST['submit_api_key']) : '';  
if(wp_verify_nonce($nonce, 'action_api_key'))
{ 
  $api_key = sanitize_key($_POST['key']);
  $data = $Curl->aha_main_crul($api_key);

  $datas = json_decode($data);
  $msg = '';
  $authentication = !empty($datas->authentication ) ? $datas->authentication :'';

  if($authentication == 'error'){
    $msg= "<div class='alert '>
    <span class='closebtn'>&times;</span>  
    <strong>Error!</strong> Please Enter valid API Key.
    </div>";
  }else{
    $key_exit = $wpdb->get_var("SELECT COUNT(*)  FROM ".$prefix."aha_key"); 

    if($key_exit > 0) {
      $wpdb->query($wpdb->prepare("UPDATE ".$prefix."aha_key SET api_key = '%s'",$api_key));

      $wpdb->query($wpdb->prepare("UPDATE ".$prefix."aha_api SET api_key = '%s'",$api_key));	
    }else{
      $wpdb->insert($prefix.'aha_key', array(
        'id' => 1,
        'api_key' => $api_key
      ));
      $wpdb->query($wpdb->prepare("UPDATE ".$prefix."aha_api SET api_key = '%s'",$api_key));
    }
    $msg= "<div class='alert success'>
    <span class='closebtn'>&times;</span>  
    You have successfully installed the API setting.
    </div>`";
  }
}

?>
<h3>AHAthat Configuration</h3>
<div class="configuration-div">
  <img id="img_load" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
  <?php 
  if(isset($msg)) echo $msg; 
    $key = '';
    $resultz = $wpdb->get_results( 'SELECT * FROM '.$prefix.'aha_key ' );
    if(!empty($resultz)){
      $key = ($resultz[0]->api_key != '' ? $resultz[0]->api_key : '' );
      $resultz[0]->api_key;
    } ?>
    <form action=""  method="post">
      <?php wp_nonce_field('action_api_key', 'submit_api_key'); ?>
      <h3>API Key</h3> <hr>
      <input type="text" id="fname" name="key" value="<?php echo $key; ?>" placeholder="API Key..">
      <input type="submit" class="button button-primary" id="form_sub" name="Submit" value="Submit">
    </form>
    <hr>
    <div>
      <h3><b>Note: </b></h3> IF you don't have API key then click here for API Key: <a target="_blank" href="https://www.ahathat.com/plugin-setup">https://www.AHAthat.com/plugin-setup</a>
    </div>
</div>	

