<?php

namespace WeatherWidget;

class Icons {

	var $codes = array(
		1000 => 'clear',
		1001 => 'cloudy',
		4000 => 'drizzle',
		5001 => 'flurries',
		2100 => 'fog_light',
		2000 => 'fog',
		6000 => 'freezing_drizzle',
		6201 => 'freezing_rain_heavy',
		6200 => 'freezing_rain_light',
		6001 => 'freezing_rain',
		7101 => 'ice_pellets_heavy',
		7102 => 'ice_pellets_light',
		7000 => 'ice_pellets',
		1100 => 'mostly_clear',
		1102 => 'mostly_cloudy',
		1101 => 'partly_cloudy',
		4201 => 'rain_heavy',
		4200 => 'rain_light',
		4001 => 'rain',
		5101 => 'snow_heavy',
		5100 => 'snow_light',
		5000 => 'snow',
		8000 => 'tstorm',
	);

	var $plugin = null;

	function __construct($plugin) {
		$this->plugin = $plugin;
	}

	function get( $code = '1000', $day = true, $size = 'small' ) {
		$name = $this->codes[ $code ];

		return sprintf(
			'%sassets/images/%s/%s%s_%s_%s.png',
			$this->plugin['url'],
			$size,
			$code,
			$day ? '0' : '1',
			$name,
			$size
		);
	}

	function getSVG( $name ) {
		$file = file_get_contents( $this->plugin->dir . "/assets/images/svg/$name.svg" );

		return "<span class='icon'>$file</span>";
	}
}
