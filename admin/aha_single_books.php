<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	require  dirname (dirname( __FILE__ ) )  .'/functions/aha_setting_function.php' ;
	$setting = new Aha_setting_function; 
	global $wpdb;
 	$prefix	= $wpdb->prefix ;
 	$id = sanitize_text_field($_POST['id']);
 	$type = sanitize_text_field($_POST['type']);

	$resultz = $wpdb->get_results( 'SELECT * FROM '.$prefix.'aha_api WHERE id='.$id);   
	if(!empty($resultz)){
		$key = ($resultz[0]->api_key != '' ? $resultz[0]->api_key : '' );
	}	
	require  dirname (dirname( __FILE__ ) )  .'/functions/aha_curl_function.php' ;
	$Curl = new Aha_curl_function;
	$settings  = $Curl->aha_settings($id) ;
	$categories = json_decode($settings->categories);
	$book_id   =  $categories[0];	
	if($type == 'single'){
		$key = $key.'-'.sanitize_text_field($_POST['limit']);
		$bookss = $setting->aha_main_crul($key,'https://www.ahathat.com/plugin-setup/all_books') ;
		$bookss=json_decode($bookss);

		// Display a Single AHAbook 
 		foreach($bookss as $result) {
        	$cover = ($result->cover !='')? $result->cover : 'default_cover.jpg' ;	
        	$bookCoverUrl = plugins_url('/assets/images/books_cover/'.$cover,__DIR__);
        	$defaultCoverUrl = plugins_url('/assets/images/books_cover/default_cover.jpg',__DIR__);
        	$bookCoverUrl = @fopen($bookCoverUrl, 'r') ? $bookCoverUrl : $defaultCoverUrl; 
 			if($book_id  ==  $result->id){ ?>
				<div class="col-sm-2 col-md-2 ">
  				<img src="<?php echo $bookCoverUrl; ?>" class="book_con book-image" width="100" border="0">
				<?php $bookdatas= $result->id;    	?>
				
				<label class="form-check-label" for="checkboxG3"><input type="radio" name="checkboxG3[]" id="checkboxG3" value="<?php echo $bookdatas ; ?>" <?php if($book_id  ==  $result->id) echo 'checked'; ?> /> <?php echo $result->title ; ?>  </label>
				
				
			</div>
  		<?php } } 

		foreach($bookss as $result) {
		    $cover = ($result->cover !='')? $result->cover : 'default_cover.jpg' ;
		    $bookCoverUrl = plugins_url('/assets/images/books_cover/'.$cover,__DIR__);
			$defaultCoverUrl = plugins_url('/assets/images/books_cover/default_cover.jpg',__DIR__);
			$bookCoverUrl = @fopen($bookCoverUrl, 'r') ? $bookCoverUrl :$defaultCoverUrl;

			if($book_id  !=  $result->id){	?>
				<div class="col-sm-2 col-md-2 ">
					<img src="<?php echo $bookCoverUrl; ?>" class=" book_con book-image" width="100" border="0">
					<?php $bookdatas = $result->id;    	?>
				
					<label class="form-check-label" for="checkboxG3"><input type="radio" name="checkboxG3[]" id="checkboxG3" value="<?php echo $bookdatas ; ?>" <?php if($book_id  ==  $result->id) echo 'checked'; ?> /> <?php echo $result->title ; ?>  </label>
				</div>
				  <?php 
			} 
		} ?>
		<hr class="col-md-12 col-sm-12">
	<div class="col-md-12 col-sm-12">  <?php    
  		$totals = (isset($_POST['limit']) && sanitize_text_field($_POST['limit']) !='')? sanitize_text_field($_POST['limit']) : $settings->limit_a ;
 		 $total= $totals + 24;
 		 ?><?php if($totals  > '24'){ ?>
    		<span id="prepage_single"   class="button button-primary">PRE</span>  
		<?php   }  ?>
			<span id="nextpage_single"   class="button button-primary">NEXT</span>

			<img id="img_loads" src="<?php echo plugins_url('/assets/images/loader.gif',__DIR__ ); ?>" style="display:none">
  	</div><?php 

	} elseif(isset($_POST['type']) AND sanitize_text_field($_POST['type'])  == 'labs'){
		//$key = $key.'-'.$settings->limit_l;
	   
		$libraries = $setting->aha_main_crul($key,'https://www.ahathat.com/plugin-setup/libraries') ;
		$datas=json_decode($libraries);

		//  Display AHAbooks in an AHAlibrary
  		foreach($datas as $result) { 
  			$cover_image =($result->cover !='')? $result->cover : 'default_cover.jpg';
			$cover = plugins_url('/assets/images/books_library/'.$cover_image,__DIR__); 
			$bookdatas = $result->id;
		 	$book_ids   =  $categories; 
			if($book_ids) {$idss = (in_array(  $bookdatas, $book_ids) ?'checked="checked"' : '' ); }else{	$idss=''; }
			  ?> 
	  		<div class="col-sm-2 col-md-2 ">
					<img src="<?php echo $cover; ?>" class=" book_con book-image" width="100" border="0">				
					<label class="form-check-label" for="checkboxG3">
						<input type="radio" name="checkboxG3[]" id="checkboxG3" value="<?php echo $bookdatas ; ?>"  <?php echo $idss; ?>> 
						<?php echo $result->title  ; ?>
					</label>
				</div>
	    <?php 

		} 
	}elseif(isset($_POST['type']) AND sanitize_text_field($_POST['type']) == 'cats'){

		$libraries = $setting->aha_main_crul($_POST['cat_id'],'https://www.ahathat.com/plugin-setup/search_books/'.$settings->limit_l) ;
	 	print_r($libraries);
	 	die;
	}
die;
?>