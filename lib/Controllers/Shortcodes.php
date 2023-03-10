<?php

namespace WeatherWidget\Controllers;

class Shortcodes {

    var $plugin = null;
    var $views = null;

    function __construct($plugin, $views) {
        $this->plugin = $plugin;
        $this->views = $views;

        add_shortcode( 'ww_bar', [$this, 'ww_bar'] );
        add_shortcode( 'ww', [$this, 'ww'] );

    }

    function ww_bar() {
        return $this->views->get('bar');;
    }

    function ww() {
        return $this->views->get('today');
    }
}
