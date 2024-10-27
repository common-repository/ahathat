<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require  dirname (dirname( __FILE__ ) )  .'/functions/aha_setting_function.php' ;
	$setting = new Aha_setting_function;

	global $wpdb;
    $prefix = $wpdb->prefix;

	$tabs = array(
		'first'   => _( 'AHApage setting'), 
		'second'  => _( 'AHAbook option')
		);
	$page = wp_unslash($_SERVER['QUERY_STRING']);
    echo $setting->aha_menu_tab($_GET , $tabs, $page) ;

    $ahaPageId = isset($_GET['id']) ? intval($_GET['id']) : '';
    $tex_id = isset($_GET['tex_id']) ? sanitize_text_field($_GET['tex_id']) : NULL;
    
    if($tex_id != NULL){
		$wpdb->update($prefix.'aha_stg', array('show_powered' => 1), array('id_wid'=>$ahaPageId));
      	$wpdb->update($prefix.'aha_key', array('payment_txid' => $tex_id), array('id'=>$ahaPageId));
    }

    $catList = array();
    $categoriesData= $setting->aha_get_categories_api();
    $price_paypal = $setting->aha_price();
    $data_aha = $setting->getTableData('aha_stg','id_wid', $ahaPageId);

    $ettingsData = $setting->getTableData('aha_settings','id_wid', $ahaPageId); 

    $check_payment = $setting->getTableData('aha_key','id','1');
    $getTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'first';

    $singleAHAbook = !empty($data_aha) && $data_aha->end_of_aha == 0 ? "checked" : '';
    $Categories = !empty($data_aha) && $data_aha->end_of_aha == 1 ? "checked" : '';
    $library = !empty($data_aha) && $data_aha->end_of_aha == 2 ? "checked" : '';

    $limit_a = !empty($data_aha) && $data_aha->limit_a != 0 ? $data_aha->limit_a : '24';
    $limit_c = !empty($data_aha) && $data_aha->limit_c != 0 ? $data_aha->limit_c : '0';
    $limit_l = !empty($data_aha) && $data_aha->limit_l != 0 ? $data_aha->limit_l : '';

	$repeatContent = !empty($data_aha) && $data_aha->end_book == 0 ? "checked" : '';
	$turnOffAHApage = !empty($data_aha) && $data_aha->end_book == 1 ? "checked" : '';
	$maximum_share = !empty($data_aha) && $data_aha->maximum_share != '' ? $data_aha->maximum_share : 10;

	$Ascending = !empty($data_aha) && $data_aha->shows == 1 ? 'checked' : '';
	$Descending = !empty($data_aha) && $data_aha->shows == 2 ? 'checked' : '';
	$Randomly = !empty($data_aha) && $data_aha->shows == 0 ? 'checked' : '';

	$show_powered_no = !empty($data_aha) && $data_aha->show_powered == 1 ? "checked" : '';
	$show_powered_yes = !empty($data_aha) && $data_aha->show_powered == 0 ? "checked" :'';
	$catList = !empty($data_aha) ? json_decode($data_aha->categories) : ''; 


	//Setting page data 

	$b_color = !empty($ettingsData) && $ettingsData->b_color != '' ? $ettingsData->b_color : '#F5F5F5';
	$t_color = !empty($ettingsData) && $ettingsData->t_color != '' ? $ettingsData->t_color : '#666666';
	$text_color = !empty($ettingsData) && $ettingsData->text_color != '' ? $ettingsData->text_color : '#141412';
	$s_color = !empty($ettingsData) && $ettingsData->s_color != '' ? $ettingsData->s_color : '#004DCC'; 
	$button_color = !empty($ettingsData) && $ettingsData->button_color != '' ? $ettingsData->button_color : '#96C841';
	$width = !empty($ettingsData) && $ettingsData->width != '' ? $ettingsData->width : 'auto';
	$height = !empty($ettingsData) && $ettingsData->height != '' ? $ettingsData->height : 'auto';
	$position = !empty($ettingsData) && $ettingsData->position != '' ? $ettingsData->position : '4';
	$font = !empty($ettingsData) && $ettingsData->font != '' ? $ettingsData->font : '16';
	$title_font = !empty($ettingsData) && $ettingsData->title_font != '' ? $ettingsData->title_font : '22';

	$nonce = isset($_REQUEST['submit_aha_setting']) ? sanitize_text_field($_REQUEST['submit_aha_setting']) : '';  

	if(wp_verify_nonce($nonce, 'action_aha_setting'))
	{
		if (isset($_POST['end_of_aha'])){
			$end_book = NULL;
			$end_of_aha = isset($_POST['end_of_aha']) ? sanitize_text_field($_POST['end_of_aha']) : '';
			if ($end_of_aha == 0) {
				$end_book =isset($_POST['end_book']) ? sanitize_text_field($_POST['end_book']) : '';
			}

			if ($end_of_aha == 1 ) {
				$categoriesPost = isset($_POST['categories']) ? wp_unslash($_POST['categories']) : NULL;
			}else{
				$categoriesPost = isset($_POST['checkboxG3']) ? wp_unslash($_POST['checkboxG3']) : NULL;
			}

			$categoriesList = json_encode($categoriesPost);	 

			if (!empty($data_aha)) {
				if (isset($_POST['Update'])) {
					$wpdb->update($prefix.'aha_stg', array(
					    'end_of_aha' 	=> $end_of_aha,
			          	'categories' 	=> $categoriesList,
			          	'end_book' 		=> $end_book,
			          	'show_powered'	=> isset($_POST['show_powered']) ? sanitize_text_field($_POST['show_powered']): '',
			          	'maximum_share' => isset($_POST['maximum_share']) ? sanitize_text_field($_POST['maximum_share']): '',
			          	'max_count' 	=> isset($_POST['max_count']) ? sanitize_text_field($_POST['max_count']) : '',
			          	'shows'			=> isset($_POST['shows']) ? sanitize_text_field($_POST['shows']) : '',		
			          	'limit_a'		=> isset($_POST['limit_a']) ? sanitize_text_field($_POST['limit_a']) : '',		
			          	'limit_c' 		=> isset($_POST['limit_c']) ? sanitize_text_field($_POST['limit_c']) : '',		
			          	'limit_l' 		=> isset($_POST['limit_l']) ? sanitize_text_field($_POST['limit_l']) : ''), 
					    array('id_wid'=>$ahaPageId)
					);
				}
			  	
        	}else{
        		$wpdb->insert($prefix.'aha_stg' ,array(
		          	'id_wid' 		=> $ahaPageId,
		          	'end_of_aha' 	=> $end_of_aha,
		          	'categories' 	=> $categoriesList,
		          	'end_book' 		=> $end_book,
		          	'show_powered'	=> 0,
		          	'maximum_share' => isset($_POST['maximum_share']) ? sanitize_text_field($_POST['maximum_share']): '',
		          	'max_count' 	=> isset($_POST['max_count']) ? sanitize_text_field($_POST['max_count']) : '',
		          	'shows'			=> isset($_POST['shows']) ? sanitize_text_field($_POST['shows']) : '',		
		          	'limit_a'		=> isset($_POST['limit_a']) ? sanitize_text_field($_POST['limit_a']) : '',		
		          	'limit_c' 		=> isset($_POST['limit_c']) ? sanitize_text_field($_POST['limit_c']) : '',		
		          	'limit_l' 		=> isset($_POST['limit_l']) ? sanitize_text_field($_POST['limit_l']) : ''
	        	));
        	}
		}else if (isset($_POST['aha_setting'])) {
			
			if(!empty($ettingsData)){

				if (isset($_POST['Update'])) {
					$wpdb->update($prefix.'aha_settings', array(
						'b_color' => isset($_POST['b_color']) ? sanitize_text_field($_POST['b_color']): '',
						't_color' => isset($_POST['t_color']) ? sanitize_text_field($_POST['t_color']): '',
						'text_color' =>isset($_POST['text_color']) ? sanitize_text_field($_POST['text_color']): '',
						's_color' => isset($_POST['s_color']) ? sanitize_text_field($_POST['s_color']): '',
						'button_color' => isset($_POST['button_color']) ? sanitize_text_field($_POST['button_color']): '',
						'active' => isset($_POST['active']) ? sanitize_text_field($_POST['active']): '',
						'width' => isset($_POST['width']) ? sanitize_text_field($_POST['width']): $width,
						'height' => isset($_POST['height']) ? sanitize_text_field($_POST['height']): $height,
						'font' => isset($_POST['font']) ? sanitize_text_field($_POST['font']): '',
						'title_font' => isset($_POST['title_font']) ? sanitize_text_field($_POST['title_font']): '',
						'share_btn' => isset($_POST['share_btn']) ? sanitize_text_field($_POST['share_btn']): '',
						'power_btn' => isset($_POST['power_btn']) ? sanitize_text_field($_POST['power_btn']): '',
						'next_pre' => isset($_POST['next_pre']) ? sanitize_text_field($_POST['next_pre']): '',
						'active_s' => isset($_POST['active_s']) ? sanitize_text_field($_POST['active_s']): '',
						'position' => isset($_POST['position']) ? sanitize_text_field($_POST['position']): ''), 
					    array('id_wid'=>$ahaPageId)
					);
				}


			}else{

				$wpdb->insert($prefix.'aha_settings' ,array(
					'id_wid' => $ahaPageId,
					'b_color' => isset($_POST['b_color']) ? sanitize_text_field($_POST['b_color']): '',
					't_color' => isset($_POST['t_color']) ? sanitize_text_field($_POST['t_color']): '',
					'text_color' =>isset($_POST['text_color']) ? sanitize_text_field($_POST['text_color']): '',
					's_color' => isset($_POST['s_color']) ? sanitize_text_field($_POST['s_color']): '',
					'button_color' => isset($_POST['button_color']) ? sanitize_text_field($_POST['button_color']): '',
					'active' => isset($_POST['active']) ? sanitize_text_field($_POST['active']): '',
					'width' => isset($_POST['width']) ? sanitize_text_field($_POST['width']): '',
					'height' => isset($_POST['height']) ? sanitize_text_field($_POST['height']): '',
					'font' => isset($_POST['font']) ? sanitize_text_field($_POST['font']): '',
					'title_font' => isset($_POST['title_font']) ? sanitize_text_field($_POST['title_font']): '',
					'share_btn' => isset($_POST['share_btn']) ? sanitize_text_field($_POST['share_btn']): '',
					'power_btn' => isset($_POST['power_btn']) ? sanitize_text_field($_POST['power_btn']): '',
					'next_pre' => isset($_POST['next_pre']) ? sanitize_text_field($_POST['next_pre']): '',
					'active_s' => isset($_POST['active_s']) ? sanitize_text_field($_POST['active_s']): '',
					'position' => isset($_POST['position']) ? sanitize_text_field($_POST['position']): '',
				));		         

			}
		}

  		echo '<script type="text/javascript">location.href = "//'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'";</script>'; 	    
	}
