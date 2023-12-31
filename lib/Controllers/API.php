<?php

namespace WeatherWidget\Controllers;

class API {

	var $options = null;
	var $apis    = array();
	var $check   = 'hourly';
	var $latlng  = array(
		'lat' => -37.2227237,
		'lng' => 144.1772286,
	);
	var $data    = null;

	function __construct( $options ) {
		$this->options = $options;

		$apis = array(
			// 'tomorrow',
			'wunder',
			'twilight',
		);

		$this->data = $this->get_data();

		foreach ( $apis as $api ) {
			$className = 'WeatherWidget\Models\API\\' . ucfirst( $api );
			if ( class_exists( $className ) ) {
				$this->apis[ $api ] = new $className( $this->latlng, $this->options );
			}
		}
	}

	function get() {
		date_default_timezone_set( wp_timezone_string() );

		$data = $this->data ?: array();

		foreach ( $this->apis as $key => $api ) {
			// Check if current data is outdated.
			$last_checked = $this->options->get( "last_checked_$key" );
			$api_check    = $this->get_formatted_check( $api->check );
			if ( strtotime( $api_check, $last_checked ) < time() ) {
				$new_data = array();
				$new_data = $this->fetch( $key );

				if ( ! $new_data ) {
					continue;
				}

				$new_data = $this->filter( $new_data );
				$new_data = $this->format( $new_data );

				// Update new data.
				$data = (object) array_replace( (array) $data, (array) $new_data );

				$this->options->set( "last_checked_$key", time() );
				$this->set_data( $data );
			}
		}

		return $data;
	}

	/**
	 * Get data from database and decode.
	 *
	 * @return array
	 */
	function get_data() {
		return json_decode( $this->options->get( 'data' ) );
	}

	/**
	 * Format and store data in database.
	 *
	 * @return bool
	 */
	function set_data( $data ) {
		$this->data = $data;
		return $this->options->set( 'data', json_encode( $data ) );
	}

	function get_formatted_check( $check = false ) {
		$check   = $check ?: $this->check;
		$formats = array(
			'5 minutes' => '+5 min',
			'hourly'    => '+1 hour',
		);

		return $formats[ $check ];
	}

	function fetch( $api ) {
		$data = array();

		try {
			$response = $this->apis[ $api ]->get();
			if ( ! $response ) {
				return false;
			}
			$response = $this->apis[ $api ]->get_results();
			$data     = $response;
		} catch ( \Exception $e ) {
			// TODO: Something with error.
			$data = false;
		}

		return $data;
	}

	function filter( $data = array() ) {
		return $data;
	}

	function format( $data = array() ) {
		return $data;
	}
}
