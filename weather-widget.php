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

register_activation_hook(__FILE__, 'ng_ww_activate');
function ng_ww_activate() {
    if ( ! wp_next_scheduled('ng_ww_get_week_data')) {
        wp_schedule_event(time(), 'hourly', 'ng_ww_get_week_data');
    }
    if ( ! wp_next_scheduled('ng_ww_get_today_data')) {
        wp_schedule_event(time(), 'hourly', 'ng_ww_get_today_data');
    }
}

register_deactivation_hook(__FILE__, 'ng_ww_deactivate');
function ng_ww_deactivate() {
    wp_clear_scheduled_hook('ng_ww_get_week_data');
    wp_clear_scheduled_hook('ng_ww_get_today_data');
}

function ng_ww_get_week_data() {
	$api = new API();
	$api->get('week');
}

function ng_ww_get_today_data() {
	$api = new API();
	$api->get('today');
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

// $params = array(
// 	'lat'   => NG_WW_LATLON['lat'],
// 	'lon'   => NG_WW_LATLON['lon'],
// 	'appid' => NG_WW_API,
// 	'cnt'   => 48,
// 	'mode'  => 'json',
// 	'units' => 'metric',
// 	'lang'  => 'en',
// );

// // Check every hour.
// $last = get_option( 'ng_ww_time' );
// $time = time();
// if ( strtotime( '+1 hour', $last ) < $time ) {
// 	// $url  = add_query_arg( $params, NG_WW_API_FOR );
// 	// $resp = wp_remote_get( $url );
// 	// $body = wp_remote_retrieve_body( $resp );
// 	// $code = $resp['response']['code'];
// 	// file_put_contents( NG_WW_PATH . "/ww-data_for-$code-$time.json", $body );

// 	// $url  = add_query_arg( $params, NG_WW_API_WEA );
// 	// $resp = wp_remote_get( $url );
// 	// $body = wp_remote_retrieve_body( $resp );
// 	// $code = $resp['response']['code'];
// 	// file_put_contents( NG_WW_PATH . "/ww-data_wea-$code-$time.json", $body );

//     ng_ww_tomorrow_api();

// 	update_option( 'ng_ww_time', $time );
// }

function ng_ww_scripts() {
	wp_register_style( 'ng_ww', NG_WW_URI . 'ww.css' );
}
add_action( 'wp_enqueue_scripts', 'ng_ww_scripts' );


add_shortcode( 'ww', 'ng_shortcode_ww' );
function ng_shortcode_ww( $attrs = array() ) {

	require_once __DIR__ . '/lib/WeatherCodes.php';
	require_once __DIR__ . '/lib/WeatherIcons.php';

	$content = '';

	$options = new Options();


	wp_enqueue_style( 'ng_ww' );

	date_default_timezone_set( wp_timezone_string() );

	$content .= sprintf( '<ul class="weather-widget-list">' );
	$content .= sprintf( '<h4 class="time">%s</h4>', date( 'M d, h:ia' ) );
	$content .= sprintf( '<h3 class="location">%s</h3>', 'Kyneton, AU' );

	// Today forecast
	$data = $options->get('data_today');
	if ( $data ) {
		ob_start();
		include NG_WW_PATH . '/parts/today.php';
		$content .= ob_get_clean();
	}

	// Week forecast
	$data = $options->get('data');
	if ( $data ) {
		ob_start();
		include NG_WW_PATH . '/parts/week.php';
		$content .= ob_get_clean();
	}

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


class Options {
	const OPTIONS_PRE = 'ww_';
	const OPTIONS_KEY = 'settings';

	var $options = [];

	function __construct() {
		$this->options = get_option($this::OPTIONS_PRE . $this::OPTIONS_KEY);
	}

	function set($key = null, $value = null) {
		if ( ! $key ) {
			$key = $this::OPTIONS_KEY;
		}
		update_option( $this::OPTIONS_PRE . $key, $value );
	}

	function get($key = null) {
		if ( ! $key ) {
			$key = $this::OPTIONS_KEY;
		}

		return get_option($this::OPTIONS_PRE . $key);
	}
}


class API {

	var $api_key = '';
	var $api_url = '';
	var $client = null;

	function __construct() {
		require_once 'vendor/autoload.php';
		$this->client = new GuzzleHttp\Client();
		$this->api_key = '2WKHCYOq4HTFbaD9gd3Q7zm8AunDMmie';
		$this->api_url = 'https://api.tomorrow.io/v4/timelines';
	}

	function get($when = '') {
		// TODO: Combine query into 1?
		$fields = [
			'temperatureMin',
			'temperatureMax',
			'weatherCode',
			'sunriseTime',
			'sunsetTime',
		];
		$end = 'nowPlus6d';
		$times = ['1d'];

		if ( $when == 'today' ) {
			$fields = [
				'temperature',
				'temperatureApparent',
				'rainIntensity',
				'snowIntensity',
				'windSpeed',
				'windDirection',
				'pressureSurfaceLevel',
				'humidity',
				'visibility',
				'dewPoint',
				'weatherCode',
			];
			$end = 'nowPlus1h';
			$times = ['1h'];
		}
	
		$body = [
			'location' => sprintf("%s, %s", NG_WW_LATLON['lat'], NG_WW_LATLON['lon']),
			'fields' => $fields,
			'units' => 'metric',
			'timesteps' => $times,
			"startTime" => "now",
			"endTime" => $end,
			"timezone" => "Australia/Melbourne"
		];
		$body = json_encode($body);

		$request_url = "$this->api_url?apikey=$this->api_key";
		// https://api.tomorrow.io/v4/timelines?apikey=2WKHCYOq4HTFbaD9gd3Q7zm8AunDMmie'

		try {
			$response = $this->client->request(
				'POST', 
				$request_url, 
				[
					'body' => $body,
					'headers' => [
						'Accept-Encoding' => 'gzip',
						'accept' => 'application/json',
						'content-type' => 'application/json',
					],
				]
			);
		} catch ( \Exception $e ) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			echo $responseBodyAsString;
		}

		$options = new Options();
		if ( $when == 'today' ) {
			$options->set('data_today', $response->getBody()->getContents() );
		} else {
			$options->set('data', $response->getBody()->getContents() );
		}
	}
}
