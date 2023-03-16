<?php

namespace WeatherWidget\Controllers;

use DateTime;

class Views {

    var $plugin = null;
    var $apis = null;
    var $icons = null;

    var $data = null;

    function __construct($plugin, $apis, $icons) {
        $this->plugin = $plugin;
        $this->apis = $apis;
        $this->icons = $icons;

        add_action('wp_enqueue_scripts', [$this, 'scripts']);
    }

    function scripts() {
        wp_register_style('ww', $this->plugin->url . '/assets/styles/ww.css', [], $this->plugin->version);
    }

    function get( $template = '' ) {
        $content = '';

        wp_enqueue_style( 'ww' );

        $data = [];
        if (! $this->data ) {
            $this->data = $this->format_data( $this->apis->get() );
        }
        $data = $this->get_data();
        $icons = $this->icons;
        if ( $data ) {
            ob_start();
            include $this->plugin->dir . "/parts/$template.php";
            $content .= ob_get_clean();
        }
        unset( $data );

        return $content;
    }

    function get_data() {
        return clone $this->data;
    }

    function format_data( $data ) {
        $formats = array(
            'temp' => function( $v ) {
                return sprintf( '%s°C', $v );
            },
			'precipRate' => function( $v ) {
				return sprintf( '%s %smm', $this->icons->getSVG( 'rain' ), $v );
			},
			'snowRate'   => function( $v ) {
				return sprintf( '%s %smm', $this->icons->getSVG( 'snow' ), $v );
			},
			'windSpeed'  => function( $v, $values ) {
				return sprintf(
					'%s %01.0f / %01.0f knots %s',
					str_replace( '180deg', ($values->winddir + 180) . 'deg', $this->icons->getSVG( 'wind' ) ),
					$v / 1.852, // kph to knots
					$values->windGust / 1.852, //kph to knots
					compass_direction( $values->winddir )
				);
			},
			'pressure'   => function( $v ) {
				return sprintf( '%s %dhPa', $this->icons->getSVG( 'pressure' ), $v );
			},
			'humidity'   => function( $v ) {
				return sprintf( 'Humidity: %s%%', $v );
			},
			'visibility' => function( $v ) {
				return sprintf( 'Visibility: %skm', $v );
			},
			'dewpt'      => function( $v ) {
				return sprintf( 'Dew Point: %d°C', $v );
			},
			'uv'         => function( $v ) {
				return sprintf( 'UV: %s', $v );
			},
            'civil_twilight_end' => function( $v ) {
                $time = date('g:i:s a', strtotime($v) );
                return sprintf('Last Light: %s',  $time);
            },
            'civil_twilight_begin' => function( $v ) {
                $time = date('g:i:s a', strtotime($v) );
                return sprintf('First Light: %s', $time);
            },
		);

        foreach ( $formats as $key => $func ) {
			if ( $data->$key ) {
				$data->$key = "<span class='$key'>" . $func( $data->$key, $data ) . '</span>';
			}
		}

        return $data;
    }
}
