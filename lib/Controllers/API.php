<?php 

namespace WeatherWidget\Controllers;

class API {

    var $options = null;
    var $apis = [];
    var $check = 'hourly';
    var $latlng = [
        'lat' => -37.2227237,
        'lng' => 144.1772286
    ];
    var $data = null;

    function __construct($options) {
        $this->options = $options;

        $apis = [
            // 'tomorrow',
            'wunder',
            'twilight',
        ];

        $this->data = $this->get_data();

        foreach( $apis as $api ) {
            $className = "WeatherWidget\Models\API\\" . ucfirst($api);
            if ( class_exists($className) ) {
                $this->apis[$api] = new $className($this->latlng, $this->options);
            }
        }
    }

    function get() {
        date_default_timezone_set( wp_timezone_string() );

        // Check if current data is outdated.
        $last_checked = $this->options->get('last_checked');
        if ( strtotime($this->get_formatted_check($this->check), $last_checked) > time() ) {
            return $this->data;
        }

        $data = $this->fetch();
        $data = $this->filter();
        $data = $this->format();

        // $this->options->set('last_checked', time());
        $this->set_data($data);

        return $data;
    }

    /**
     * Get data from database and decode.
     *
     * @return array
     */
    function get_data() {
        return json_decode($this->options->get('data'));
    }

    /**
     * Format and store data in database.
     *
     * @return bool
     */
    function set_data($data) {
        $this->data = $data;
        return $this->options->set('data', json_encode($data));
    }

    function get_formatted_check() {
        $check = $this->check;
        $formats = [
            '5 minutes' => '+5 min',
            'hourly' => '+1 hour'
        ];

        return $formats[$check];
    }

    function fetch() {
        $data = [];
        foreach( $this->apis as $key => $api ) {
            try {
                $response = $api->get();
                $response = $api->get_results();
                $data[$key] = $response;
            } catch( \Exception $e) {
                // DOTO: something with error
                $data[$key] = false;
            }
        }

        $this->data = $data;
        return $data;
    }
    function filter() {
        $data = [];

        foreach( $this->data as $api_data ) {
            $api_data = (array) $api_data;
            $data = array_merge($data, $api_data);
        }
        $this->data = (object) $data;
        return $data;
    }
    function format() {
        return $this->data;
    }
}
