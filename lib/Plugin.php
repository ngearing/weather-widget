<?php

namespace WeatherWidget;

use WeatherWidget\Controllers\API as APIS;
use WeatherWidget\Controllers\Shortcodes;
use WeatherWidget\Controllers\Views;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Plugin {

    var $plugin = null;
    var $updater = null;
    var $options = null;
    var $apis = null;
    var $icons = null;
    var $codes = null;
    var $views = null;
    var $shortcodes = null;

    function __construct($file) {
        $this->plugin = get_file_data($file, ['Plugin Name'=>'name','Version'=>'version']);
        $this->plugin['file'] = $file;
        $this->plugin['dir'] = dirname($file);
        $this->plugin['url'] = plugins_url('',$file);
        $this->plugin = (object) $this->plugin;

        // Plugin updates
        $this->updater = PucFactory::buildUpdateChecker(
            'https://bitbucket.org/ngearing/weather-widget/',
            __FILE__,
            'weather-widget'
        );

        $this->options = new Options();
        $this->apis = new APIS($this->options);
        $this->icons = new Icons($this->plugin);
        $this->codes = new Codes();
        $this->views = new Views($this->plugin, $this->apis, $this->icons);
        $this->shortcodes = new Shortcodes($this->plugin, $this->views);
    }

    function init() {
        register_activation_hook(__FILE__, [$this, 'plugin_activation']);
        register_deactivation_hook(__FILE__, [$this, 'plugin_deactivate']);
    }

    function plugin_activation() {
        if (!wp_next_scheduled('ng_ww_get_week_data')) {
            wp_schedule_event(time(), 'hourly', 'ng_ww_get_week_data');
        }
        if (!wp_next_scheduled('ng_ww_get_today_data')) {
            wp_schedule_event(time(), 'hourly', 'ng_ww_get_today_data');
        }
    }

    function plugin_deactivate() {
        wp_clear_scheduled_hook('ng_ww_get_week_data');
        wp_clear_scheduled_hook('ng_ww_get_today_data');
    }

    function ng_ww_get_week_data() {
        $api = new \WeatherWidget\API();
        $api->get('week');
    }

    function ng_ww_get_today_data() {
        $api = new \WeatherWidget\API();
        $api->get('today');
    }
}
