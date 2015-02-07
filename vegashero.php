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

        add_action('admin_init', array($this, 'registerSettings'));
        add_action('admin_menu', array($this, 'addOptionsPage'));

        add_filter( 'single_template', array($this, 'getSingleTemplate'));
        add_filter( 'archive_template', array($this, 'getArchiveTemplate'));

        // add custom template
        $this->templates = array();

        // Add a filter to the attributes metabox to inject template into the cache.
        add_filter('page_attributes_dropdown_pages_args', array( $this, 'registerProjectTemplates' ));

        // Add a filter to the save post to inject out template into the page cache
        add_filter('wp_insert_post_data', array( $this, 'registerProjectTemplates'));

        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter('template_include', array( $this, 'viewProjectTemplate'));

        // Add your templates to this array.

        $this->templates = array(
            'templates/custom-page-template.php'     => 'Vegas Hero Games Lobby',
        );
        //end

        add_action('vegashero_import', array($this, 'import_games'));
        if( ! wp_next_scheduled('vegashero_import')) {
            wp_schedule_single_event(time(), 'vegashero_import');
        }
    }

    public function registerProjectTemplates( $atts ) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;

    }

    /**
     * Checks if the template is assigned to the page
     */
    public function viewProjectTemplate( $template ) {

        global $post;

        if (!isset($this->templates[get_post_meta(
            $post->ID, '_wp_page_template', true
        )] ) ) {

            return $template;

        }

        $file = plugin_dir_path(__FILE__). get_post_meta(
            $post->ID, '_wp_page_template', true
        );

        // Just to be safe, we check if the file exist first
        if( file_exists( $file ) ) {
            return $file;
        } else { echo $file; }

        return $template;

    }


    public function registerSettings() {
        register_setting('vegashero_options', 'vegashero_options');
        // http://ottopress.com/2009/wordpress-settings-api-tutorial/
        add_settings_section('vegashero_settings_provider', 'Game Providers', array($this, 'getOptionsSectionText'), 'vegashero');

        $vegasgod = $this->_getVegasgod();
        $sites = $vegasgod->getSites();
        foreach($sites as $site) {
            add_settings_field('vegashero_options_'.$site, $site, array($this, 'getOptionsInputBox'), 'vegashero', 'vegashero_settings_provider');
        }
    }

    public function getOptionsInputBox() {
        $options = get_option('vegashero_options');
        echo "<input id='' name='vegashero_options[]' size='40' type='text' value='' />";
    }

    public function getOptionsSectionText() {
        echo "<p>Description for this section</p>";
    }

    public function addOptionsPage() {
        add_options_page('Vegas Hero Options', 'Vegas Hero Options', 'manage_options', 'vegashero', array($this, 'getOptionsPage'));
    }

    public function getOptionsPage() {
        echo '<div class="wrap">';
        echo '<h2>Vegas Hero Settings</h2>';
        echo '<form method="post" action="options.php">';
        settings_fields('vegashero_options');
        do_settings_sections('vegashero');
        submit_button();
        echo '</form>';
        echo '</div>';

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
                    'category_description' => 'Vegas Game Provider',
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
                    'category_description' => 'Vegas Game Category',
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
                'post_content'   => the_content(),
                'post_name'      => sanitize_title($game->name),
                'post_title'     => ucfirst($game->name),
                'post_status'    => $game->status ? 'publish' : 'draft',
                'post_type'      => $this->_custom_post_type,
                'post_excerpt'   => post_excerpt(),
                'page_template'  => plugin_dir_url( __FILE__ ) . 'templates/single.php'

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
                'name' => 'Vegas Games',
                'singular_name' => 'Vegas Game'
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'games')
        );
        register_post_type($this->_custom_post_type, $options);
    }


}
function lobby_stylesheets() {
  wp_enqueue_style('vh-bootstrap-js',  plugin_dir_url( __FILE__ ) . 'templates/js/bootstrap.min.js');
  /*wp_enqueue_style('vh-bootstrap',  plugin_dir_url( __FILE__ ) . 'templates/css/bootstrap.min.css');*/
  wp_enqueue_style('vh-bootstrap-theme',  plugin_dir_url( __FILE__ ) . 'templates/css/bootstrap-theme.min.css');
  wp_enqueue_style('vh-dropdown',  plugin_dir_url( __FILE__ ) . 'templates/css/dropdown.css');
  wp_enqueue_style('lobby-styles',  plugin_dir_url( __FILE__ ) . 'templates/css/vh-lobby.css');

}
add_action( 'get_header', 'lobby_stylesheets' );
$vegashero = new Vegashero();
