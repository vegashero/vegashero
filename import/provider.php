<?php

class Vegashero_Import_Provider extends Vegashero_Import
{

    private $_license = '';

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        $dashboard = Vegashero_Settings_Dashboard::getInstance();
        $this->_license = $dashboard->getLicense();

        add_action('init', array($this, 'registerGameProviderTaxonomy'));

        // this action is scheduled in queue.php
        add_action('vegashero_import_provider', array($this, 'importGamesForProvider'));
    }

    private function _insertNewGame($game) {
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

        $post_meta_game_id = add_post_meta($post_id, $this->_config->postMetaGameId, $game->id, true); // add post meta data
        $post_meta_game_src_id = add_post_meta($post_id, $this->_config->postMetaGameSrc, $game->src, true); // add post meta data
        $post_meta_game_title = add_post_meta($post_id, $this->_config->postMetaGameTitle, sanitize_title(strtolower(trim($game->name))), true); // add post meta data

        $game_category_term_id = wp_set_object_terms($post_id, $category_id, $this->_config->gameCategoryTaxonomy); // link category and post
        $game_provider_term_id = wp_set_object_terms($post_id, $provider_id, $this->_config->gameProviderTaxonomy); // link provider and post

        $this->_groupTerms(array($category_id), $this->_config->gameCategoryTermGroupId, $this->_config->gameCategoryTaxonomy);
        $this->_groupTerms(array($provider_id), $this->_config->gameProviderTermGroupId, $this->_config->gameProviderTaxonomy);
    }

    private function _updateExistingGame($existing, $new) {
        $this->_updateStatus($existing, $new);
    }

    public function importGamesForProvider($provider) {
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

        $endpoint = sprintf('%s/vegasgod/games/provider/%s?license=%s', $this->_config->apiUrl, $provider, $this->_license);
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
                    $this->_insertNewGame($game);
                } else { 
                    $this->_updateExistingGame($post, $game);
                    $this->_updateExistingPostMeta($post, $game);
                }
            }
        }
    }

    public function registerGameProviderTaxonomy() {
        //require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
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
                'slug' => get_option('vh_game_provider_url_slug', $this->_config->gameProviderUrlSlug),
                'with_front' => true
            )
        );

        register_taxonomy( $this->_config->gameProviderTaxonomy, array( $this->_config->customPostType ), $args );
        register_taxonomy_for_object_type( $this->_config->gameProviderTaxonomy, $this->_config->customPostType );
        flush_rewrite_rules();
    }

}
