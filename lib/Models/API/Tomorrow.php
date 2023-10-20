<?php

namespace WeatherWidget\Models\API;

class Tomorrow {

	var $api_key = '';
	var $api_url = '';
	var $client  = null;
	var $latlng = null;

	function __construct($latlng, $options) {
		$this->client  = new \GuzzleHttp\Client();
		$this->api_key = '';
		$this->api_url = 'https://api.tomorrow.io/v4/timelines';
		$this->latlng = $latlng;
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
			'location'  => sprintf( '%s, %s', $this->latlng['lat'], $this->latlng['lng'] ),
			'fields'    => $fields,
			'units'     => 'metric',
			'timesteps' => $times,
			'startTime' => 'now',
			'endTime'   => $end,
			'timezone'  => 'Australia/Melbourne',
		);
		$body = json_encode( $body );

		$request_url = "$this->api_url?apikey=$this->api_key";

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

		return $response;
	}
}
