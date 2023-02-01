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
		'lat' => -37.2227237,
		'lon' => 144.1772286,
	)
);
define( 'NG_WW_API_FOR', 'https://api.openweathermap.org/data/2.5/forecast' );
define( 'NG_WW_API_WEA', 'https://api.openweathermap.org/data/2.5/weather' );

$params = array(
	'lat'   => NG_WW_LATLON['lat'],
	'lon'   => NG_WW_LATLON['lon'],
	'appid' => NG_WW_API,
	'cnt'   => 48,
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


function ng_ww_scripts() {
	wp_register_style( 'ng_ww', NG_WW_URI . 'ww.css' );
}
add_action( 'wp_enqueue_scripts', 'ng_ww_scripts' );


add_shortcode( 'ww', 'ng_shortcode_ww' );

function ng_shortcode_ww( $attrs = array() ) {

	$content = '';

	$last_data = get_option( 'ng_ww_time' );
	$data      = file_get_contents( NG_WW_PATH . "/ww-data_for-200-$last_data.json" );
	$data      = json_decode( $data );
	if ( ! $data ) {
		return;
	}

	$days = array();
	foreach ( $data->list as $day ) {
		$days[ date( 'Y-m-d', $day->dt ) ][] = $day;
	}

	wp_enqueue_style( 'ng_ww' );

	date_default_timezone_set( wp_timezone_string() );

	$content .= sprintf( '<ul class="weather-widget-list">' );
	$content .= sprintf( '<h4 class="time">%s</h4>', date( 'M d, H:ia' ) );
	$content .= sprintf( '<h3 class="location">%s</h3>', 'Kyneton, AU' );

	foreach ( $days as $key => $day ) :
		if ( $key === date( 'Y-m-d' ) ) {
			ob_start();
			include NG_WW_PATH . '/parts/current.php';
			$content .= ob_get_clean();
		}

		ob_start();
		include NG_WW_PATH . '/parts/day.php';
		$content .= ob_get_clean();

	endforeach;

	$content .= sprintf( '</ul>' );

	return $content;
}


function compass_direction( $deg = 0 ) {
	$dir = 'N';

	$list = array(
		'N'   => '0 - 11.25',
		'NNE' => '11.25 - 33.75',
		'NE'  => '33.75 - 56.25',
		'ENE' => '56.25 - 78.75',
		'E'   => '78.75 - 101.25',
		'ESE' => '101.25 - 123.75',
		'SE'  => '123.75 - 146.25',
		'SSE' => '146.25 - 168.75',
		'S'   => '168.75 - 191.25',
		'SSW' => '191.25 - 213.75',
		'SW'  => '213.75 - 236.25',
		'WSW' => '236.25 - 258.75',
		'W'   => '258.75 - 281.25',
		'WNW' => '281.25 - 303.75',
		'NW'  => '303.75 - 326.25',
		'NNW' => '326.25 - 348.75',
		'N'   => '348.75 - 360',
	);

	foreach ( $list as $k => $v ) {
		$range = explode( ' - ', $v );
		if ( $deg == $range[0] || $deg > $range[0] ) {
			$dir = $k;
		}
	}

	return $dir;
}

function ng_ww_tomorrow_api() {
	require_once 'vendor/autoload.php';

	$client = new \GuzzleHttp\Client();

	$response = $client->request(
		'GET',
		'https://api.tomorrow.io/v4/timelines?location=-37.2227237%2C%20144.1772286&fields=temperature&fields=temperatureApparent&fields=dewPoint&fields=humidity&fields=windSpeed&fields=windDirection&fields=pressureSurfaceLevel&fields=sunriseTime&fields=sunsetTime&fields=visibility&fields=weatherCodeFullDay&units=metric&timesteps=1d&startTime=now&endTime=nowPlus6d&timezone=Australia%2FCanberra&apikey=2WKHCYOq4HTFbaD9gd3Q7zm8AunDMmie',
		array(
			'headers' => array(
				'Accept-Encoding' => 'gzip',
				'accept'          => 'application/json',
			),
		)
	);

	echo $response->getBody();
}
