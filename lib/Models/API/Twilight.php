<?php

namespace WeatherWidget\Models\API;

class Twilight {

	var $api_url  = '';
	var $client   = null;
	var $lat      = null;
	var $lng      = null;
	var $response = null;
	var $data     = null;

	function __construct( $latlng, $options ) {
		$this->client  = new \GuzzleHttp\Client( array( 'verify' => false ) );
		$this->api_url = 'https://api.sunrise-sunset.org/json';
		$this->lat     = $latlng['lat'];
		$this->lng     = $latlng['lng'];
	}

	function get() {
		$request_url = "$this->api_url?lat=$this->lat&lng=$this->lng&date=today&formatted=0";

		$response = $this->client->request(
			'GET',
			$request_url
		);

		$this->response = json_decode( $response->getBody()->getContents() );
		return $this->response;
	}

	function get_results() {
		$this->data = $this->response->results;
		return $this->data;
	}
}
