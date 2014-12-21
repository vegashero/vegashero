<?php
/**
 * Plugin Name: Vegas Hero
 * Plugin URI: http://vegashero.co
 * Description: Bulk import of gambling games
 * Version: 0.0.0
 * Author: Vegas Heroes
 * Author URI: http://vegashero.co
 * License: GPL2
 */

if ( ! defined( 'WPINC' ) ) {
    exit();
}

$custom_post_type = 'vegashero_game';
$taxonomy = 'vegashero_game_categories';

register_activation_hook(__FILE__, 'vegashero_install');

function vegashero_install() {
}


/*
 * create custom post type
 */
function vegashero_create_custom_post_type() {
    global $custom_post_type;
    $options = array(
        'labels' => array(
            'name' => 'Vegas Hero Games',
            'singular_name' => 'Vegas Hero Game'
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'games')
    );
    register_post_type($custom_post_type, $options);
}

add_action('init', 'vegashero_create_custom_post_type');

/*
 * create categories/tags via taxonomy
 */

function vegashero_create_taxonomies() {

    global $custom_post_type;
    global $taxonomy;

    $labels = array(
        'name'              => 'Game Categories',
        'singular_name'     => 'Game Category',
        'search_items'      => 'Search Game',
        'all_items'         => 'All Games',
        'edit_item'         => 'Edit Game',
        'update_item'       => 'Update Game',
        'add_new_item'      => 'Add New Game',
        'new_item_name'     => 'New Course Game',
        'menu_name'         => 'Game Categories',
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'games' ),
    );

    register_taxonomy( $taxonomy, array( $custom_post_type ), $args );
}

add_action( 'init', 'vegashero_create_taxonomies');

function vegashero_add_categories() {

    global $taxonomy;

    $vegasgod_plugin = WP_PLUGIN_DIR . '/vegasgod/api.php';
    if( ! file_exists($vegasgod_plugin)) {
        throw new Exception('Requires Vegas God Plugin');
    }
    require_once WP_PLUGIN_DIR . '/vegasgod/api.php';
    $vegasgod_api = new \Vegasgod\Api;
    $categories = $vegasgod_api->getCategories();

    foreach($categories as $category) {

        $term = trim($category);

        if( ! term_exists($term, $taxonomy)){
            $args = array(
                'slug' => sanitize_title($term)
            );
            wp_insert_term($term, $taxonomy, $args); 
        } 

    }

}

add_action('init', 'vegashero_add_categories');

function vegashero_add_games() {

    global $custom_post_type;

    $vegasgod_plugin = WP_PLUGIN_DIR . '/vegasgod/api.php';
    if( ! file_exists($vegasgod_plugin)) {
        throw new Exception('Requires Vegas God Plugin');
    }
    require_once WP_PLUGIN_DIR . '/vegasgod/api.php';
    $vegasgod_api = new \Vegasgod\Api;
    $games = $vegasgod_api->getGames();
    echo '<pre>';
    print_r($games);
    echo '</pre>';
    die();
}

add_action('init', 'vegashero_add_games');

/*
 * settings
 */
function vegashero_create_settings() {
    $id = 'vegashero-settings';
    $title = 'Vegas Hero Settings';
    $callback = function() {
        echo '<p>Intro text for our settings section</p>';
    };
    $page = 'writing';

    add_settings_section($id, $title, $callback, $page);


    add_settings_field(
        'vegashero_mrgreen_affiliate_code', #id
        'Affiliate code', #title
        function(){}, #callback
        'writing', #page
        'vegashero_settings', #section
        '' // args
    );

    // Register our setting in the "reading" settings section
    register_setting( 'reading', 'wporg_setting_name');
}

add_action( 'admin_init', 'vegashero_create_settings');

