<?php

namespace WeatherWidget\Models\API;

class Tomorrow {

	var $api_key = '';
	var $api_url = '';
	var $client  = null;

	function __construct() {
		$this->client  = new \GuzzleHttp\Client();
		$this->api_key = '2WKHCYOq4HTFbaD9gd3Q7zm8AunDMmie';
		$this->api_url = 'https://api.tomorrow.io/v4/timelines';
	}

	function get( $when = '' ) {
		// TODO: Combine query into 1?
		$fields = array(
			'temperatureMin',
			'temperatureMax',
			'weatherCode',
			'sunriseTime',
			'sunsetTime',
		);
		$end    = 'nowPlus6d';
		$times  = array( '1d' );

		if ( $when == 'today' ) {
			$fields = array(
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
				'uvIndex',
				'weatherCode',
			);
			$end    = 'nowPlus1h';
			$times  = array( '1h' );
		}

		$body = array(
			'location'  => sprintf( '%s, %s', NG_WW_LATLON['lat'], NG_WW_LATLON['lon'] ),
			'fields'    => $fields,
			'units'     => 'metric',
			'timesteps' => $times,
			'startTime' => 'now',
			'endTime'   => $end,
			'timezone'  => 'Australia/Melbourne',
		);
		$body = json_encode( $body );

		$request_url = "$this->api_url?apikey=$this->api_key";
		// https://api.tomorrow.io/v4/timelines?apikey=2WKHCYOq4HTFbaD9gd3Q7zm8AunDMmie'

		try {
			$response = $this->client->request(
				'POST',
				$request_url,
				array(
					'body'    => $body,
					'headers' => array(
						'Accept-Encoding' => 'gzip',
						'accept'          => 'application/json',
						'content-type'    => 'application/json',
					),
				)
			);
		} catch ( \Exception $e ) {
			$response             = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			error_log($responseBodyAsString);
			return false;
		}

		$options = new Options();
		if ( $when == 'today' ) {
			$options->set( 'data_today', $response->getBody()->getContents() );
			$options->set( 'last_checked_today', time() );
		} else {
			$options->set( 'data', $response->getBody()->getContents() );
			$options->set( 'last_checked', time() );
		}

		return $response->getBody()->getContents();
	}
}
