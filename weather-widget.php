<?php

/**
 * Plugin Name: Weather Widget
 */

if ( ! defined('ABSPATH') ) {
    die;
}

define('NG_WW_VERSION', '0.0.1' );
define('NG_WW_PATH', plugin_dir_path( __FILE__ ) );
define('NG_WW_URI', plugin_dir_url( __FILE__ ) );

define('NG_WW_API', 'eaad7c88ca3be3e5552347b4bee21fc4');
define('NG_WW_LATLON', ['lat' => 37.247494, 'lon' => 144.4552171] );
define('NG_WW_API_URL', 'https://api.openweathermap.org/data/2.5/forecast' );

$params = [
    'lat' => NG_WW_LATLON[0],
    'lon' => NG_WW_LATLON[1],
    'appid' => NG_WW_API,
    'cnt' => 7,
    'mode' => 'json',
    'units' => 'metric',
    'lang' => 'en',
];

// Check every hour.
$last = get_option('ng_ww_time');
$time = time();
if ( strtotime('+1 hour', $last) < $time ) {
    $resp = wp_remote_get(NG_WW_API_URL, $params);
    $body = wp_remote_retrieve_body( $resp );

    update_option('ng_ww_time', $time);
    file_put_contents( NG_WW_PATH . "/ww-data_$time.json", $body );
}
