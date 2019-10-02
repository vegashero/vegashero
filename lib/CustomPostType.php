<?php

namespace VegasHero;

class CustomPostType
{

    private $_config;

    public function __construct() {
        $this->_config = \VegasHero\Config::getInstance();

        // add_action( 'init', array($this, 'setPermalinkStructure'));
        add_action('init', array($this, 'registerGameCategoryTaxonomy'));
        add_action('init', array($this, 'registerGameOperatorTaxonomy'));
        add_action('init', array($this, 'registerGameProviderTaxonomy'));
        add_action('init', array($this, 'registerCustomPostType'));
        //add_action('generate_rewrite_rules', array($this, 'addRewriteRules'));

    }

    public function addRewriteRules( $wp_rewrite ) {
        //'slug' => get_option('vh_custom_post_type_url_slug') ? sprintf('%s/%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_category_url_slug')) : get_option('vh_game_category_url_slug'),
        $new_rules = array( 
//            get_option('vh_custom_post_type_url_slug') . '/' . get_option('vh_game_category_url_slug'). '/(.+)' => 'index.php?'.get_option('vh_custom_post_type_url_slug') . '-' . get_option('vh_game_category_url_slug').'=' . $wp_rewrite->preg_index(1),
        );
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
        //echo "<pre>";
        //print_r($wp_rewrite->rules);
        //echo "</pre>";
    }

    public function registerGameOperatorTaxonomy() {
        $labels = array(
            'name'              => __('Game Operators', 'vegashero'),
            'singular_name'     => __('Game Operator', 'vegashero'),
            'search_items'      => __('Search Game Operators', 'vegashero'),
            'all_items'         => __('All Games Operators', 'vegashero'),
            'edit_item'         => __('Edit Game Operator', 'vegashero'),
            'update_item'       => __('Update Game Operator', 'vegashero'),
            'add_new_item'      => __('Add New Game Operator', 'vegashero'),
            'new_item_name'     => __('New Game Operator', 'vegashero'),
            'menu_name'         => __('Game Operators', 'vegashero'),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            // TODO refactor into a method
            'query_var'         => get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_operator_url_slug')) : get_option('vh_game_operator_url_slug'),
            // 'rewrite'           => true
            'rewrite' => array(
                'slug' => get_option('vh_custom_post_type_url_slug') ? sprintf('%s/%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_operator_url_slug')) : get_option('vh_game_operator_url_slug'),
                'with_front' => true
            )
        );

