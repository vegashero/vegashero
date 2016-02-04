<?php

class Vegashero_Stylesheet
{
    public function __construct() {
        add_action( 'get_header', array($this, 'lobbyScripts'));
        // add_action( 'get_footer', array($this, 'footerScripts'));
    }

    public function lobbyScripts() {
        // wp_enqueue_script('vh-jquery-js',  plugin_dir_url( __FILE__ ) . 'templates/js/jquery-1.11.2.min.js');
        wp_enqueue_style('lobby-styles',  plugin_dir_url( __FILE__ ) . 'templates/css/vh-lobby.css');
        wp_enqueue_style('page-styles',  plugin_dir_url( __FILE__ ) . 'templates/css/vh-game.css');
    }
}
