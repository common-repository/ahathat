<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (!class_exists('Aha_setting_function')) {
    class Aha_setting_function {
        public function aha_tabs($gets, $tabs, $page) {
            $get  = (isset($gets['tab']) ? sanitize_text_field($gets['tab']) : 'first');
            $html = '<h2 class="nav-tab-wrapper">';
            foreach ($tabs as $tab => $name) {
                $class = ($tab == $get) ? 'nav-tab-active' : '';
                $html .= '<a class="nav-tab ' . $class . '" href="?' . $page . '&tab=' . $tab . '">' . $name . '</a>';
            } //$tabs as $tab => $name
            $html .= '</h2>';
            return $html;
        }
        public function aha_menu_tab($gets, $tabs, $page) {
            $get  = (isset($gets['tab']) ? sanitize_text_field($gets['tab']) : 'first');
            $html = '<h2 class="nav-tab-wrapper">';
            $html .= '<a class="nav-tab" href="?page=AHAthat_widget&action=setting&id=' . intval($_GET['id']) . '&tab=first">AHApage Content</a>';
            $html .= '<a class="nav-tab" href="?page=AHAthat_widget&action=setting&id=' . intval($_GET['id']) . '&tab=second">AHApage Theme Settings</a>';
            $html .= '<a class="nav-tab" href="?page=AHAthat_widget&action=share&id=' . intval($_GET['id']) . '&tab=first">AHApage Content Shared</a>';
            $html .= '</h2>';
            return $html;
        }
        public function aha_main_crul($api_key, $url) {
            $data_args = array( 'body' => array('key' => $api_key , 'origin' => plugin_dir_url( __DIR__ ) ));
            $response = wp_remote_post( $url,$data_args );
            return wp_remote_retrieve_body( $response );
        }
        public function aha_get_categories_api() {
            $response = wp_remote_post( 'https://www.ahathat.com/plugin-setup/categories');
            return wp_remote_retrieve_body( $response );
        }
        public function aha_price() {
            $response = wp_remote_post( 'https://www.ahathat.com/plugin-setup/price');
            return wp_remote_retrieve_body( $response );
        }


        public function getTableData($table,$row,$id) {
            global $wpdb;
            $prefix = $wpdb->prefix;
            $results = $wpdb->get_row( "SELECT * FROM ".$prefix."".$table." WHERE ".$row." = '".$id."'" );

            return  $results;
        }
    }
}