?>
<script type="text/javascript">

jQuery(document).ready(function() {
	

	if (jQuery('input#library').is(':checked')) {
        jQuery(".book_main_con").removeClass('hidden').addClass('row');
        jQuery(".book_main_con_all").removeClass('row').addClass('hidden');
		   jQuery(".search_form").css('display','none');
        <?php     
        if (!empty($data_aha)) {
        	if($data_aha->end_of_aha == 2 && is_array($catList)) 
        		foreach($catList as $lib){ ?>
			  		jQuery("input:checkbox[value=<?php echo $lib; ?>].css-checkbox").attr("checked", true);
		<?php 	} 
		} ?>
	}else if (jQuery('input#AHAbook-0').is(':checked')) {
		jQuery(".book_main_con").removeClass('row').addClass('hidden');
		jQuery(".book_main_con_all").removeClass('hidden').addClass('row');
		jQuery(".search_form").css('display','none');
		<?php 
		if (!empty($data_aha)) { 
			if($data_aha->end_of_aha == 0 && is_array($catList)){
				foreach($catList as $book){ ?>
					jQuery("input:radio[value=<?php echo $book; ?>].css-checkbox").attr("checked", 'true');
		<?php 	} 
			}else{ ?>		
				jQuery("input:checkbox:firs").attr("checked", 'true');		<?php 
			} 
		} ?> 
	}else if (jQuery('input#Categories').is(':checked')) {
        jQuery(".search_form").css('display','block');
		jQuery(".book_main_con_all").removeClass('row').addClass('hidden');
	    jQuery(".book_main_con").removeClass('row').addClass('hidden'); <?php 
     	if (!empty($data_aha)) { 
     		if($data_aha->end_of_aha == 1 && is_array($catList)){
     			
     			foreach($catList as $category){ ?>
					jQuery("input:checkbox[value=<?php echo $category; ?>].pcatchecks").attr("checked", true);
		<?php 	} 
			} 
		} ?>
	}	  
});

