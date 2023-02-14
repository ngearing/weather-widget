<?php

use WeatherWidget\Options;
use WeatherWidget\API;

add_shortcode( 'ww', 'ng_shortcode_ww' );
function ng_shortcode_ww( $attrs = array() ) {

	$content = '';

	$options = new Options();

	wp_enqueue_style( 'ng_ww' );

	date_default_timezone_set( wp_timezone_string() );

	$content .= sprintf( '<ul class="weather-widget-list">' );
	$content .= sprintf( '<h4 class="time">%s</h4>', date( 'M d, h:ia' ) );
	$content .= sprintf( '<h3 class="location">%s</h3>', 'Kyneton, AU' );

	// Today forecast
	$data = json_decode( $options->get( 'data_today' ) );
	// Run import if no data or data old.
	if ( ! $data ) {
		$data = ( new API() )->get( 'today' );
		// TODO: Add check here for old data.
	}
	if ( $data ) {
		ob_start();
		include NG_WW_PATH . '/parts/today.php';
		$content .= ob_get_clean();
	}

	// Week forecast
	$data = json_decode( $options->get( 'data' ) );
	// Run import if no data or data old.
	if ( ! $data ) {
		$data = ( new API() )->get( 'week' );
		// TODO: Add check here for old data.
	}
	if ( $data ) {
		ob_start();
		include NG_WW_PATH . '/parts/week.php';
		$content .= ob_get_clean();
	}

	$content .= sprintf( '</ul>' );

	return $content;
}