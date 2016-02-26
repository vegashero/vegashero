<?php

class Vegashero_Custom_Post_Type
{

    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();

        // add_action( 'init', array($this, 'setPermalinkStructure'));
        add_action('init', array($this, 'registerCustomPostType'));
        add_action('init', array($this, 'registerGameCategoryTaxonomy'));

    }

   // public function setPermalinkStructure() {
   //     global $wp_rewrite;
   //     $wp_rewrite->set_permalink_structure('/%postname%/');
   // } 

    public function registerGameCategoryTaxonomy() {
        //require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
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
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var' => get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_category_url_slug')) : get_option('vh_game_category_url'),
            'rewrite' => array(
                'slug' => get_option('vh_custom_post_type_url_slug') ? sprintf('%s/%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_category_url_slug')) : get_option('vh_game_category_url'),
                'with_front' => true
            )
        );

        if(get_option('vh_custom_post_type_url_slug')) {
        }

        register_taxonomy( $this->_config->gameCategoryTaxonomy, array($this->_config->customPostType), $args );
        register_taxonomy_for_object_type( $this->_config->gameCategoryTaxonomy, $this->_config->customPostType );
        flush_rewrite_rules();

    }

    public function registerCustomPosttype() {
        $options = array(
            'labels' => array(
                'name' => 'Vegas Hero Games',
                'singular_name' => 'Vegas Hero Game'
            ),
            'public' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'has_archive' => get_option('vh_custom_post_type_url_slug', $this->_config->customPostTypeUrlSlug),
            'query_var' => get_option('vh_custom_post_type_url_slug', $this->_config->customPostTypeUrlSlug),
            'hierarchical' => false,
            'taxonomies' => array(
                'post_tag',
                $this->_config->gameProviderTaxonomy,
                $this->_config->gameCategoryTaxonomy
            ),
            'show_ui' => true,
            'can_export' => false,
            //'rewrite' => true,
            'rewrite' => array(
                'slug' => get_option('vh_custom_post_type_url_slug', $this->_config->customPostTypeUrlSlug),
                'with_front' => true
            ),
            'show_in_rest' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields')
        );
        register_post_type($this->_config->customPostType, $options);
    }


}
