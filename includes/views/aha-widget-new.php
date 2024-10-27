<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    global $wpdb;
    $prefix	= $wpdb->prefix ;
    $key_exit = $wpdb->get_var("SELECT COUNT(*)  FROM ".$prefix."aha_key"); 
      if($key_exit == 0) {  
        echo "<script>window.location = '".admin_url('admin.php?page=configuration')."'; </script>";  
      }
?>	
 <h1>Add AHApage</h1>
<div class="configuration-div">
   
    <ul type="disk">
        <li>Please add an AHApage name. After adding the name, you will see a "Short Code" that you can use to place on your WordPress site.</li>
        <li>Please edit the AHApage by clicking on the name and update the attributes detailing how the AHApage will act on your site.</li> 
    </ul>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr class="row-name">
                    <th scope="row">
                        <label for="name">AHApage Name</label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" placeholder="<?php echo esc_attr( '', 'aha' ); ?>" value="" required="required" />&nbsp;&nbsp;<span class="title_spans"><b>?</b></span><span class="title_span">We keep track of the number of reads and shares of AHAmessages from your site. The name that you type in will appear in your WordPress Dashboard.</span>
                    </td>
                </tr>
             </tbody>
        </table>
        <input type="hidden" name="field_id" value="0">
        <?php wp_nonce_field( 'new-widget' ); ?>
        <?php submit_button( __( 'Add AHApage', 'aha' ), 'primary', 'submit_widget' ); ?>
    </form>
</div>