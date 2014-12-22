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

register_activation_hook(__FILE__, 'vegashero_install');

function vegashero_install() {

    $custom_post_type = 'vegashero_game';
    $taxonomy = 'vegashero_game_categories';
    vegashero_create_taxonomies($custom_post_type, $taxonomy);
    vegashero_create_custom_post_type($custom_post_type);
    add_action('vegashero_import_games', 'vegashero_add_games');
    wp_schedule_single_event(time(), 'vegashero_import_games', array($custom_post_type, $taxonomy));
    wp_get_schedule('vegashero_import_games', array($custom_post_type, $taxonomy));
}


/*
 * create custom post type
 */
function vegashero_create_custom_post_type($custom_post_type) {

    $options = array(
        'labels' => array(
            'name' => 'Vegas Hero Games',
            'singular_name' => 'Vegas Hero Game'
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'games')
    );
    $return = register_post_type($custom_post_type, $options);
    if(is_wp_error($return)) {
        echo '<pre>';
        print_r($return);
        echo '</pre>';
        die($return->get_error_message());
    } 
}


/*
 * create categories/tags via taxonomy
 */

function vegashero_create_taxonomies($custom_post_type, $taxonomy) {

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


function vegashero_add_games($custom_post_type, $taxonomy) {

    $vegasgod_plugin = WP_PLUGIN_DIR . '/vegasgod/api.php';
    if( ! file_exists($vegasgod_plugin)) {
        throw new Exception('Requires Vegas God Plugin');
    }
    require_once WP_PLUGIN_DIR . '/vegasgod/api.php';
    $vegasgod_api = new \Vegasgod\Api;
    $games = $vegasgod_api->getGames();

    foreach($games as $game) {

        $term = trim($game->category);

        if( ! term_exists($term, $taxonomy)){
            $args = array(
                'slug' => sanitize_title($term)
            );
            $term_details = wp_insert_term($term, $taxonomy, $args); 
            $term_id = (int)$term_details['term_id'];
            $term_taxonomy_id = $term_details['term_taxonomy_id'];
        }  else {
            $term_details = get_term_by('name', $term, $taxonomy);
            $term_id = (int)$term_details->term_id;
            $term_taxonomy_id = $term_details->term_taxonomy_id;
        }

        $post = array(
            'post_content'   => 'Post content goes here',
            'post_name'      =>  sanitize_title($game->name),
            'post_title'     => ucfirst($game->name),
            'post_status'    => $game->status ? 'publish' : 'draft',
            'post_type'      => $custom_post_type,
            'post_excerpt'   => 'Post excerpt goes here',
            'post_category'  => array($term_id),
            'post_template' => sprintf('%s.php', trim($game->type))
        ); 
        $post_id = wp_insert_post($post);
        // $category_ids = wp_set_post_categories($post_id, array($term_id));
        $post_meta = array(
            'ref' => trim($game->ref),
            'type' => $game->type,
            'large_image' => $game->large_image,
            'thumb_image' => $game->thumb_image
        );
        $post_meta_id = add_post_meta($post_id, 'game_meta', $post_meta, true);
    }
}


// add_action('init', 'vegashero_add_games');

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
