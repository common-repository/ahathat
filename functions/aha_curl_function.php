<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (!class_exists('Aha_curl_function')) {
    class Aha_curl_function {
        public function aha_active_book($post) {
            $post_data = sanitize_text_field($post['data']);
            $post_submits = sanitize_text_field($post['submits']);
            if (!empty($post_data) && isset($post_submits) && $post_submits != '') {
                global $wpdb;
                $prefix  = $wpdb->prefix;
                $data    = base64_decode($post_data);
                $array   = explode(',', $data);
                $book_id = $array[0];
                $api_key = $array[1];
                $getId = intval($_GET['id']);
                $aha_api = $prefix.'aha_api';
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$aha_api} SET status = '%d' , book_id = '%d'  short_code = '%d' , api_key = '%d' WHERE id = ",
                        1, $book_id , '[aha_key id=' . $getId . ' ]',$api_key, $getId
                    ) // $wpdb->prepare
                ); // $wpdb->query

            } 
        }
        public function aha_main_crul($api_key) {

            $data_args = array( 'body' => array('key' => $api_key));
            $response = wp_remote_post( 'https://www.ahathat.com/plugin-setup/app_data_api',$data_args );
            return wp_remote_retrieve_body( $response );
        }
        
        public function aha_active_key() {
            global $wpdb;
            $prefix  = $wpdb->prefix;
            $resultz = $wpdb->get_results('SELECT * FROM ' . $prefix . 'aha_key');
            return $resultz[0];
        }
        public function aha_settings($id) {
            global $wpdb;
            $prefix  = $wpdb->prefix;
            $resultz = $wpdb->get_results('SELECT * FROM ' . $prefix . 'aha_api WHERE id=' . $id);
            if (!empty($resultz)) {
                $key = ($resultz[0]->api_key != '' ? $resultz[0]->api_key : '');
            } //!empty($resultz)
            $data_aha = $wpdb->get_results("SELECT * FROM " . $prefix . "aha_stg WHERE id_wid = '" . $id . "'");
            if ($data_aha != NULL) {
                return $data_aha[0];
            } //$data_aha != NULL
            else {
                return (object) array(
                    'end_of_aha' => 0,
                    'show_powered' => 0,
                    'maximum_share' => 1,
                    'shows' => 0,
                    'categories' => '',
                    'id_wid' => $id
                );
            }
        }
        public function aha_librarie_aha($data, $ibr, $setting) {

            $data_args = array( 'body' => array('key' => $data , 'ibr' => $ibr , 'setting' => $setting));
            $response = wp_remote_post( 'https://www.ahathat.com/plugin-setup/librarie_aha',$data_args );
            return wp_remote_retrieve_body( $response );
        }
        public function aha_all_book_share($data, $ibr, $setting) {
     
            $data_args = array( 'body' => array('key' => $data , 'book_id' => $ibr , 'ahas' => $setting));
            $response = wp_remote_post( 'https://www.ahathat.com/plugin-setup/all_books_share',$data_args );
            return wp_remote_retrieve_body( $response );

        }
        public function aha_login($data, $ibr, $url) {

            $data_args = array( 'body' => array('key' => $data , 'post' => $ibr , 'ahas' => $setting));
            $response = wp_remote_post( $url , $data_args );
            return wp_remote_retrieve_body( $response );

        }
        public function aha_signup($data, $ibr, $url) {

            $data_args = array( 'body' => $ibr );
            $response = wp_remote_post( $url , $data_args );
            return wp_remote_retrieve_body( $response );
        }
        public function aha_getUserIP() {
            foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
                 if (array_key_exists($key, $_SERVER) === true) {
                        foreach (explode(',', $_SERVER[$key]) as $ip) {
                            if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                                return $ip;
                        }
                    }
                }
            }
        }
        public function aha_getorders() {
            global $wpdb;
            $prefix  = $wpdb->prefix;
            $ip      = $this->getUserIP();
            $resultz = $wpdb->get_results('SELECT * FROM ' . $prefix . 'aha_order WHERE ip_address=' . $ip);
            print_r($resultz);
            die;
        }
    }
} //!class_exists('Aha_curl_function')





