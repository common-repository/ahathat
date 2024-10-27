<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*** Admin Menu*/
if ( !class_exists( 'Ahathat_admin_menu' )) {

  class Ahathat_admin_menu {
      /*** Kick-in the class */
      public function __construct() {
          add_action( 'admin_menu', array( $this, 'aha_admin_menu' ) );
      }
      /*** Add menu items
       * @return void
       */
      public function aha_admin_menu() {
        /** Top Menu **/
        add_menu_page( __( 'AHAthat_widget'), __( 'AHAthat'), 'manage_options', 'AHAthat_widget', array( $this, 'aha_plugin_page' ), 'dashicons-book-alt', null );
        add_submenu_page( 'AHAthat_widget', __( 'Configuration'), __( 'Configuration'), 'manage_options', 'configuration', array( $this, 'aha_plugin_pages' ) );
        add_submenu_page( 'AHAthat_widget', __( 'Content Shared'), __( 'Content Shared'), 'manage_options', 'share_report', array( $this, 'aha_share_report' ) );
      }
      /*** Handles the plugin page
       * @return void
       */
      public function aha_plugin_page() {
          $action = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action']) : 'list';
          $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
             switch ($action) {   
              case 'view':
                    $template = dirname( __FILE__ ) . '/views/aha-widget-single.php';
                  break;
              case 'edit':
                    $template = dirname( __FILE__ ) . '/views/aha-widget-edit.php';
                  break;
              case 'new':
                    $template = dirname( __FILE__ ) . '/views/aha-widget-new.php';
                  break;
              case 'setting':
                    $template = dirname (dirname( __FILE__ ) )."/admin/aha_setitng.php";
                  break;
              case 'share':
                    $template = dirname (dirname( __FILE__ ) )."/admin/aha_export-csv.php";
                  break; 
              case 'delete':
                  global $wpdb;
                    $table_name = $wpdb->prefix . 'aha_api';
                    $ids = isset($_REQUEST['id']) ? wp_unslash($_REQUEST['id']) : array();
                    if (is_array($ids)) $ids = implode(',', $ids);
                    if (!empty($ids)) {
                      $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
                    } 
                    $template = dirname( __FILE__ ) . '/views/aha-widget-list.php';
                 break;
              default:
                    $template = dirname( __FILE__ ) . '/views/aha-widget-list.php';
                  break;
           }

          if ( file_exists( $template ) ) {
              include $template;
          }
      }
    public function aha_plugin_pages() {
       include dirname (dirname( __FILE__ ) )."/admin/aha_dashboard.php";
    } 
    public function aha_share_report() {
      include dirname (dirname( __FILE__ ) )."/admin/aha_export-csv.php";
    }
  }
}