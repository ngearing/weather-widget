<?php 

namespace WeatherWidget\Controllers;

class API {

    var $apis = [];

    function __construct() {
        
        $apis = [
            'tomorrow',
            'wunder'
        ];

        foreach( $apis as $api ) {
            $className = "Weather_Widget\Models\API\\" . ucfirst($api);
            if ( class_exists($className) ) {
                $this->apis[$api] = new $className;
            }
        }
    }
}
