<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( !function_exists( 'aha_activation' )) {

    function aha_activation() {
        global $wpdb;
        $prefix          = $wpdb->prefix;
        $aha_api_tbl     = $prefix . "aha_api";
        $aha_key_tbl     = $prefix . "aha_key";
        $book_aha_report = $prefix . "aha_report";
        $aha_settings       = $prefix . "aha_settings";
        $aha_stg         = $prefix . "aha_stg";
        $aha_order       = $prefix . "aha_order";

        $wpdb->query("CREATE TABLE IF NOT EXISTS `$aha_api_tbl` ( `id` mediumint(9) NOT NULL AUTO_INCREMENT, `api_key` varchar(222), `name` varchar(222), `short_code` varchar(222), `status` mediumint(9), `book_id` mediumint(9), `created` datetime,PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $wpdb->query("CREATE TABLE IF NOT EXISTS `$aha_key_tbl` ( `id` mediumint(9) NOT NULL AUTO_INCREMENT,  `api_key` varchar(222), `payment_txid` varchar(222),PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $wpdb->query("CREATE TABLE IF NOT EXISTS `$aha_order` ( `id` mediumint(9) NOT NULL AUTO_INCREMENT,  `order_no` mediumint(9),`share_limit` mediumint(9),`w_id` mediumint(9),  `ip_address` varchar(22),PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $wpdb->query("CREATE TABLE IF NOT EXISTS `$book_aha_report` ( `id` mediumint(9) NOT NULL AUTO_INCREMENT,  `fb` mediumint(9), `twitter` mediumint(9), `linkedin` mediumint(9),`book_id` mediumint(9),`email` varchar(222),`name` varchar(222),`user_id` mediumint(9),`w_id` mediumint(9),`book_name` varchar(222), `app_id` varchar(222), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $wpdb->query("CREATE TABLE IF NOT EXISTS `$aha_stg` ( `id` mediumint(9) NOT NULL AUTO_INCREMENT,  `end_of_aha` mediumint(9), `show_powered` mediumint(9), `end_book` mediumint(9), `maximum_share` mediumint(9), `max_count` mediumint(9), `shows` mediumint(9), `limit_a` mediumint(9), `limit_c` mediumint(9), `limit_l` mediumint(9), `categories` varchar(222), `id_wid` 
    mediumint(9), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $wpdb->query("CREATE TABLE IF NOT EXISTS `$aha_settings` ( `id` mediumint(9) NOT NULL AUTO_INCREMENT,  `b_color` varchar(222), `t_color` varchar(222), `text_color` varchar(222), `s_color` varchar(222), `button_color` varchar(222), `active` mediumint(9),`font` varchar(222),`title_font` varchar(222),`share_btn` varchar(222),`power_btn` varchar(222),`next_pre` varchar(222), `width` mediumint(9),`position` mediumint(9), `height` mediumint(9), `active_s` mediumint(9),`id_wid` 
    mediumint(9), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

    }
}
?>