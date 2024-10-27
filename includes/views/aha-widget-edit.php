<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<h1>Edit AHApage</h1>
<div class="configuration-div">
   
	<ul type="disk">
    <li>Please add an AHApage name. After adding the name, you will see a "Short Code" that you can use to place on your WordPress site.</li>
    <li>Please edit the AHApage by clicking on the name and update the attributes detailing how the AHApage will act on your site.</li> </ul>
        <?php $item = aha_get_widget( $id ); ?>
        <form action="" method="post">  
            <table class="form-table">
                <tbody>
                    <tr class="row-name">
                        <th scope="row">
                        <label for="name">AHApage Name</label>
                        </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" placeholder="<?php echo esc_attr( '', 'aha' ); ?>" value="<?php echo esc_attr( $item->name ); ?>" required="required" />&nbsp;&nbsp;<span class="title_spans"><b>?</b></span><span class="title_span">We keep track of the number of reads and shares of AHAmessages from your site. The name that you type in will appear in your WordPress Dashboard.</span>
                    </td>
                </td> 
             </tr> 
        </tbody>
     </table>
        <input type="hidden" name="field_id" value="<?php echo $item->id; ?>">
        <?php wp_nonce_field( 'new-widget' ); ?>
        <?php submit_button( __( 'Update AHApage Name', 'aha' ),'primary', 'submit_widget');  ?>
    </form>
</div>