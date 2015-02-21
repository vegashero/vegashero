<?php

class Vegashero_Stylesheet
{
    public function __construct() {
        add_action( 'get_header', array($this, 'lobbyStylesheets'));
    }

    public function lobbyStylesheets() {
        wp_enqueue_style('vh-bootstrap-js',  plugin_dir_url( __FILE__ ) . 'templates/js/bootstrap.min.js');
        /*wp_enqueue_style('vh-bootstrap',  plugin_dir_url( __FILE__ ) . 'templates/css/bootstrap.min.css');*/
        wp_enqueue_style('vh-bootstrap-theme',  plugin_dir_url( __FILE__ ) . 'templates/css/bootstrap-theme.min.css');
        wp_enqueue_style('vh-dropdown',  plugin_dir_url( __FILE__ ) . 'templates/css/dropdown.css');
        wp_enqueue_style('lobby-styles',  plugin_dir_url( __FILE__ ) . 'templates/css/vh-lobby.css');
        wp_enqueue_style('page-styles',  plugin_dir_url( __FILE__ ) . 'templates/css/vh-game.css');

    }

}
