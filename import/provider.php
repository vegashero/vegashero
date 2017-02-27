<?php

class Vegashero_Import_Provider extends Vegashero_Import
{

    private $_license = '';

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        $license = Vegashero_Settings_License::getInstance();
        $this->_license = $license->getLicense();

        // this action is scheduled in queue.php
        add_action('vegashero_import_provider', array($this, 'importGamesForProvider'));
    }

    private function _haveLicense() {
        if( ! empty($this->_license)) {
            return true;
        }
    }

    private function _insertNewGame($game, $provider) {
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
        $provider_id = $this->_getProviderId(trim($provider));
        //$operator_id = $this->_getOperatorId(trim($game->operator));

        $game_title = sanitize_title(strtolower(trim($game->name)));
        $post_meta_game_id = add_post_meta($post_id, $this->_config->postMetaGameId, $game->id, true); // add post meta data
        $post_meta_game_src_id = add_post_meta($post_id, $this->_config->postMetaGameSrc, $game->src, true); // add post meta data
        $post_meta_game_title = add_post_meta($post_id, $this->_config->postMetaGameTitle, $game_title, true); // add post meta data
        $post_meta_game_img = add_post_meta($post_id, $this->_config->postMetaGameImg, sprintf("%s/%s/%s/cover.jpg", $this->_config->gameImageUrl, sanitize_title(strtolower(trim($provider))), $game_title), true); // add post meta data

        $game_category_term_id = wp_set_object_terms($post_id, $category_id, $this->_config->gameCategoryTaxonomy); // link category and post
        $game_provider_term_id = wp_set_object_terms($post_id, $provider_id, $this->_config->gameProviderTaxonomy); // link provider and post
        //$game_operator_term_id = wp_set_object_terms($post_id, $operator_id, $this->_config->gameOperatorTaxonomy); // link operator and post

        $this->_groupTerms(array($category_id), $this->_config->gameCategoryTermGroupId, $this->_config->gameCategoryTaxonomy);
        $this->_groupTerms(array($provider_id), $this->_config->gameProviderTermGroupId, $this->_config->gameProviderTaxonomy);
        //$this->_groupTerms(array($operator_id), $this->_config->gameOperatorTermGroupId, $this->_config->gameOperatorTaxonomy);
    }

    private function _updateExistingGame($existing, $new, $provider) {
        $this->_updateStatus($existing, $new);
        //$this->_updateProviders($existing, $new, $provider);
    }

    /*
    private function _getProviderIds($providers) {
        $provider_ids = array();
        foreach($providers as $provider) {
            $provider_id = $this->_getProviderId(trim($provider));
            array_push($provider_ids, $provider_id);
        }
        return $provider_ids;
    }   

    private function _updateProviders($existing, $new, $provider) {
        $update = false;
        $providers = wp_get_post_terms($existing->ID, $this->_config->gameProviderTaxonomy, array('fields' => 'names'));
        if( ! in_array($provider, $providers) && $new->{$provider}) {
            array_push($providers, $provider);
            $update = true;
        }  elseif(! $new->{$provider}) {
            if(($key = array_search($provider, $providers)) !== false) {
                unset($providers[$key]);
                $update = true;
            }
        }
        if($update) {
            $provider_ids = $this->_getProviderIds($providers);
            $game_provider_term_id = wp_set_object_terms($existing->ID, $provider_ids, $this->_config->gameProviderTaxonomy); 
            $this->_groupTerms($provider_ids, $this->_config->gameProviderTermGroupId, $this->_config->gameProviderTaxonomy);
        }
    }
     */

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

        # first time importing games for this provider
        /*
        if( ! term_exists($provider, $this->_config->gameProviderTaxonomy)){ 
            $endpoint = sprintf('%s/vegasgod/games/provider/%s', $this->_config->apiUrl, $provider);
        } else {
            # get all games so we can remove providers
            $endpoint = sprintf('%s/vegasgod/games/', $this->_config->apiUrl);
        }
         */
        $endpoint = sprintf('%s/vegasgod/games/provider/%s', $this->_config->apiUrl, $provider);
        if($this->_haveLicense()) {
            $endpoint = sprintf('%s?license=%s&referer=%s', $endpoint, $this->_license, get_site_url());
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
                    $this->_insertNewGame($game, $provider);
                } else { 
                    $this->_updateExistingGame($post, $game, $provider);
                    $this->_updateExistingPostMeta($post, $game);
                }
            }
        }
    }

}
