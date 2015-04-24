<?php

class Vegashero_Stylesheet
{
    public function __construct() {
        add_action( 'get_header', array($this, 'lobbyScripts'));
        // add_action( 'get_footer', array($this, 'footerScripts'));
    }

    public function lobbyScripts() {
        wp_enqueue_script('vh-jquery-js',  plugin_dir_url( __FILE__ ) . 'templates/js/jquery-1.11.2.min.js');
        wp_enqueue_script('vh-bootstrap-js',  plugin_dir_url( __FILE__ ) . 'templates/js/bootstrap.min.js');
        wp_enqueue_script('vh-nivo-pack-js',  plugin_dir_url( __FILE__ ) . 'templates/js/jquery.nivo.slider.pack.js');
        wp_enqueue_style('vh-bootstrap-theme',  plugin_dir_url( __FILE__ ) . 'templates/css/bootstrap-theme.min.css');
        wp_enqueue_style('vh-default-style',  plugin_dir_url( __FILE__ ) . 'templates/default/default.css');
        wp_enqueue_style('vh-dropdown',  plugin_dir_url( __FILE__ ) . 'templates/css/dropdown.css');
        wp_enqueue_style('lobby-styles',  plugin_dir_url( __FILE__ ) . 'templates/css/vh-lobby.css');
        wp_enqueue_style('page-styles',  plugin_dir_url( __FILE__ ) . 'templates/css/vh-game.css');
        wp_enqueue_style('nivo-style',  plugin_dir_url( __FILE__ ) . 'templates/css/nivo-slider.css');
    }
    // public function footerScripts() {
    //   echo'<script type="text/javascript">
    //   $(window).load(function() {
    //       $("#slider").nivoSlider();
    //   });
    //   </script>';
    // }
}
