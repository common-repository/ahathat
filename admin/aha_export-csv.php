<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  if(sanitize_text_field($_GET['page']) == 'AHAthat_widget'){
  require  dirname (dirname( __FILE__ ) ) .'/functions/aha_setting_function.php' ;
  $setting = new Aha_setting_function;
  $tabs = array(
      'first'   => _( 'AHApage Content Shared' ), 
      'second'  => _( 'Login user share' )
      );
      $page= wp_unslash($_SERVER['QUERY_STRING']);
      echo $setting->aha_menu_tab($_GET , $tabs, $page) ;
      $get = (isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'first' );
      global $wpdb;
      $prefix = $wpdb->prefix ;
      $id = (isset($_GET['id'])  ? intval($_GET['id']) : '' );
      $resultz = $wpdb->get_results( 'SELECT * FROM '.$prefix.'aha_api WHERE id = ' .$id );
        if(!empty($resultz)){
          $key = ($resultz[0]->api_key != '' ? $resultz[0]->api_key : '' );
        }
}
if(sanitize_text_field($_GET['page']) == 'AHAthat_widget'){
  global $wpdb;
  $prefix = $wpdb->prefix ;
  if(isset($_GET['order']) AND sanitize_text_field($_GET['order']) == 'desc'){
    $order =  "ORDER BY ".$prefix."aha_api.name DESC" ; 
  }else{
    $order =  "ORDER BY ".$prefix."aha_api.name ASC" ;
  }
  $data = $wpdb->get_results( "SELECT SUM(".$prefix."aha_report.twitter) as twitter,SUM(".$prefix."aha_report.linkedin) as linkedin,SUM(".$prefix."aha_report.fb) as fb,".$prefix."aha_report.book_name,".$prefix."aha_api.name FROM ".$prefix."aha_report INNER JOIN ".$prefix."aha_api ON ".$prefix."aha_report.w_id=".$prefix."aha_api.id  WHERE ".$prefix."aha_report.w_id = '".$id."' GROUP BY ".$prefix."aha_report.book_id ".$order.";" );
    }else{
      global $wpdb;
     $prefix  = $wpdb->prefix ;
      if(isset($_GET['order']) AND sanitize_text_field($_GET['order'])  == 'desc'){
        $order =  "ORDER BY ".$prefix."aha_api.name DESC" ;
      }else{
        $order =  "ORDER BY ".$prefix."aha_api.name ASC" ;
      }
    require  dirname (dirname( __FILE__ ) ) .'/functions/aha_setting_function.php' ;
    $setting = new Aha_setting_function;
    $tabs = array(
      'first'   => "All Content Shared"
      );
    $page = wp_unslash($_SERVER['QUERY_STRING']);
    echo $setting->aha_tabs($_GET , $tabs, $page) ;
    $get = (isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'first' );
    $data = $wpdb->get_results( "SELECT SUM(".$prefix."aha_report.twitter) as twitter,SUM(".$prefix."aha_report.linkedin) as linkedin,SUM(".$prefix."aha_report.fb) as fb,".$prefix."aha_report.book_name,".$prefix."aha_api.name FROM ".$prefix."aha_report INNER JOIN ".$prefix."aha_api ON ".$prefix."aha_report.w_id=".$prefix."aha_api.id GROUP BY ".$prefix."aha_report.book_id ".$order.";" );
  }
?>

<?php if ( $get != 'second'  ) {
  $order = (isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'ASC' );
  ?>
<?php if(sanitize_text_field($_GET['page']) != 'AHAthat_widget')
  { ?>  
  <h3>All Content Shared</h3> 
    <?php }else { ?>
      <h3>AHApage Content Shared</h3>
    <?php } ?>
    <div class="divs">
      <table class="wp-list-table widefat fixed striped widgets">
        <tr> 
          <?php if(sanitize_text_field($_GET['page']) != 'AHAthat_widget') 
        { ?>
        <th>AHApage &nbsp; <?php if ( $order == 'ASC'  ) { ?> <a class="nav-tab" href="?page=share_report&order=desc">&#10506;</a> <?php }else{ ?><a class="nav-tab" href="?page=share_report&order=ASC">&#10507;</a>  <?php } ?></th>
          <?php } ?>
            <th>AHAbook</th>
            <th>Facebook</th>
            <th>Twitter</th>
            <th>Linkedin</th>
      </tr>
   <?php  
  foreach($data as $datas) {
      /*   $bookData = $wpdb->get_results("SELECT * FROM `".$prefix."bookData` WHERE `id` =  '".$datas->book_id."' LIMIT 1") ; */
    ?> 
  <tr>
    <?php if(sanitize_text_field($_GET['page']) != 'AHAthat_widget')
  { ?>
    <td><?php echo $datas->name;?></td>
    <?php } ?>
      <td><?php echo $datas->book_name;?></td>
      <td><?php echo $datas->fb ;?></td>
      <td><?php echo $datas->twitter ;?></td>
      <td><?php echo $datas->linkedin ;?></td>
     </tr>
  <?php } ?>
  </table>
</div>
<?php }else { 
        if(sanitize_text_field($_GET['page']) == 'AHAthat_widget'){
          $data = $wpdb->get_results( "SELECT * FROM ".$prefix."aha_report WHERE app_id = '".$key."'  AND user_id != 0" );
        }else{
          $data = $wpdb->get_results( "SELECT * FROM ".$prefix."aha_report WHERE user_id != 0" ); 
        } ?>
<h3>Share Record</h3>
<div class="divs">
 <h3>AHAbook Shares</h3> <hr>
  <table>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>AHAbook</th>
        <th>Facebook</th>
        <th>Twitter</th>
        <th>Linkedin</th>
      </tr>
   <?php  
     foreach($data as $datas) {
  /*   $bookData = $wpdb->get_results("SELECT * FROM `".$prefix."bookData` WHERE `id` =  '".$datas->book_id."' LIMIT 1") ; */
   ?> 
      <tr>
        <td><?php echo $datas->name;?></td>
        <td><?php echo $datas->email;?></td>
        <td><?php echo $datas->book_name;?></td>
        <td><?php echo $datas->fb ;?></td>
        <td><?php echo $datas->twitter ;?></td>
        <td><?php echo $datas->linkedin ;?></td>
     </tr>
     <?php } ?>
    </table>
 </div>
 <?php } ?>