<?php
/*
* Plugin Name:  AHAthat Plugin
* Plugin URI:   https://wordpress.org/plugins/ahathat/
* Description:  Show AHAmessages on your website using AHAthat plugin (code : [aha_key = id])
* Author:       Mitchell Levy
* Author URI:   https://AHAthat.com/author
* Version:      1.6
* Requires at least: 4.0
* Tested up to: 5.6
*/
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'add_action' )) {

echo 'Hi there!  I am just a plugin, not much I can do when called directly.';

exit;

}

add_action('init', function() {
    require __DIR__  . '/includes/class-aha-admin-menu.php';
    require __DIR__  . '/includes/class-aha-widget-list-table.php';
    require __DIR__  . '/includes/aha-widget-functions.php';
    new Ahathat_admin_menu();
});
require __DIR__  . '/admin/aha_functions.php';
require __DIR__  . '/admin/aha_activation.php';
require __DIR__  . '/admin/aha_deactivation.php';
require __DIR__  . '/admin/aha_key.php';

register_activation_hook( __FILE__ , "aha_activation");
register_deactivation_hook( __FILE__ , "aha_deactivation");
add_action("wp_enqueue_scripts", "aha_enqueue_scripts");
add_action("admin_enqueue_scripts", "aha_enqueue_scripts");
add_action("wp_ajax_aha_key", "aha_key");
add_action("wp_ajax_nopriv_aha_key", "aha_key");
add_action("wp_ajax_next", "aha_key");
add_action("wp_ajax_nopriv_next", "aha_key");
add_action("wp_ajax_last", "aha_key");
add_action("wp_ajax_nopriv_last", "aha_key");
add_action("wp_ajax_aha_login", "aha_login");
add_action("wp_ajax_nopriv_aha_login", "aha_login");
add_action("wp_ajax_aha_share", "aha_share");
add_action("wp_ajax_nopriv_aha_share", "aha_share");
add_action("wp_ajax_aha_single_books", "aha_single_books");
add_action("wp_ajax_nopriv_aha_single_books", "aha_single_books");


if ( !function_exists( 'aha_enqueue_scripts' )) {
    function aha_enqueue_scripts()
    {

        $actual_link = $_SERVER['REQUEST_URI'];

        if (strpos($actual_link, 'AHAthat_widget')) {
            wp_enqueue_style( 'bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
            wp_enqueue_style( "aha-style" , plugins_url('assets/css/style.css',__FILE__ ));
            wp_enqueue_script( 'bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array( 'jquery' ));
            wp_enqueue_script( "admin" , plugins_url('assets/js/admin.js', __FILE__ ) ,array('jquery'),'',true);
            wp_enqueue_script( "aha" , plugins_url('assets/js/aha.js', __FILE__ ),array('jquery'),'',true);   

        }
        wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css'); 
        wp_enqueue_style('font-awesome', plugins_url('assets/css/fonts.css',__FILE__ )); 
        wp_enqueue_style( "aha" ,  plugins_url('assets/css/aha.css',__FILE__ ));     
        wp_enqueue_script( "jscolormin" ,  plugins_url('assets/js/jscolor.min.js', __FILE__ ));
        wp_enqueue_script( "functions" ,plugins_url('assets/js/functions.js', __FILE__ ),array('jquery'),'',true);                 
        wp_localize_script( 'functions', 'my_ajax_obj', array('ajax_url' => esc_url(admin_url('admin-ajax.php')),'nonce' => wp_create_nonce('ajax-nonce')));
    }
}

if ( !function_exists( 'aha_share' )) {

    function aha_share() {
        $nonce = sanitize_key($_POST['_ajax_nonce']);
        if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
            die ( 'Access denied..');
        require __DIR__ . "/admin/aha_share.php";
    }
}

if ( !function_exists( 'aha_login' )) {

    function aha_login() {
        $nonce = sanitize_key($_POST['_ajax_nonce']);
        if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
            die ( 'Access denied..');
        require __DIR__ . "/admin/aha_login.php";
    }
}

if ( !function_exists( 'aha_single_books' )) {

    function aha_single_books() {
        $nonce = sanitize_key($_POST['_ajax_nonce']);
        if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
            die ( 'Access denied..');
        require __DIR__ . "/admin/aha_single_books.php";
    }
}

if ( !function_exists( 'ajax_ahathat_widget' )) {

    function ajax_ahathat_widget() {
        $nonce = sanitize_key($_POST['_ajax_nonce']);
        if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
            die ( 'Access denied..');
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        echo $action;
        die();
    }

}

if ( !function_exists( 'aha_save' )) {

    function aha_save(){
        if ( ! isset( $_POST['submit_widget'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-widget' ) ) {
            die( _( 'Are you cheating?', 'aha' ) );
        }
        if ( ! current_user_can( 'read' ) ) {
            wp_die( _( 'Permission Denied!', 'aha' ) );
        }
        $errors   = array();
        $page_url = admin_url( 'admin.php?page=AHAthat_widget' );
        $field_id = isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0;
        $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        // some basic validation
        if ( ! $name ) {
        $errors[] = _( 'Error: Name is required', 'aha' );
        }
        // bail out if error found
        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        $fields = array(
            'name' => $name,
        );
        // New or edit?
        if ( ! $field_id ) {
            $insert_id = aha_insert_widget( $fields );
        } else {
          $fields['id'] = $field_id;
          $insert_id = aha_insert_widget( $fields );
        }
        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'message' => 'error' ), $page_url );
        } else {
            $redirect_to = add_query_arg( array( 'message' => 'success' ), $page_url );
        }
        wp_safe_redirect( $redirect_to );
        exit;
    }
}

add_action( 'admin_init', 'aha_save' );
add_action('wp_ajax_ahathat_widget', 'ajax_ahathat_widget');
add_action('wp_ajax_nopriv_ahathat_widget', 'ajax_ahathat_widget');
add_shortcode("aha_key", "aha_key");