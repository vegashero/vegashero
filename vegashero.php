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


/*
 * create custom content type
 */
function vegashero_create_custom_post_type() {
    $options = array(
        'labels' => array(
            'name' => _x('Vegas Hero Games'),
            'singular_name' => _x('Vegas Hero Game')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'games')
    );
    register_post_type('vegashero-game', $options);
}

add_action('init', 'vegashero_create_custom_post_type');

/*
 * create categories/tags via taxonomy
 */

