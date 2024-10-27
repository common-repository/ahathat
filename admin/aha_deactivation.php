<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( !function_exists( 'aha_deactivation' )) {

	function aha_deactivation()
	{
	     global $wpdb;     
		$table_name = $wpdb->prefix . "aha_api";     
		$sql = "DROP TABLE IF EXISTS $table_name;";     
		$wpdb->query($sql);     
		delete_option("1.0");

		$table_name = $wpdb->prefix . "aha_report";     
		$sql = "DROP TABLE IF EXISTS $table_name;";     
		$wpdb->query($sql);     
		delete_option("1.0");

		$table_name = $wpdb->prefix . "aha_settings";     
		$sql = "DROP TABLE IF EXISTS $table_name;";     
		$wpdb->query($sql);     
		delete_option("1.0");
		
		$table_name = $wpdb->prefix . "aha_stg";     
		$sql = "DROP TABLE IF EXISTS $table_name;";     
		$wpdb->query($sql);     
		delete_option("1.0");

		$table_name = $wpdb->prefix . "aha_order";     
		$sql = "DROP TABLE IF EXISTS $table_name;";     
		$wpdb->query($sql);     
		delete_option("1.0");
		
		$table_name = $wpdb->prefix . "aha_key";     
		$sql = "DROP TABLE IF EXISTS $table_name;";     
		$wpdb->query($sql);     
		delete_option("1.0");
	}
}
