<?php

namespace WeatherWidget\Models\API;

class Wunder {

	var $stationID = '';
	var $api_key   = '';
	var $api_url   = '';
	var $client    = null;

	function __construct() {
		$this->client    = new \GuzzleHttp\Client();
		$this->stationID = 'IKYNET36';
		$this->api_key   = '806e8f0fde414af3ae8f0fde418af305';
		$this->api_url   = 'https://api.weather.com/v2/pws/observations/current';
	}

	function get( $when = '' ) {
		$request_url = "$this->api_url?stationId=$this->stationID&format=json&units=m&apiKey=$this->api_key";

		try {
			$response = $this->client->request(
				'GET',
				$request_url
			);
		} catch ( \Exception $e ) {
			$response             = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			error_log( $responseBodyAsString );
			return false;
		}

		$options = new Options();
		// $response         = $response->getResponse();
		$responseBody     = $response->getBody();
		$responseContents = $responseBody->getContents();

		$options->set( 'data', $responseContents );
		$options->set( 'last_checked', time() );

		return json_decode( $responseContents );
	}
}
