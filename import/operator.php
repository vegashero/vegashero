<?php

class Vegashero_Import_Operator
{

    private $_config = array();
    private $_license = '';

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        $dashboard = Vegashero_Settings_Dashboard::getInstance();
        $this->_license = $dashboard->getLicense();
        //echo sprintf('license: %s', $this->_license);

        // add_action( 'init', array($this, 'setPermalinkStructure'));
        add_action('init', array($this, 'registerCustomPostType'));
        add_action('init', array($this, 'registerTaxonomies'));
        add_filter( 'block_local_requests', '__return_false' );
        // this action is scheduled in queue.php
        add_action('vegashero_import_operator', array($this, 'importGamesForOperator'));
    }

    private function _setOperators() {
        $endpoint = sprintf('%s/vegasgod/operators', $this->_config->apiUrl);
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
        // $this->_operators = array_slice(array_keys((array)$game), 6, -2);
    }
    
   // public function setPermalinkStructure() {
   //     global $wp_rewrite;
   //     $wp_rewrite->set_permalink_structure('/%postname%/');
   // } 

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
            'post_status' => 'any',
            'meta_key' => 'game_id',
            'meta_value' => $game->id,
            'meta_compare' => '='
        );
        return get_posts($args);
    }

    private function _insertNewGame($game, $operator) {
        // [id] => 6
        // [name] => wild witches
        // [provider] => netent
        // [category] => video slots
        // [src] => http://www.affiliaterepublik.com/game/slots-million/1311/default/730/en/wildwitches.iframe
        // [status] => 1
        // [mrgreen] => 1
        // [slotsmillion] => 1
        // [europa] => 0
        // [created] => 2015-03-20 11:36:22
        // [modified] => 2015-03-20 11:36:22
        $post = array(
            'post_content'   => the_content() ? the_content() : '',
            'post_name'      => sanitize_title($game->name),
            'post_title'     => ucfirst($game->name),
            'post_status'    => $game->status ? 'publish' : 'draft',
            'post_type'      => $this->_config->customPostType,
            'post_excerpt'   => the_excerpt() ? the_excerpt() : ''
        );
        $post_id = wp_insert_post($post);
        $category_id = $this->_getCategoryId(trim($game->category));
        $provider_id = $this->_getProviderId(trim($game->provider));
        $operator_id = $this->_getOperatorId(trim($operator));

        $post_meta_game_id = add_post_meta($post_id, $this->_config->postMetaGameId, $game->id, true); // add post meta data
        $post_meta_game_src_id = add_post_meta($post_id, $this->_config->postMetaGameSrc, $game->src, true); // add post meta data
        $post_meta_game_title = add_post_meta($post_id, $this->_config->postMetaGameTitle, sanitize_title(strtolower(trim($game->name))), true); // add post meta data

        $game_category_term_id = wp_set_object_terms($post_id, $category_id, $this->_config->gameCategoryTaxonomy); // link category and post
        $game_provider_term_id = wp_set_object_terms($post_id, $provider_id, $this->_config->gameProviderTaxonomy); // link provider and post
        $game_operator_term_id = wp_set_object_terms($post_id, $operator_id, $this->_config->gameOperatorTaxonomy); // link operator and post

        $this->_groupTerms(array($category_id), $this->_config->gameCategoryTermGroupId, $this->_config->gameCategoryTaxonomy);
        $this->_groupTerms(array($provider_id), $this->_config->gameProviderTermGroupId, $this->_config->gameProviderTaxonomy);
        $this->_groupTerms(array($operator_id), $this->_config->gameOperatorTermGroupId, $this->_config->gameOperatorTaxonomy);
    }

    private function _updateExistingPostMeta($existing, $game) {
        $game_id = get_post_meta($existing->ID, $this->_config->postMetaGameId, true);
        $game_src = get_post_meta($existing->ID, $this->_config->postMetaGameSrc, true);
        $game_title = get_post_meta($existing->ID, $this->_config->postMetaGameTitle, true);

        $providers = wp_get_post_terms($existing->ID, $this->_config->gameProviderTaxonomy);

        if($game_id != $game->id) {
            update_post_meta($existing->ID, $this->_config->postMetaGameId, $game->id, $game_id);
        }
        if($game_src != $game->src) {
            update_post_meta($existing->ID, $this->_config->postMetaGameSrc, $game->src, $game_src);
        }
        if($game_title != sanitize_title(strtolower(trim($game->name)))) {
            update_post_meta($existing->ID, $this->_config->postMetaGameTitle, sanitize_title(strtolower(trim($game->name))), $game_title);
        }
    }
    
    private function _getOperatorIds($operators) {
        $operator_ids = array();
        foreach($operators as $operator) {
            $operator_id = $this->_getOperatorId(trim($operator));
            array_push($operator_ids, $operator_id);
        }
        return $operator_ids;
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

    private function _updateStatus($existing, $new) {
        $new->status = $new->status ? 'publish' : 'draft';
        if($existing->post_status != $new->status) {
            $existing->post_status = $new->status;
            $existing->edit_date = true;
            wp_update_post($existing, true);
        }
    }

    private function _updateOperators($existing, $new, $operator) {
        $update = false;
        $operators = wp_get_post_terms($existing->ID, $this->_config->gameOperatorTaxonomy, array('fields' => 'names'));
        if( ! in_array($operator, $operators) && $new->{$operator}) {
            array_push($operators, $operator);
            $update = true;
        }  elseif(! $new->{$operator}) {
            if(($key = array_search($operator, $operators)) !== false) {
                unset($operators[$key]);
                $update = true;
            }
        }
        if($update) {
            $operator_ids = $this->_getOperatorIds($operators);
            $game_operator_term_id = wp_set_object_terms($existing->ID, $operator_ids, $this->_config->gameOperatorTaxonomy); 
            $this->_groupTerms($operator_ids, $this->_config->gameOperatorTermGroupId, $this->_config->gameOperatorTaxonomy);
        }
    }

    private function _updateExistingGame($existing, $new, $operator) {
        $this->_updateStatus($existing, $new);
        $this->_updateOperators($existing, $new, $operator);
    }

    private function _haveLicense() {
        if( ! empty($this->_license)) {
            return true;
        }
    }

    public function importGamesForOperator($operator) {
        // $this->registerTaxonomies();

        // [id] => 6
        // [name] => wild witches
        // [provider] => netent
        // [category] => video slots
        // [src] => http://www.affiliaterepublik.com/game/slots-million/1311/default/730/en/wildwitches.iframe
        // [status] => 1
        // [mrgreen] => 1
        // [slotsmillion] => 1
        // [europa] => 0
        // [created] => 2015-03-20 11:36:22
        // [modified] => 2015-03-20 11:36:22

        # first time importing games for this operator
        if( ! term_exists($operator, $this->_config->gameOperatorTaxonomy)){ 
            $endpoint = sprintf('%s/vegasgod/games/%s', $this->_config->apiUrl, $operator);
        } else {
            # get all games so we can remove operators
            $endpoint = sprintf('%s/vegasgod/games/', $this->_config->apiUrl);
        }
        if($this->_haveLicense()) {
            $endpoint = sprintf('%s?license=%s', $endpoint, $this->_license);
        }
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $games = json_decode(json_decode($response));

        if(count($games > 0)) {
            foreach($games as $game) {
                // check if post exists for this game
                $posts = $this->_getPostsForGame($game);

                $post_id = 0;
                if(count($posts)) {
                    $post = $posts[0];
                    $post_id = $post->ID;
                }

                if( ! $post_id) { // no existing post
                    $this->_insertNewGame($game, $operator);
                } else { 
                    $this->_updateExistingGame($post, $game, $operator);
                    $this->_updateExistingPostMeta($post, $game);
                }
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
            // 'rewrite'           => true 
            'rewrite' => array(
                'slug' => $this->_config->gameCategoryUrlSlug,
                'with_front' => true
            )
        );

        register_taxonomy( $this->_config->gameCategoryTaxonomy, array($this->_config->customPostType), $args );
        register_taxonomy_for_object_type( $this->_config->gameCategoryTaxonomy, $this->_config->customPostType );

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
            // 'rewrite'           => true
            'rewrite' => array(
                'slug' => $this->_config->gameOperatorUrlSlug,
                'with_front' => true
            )
        );

        register_taxonomy( $this->_config->gameOperatorTaxonomy, array( $this->_config->customPostType ), $args );
        register_taxonomy_for_object_type( $this->_config->gameOperatorTaxonomy, $this->_config->customPostType );

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
            // 'rewrite'           => true
            'rewrite' => array(
                'slug' => $this->_config->gameProviderUrlSlug,
                'with_front' => true
            )
        );

        register_taxonomy( $this->_config->gameProviderTaxonomy, array( $this->_config->customPostType ), $args );
        register_taxonomy_for_object_type( $this->_config->gameProviderTaxonomy, $this->_config->customPostType );
    }

    public function registerTaxonomies() {
        require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        $this->_registerGameCategoryTaxonomy();
        $this->_registerGameOperatorTaxonomy();
        $this->_registerGameProviderTaxonomy();
        flush_rewrite_rules();
    }

    public function registerCustomPosttype() {
        $options = array(
            'labels' => array(
                'name' => 'Vegas Hero Games',
                'singular_name' => 'Vegas Hero Game'
            ),
            'public' => true,
            'query_var' => true,
            'has_archive' => $this->_config->customPostTypeUrlSlug,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'hierarchical' => false,
            'taxonomies' => array(
                'post_tag',
                $this->_config->gameProviderTaxonomy,
                $this->_config->gameOperatorTaxonomy,
                $this->_config->gameCategoryTaxonomy
            ),
            'show_ui' => true,
            'can_export' => false,
            'rewrite' => true,
            'rewrite' => array(
                'slug' => $this->_config->customPostTypeUrlSlug,
                'with_front' => true
            )
        );
        register_post_type($this->_config->customPostType, $options);
    }


}
