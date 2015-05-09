<?php

class Vegashero_Import 
{

    private $_config = array();
    private $_operators = array();

    public function __construct() {

        $this->_config = new Vegashero_Config();

        add_action('init', array($this, 'registerCustomPostType'));
        add_action('init', array($this, 'registerTaxonomies'));

        // this action is scheduled in update.php
        add_action('vegashero_import', array($this, 'import_games'));
    }

    private function _setOperators() {
        $endpoint = sprintf('%s/vegasgod/operators', $this->_config->apiUrl);
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
        // $this->_operators = array_slice(array_keys((array)$game), 6, -2);
    }

    private function _getOperatorsForGame($game) {
        $operators = array();
        if( ! count($this->_operators)) {
            $this->_setOperators($game);
        }
        foreach($this->_operators as $operator) {
            $operator = trim($operator);
            if($game->{$operator}) {
                $operator_id = $this->_getOperatorId($operator);
                array_push($operators, $operator_id); 
            }
        }
        return $operators;
    }

    private function _getOperatorId($operator) {
        if( ! $operator_id = term_exists($operator, $this->_config->gameOperatorTaxonomy)){
            $operator_id = wp_insert_category(
                array(
                    'cat_name' => $operator,
                    'category_description' => 'Vegas Hero Game Operators',
                    'category_nicename' => sanitize_title($operator),
                    'taxonomy' => $this->_config->gameOperatorTaxonomy
                ),
                true
            );
        }  else {
            $term_details = get_term_by('name', $operator, $this->_config->gameOperatorTaxonomy);
            $operator_id = (int)$term_details->term_id;
        }
        return $operator_id;
    }

    private function _getProviderId($provider) {
        if( ! $provider_id = term_exists($provider, $this->_config->gameProviderTaxonomy)){
            $provider_id = wp_insert_category(
                array(
                    'cat_name' => $provider,
                    'category_description' => 'Vegas Hero Game Providers',
                    'category_nicename' => sanitize_title($provider),
                    'taxonomy' => $this->_config->gameProviderTaxonomy
                ),
                true
            );
        }  else {
            $term_details = get_term_by('name', $provider, $this->_config->gameProviderTaxonomy);
            $provider_id = (int)$term_details->term_id;
        }
        return $provider_id;
    }


    private function _getCategoryId($category) {
        if( ! $category_id = term_exists($category, $this->_config->gameCategoryTaxonomy)){
            $category_id = wp_insert_category(
                array(
                    'cat_name' => $category,
                    'category_description' => 'Vegas Hero Game Categories',
                    'category_nicename' => sanitize_title($category),
                    // 'category_parent' => $parent_id,
                    'taxonomy' => $this->_config->gameCategoryTaxonomy
                ),
                true
            );
        }  else {
            $term_details = get_term_by('name', $category, $this->_config->gameCategoryTaxonomy);
            $category_id = (int)$term_details->term_id;
        }
        return $category_id;
    }

    private function _getPostsForGame($game) {
        $args = array(
            'post_type' => $this->_config->customPostType,
            'meta_query' => array(
                array(
                    'key' => 'game_id',
                    'value' => $game->id
                )
            )
        );
        return get_posts($args);
    }

