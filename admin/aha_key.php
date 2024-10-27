<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( !function_exists( 'aha_key' )) {

	function aha_key($atts)
	{	
		ob_start();
		extract( shortcode_atts( array(  'id' => '',), $atts, 'aha_key' ) );
		$postCall = 0;
		if (isset($_POST) && !empty($_POST)) {
			$nonce = sanitize_key($_POST['_ajax_nonce']);
			if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ){
	      		die ( 'Access denied..');
			}else{
				$postCall = 1;
	      	}

		}
		

		$id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : $id;
		global $wpdb;
		$prefix	= $wpdb->prefix ;		
		$_SESSION['guest_data'] = array();
		$ahas = '';
		$ip_address = array();		
		
		require  dirname (dirname( __FILE__ ) )  . '/functions/aha_curl_function.php';
		$Curl = new Aha_curl_function;
		
		$books_data= $Curl->aha_active_key($id) ;	
		$settings  = $Curl->aha_settings($id) ;
		$user_ip  = $Curl->aha_getUserIP();

		// print_r($settings);
		if(isset($_POST['email'])){
			$email = sanitize_email($_POST['email'] );
		}
		if(isset($email) AND $email != NULL)
		{
			$aha_url ='https://www.ahathat.com/plugin-setup/login';
			$datap = wp_unslash($_POST);
			$responseData = $Curl->aha_login($books_data->api_key , json_encode($datap),esc_url_raw($aha_url));
			$user_data = json_decode($responseData);
			if($user_data[0] != 'error')
			{ 	if(session_id() == '' || !isset($_SESSION)) { session_start(); }
				$_SESSION['user_data'] = array();
				$_SESSION['user_data'] = $user_data[0] ;
			}
		}

		if(isset($_POST['logout'])){ unset($_SESSION['user_data']);}


		$session_name = (isset($_POST['session_name']) ? sanitize_text_field($_POST['session_name']) : (isset($_SESSION['user_data']->name ) ? sanitize_text_field($_SESSION['user_data']->name)  :''));

		$categories = json_decode($settings->categories);	//$book_id   = $books_data->book_id ;
		if (empty($categories)) {
			echo 'Please Select AHAbook Content';
			wp_die();
		}
	    $end_book  = $settings->end_of_aha;
		$book_id   =  $categories[0];
		

		$action = (isset($_POST['action']) ? sanitize_text_field($_POST['action']) : NULL );	 

		if(isset($_POST['tweets']))
		{
		   	$aha_post_tweets = sanitize_text_field($_POST['tweets']);

			    $ahas =  "AND `order_no` =  '".$aha_post_tweets."'" ; 
			    $ip_address = $wpdb->get_results("SELECT * FROM `".$prefix."aha_order` WHERE `ip_address` =  '".$user_ip."' AND `w_id` = '".$id."' LIMIT 1") ;
				
				if(empty($ip_address))
				{
					$wpdb->insert($prefix.'aha_order', array(
		    			'ip_address' => $user_ip,
		    			'order_no' => $aha_post_tweets,
		    			'w_id' => $id,
		    			'share_limit' => 1
					));
				}else{

					$table = $prefix.'aha_order';
					$wpdb->query(
					    $wpdb->prepare(
					        "UPDATE {$table} SET order_no = '%d'  WHERE ip_address = '%s' AND w_id = '%d'",
					         $aha_post_tweets,  $user_ip,$id
					    ) // $wpdb->prepare
					); // $wpdb->query

				}
		}else{
		    $ahas ='ORDER BY RAND()';  
			if($action == NULL && $settings->shows == 1 )
			{
		     	$ahas =  "AND `order_no` = 1" ;
	    		$ip_address = $wpdb->get_results("SELECT * FROM `".$prefix."aha_order` WHERE `ip_address` =  '".$user_ip."' AND `w_id` = '".$id."' LIMIT 1");
				if(!empty($ip_address))
				{
					$ahas =  "AND `order_no` = ".$ip_address[0]->order_no ;
					$order_no = $ip_address[0]->order_no  + 1;
					$table = $prefix.'aha_order';
					$wpdb->query(
					    $wpdb->prepare(
					        "UPDATE {$table} SET order_no = '%d'  WHERE ip_address = '%s' AND w_id = '%d'",
					         $order_no,  $user_ip,$id
					    ) // $wpdb->prepare
					); 
				}	
			}else if($action == NULL && $settings->shows == 2){
 				$ahas ='ORDER BY `order_no` DESC';
   				$ip_address = $wpdb->get_results("SELECT * FROM `".$prefix."aha_order` WHERE `ip_address` =  '".$user_ip."' AND `w_id` = '".$id."' LIMIT 1");
				if(!empty($ip_address))
				{
					$ahas =  "AND `order_no` = ".$ip_address[0]->order_no ;

					$order_no = $ip_address[0]->order_no  - 1;
					$table = $prefix.'aha_order';
					$wpdb->query(
					    $wpdb->prepare(
					        "UPDATE {$table} SET order_no = '%d'  WHERE ip_address = '%s' AND w_id = '%d'",
					         $order_no,  $user_ip,$id
					    ) // $wpdb->prepare
					); // $wpdb->query
				}
 			} 
	    }
// print_r($ahas); die();
		if($book_id != '' OR $end_book == '1' OR $end_book == '2'){
			if($end_book == '0')
			{
				$book_id   =  $categories[0];
				$all_book_share  = $Curl->aha_all_book_share($books_data->api_key , $book_id ,$ahas) ;

				// print_r($books_data->api_key);die();
				if (!empty($all_book_share)) {
			
				$aha_data =	json_decode($all_book_share);
				$book_aha = $aha_data->aha;
				$bookData = $aha_data->bookData;
				$author_detail = $aha_data->author;
				$total_order = $aha_data->aha_total[0]->total;

				if(empty($ip_address) && $settings->shows == 2)
				{
					$ahas =  "AND `order_no` = ".$ip_address[0]->order_no ;
					$table = $prefix.'aha_order';
					$wpdb->insert($prefix.'aha_order', array(
		    			'ip_address' => $user_ip,
		    			'order_no' => $total_order,
		    			'w_id' => $id,
		    			'share_limit' => 1
					));
				}else if (empty($ip_address) && $settings->shows == 1) {
					$table = $prefix.'aha_order';
					$wpdb->insert($prefix.'aha_order', array(
		    			'ip_address' => $user_ip,
		    			'order_no' => 1,
		    			'w_id' => $id,
		    			'share_limit' => 1
					));
				}


				$aname = ''; 
					foreach($author_detail as $data)
					{
						if ($aname == '') {
							$aname .= $data->name;
						}else{
							$aname .= ', '.$data->name;
						}
					}
					if(!empty($book_aha))
					{
						$aha_tweet = $book_aha[0]->aha;
						$book_id= $bookData[0]->id;
				    }
				 }		 
			}else{
				if(!empty($categories))
				{	
					$rand_keys = array_rand($categories, 1);
					$librarie_aha  = $Curl->aha_librarie_aha($books_data->api_key,$categories[$rand_keys],$end_book) ;
					if (!empty($librarie_aha)) {
					

					$aha_data =	json_decode($librarie_aha);
					$book_aha = $aha_data->aha;
					$bookData = $aha_data->bookData;
					$author_detail = $aha_data->author;
					$total_order = $aha_data->aha_total[0]->total; 
					$aname = ''; 
						foreach($author_detail as $data)
						{
							if ($aname == '') {
								$aname .= $data->name;
							}else{
								$aname .= ', '.$data->name;
							}
							
						}
						if(!empty($book_aha))
						{
							$aha_tweet = $book_aha[0]->aha;
							$book_id= $bookData[0]->id;
						}
					}
				}
			}	
			if(!empty($book_aha))
			{
				$tweetText = htmlspecialchars_decode( $aha_tweet );
				$tweetText1 = $tweetText;
				$tt = str_replace("#","&#35;",$tweetText);
				$tt = str_replace("@","&#64;",$tt);
				$tt = str_replace("@","&#64;",$tt);
				$tt = str_replace('"','&quot;',$tt);
				$tt = str_replace("'","&acute;",$tt);
				$tweetText2 = urlencode($tweetText);
				if(substr($tweetText2,0,1)=="#")
					{
						$tweetText2 = substr($tweetText2, 1);
					}
				$ct = mb_strlen($tweetText);
		    }
		 	$resultz = $wpdb->get_results( 'SELECT * FROM '.$prefix.'aha_api WHERE id='.$id);
			if(!empty($resultz))
			{
				$key = ($resultz[0]->api_key != '' ? $resultz[0]->api_key : '' );
			}
		 	
		 	$ahapageSettings = $wpdb->get_results( "SELECT * FROM ".$prefix."aha_settings WHERE id_wid = '".$id."' AND active_s = '1' " );
			if ($postCall == 0) {
				if(!empty($ahapageSettings)){

					$ahapageSettings = $ahapageSettings[0];
					$b_color = !empty($ahapageSettings) && $ahapageSettings->b_color != '' ? $ahapageSettings->b_color : 'F5F5F5';
					$t_color = !empty($ahapageSettings) && $ahapageSettings->t_color != '' ? $ahapageSettings->t_color : '666666';
					$text_color = !empty($ahapageSettings) && $ahapageSettings->text_color != '' ? $ahapageSettings->text_color : '141412';
					$s_color = !empty($ahapageSettings) && $ahapageSettings->s_color != '' ? $ahapageSettings->s_color : '004DCC'; 
					$button_color = !empty($ahapageSettings) && $ahapageSettings->button_color != '' ? $ahapageSettings->button_color : '96C841';
					$width = !empty($ahapageSettings) && $ahapageSettings->width != ''  && $ahapageSettings->width >450 ? $ahapageSettings->width.'px' : 'auto';
					$height = !empty($ahapageSettings) && $ahapageSettings->height != ''  && $ahapageSettings->height > 250 ? $ahapageSettings->height.'px' : 'auto';
					$position = !empty($ahapageSettings) && $ahapageSettings->position != '' ? $ahapageSettings->position : '4';
					$font = !empty($ahapageSettings) && $ahapageSettings->font != '' ? $ahapageSettings->font : '18';
					$title_font = !empty($ettingsData) && $ettingsData->title_font != '' ? $ettingsData->title_font : '22';

					if ($position == 0) {
						$text_align = 'center';
					}else if ($position == 1) {
						$text_align = 'left';
					}else if ($position == 2) {
						$text_align = 'right';
					}else if ($position == 3) {
						$text_align = 'justify';
					}else if ($position == 4) {
						$text_align = 'unset';
					}


					?><style>
						.ahacard{
								background:#<?= $b_color ?>!important;
						}
						@media screen and (min-width: 480px)
					
						{ /*Styles */

							.ahacard{
								width: <?= $width ?>!important;
								height: <?= $height ?>!important;
							}
						}
						.aha-content{
							font-size: <?= $font ?>px!important;
							color: #<?= $text_color ?>!important;
						}

						.aha-content {
							text-align: <?= $text_align ?>!important;
						}

						.aha-title-size{
							font-size: <?= $title_font ?>px!important;
						}
						.plg_btn{
							background:#<?= $button_color ?>!important;
						}
					</style> <?php 
				}  
			}

		if(!empty($book_aha)){ ?>

			<div class="ahacard ahamessagearea">
				<div class="ahacontainer">
					<div class="aha-header">
						<div class="aha-text-center ">
							<a href="<?php echo 'https://www.ahathat.com/ahabook/'.$book_id.'/3/bd' ;?>" class="aha-text-color aha-title-size" >
							<?php echo $bookData[0]->title;?></a>
						</div>
						<div class="aha-row aha-text-center aha-info-line">
							<div class="aha-col-3">
								<a href="<?php echo 'https://www.ahathat.com/ahabook/'.$book_id.'/3/bd' ;?>" class="aha-btn" target="_blank">More Info</a>
							</div>
							<div class="aha-col-6">
								<a class="aha-btn" href="<?php echo 'https://www.ahathat.com/ahabook/'.$book_id.'/3/ad' ;?>"
								 target="_blank">
								<?php echo $aname;?></a>
							</div>
							<div class="aha-col-3">
								<span class="aha-btn" >AHA
							<?php echo ' #'.$book_aha[0]->order_no ; ?></span>
							</div>
							
						</div>						
					</div>
					<div class="aha-content"><?php 

						 	$descriptions=str_replace("Aha Amplifier","AHAthat", stripslashes($tweetText));
							$descriptionss	=str_replace("AhaAmplifier","AHAthat", $descriptions);
							$descriptionsss  = str_replace("ahaamplifier","AHAthat", $descriptionss);
							echo str_replace("AhaBook","AHAbook", $descriptionsss);
							if(isset($ip_address[0]->share_limit) AND $ip_address[0]->share_limit != ''){
								$ips =  $ip_address[0]->share_limit+1;
							}else{
								$ips = $settings->maximum_share;
							}?>

					</div>
					<div class="aha-footer aha-row aha-text-center">
						<div class="aha-col-4">
							<a class="aha-twitter aha-icon" <?php if( $ips> $settings->maximum_share AND empty($session_name ) AND $settings->maximum_share != 0){ echo
									"href='#' data-modas-id='popup3'" ;} else { ?> href="javascript:copyToClipboardajaxtwitters(
									<?= $book_id;?>,'
									<?php echo admin_url(); ?>','
									<?php echo addslashes($bookData[0]->title);?>','
									<?php echo $id; ?>')"
									<?php } ?> ><i class="fa fa-twitter " aria-hidden="true"></i></a>
								<p style="display: none;" class="<?php echo $ct ; echo '';?> twitter_content">https://twitter.com/intent/tweet?text=
									<?php if($ct<=280){ echo urlencode($tweetText) ."%20%23AHAthat"; }else{	echo urlencode($tweetText);}?>
								</p>

								<a class="aha-facebook aha-icon" <?php if( $ips> $settings->maximum_share AND empty($session_name ) AND $settings->maximum_share != 0){ echo
									"href='#' data-modas-id='popup3'" ;} else { ?>
									href="javascript:copyToClipboardajaxFBs(
									<?= $book_id;?>,'
									<?php if(strlen($tt)<=280){ echo $tt ."%20%23AHAthat"; }else{ echo $tt ."%20%23AHAthat";} ?>','
									<?php echo admin_url(); ?>','
									<?php echo addslashes($bookData[0]->title);?>','
									<?php echo $id; ?>');"
									<?php } ?> ><i class="fa fa-facebook " aria-hidden="true" ></i></a>
								<a class="aha-linkedin copyToClipboardajaxlinked aha-icon" <?php if( $ips> $settings->maximum_share AND empty($session_name ) AND $settings->maximum_share != 0){ echo
									"href='#' data-modas-id='popup3'" ;} else { ?> href="javascript:copyToClipboardajaxtwitterss(
									<?= $book_id;?>,'
									<?php echo admin_url(); ?>','
									<?php echo addslashes($bookData[0]->title);?>','
									<?php echo $id; ?>');"
									<?php }?> " ><i class="fa fa-linkedin" aria-hidden="true" ></i></a>
						
								<p style="display: none;"  class="<?php echo $ct ; echo '';?> twitter_contents">https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo "https://www.ahathat.com/external/tweetsshare/$book_id";?>&amp;title=<?php echo urlencode($bookData[0]->title);?>&amp;summary=<?php if($ct<=280){ echo urlencode($tweetText) ."%20%23AHAthat"; }else{ echo urlencode($tweetText) ."%20%23AHAthat";} ?> </p>
						</div>
						<div class="aha-col-8">
							<a class="plg_btn " href="javascript:last('<?php if($book_aha[0]->order_no == 1) {echo $total_order ;}else{echo $book_aha[0]->order_no - 1;} ?>','<?php echo admin_url(); ?>','<?php echo $id; ?>','<?php echo $session_name; ?>');">Prev</a>
							
							<a class="plg_btn " href="javascript:random('437','<?php echo admin_url(); ?>','<?php echo $id; ?>','<?php echo $session_name; ?>');" style=" margin-left: 1px; margin-right: 1px;">Random AHA</a>

							<a class="plg_btn" href="javascript:next('<?php if($total_order == $book_aha[0]->order_no) {echo 1 ;}else{echo $book_aha[0]->order_no + 1;} ?>','<?php echo admin_url(); ?>','<?php echo $id; ?>','<?php echo $session_name; ?>');">Next</a>
							
						</div>						
					</div>
					<div class="aha-poweredby">					
						<?php if($settings->show_powered == 0){ ?>
							<a href="https://www.ahathat.com/" target="_blank">Powered by
						AHAthat&reg;</a>
						<?php } ?>										
					</div>
				</div>
			</div>

			<?php
			if ($postCall) {
				wp_die();
			}
			?>

			<div id="popup3" class="modas-box">
			  <header> <a href="#" class="js-modas-close close">×</a>
			    <h3>AHAthat Login</h3>
				<img id="img_load" src="<?php echo  plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
			  </header>
			  <div class="modas-body">
				<h3>Please Login/Signup to Continue Sharing AHAmessages.</h3>
				<a  href="#" data-modas-id="popup1">LOGIN</a> / <a class="js-open-modas" href="#" data-modas-id="popup2">SIGNUP</a>
			  </div>
			  <footer> <a href="#" class="btn btn-small js-modas-close">Close</a> </footer>
			</div>

			<div id="popup1" class="modas-box"  
			 <?php if(isset($user_data[0]) && $user_data[0] == 'error' ) { 
				$custom_css = "
				#popup1 {
				display: block !important;	
				}";
				wp_add_inline_style( 'custom-style', $custom_css );
			  } 
			 echo "style='top: 79px; left: 337px;'";?> >
			  <header> 
				  <a href="#" class="js-modas-close close">×</a>
			      <h3>LOGIN</h3>
				  <img id="img_load" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
				  <?php if(isset($user_data[0]) && $user_data[0] == 'error' ) { ?>
			      <span id="error">Invalid Email or password</span>
			    <?php } ?>
			  </header>
			  <div class="modas-body">
			   <form action="" method="post" class="form-horizontal_1 login">
					<!-- Text input-->  
					<?php wp_nonce_field('action_api_key', 'submit_api_key'); ?>
					 <div class="form-group">
			   			<label class="col-md-4 control-label" for="email">Email</label>  
			  			<div class="col-md-12">
			   				<input id="email" name="email" placeholder="Email" class="form-control 	input-md"  type="text"> 
			 			 </div>
					</div>
					<!-- Text input-->
					 <div class="form-group">
			  			 <label class="col-md-4 control-label" for="password">Password</label>  
							<div class="col-md-12">
			  					 <input id="password" name="password" placeholder="Password" class="form-control input-md"  type="password">
								<input id="process" name="process"  value="login"  type="hidden">
			  				</div>
					</div>
					<!-- Button -->
					<div class="form-group">
			  			<div class="col-md-4">
							<button class="plg_btns" type="submit">LOGIN</button>
			  			</div>
					</div>
				</form>
			  </div>
			  <footer> <a href="#" class="btn btn-small js-modas-close">Close</a> </footer>
			</div>
				<div id="popup2" class="modas-box">
			  		<header> 
						<a href="#" class="js-modas-close close">×</a>
			    		<h3>SIGNUP</h3>
						<img id="img_loads" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
			  		</header>
			 		 <div class="modas-body"> 
			      	 <form action="" method="post" class="form-horizontal_1 signup">
						<span id="error"></span>
						 <!-- Text input-->
							<div class="form-group">
			  					<label class="col-md-4 control-label" for="name">Name</label>  
			  						<div class="col-md-5">
			  							<input id="name" name="name" placeholder="Name" class="form-control input-md"  type="text">
									</div>
							</div>    
						<!-- Text input-->
						<div class="form-group">
			  				<label class="col-md-4 control-label" for="email">Email</label>  
			  					<div class="col-md-5">
			  						<input id="email" name="email" placeholder="Email" class="form-control input-md"  type="text">
								</div>
						</div>
						<!-- Text input-->
						<div class="form-group">
			  				<label class="col-md-4 control-label" for="password">Password</label>  
			  					<div class="col-md-5">
			  						<input id="password" name="password" placeholder="Password" class="form-control input-md"  type="password">
			    				</div>
						</div>
						<!-- Text input-->	
						<div class="form-group">
			  				<label class="col-md-4 control-label" for="cpassword">Confirm Password</label>  
			  				<div class="col-md-5">
			  					<input id="cpassword" name="cpassword" placeholder="Confirm Password" class="form-control input-md"  type="password">
							    <input id="process" name="process"  value="signup"  type="hidden">
			    			</div>
						</div>
						<!-- Button -->
						<div class="form-group">
			 			 <label class="col-md-4 control-label" for="submit"></label>
			  				<div class="col-md-4">
			   					<a class="plg_btns" href="javascript:signup('<?php echo admin_url(); ?>')">Submit</a>
							  </div>
						</div>
				</form>
			  </div>
			  <footer> <a href="#" class="btn btn-small js-modas-close">Close</a> </footer>
			</div>

			<?php
   
	 	}else{
			echo 'Please Select AHAbook Content';
		}
	}

		$action = (isset($_POST['action']) ? sanitize_text_field($_POST['action']) : NULL );
		if($action == NULL)
		{	
			$output = ob_get_clean(); return $output;
		}
	}
}