<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    if ( ! class_exists ( 'WP_List_Table' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
    }
    /*** List table class*/
if ( !class_exists( 'Aha_display_short_code' )) {
    class Aha_display_short_code extends \WP_List_Table {
        function _construct() {
            parent::_construct( array(
                'singular' => 'widget',
                'plural'   => 'widgets',
                'ajax'     => false
            ) );
        }
        function get_table_classes() {
            return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
        }
        /*** Message to show if no designation found
         * @return void
         */
        function no_items() {
            _e( 'no widget founds');
        }
        /*** Default column values if no callback found
         * @param  object  $item
         * @param  string  $column_name
         *
         * @return string
         */
        function column_default( $item, $column_name ) {
            switch ( $column_name ) {
                case 'name':
                    return $item->name;
                case 'short_code':
                    return $item->short_code;
                case 'created':
                    return $item->created;
                default:
                    return isset( $item->$column_name ) ? $item->$column_name : '';
            }
        }
        /*** Get the column names
         * @return array
         */
        function get_columns() {
            $columns = array(
                'cb'           => '<input type="checkbox" />',
                'name'      => "Name",
                'short_code'      => 'Short Code',
                'created'      => 'Date',
            );
            return $columns;
        }
        /*** Render the designation name column
         * @param  object  $item
         * @return string
         */
        function column_name( $item ) {
            $actions           = array();
            $actions['edit']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=AHAthat_widget&action=edit&id=' . $item->id ), $item->id, _( 'Edit this item'), _( 'Edit') );
            $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=AHAthat_widget&action=delete&id=' . $item->id ), $item->id, _( 'Delete this item'), _( 'Delete') );
            return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=AHAthat_widget&action=view&id=' . $item->id ), $item->name, $this->row_actions( $actions ) );
          }
        /*** Get sortable colum
         ** @return array
         */
        function get_sortable_columns() {
            $sortable_columns = array(
                'name' => array( 'name', true ),
            );
            return $sortable_columns;
        }
        /*** Set the bulk actions
        * @return array
        */
        function get_bulk_actions() {
            $actions = array(
            'trash'  => _( 'Move to Trash'),
            );
            return $actions;
        }
        /*** Render the checkbox column
         * @param  object  $item
         * @return string
        */
        function column_cb( $item ) {
              return sprintf(
                '<input type="checkbox" name="widget_id[]" value="%d" />', $item->id
            );
        }
        /*** Set the views
         * @return array
        */
        public function get_views() {
            $status_links   = array();
            $base_link      = admin_url( 'admin.php?page=sample-page' );
            foreach ($this->counts as $key => $value) {
                $class = ( $key == $this->page_status ) ? 'current' : 'status-' . $key;
                $status_links[ $key ] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg( array( 'status' => $key ), $base_link ), $class, $value['label'], $value['count'] );
            }
            return $status_links;
        }
        /*** Prepare the class items
         ** @return void
         */
        function prepare_items() {
            $columns               = $this->get_columns();
            $hidden                = array( );
            $sortable              = $this->get_sortable_columns();
            $this->_column_headers = array( $columns, $hidden, $sortable );
            $per_page              = 10;
            $current_page          = $this->get_pagenum();
            $offset                = ( $current_page -1 ) * $per_page;
            $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';
            // only ncessary because we have sample data
            $args = array(
                'offset' => $offset,
                'number' => $per_page,
            );
            if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
                $args['orderby'] = sanitize_text_field($_REQUEST['orderby']);
                $args['order']   = sanitize_text_field($_REQUEST['order']) ;
            }
            $this->items  = aha_get_all_widget( $args );
            $this->set_pagination_args( array(
                'total_items' => aha_get_widget_count(),
                'per_page'    => $per_page
            ) );
        }
    }
}