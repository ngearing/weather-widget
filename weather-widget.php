<?php

/**
 * Plugin Name: Weather Widget
 * Author: Nathan
 * Version: 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'NG_WW_VERSION', '0.0.1' );
define( 'NG_WW_PATH', plugin_dir_path( __FILE__ ) );
define( 'NG_WW_URI', plugin_dir_url( __FILE__ ) );

define( 'NG_WW_API', 'eaad7c88ca3be3e5552347b4bee21fc4' );
define(
	'NG_WW_LATLON',
	array(
		'lat' => 37.247494,
		'lon' => 144.4552171,
	)
);
define( 'NG_WW_API_FOR', 'https://api.openweathermap.org/data/2.5/forecast' );
define( 'NG_WW_API_WEA', 'https://api.openweathermap.org/data/2.5/weather' );

$params = array(
	'lat'   => NG_WW_LATLON['lat'],
	'lon'   => NG_WW_LATLON['lon'],
	'appid' => NG_WW_API,
	'cnt'   => 6,
	'mode'  => 'json',
	'units' => 'metric',
	'lang'  => 'en',
);

// Check every hour.
$last = get_option( 'ng_ww_time' );
$time = time();
if ( strtotime( '+1 hour', $last ) < $time ) {
	$url  = add_query_arg( $params, NG_WW_API_FOR );
	$resp = wp_remote_get( $url );
	$body = wp_remote_retrieve_body( $resp );
	$code = $resp['response']['code'];
	file_put_contents( NG_WW_PATH . "/ww-data_for-$code-$time.json", $body );

	$url  = add_query_arg( $params, NG_WW_API_WEA );
	$resp = wp_remote_get( $url );
	$body = wp_remote_retrieve_body( $resp );
	$code = $resp['response']['code'];
	file_put_contents( NG_WW_PATH . "/ww-data_wea-$code-$time.json", $body );

	update_option( 'ng_ww_time', $time );
}
