<?php

/**
 * Plugin Name: Weather Widget
 * Plugin URI: https://bitbucket.org/ngearing/weather-widget/
 * Author: Nathan
 * Description: A weather widget.
 * Version: 0.1.1
 *
 * Update URI: https://bitbucket.org/ngearing/weather-widget/
 * download_url: https://bitbucket.org/ngearing/weather-widget/
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

define( 'NG_WW_VERSION', '0.0.3' );
define( 'NG_WW_PATH', plugin_dir_path( __FILE__ ) );
define( 'NG_WW_URI', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, 'ng_ww_activate' );
function ng_ww_activate() {
	if ( ! wp_next_scheduled( 'ng_ww_get_week_data' ) ) {
		wp_schedule_event( time(), 'hourly', 'ng_ww_get_week_data' );
	}
	if ( ! wp_next_scheduled( 'ng_ww_get_today_data' ) ) {
		wp_schedule_event( time(), 'hourly', 'ng_ww_get_today_data' );
	}
}

register_deactivation_hook( __FILE__, 'ng_ww_deactivate' );
function ng_ww_deactivate() {
	wp_clear_scheduled_hook( 'ng_ww_get_week_data' );
	wp_clear_scheduled_hook( 'ng_ww_get_today_data' );
}

function ng_ww_get_week_data() {
	$api = new \WeatherWidget\API();
	$api->get( 'week' );
}

function ng_ww_get_today_data() {
	$api = new \WeatherWidget\API();
	$api->get( 'today' );
}

define( 'NG_WW_API', 'eaad7c88ca3be3e5552347b4bee21fc4' );
define(
	'NG_WW_LATLON',
	array(
		'lat' => -37.2227237,
		'lon' => 144.1772286,
	)
);
define( 'NG_WW_API_FOR', 'https://api.openweathermap.org/data/2.5/forecast' );
define( 'NG_WW_API_WEA', 'https://api.openweathermap.org/data/2.5/weather' );

function ng_ww_scripts() {
	wp_register_style( 'ng_ww', NG_WW_URI . 'ww.css' );
}
add_action( 'wp_enqueue_scripts', 'ng_ww_scripts' );


// Plugin updates
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://bitbucket.org/ngearing/weather-widget/',
	__FILE__,
	'weather-widget'
);
