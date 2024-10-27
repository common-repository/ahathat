<?php
/*** Get all widget
 * @param $args array *
 * @return array
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function aha_get_all_widget( $args = array() ) {
    global $wpdb;
    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'ASC',
    );
    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'widget-all';
    $items     = wp_cache_get( $cache_key, 'aha' );
    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'aha_api ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );
        wp_cache_set( $cache_key, $items, 'aha' );
    }
    return $items;
}

/** * Fetch all widget from database
 * @return array
 */
function aha_get_widget_count() {
    global $wpdb;
    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'aha_api' );
}

/** * Fetch a single widget from database 
 * @param int   $id 
 * @return array
 */
function aha_get_widget( $id = 0 ) {
    global $wpdb;
    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'aha_api WHERE id = %d', $id ) );
}

/*** Insert a new widget
 * @param array $args
 */
function aha_insert_widget( $args = array() ) {
    global $wpdb;
    $defaults = array(
        'id'         => null,
        'name' => '',
        'created' => date('Y-m-d H:i:s'),
    );
    $args = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'aha_api';
    // some basic validation
    if ( empty( $args['name'] ) ) {
        return new WP_Error( 'no-name', _( 'No Name provided.', 'aha' ) );
    }
    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );
    if ( ! $row_id ) {
        // insert a new
        if ( $wpdb->insert( $table_name, $args ) ) {
			$resultz = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'aha_key ' );
			$api_data = Array ( 'api_key' => $resultz[0]->api_key ,
					'status' => '1' ,
                    'short_code' => '[aha_key id="'.$wpdb->insert_id.'"]'
                    ) ;
			$wpdb->update( $table_name, $api_data, array( 'id' => $wpdb->insert_id ) );
            return $wpdb->insert_id;
        }
    } else {
        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) ) {
            return $row_id;
        }
    }
    return false;
}