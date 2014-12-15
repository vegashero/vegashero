<?php
/*
Plugin Name: Vegas Hero
*/

if ( ! defined( 'WPINC' ) ) {
    exit();
}

// register_activation_hook(__FILE__, 'vegashero_install');
// 
// function vegashero_install() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'vegashero';
// }


function vegashero_create_custom_post_type() {
    $options = array(
        'labels' => array(
            'name' => _x('Games'),
            'singular_name' => _x('Game')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'game')
    );
    register_post_type('vegashero_game', $options);
}

add_action('init', 'vegashero_create_custom_post_type');

