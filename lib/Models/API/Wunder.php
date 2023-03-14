<?php

namespace WeatherWidget\Models\API;

class Wunder {

	var $check     = '5 minutes';
	var $stationID = '';
	var $api_key   = '';
	var $api_url   = '';
	var $client    = null;
	var $response  = null;
	var $data      = null;

	function __construct() {
		$this->client    = new \GuzzleHttp\Client( array( 'verify' => false ) );
		$this->stationID = 'IKYNET36';
		$this->api_key   = '806e8f0fde414af3ae8f0fde418af305';
		$this->api_url   = 'https://api.weather.com/v2/pws/observations/current';
	}

	function get() {
		$request_url = "$this->api_url?stationId=$this->stationID&format=json&units=m&apiKey=$this->api_key";

		$response = $this->client->request(
			'GET',
			$request_url
		);

		$this->response = json_decode( $response->getBody()->getContents() );
		return $this->response;
	}

	function get_results() {
		$this->data = (object) array_merge( (array) $this->response->observations[0], (array) $this->response->observations[0]->metric );
		unset( $this->data->metric );
		return $this->data;
	}
}
