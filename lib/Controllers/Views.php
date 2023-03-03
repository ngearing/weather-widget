<?php

namespace WeatherWidget\Controllers;

class Views {

    var $plugin = null;
    var $apis = null;

    function __construct($plugin, $apis) {
        $this->plugin = $plugin;
        $this->apis = $apis;
    }

    function get( $template = '' ) {
        $content = '';

        $data = $this->apis->get();
        if ( $data ) {
            ob_start();
            include $this->plugin['dir'] . "/parts/$template.php";
            $content .= ob_get_clean();
        }

        return $content;
    }
}