        register_taxonomy( $this->_config->gameOperatorTaxonomy, array( $this->_config->customPostType ), $args );
        register_taxonomy_for_object_type( $this->_config->gameOperatorTaxonomy, $this->_config->customPostType );
        flush_rewrite_rules();

    }

    public function registerGameProviderTaxonomy() {
        //require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        $labels = array(
            'name'              => __('Game Providers', 'vegashero'),
            'singular_name'     => __('Game Provider', 'vegashero'),
            'search_items'      => __('Search Game Providers', 'vegashero'),
            'all_items'         => __('All Games Providers', 'vegashero'),
            'edit_item'         => __('Edit Game Provider', 'vegashero'),
            'update_item'       => __('Update Game Provider', 'vegashero'),
            'add_new_item'      => __('Add New Game Provider', 'vegashero'),
            'new_item_name'     => __('New Game Provider', 'vegashero'),
            'menu_name'         => __('Game Providers', 'vegashero'),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_provider_url_slug')) : get_option('vh_game_provider_url_slug'),
            'rewrite' => array(
                'slug' => get_option('vh_custom_post_type_url_slug') ? sprintf('%s/%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_provider_url_slug')) : get_option('vh_game_provider_url_slug'),
                'with_front' => true
            )
        );

        register_taxonomy( $this->_config->gameProviderTaxonomy, array( $this->_config->customPostType ), $args );
        register_taxonomy_for_object_type( $this->_config->gameProviderTaxonomy, $this->_config->customPostType );
        flush_rewrite_rules();

    }

   // public function setPermalinkStructure() {
   //     global $wp_rewrite;
   //     $wp_rewrite->set_permalink_structure('/%postname%/');
   // } 

    public function registerGameCategoryTaxonomy() {
        //require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        $labels = array(
            'name'              => __('Game Categories', 'vegashero'),
            'singular_name'     => __('Game Category', 'vegashero'),
            'search_items'      => __('Search Game Category', 'vegashero'),
            'all_items'         => __('All Game Categories', 'vegashero'),
            'edit_item'         => __('Edit Category', 'vegashero'),
            'update_item'       => __('Update Category', 'vegashero'),
            'add_new_item'      => __('Add New Game Category', 'vegashero'),
            'new_item_name'     => __('New Category', 'vegashero'),
            'menu_name'         => __('Game Categories', 'vegashero'),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            // TODO refactor into a method
            'query_var' => get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_category_url_slug')) : get_option('vh_game_category_url_slug'),
            //'query_var' => get_option('vh_game_category_url_slug'),
            'rewrite' => array(
               'slug' => get_option('vh_custom_post_type_url_slug') ? sprintf('%s/%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_category_url_slug')) : get_option('vh_game_category_url_slug'),
                'with_front' => true
           )
        );

        register_taxonomy( $this->_config->gameCategoryTaxonomy, array($this->_config->customPostType), $args );
        register_taxonomy_for_object_type( $this->_config->gameCategoryTaxonomy, $this->_config->customPostType );
        flush_rewrite_rules();
    }

    public function registerCustomPosttype() {

        $cptnamevalue = get_option('vh_cptname');

        if ($cptnamevalue == "") {
            $cptnamevalue = __('VegasHero Games', 'vegashero');
        } else { 
            $cptnamevalue = get_option('vh_cptname'); 
        }
        
        $options = array(
            'labels' => array(
                'name' => $cptnamevalue,
                'singular_name' => __('VegasHero Game', 'vegashero'),
                'search_items'  => __('Search Game', 'vegashero'),
                'all_items'     => __('All Games', 'vegashero'),
                'edit_item'     => __('Edit Game', 'vegashero'),
                'update_item'   => __('Update Game', 'vegashero'),
                'add_new_item'  => __('Add New Game', 'vegashero'),
                'new_item_name' => __('New Game', 'vegashero'),
                'menu_name' => __('VegasHero Games', 'vegashero')
            ),
            'public' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'has_archive' => get_option('vh_custom_post_type_url_slug'),
            //'has_archive' => false,
            //'query_var' => false,
            'query_var' => get_option('vh_custom_post_type_url_slug'),
            'hierarchical' => false,
            'taxonomies' => array(
                'post_tag',
                $this->_config->gameProviderTaxonomy,
                $this->_config->gameCategoryTaxonomy
            ),
            'show_ui' => true,
            'can_export' => false,
            'rewrite' => true,
            'rewrite' => array(
                'hierarchical' => true,
                'slug' => sprintf('%s', get_option('vh_custom_post_type_url_slug')),
            //    'ep_mask' => EP_CATEGORIES,
            //    'with_front' => true
            ),
            'show_in_rest' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions', 'author')
        );
        register_post_type($this->_config->customPostType, $options);
        flush_rewrite_rules();
    }
}


/** Admin taxonomy filters for vegashero_games custom post type */

function add_game_category_taxonomy_filters() {
    global $typenow;
    $post_type = 'vegashero_games';
    $taxonomy = 'game_category';
    $posttype_slug = get_option('vh_custom_post_type_url_slug');
    $category_slug = get_option('vh_game_category_url_slug');
    $taxonomy_permalink_slug = $posttype_slug.'-'.$category_slug;
    if ($typenow == $post_type) {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => sprintf(__('All %s'), $info_taxonomy->label),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy_permalink_slug,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
            'value_field' => 'slug',
        ));
    };
}

add_action('restrict_manage_posts', 'add_game_category_taxonomy_filters');


function add_game_operator_taxonomy_filters() {
    global $typenow;
    $post_type = 'vegashero_games';
    $taxonomy = 'game_operator';
    $posttype_slug = get_option('vh_custom_post_type_url_slug');
    $operator_slug = get_option('vh_game_operator_url_slug');
    $taxonomy_permalink_slug = $posttype_slug.'-'.$operator_slug;
    if ($typenow == $post_type) {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => sprintf(__('All %s'), $info_taxonomy->label),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy_permalink_slug,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
            'value_field' => 'slug',
        ));
    };
}

add_action('restrict_manage_posts', 'add_game_operator_taxonomy_filters');


function add_game_provider_taxonomy_filters() {
    global $typenow;
    $post_type = 'vegashero_games';
    $taxonomy = 'game_provider';
    $posttype_slug = get_option('vh_custom_post_type_url_slug');
    $provider_slug = get_option('vh_game_provider_url_slug');
    $taxonomy_permalink_slug = $posttype_slug.'-'.$provider_slug;
    if ($typenow == $post_type) {
        $selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
        $info_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => sprintf(__('All %s', 'vegashero'), $info_taxonomy->label),
            'taxonomy' => $taxonomy,
            'name' => $taxonomy_permalink_slug,
            'orderby' => 'name',
            'selected' => $selected,
            'show_count' => true,
            'hide_empty' => true,
            'value_field' => 'slug',
        ));
    };
}

add_action('restrict_manage_posts', 'add_game_provider_taxonomy_filters');
