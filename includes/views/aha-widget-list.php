<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 ?>
<div class="wrap">
    <h2><?php _e( 'AHAthat: Add a New AHApage on a Wordpress Site.', 'aha' ); ?> <a href="<?php echo admin_url( 'admin.php?page=AHAthat_widget&action=new' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'aha' ); ?></a></h2>
    <form method="post">
        <input type="hidden" name="page" value="aha_list_table">
           <?php
            $list_table = new Aha_display_short_code();
            $list_table->prepare_items();
            $list_table->search_box( 'search', 'search_id' );
            $list_table->display();
        ?>
    </form>
</div>