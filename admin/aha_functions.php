<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( !function_exists( 'print_aha' )) {
	function print_aha( $array = array() )
	{
		echo "<pre>".print_r($array,true)."</pre>";
	}
}
if ( !function_exists( 'aha_array' )) {
	function aha_array( $array = array() , $key = "" )
	{
		$arr = array();
		foreach ( $array as $data )
		{
			if( is_object( $data ) )
			{
				if( isset( $data->$key ) )
				{
					$arr[] = $data->$key;
				}
			} elseif( is_array( $data ) ) {
				if( isset( $data[$key] ) )
				{
					$arr[] = $data[$key];
				}
			}
		}
		return $arr;
	}
}
