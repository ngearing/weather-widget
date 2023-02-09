<?php


class WeatherCodes {

    var $codes = [];

    function __construct() {
        $codes = file_get_contents(__DIR__ . '/weather-codes.json');
        if ($codes) {
            $this->codes = json_decode($codes);
        }
    }

    function get($code = '1000', $key = 'weatherCodeFullDay') {
        return $this->codes->$key->$code;
    }
}