    public function import_games($operator) {

        require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        $this->registerTaxonomies();

        // [id] => 6
        // [name] => wild witches
        // [provider] => netent
        // [category] => video slots
        // [src] => http://www.affiliaterepublik.com/game/slots-million/1311/default/730/en/wildwitches.iframe
        // [status] => 1
        // [mrgreen] => 1
        // [slotsmillion] => 1
        // [created] => 2015-03-20 11:36:22
        // [modified] => 2015-03-20 11:36:22 

        $endpoint = sprintf('%s/vegasgod/games/%s', $this->_config->apiUrl, $operator);
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $games = json_decode(json_decode($response));
        // $option_name = sprintf('%s%s', $this->_config->settingsNamePrefix, $operator);

        if(count($games > 0)) {
            foreach($games as $game) {
                // check if post exists for this game
                $posts = $this->_getPostsForGame($game);

                $post_id = 0;
                if(count($posts)) {
                    $post = $posts[0];
                    $post_id = $post->ID;
                }

                // insert new post
                if( ! $post_id) {
                    $post = array(
                        'post_content'   => the_content(),
                        'post_name'      => sanitize_title($game->name),
                        'post_title'     => ucfirst($game->name),
                        'post_status'    => $game->status ? 'publish' : 'draft',
                        'post_type'      => $this->_config->customPostType,
                        'post_excerpt'   => the_excerpt()
                    );
                    $post_id = wp_insert_post($post);
                } 

                $category_id = $this->_getCategoryId(trim($game->category));
                $provider_id = $this->_getProviderId(trim($game->provider));
                $operators = $this->_getOperatorsForGame($game);

                $post_meta_id = add_post_meta($post_id, 'game_id', $game->id, true); // add post meta data
                $post_meta_id = add_post_meta($post_id, 'game_src', $game->src, true); // add post meta data

                // $post_meta_id = add_post_meta($post_id, 'vegasgod_unique_game_id', $game->id, true); // add post meta data
                $game_category_term_id = wp_set_object_terms($post_id, $category_id, $this->_config->gameCategoryTaxonomy); // link category and post
                $game_provider_term_id = wp_set_object_terms($post_id, $provider_id, $this->_config->gameProviderTaxonomy); // link provider and post
                $game_operator_term_id = wp_set_object_terms($post_id, $operators, $this->_config->gameOperatorTaxonomy); // link operator and post

                $this->_groupTerms(array($category_id), $this->_config->gameCategoryTermGroupId, $this->_config->gameCategoryTaxonomy);
                $this->_groupTerms(array($provider_id), $this->_config->gameProviderTermGroupId, $this->_config->gameProviderTaxonomy);
                $this->_groupTerms($operators, $this->_config->gameOperatorTermGroupId, $this->_config->gameOperatorTaxonomy);

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
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'game-categories' ),
        );

        register_taxonomy( $this->_config->gameCategoryTaxonomy, array( $this->_config->customPostType ), $args );

    }

    private function _registerGameOperatorTaxonomy() {
        $labels = array(
            'name'              => 'Game Operators',
            'singular_name'     => 'Game Operator',
            'search_items'      => 'Search Game Operators',
            'all_items'         => 'All Games Operators',
            'edit_item'         => 'Edit Game Operator',
            'update_item'       => 'Update Game Operator',
            'add_new_item'      => 'Add New Game Operator',
            'new_item_name'     => 'New Game Operator',
            'menu_name'         => 'Game Operators',
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'game-operators' ),
        );

        register_taxonomy( $this->_config->gameOperatorTaxonomy, array( $this->_config->customPostType ), $args );

    }

    private function _registerGameProviderTaxonomy() {
        $labels = array(
            'name'              => 'Game Providers',
            'singular_name'     => 'Game Provider',
            'search_items'      => 'Search Game Providers',
            'all_items'         => 'All Games Providers',
            'edit_item'         => 'Edit Game Provider',
            'update_item'       => 'Update Game Provider',
            'add_new_item'      => 'Add New Game Provider',
            'new_item_name'     => 'New Game Provider',
            'menu_name'         => 'Game Providers',
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'game-providers' ),
        );

        register_taxonomy( $this->_config->gameProviderTaxonomy, array( $this->_config->customPostType ), $args );

    }

    public function registerTaxonomies() {
        $this->_registerGameCategoryTaxonomy();
        $this->_registerGameOperatorTaxonomy();
        $this->_registerGameProviderTaxonomy();
    }

    public function registerCustomPosttype() {

        $options = array(
            'labels' => array(
                'name' => 'Vegas Hero Games',
                'singular_name' => 'Vegas Hero Game'
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => $this->_config->customPostTypeUrlSlug)
        );
        register_post_type($this->_config->customPostType, $options);
    }


}

