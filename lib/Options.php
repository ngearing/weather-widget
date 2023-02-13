<?php

namespace WeatherWidget;

class Options {
	const OPTIONS_PRE = 'ww_';
	const OPTIONS_KEY = 'settings';

	var $options = array();

	function __construct() {
		$this->options = get_option( $this::OPTIONS_PRE . $this::OPTIONS_KEY );
	}

	function set( $key = null, $value = null ) {
		if ( ! $key ) {
			$key = $this::OPTIONS_KEY;
		}
		update_option( $this::OPTIONS_PRE . $key, $value );
	}

	function get( $key = null ) {
		if ( ! $key ) {
			$key = $this::OPTIONS_KEY;
		}

		return get_option( $this::OPTIONS_PRE . $key );
	}
}
