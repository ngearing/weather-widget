<?php

namespace WeatherWidget\Controllers;

use WeatherWidget\Options;

class Shortcodes {

    var $plugin = null;
    var $views = null;

    function __construct($plugin, $views) {
        $this->plugin = $plugin;
        $this->views = $views;

        add_shortcode( 'ww_bar', [$this, 'ww_bar'] );
        add_shortcode( 'ww', [$this, 'ww'] );

        add_action('wp_enqueue_scripts', [$this, 'scripts']);
    }

    function scripts() {
        wp_register_style('ww', $this->plugin['uri'] . '/assets/styles/ww.css', [], $this->plugin['Version']);
    }

    function ww_bar() {
        return $this->views->get('bar');;
    }

    function ww() {

        $content = '';

        $options = new Options();

        wp_enqueue_style( 'ng_ww' );
    
        date_default_timezone_set( wp_timezone_string() );
    
        $content .= sprintf( '<ul class="weather-widget-list">' );
        $content .= sprintf( '<h4 class="time">%s</h4>', date( 'M d, h:ia' ) );
        $content .= sprintf( '<h3 class="location">%s</h3>', 'Kyneton, AU' );
    
        // Today forecast.
        $data         = json_decode( $options->get( 'data' ) );
        $data         = ( new WunderAPI() )->get();
        $last_checked = $options->get( 'last_checked' );
    
        // Run import if no data or data old.
        if ( ! $data || time() > strtotime( '+1 hour', $last_checked ) ) {
            $data = ( new WunderAPI() )->get();
            // $data = json_decode( $data );
        }
        if ( $data ) {
            ob_start();
            include NG_WW_PATH . '/parts/today-wunder.php';
            $content .= ob_get_clean();
        }
    
        $content .= sprintf( '</ul>' );
    
        return $content;
    }
}