</script> 
<div class="container">
	<div class="tab-content" id="myTabContent">
  
	<?php	if ( $getTab == 'first' ) {  ?>

		<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			<hr>
		  	<h4>Select AHAbook Content</h4>
		  	<hr>
		  	<div class="col-md-12 col-sm-12">
		  		<form name="aha_setting" method="post" action="">
					<?php wp_nonce_field('action_aha_setting', 'submit_aha_setting'); ?>

					<div class="form-group">
						<input name="end_of_aha" class=" form-control" id="AHAbook-0" value="0" type="radio"  url_id="<?= $ahaPageId ?>" url="<?= admin_url() ?>" <?= $singleAHAbook ?>>
						<label class="form-check-label" for="AHAbook-0">Display  a Single AHAbook</label>
						<input type="text" id="myInputbook"  placeholder="Search for AHAbooks" >	
						<input type="hidden" id="limit_a" name="limit_a" value="<?= $limit_a ?>" >
					</div>
					<div class="form-group show_stop hidden">
						<label class="form-control-file" for="AHAbooka">When Entirety of AHAbook Content is Displayed</label>

						<input name="end_book" id="AHAbook-pa" value="0" type="radio" <?= $repeatContent ?>>
						<label class="form-check-label" for="AHAbook-pa"> Repeat Content </label>

						<input name="end_book" id="AHAbook-aa" value="1" type="radio" <?= $turnOffAHApage ?>>
						<label class="form-check-label" for="AHAbook-aa"> Turn Off AHApage  </label>
					</div>

					<div class="form-group " >
						<div class="book_main_con_all hidden"> 
							<h4>Loading AHAbook Content...<img id="img_loads" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none"></h4>
						</div>
					</div>
					<hr>

					<div class="form-group">

						<input name="end_of_aha" id="Categories" value="1" type="radio" <?= $Categories?>>
					    <label class="form-check-label" for="Categories"> Display AHAbooks in a Category</label>
						<input type="text" id="myInputcat"  placeholder="Search for Category Books" >
						<input type="hidden" id="limit_c" name="limit_c" value="<?= $limit_c ?>">
						<?php echo $categoriesData; ?>

					</div>

					<div class="form-group">
						<div class="cats_books hidden">
							<h4 class="h3CatyagoryLable">
							Loading AHAbook Content...<img id="img_loads" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
							</h4> 
						</div>
					</div>
					<hr>

					<div class="form-group">
						<input name="end_of_aha" id="library" value="2" type="radio" <?= $library ?>>
					    <label class="form-check-label" for="library">Display AHAbooks in an AHAlibrary  </label>
						<input type="text" id="myInputLIB"  placeholder="Search for AHAlibrary" >
						<input type="hidden" id="limit_l" name="limit_l" value="<?= $limit_l ?>" >
					</div>	

					<div class="form-group">
			    		<div class="book_main_con hidden">
							<h4>
								Loading AHAbook Content...<img id="img_loads" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none"> 
							</h4>
						</div>
					</div>

					<div class="form-group">
					  	<label class="form-control-file" for="AHAbook">Show "Powered by AHAthat"</label>
					  	<input name="show_powered" id="AHAbook-p" value="0" type="radio" <?= $show_powered_yes ?>> 
					    <label class="form-check-label" for="AHAbook-p">Yes </label>
					    <?php 
					    if(empty($check_payment->payment_txid))  { ?>
						   	<a href="#" data-modas-id='popup3' > 
						   		<input name="show_powered"  id="AHAbook-a" value="1" type="radio" <?= $show_powered_no ?> >
						   		<label class="form-check-label" for="AHAbook-a">No	</label> 
						    </a><?php 
						} else { ?>

							<input name="show_powered"  id="AHAbook-a" value="1" type="radio" <?= $show_powered_no ?> >
							<label class="form-check-label" for="AHAbook-a">No</label> 
				  <?php } ?>	
					</div>

					<div class="form-group">
					  	<label class="control-label" for="maximum_share">Maximum # of AHAmessages shared by a guest before they are asked to log-in to AHAthat.
						</label>  
					  	<input id="maximum_share" name="maximum_share" placeholder="Maximum AHA Share" class="form-control col-sm-1" type="number" value="<?= $maximum_share ?>" >
					</div>

					<div class="form-group">
					  	<label class="form-control-file" for="maximum_share">Display AHAmessages from AHAbook(s)</label>  
					   	<input name="shows" id="Show-1" value="1" type="radio" <?= $Ascending ?>> 
					    <label class="form-check-label" for="Show-1">Ascending</label><br>
					    <input name="shows" id="Show-2" value="2" type="radio" <?= $Descending ?>> 
					    <label class="form-check-label" for="Show-2">Descending </label> <br>
					    <input name="shows" id="Show-0" value="0" type="radio" <?= $Randomly ?>>
					    <label class="form-check-label" for="Show-0">Randomly </label>
					</div>




					<?php if (empty($data_aha)) { ?>
				    	<button id="submit" type="submit" class="button button-primary save_form">Save</button>
					<?php }else{ ?>
				    	<button id="submit" name="Update" type="submit" value="Update" class="button button-primary">Update</button> 
					<?php } ?>
				</form>

				<!-- Popup -->
				<div id="popup3" class="modas-box">
				  	<header> <a href="#" class="js-modas-close close">×</a>
				    	<h3>Show "Powered by AHAthat"</h3>
						<img id="img_load" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
				  	</header>
				  	<div class="modas-body">
						<h3>Turning off "Powered by AHAthat" is a paid for option which runs $1 a month. Click <a href="https://www.ahathat.com/plugin-setup/plugin_paypal?page=<?php echo admin_url(); ?>&w_id=<?php echo $check_payment->api_key; ?>&id=<?php echo $ahaPageId; ?>" >here</a> to enable this feature</h3>

				  	</div>
				  	<footer> <a href="#" class="btn btn-small js-modas-close">Close</a> </footer>
				</div>

				<div id="popup4" class="modas-box">
					<header> <a href="#" class="js-modas-close close">×</a>
						<h3>Payment Status</h3>
						<img id="img_load" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
					</header>
					<div class="modas-body">
						<h3>Thank You.Your Payment completed."</h3>
					</div>
					<footer> <a href="#" class="btn btn-small js-modas-close">Close</a> </footer>
				</div> 
				<!-- end popup -->
		  	</div>	
		</div>
		<?php }else{ ?>
	  	<div class="tab-pane active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
	  		<hr>
	  		 <h3>AHApage Settings</h3>
	  		 <hr>
	  		<form name="aha_setting" method="post" action="">
			  	<?php wp_nonce_field('action_aha_setting', 'submit_aha_setting'); ?>

			  	<input type="hidden" name="aha_setting" value="1">
			  	<div class="form-group row">
				  	<label class="control-label col-md-3" for="b_color">Background color</label> 
				  	<div class="col-md-2"> 
				  		<input id="b_color" name="b_color" placeholder="Background color" class="jscolor form-control " type="text" value="<?= $b_color ?>">
				  	</div>			    
				</div>

				<div class="form-group row">
				  	<label class="col-md-3 control-label" for="t_color">AHAbook title color</label>  
				  	<div class="col-md-2">
				  		<input id="t_color" name="t_color" placeholder="AHAbook title color" class="jscolor form-control input-md" type="text" value="<?= $t_color ?>">		    
				  	</div>
				</div>
				<!-- Text input-->
				<div class="form-group row">
				  	<label class="col-md-3 control-label" for="text_color">Text Color</label>  
				  	<div class="col-md-2"> 
				  		<input id="text_color" name="text_color" placeholder="Text color" class="jscolor form-control input-md" type="text" value="<?= $text_color ?>">	    
				  	</div>
				</div>
				<!-- Text input-->
		<!-- 		<div class="form-group row">
				  	<label class="col-md-3 control-label" for="s_color">Soical media button color</label>  
				  	<div class="col-md-2">
				  		<input id="s_color" name="s_color" placeholder="button color" class="jscolor form-control input-md" type="text" value="<?= $s_color ?>">		    
				  	</div>
				</div> -->
				<!-- Text input-->
				<div class="form-group row">
				  	<label class="col-md-3 control-label" for="button_color">Other button color</label>  
				  	<div class="col-md-2">
				 		 <input id="button_color" name="button_color" placeholder="Button color" class="jscolor form-control input-md" type="text" value="<?= $button_color ?>">			    
				  	</div>
				</div>


				<hr>

				<div class="form-group row">
					<label class="col-md-3 control-label" for="plg_width">AHApage width</label>  
				 	 <div class="col-md-5">
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">px</div>
							</div>
							<input   id="plg_width" name="width" placeholder="Auto" class="form-control col-md-2" type="text" value="<?= $width ?>"> 
						</div>
				  		 
				  		<small>(MIN WIDTH 450px AND MAX WIDTH 1000px) Default is auto</small>		    
				  	</div>
				</div>
				<div class="form-group row">
				  	<label class="col-md-3 control-label" for="plg_height">AHApage height</label>  
				  	<div class="col-md-5">
				  		<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">px</div>
							</div>
				  			<input  id="plg_height" name="height" placeholder="Auto" class="form-control col-md-2" type="text" value="<?= $height ?>">
				  		</div> 
				  		<small>(MIN HEIGHT 350px AND MAX HEIGHT 1000px) Default is auto</small>			    
				  	</div>
				</div>
				<div class="form-group row">
				  	<label class="col-md-3 control-label" for="position">AHAmessage position</label>  
				  	<div class="col-md-2">
				   		<select name="position" class="form-control">
							<option value="0" <?php if($position ==0 ){ echo 'selected'; }?>>Center</option>
							<option value="1" <?php if($position ==1 ){ echo 'selected'; }?>>Left</option>
							<option value="2" <?php if($position ==2 ){ echo 'selected'; }?>>Right</option>
							<option value="3" <?php if($position ==3 ){ echo 'selected'; }?>>Justify</option>
							<option value="4" <?php if($position ==4){ echo 'selected'; }?>>Unset</option>    		 
						</select> 
					</div>
				</div>
				<!-- Text input-->
				<div class="form-group row">
				  	<label class="col-md-3 control-label" for="font" >AHAmessage Font Size</label>
				  	<div class="col-md-5">
				  		<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">px</div>
							</div>
				  			<input value="<?= $font ?>" name="font" id="font" class="form-control col-md-2" >
				  		</div>
					</div>
				</div>
				<!-- Text input-->
				<div class="form-group row">
				  	<label class="col-md-3 control-label" for="title_font" >AHAmessage Title Font Size</label>
				  	<div class="col-md-5">
				  		<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">px</div>
							</div>
				  			<input value="<?= $title_font ?>" name="title_font" id="title_font" class="form-control col-md-2" >
				  		</div>
					</div>
				</div>
				<input type="hidden" value="1" name="active_s" id="active_s">
				<?php if (empty($ettingsData)) { ?>
			    	<button id="submit" type="submit" class="button button-primary save_form">Save</button>
				<?php }else{ ?>
			    	<button id="submit" name="Update" type="submit" value="Update" class="button button-primary">Update</button> 
				<?php } ?>	

	  		</form>
	  	</div>
	  <?php } ?>
	</div>
</div>