<?php
/**
 * Plugin Name: Weather Widget
 * Plugin URI: https://bitbucket.org/ngearing/weather-widget/
 * Author: Nathan
 * Description: A weather widget.
 * Version: 0.2.4
 *
 * Update URI: https://bitbucket.org/ngearing/weather-widget/
 * download_url: https://bitbucket.org/ngearing/weather-widget/
 *
 * @package weather-widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

$plugin = new WeatherWidget\Plugin( __FILE__ );
$plugin->init();
