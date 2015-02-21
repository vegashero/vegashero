<?php

class Vegashero_Import 
{

    private $_config = array();
    
    public function __construct() {

        $this->_config = new Vegashero_Config();

        add_action('init', array($this, 'registerCustomPostType'));
        add_action('init', array($this, 'registerTaxonomies'));

        add_action('vegashero_import', array($this, 'import_games'));
        if( ! wp_next_scheduled('vegashero_import')) {
            wp_schedule_single_event(time(), 'vegashero_import');
        }
    }

    private function _getVegasgod() {
        $vegasgod_plugin = WP_PLUGIN_DIR . '/vegasgod/api.php';
        if( ! file_exists($vegasgod_plugin)) {
            throw new Exception('Requires Vegas God Plugin');
        }
        require_once WP_PLUGIN_DIR . '/vegasgod/api.php';
        return new \Vegasgod\Api;
    }

    private function _getSiteId($site) {

        if( ! $site_id = term_exists($site, $this->_config->taxonomy)){
            $site_id = wp_insert_category(
                array(
                    'cat_name' => $site,
                    'category_description' => 'Vegas Hero Gaming Site',
                    'category_nicename' => sanitize_title($site),
                    'taxonomy' => $this->_config->taxonomy
                ),
                true
            );
        }  else {
            $term_details = get_term_by('name', $site, $this->_config->taxonomy);
            $site_id = (int)$term_details->term_id;
        }
        return $site_id;
    }

    private function _getProviderId($provider) {

        if( ! $provider_id = term_exists($provider, $this->_config->taxonomy)){
            $provider_id = wp_insert_category(
                array(
                    'cat_name' => $provider,
                    'category_description' => 'Vegas Game Provider',
                    'category_nicename' => sanitize_title($provider),
                    'taxonomy' => $this->_config->taxonomy
                ),
                true
            );
        }  else {
            $term_details = get_term_by('name', $provider, $this->_config->taxonomy);
            $provider_id = (int)$term_details->term_id;
        }
        return $provider_id;
    }


    private function _getGameCategoryId($category) {

        if( ! $category_id = term_exists($category, $this->_config->taxonomy)){
            $category_id = wp_insert_category(
                array(
                    'cat_name' => $category,
                    'category_description' => 'Vegas Game Category',
                    'category_nicename' => sanitize_title($category),
                    // 'category_parent' => $parent_id,
                    'taxonomy' => $this->_config->taxonomy
                ),
                true
            );
        }  else {
            $term_details = get_term_by('name', $category, $this->_config->taxonomy);
            $category_id = (int)$term_details->term_id;
        }
        return $category_id;
    }

    public function import_games() {

        require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        $this->registerTaxonomies();

        $vegasgod = $this->_getVegasgod();
        $games = $vegasgod->getGames();

        foreach($games as $game) {

            if($game->provider) {
                $provider_id = $this->_getProviderId(trim($game->provider));
                $category_ids = array($provider_id);

                $post_meta = array(
                    'ref' => trim($game->ref),
                    'type' => $game->type,
                    'provider' => $game->provider
                );

                $site_id = $this->_getSiteId(trim($game->site));
                $category_id = $this->_getGameCategoryId(trim($game->category));
                array_push($category_ids, $site_id, $category_id);


                $post = array(
                    'post_content'   => the_content(),
                    'post_name'      => sanitize_title($game->name),
                    'post_title'     => ucfirst($game->name),
                    'post_status'    => $game->status ? 'publish' : 'draft',
                    'post_type'      => $this->_config->customPostType,
                    'post_excerpt'   => the_excerpt()
                );
                $post_id = wp_insert_post($post);
                $post_meta_id = add_post_meta($post_id, $this->_config->metaKey, $post_meta, true); // add post meta data
                $term_taxonomy_ids = wp_set_object_terms($post_id, $category_ids, $this->_config->taxonomy); // link category and post
                $this->_groupTerms($category_ids, $this->_config->termGroupId, $this->_config->taxonomy);

            } else {
                throw new Exception('All games require a provider');
            }

        }
    }

    private function _groupTerms(array $term_ids, $term_group, $taxonomy) {
        if(count($term_ids)> 0) {
            foreach($term_ids as $term_id) {
                wp_update_term($term_id, $taxonomy, array(
                    'term_group' => $term_group
                ));
            }
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

        register_taxonomy( $this->_config->taxonomy, array( $this->_config->customPostType ), $args );

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
        register_post_type($this->_config->customPostType, $options);
    }


}

