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

class Vegashero 
{

    private $_taxonomy = 'vegashero_games';
    private $_custom_post_type = 'vegashero_game';

    public function __construct() {

        add_action('init', array($this, 'registerCustomPostType'));
        add_action('init', array($this, 'registerTaxonomies'));
        add_filter( 'single_template', array($this, 'getSingleTemplate'));
        add_filter( 'archive_template', array($this, 'getArchiveTemplate'));

        add_action('vegashero_import', array($this, 'import_games'));
        if( ! wp_next_scheduled('vegashero_import')) {
            wp_schedule_single_event(time(), 'vegashero_import');
        }
    }

    public function getArchiveTemplate($archive_template){

        $plugin_dir = plugin_dir_path(__FILE__);

        if ( is_post_type_archive ( $this->_custom_post_type ) ) {
            $archive_template = sprintf("%s/templates/archive-%s.php", $plugin_dir, $this->_custom_post_type);
        }
        return $archive_template;

    }

    public function getSingleTemplate($single_template) {

        $plugin_dir = plugin_dir_path(__FILE__);
        $post_id = get_the_ID();

        if ( get_post_type( $post_id ) == $this->_custom_post_type ) {
            $singe_template = sprintf("%s/templates/single-%s.php", $plugin_dir, $this->_custom_post_type);
        }
        return $single_template;

    }

    private function _getVegasgod() {
        $vegasgod_plugin = WP_PLUGIN_DIR . '/vegasgod/api.php';
        if( ! file_exists($vegasgod_plugin)) {
            throw new Exception('Requires Vegas God Plugin');
        }
        require_once WP_PLUGIN_DIR . '/vegasgod/api.php';
        return new \Vegasgod\Api;
    }

    private function _getVegasheroCategoryId() {
        $category = 'vegashero';
        if( ! $category_id = term_exists($category, $this->_taxonomy)) {
            $category_id = wp_insert_category(
                array(
                    'cat_name' => $category,
                    'category_description' => 'Vegas Hero',
                    'category_nicename' => sanitize_title($category),
                    'taxonomy' => $this->_taxonomy
                ), 
                true
            );
        }  else {
            $term_details = get_term_by('name', $category, $this->_taxonomy);
            $category_id = (int)$term_details->term_id;
        }
        return $category_id;
    }

    private function _getSiteId($site, $parent_id='') {

        if( ! $site_id = term_exists($site, $this->_taxonomy)){
            $site_id = wp_insert_category(
                array(
                    'cat_name' => $site,
                    'category_description' => 'Vegas Hero Gaming Site',
                    'category_nicename' => sanitize_title($site),
                    'category_parent' => $parent_id,
                    'taxonomy' => $this->_taxonomy
                ), 
                true
            );
        }  else {
            $term_details = get_term_by('name', $site, $this->_taxonomy);
            $site_id = (int)$term_details->term_id;
        }
        return $site_id;
    }

    private function _getProviderId($provider, $parent_id='') {

        if( ! $provider_id = term_exists($provider, $this->_taxonomy)){
            $provider_id = wp_insert_category(
                array(
                    'cat_name' => $provider,
                    'category_description' => 'Vegas Hero Game Provider',
                    'category_nicename' => sanitize_title($provider),
                    'category_parent' => $parent_id,
                    'taxonomy' => $this->_taxonomy
                ), 
                true
            );
        }  else {
            $term_details = get_term_by('name', $provider, $this->_taxonomy);
            $provider_id = (int)$term_details->term_id;
        }
        return $provider_id;
    }


    private function _getGameCategoryId($category, $parent_id = '') {

        if( ! $category_id = term_exists($category, $this->_taxonomy)){
            $category_id = wp_insert_category(
                array(
                    'cat_name' => $category,
                    'category_description' => 'Vegas Hero Game Category',
                    'category_nicename' => sanitize_title($category),
                    'category_parent' => $parent_id,
                    'taxonomy' => $this->_taxonomy
                ), 
                true
            );
        }  else {
            $term_details = get_term_by('name', $category, $this->_taxonomy);
            $category_id = (int)$term_details->term_id;
        }
        return $category_id;
    }

    public function import_games() {
        require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        $this->registerTaxonomies();

        $vegashero_id = $this->_getVegasheroCategoryId();

        $vegasgod = $this->_getVegasgod();
        $games = $vegasgod->getGames();

        foreach($games as $game) {
            $category_ids = array($vegashero_id);

            $site_id = $this->_getSiteId(trim($game->site), $vegashero_id);
            $category_id = $this->_getGameCategoryId(trim($game->category), $site_id);
            array_push($category_ids, $site_id, $category_id);

            if($game->provider) {
                $provider_id = $this->_getProviderId(trim($game->provider), $site_id);
                array_push($category_ids, $provider_id);
            }

            $post = array(
                'post_content'   => 'Post content goes here',
                'post_name'      =>  sanitize_title($game->name),
                'post_title'     => ucfirst($game->name),
                'post_status'    => $game->status ? 'publish' : 'draft',
                'post_type'      => $this->_custom_post_type,
                'post_excerpt'   => 'Post excerpt goes here'
            ); 
            $post_id = wp_insert_post($post);
            $post_meta = array(
                'ref' => trim($game->ref),
                'type' => $game->type,
                'large_image' => $game->large_image,
                'thumb_image' => $game->thumb_image
            );
            $post_meta_id = add_post_meta($post_id, 'game_meta', $post_meta, true); // add post meta data
            $term_taxonomy_ids = wp_set_object_terms($post_id, $category_ids, $this->_taxonomy); // link category and post
        }
    }

    private function _registerGameCategoryTaxonomy() {
        $labels = array(
            'name'              => 'Game Categories',
            'singular_name'     => 'Game Category',
            'search_items'      => 'Search Game',
            'all_items'         => 'All Games',
            'edit_item'         => 'Edit Game',
            'update_item'       => 'Update Game',
            'add_new_item'      => 'Add New Game',
            'new_item_name'     => 'New Game',
            'menu_name'         => 'Game Categories',
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'games' ),
        );

        register_taxonomy( $this->_taxonomy, array( $this->_custom_post_type ), $args );

    }

    public function registerTaxonomies() {
        $this->_registerGameCategoryTaxonomy();
    }

    public function registerCustomPosttype() {

        $options = array(
            'labels' => array(
                'name' => 'Vegas Hero Games',
                'singular_name' => 'Vegas Hero Game'
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'games')
        );
        register_post_type($this->_custom_post_type, $options);
    }


}

$vegashero = new Vegashero();
