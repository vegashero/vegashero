<?php

namespace VegasHero\Import;
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

abstract class Import
{

    protected $_config;
    protected $_error_reporting;
    protected $_display_errors;
    protected $_license = '';

    protected function __construct() {
        //$this->_error_reporting = error_reporting();
        $this->_display_errors = ini_get('display_errors');
        //error_reporting(0);
        ini_set('display_errors', 0);
    }

    static public function getApiNamespace($config) {
        return sprintf('%s/%s', $config->apiNamespace, $config->apiVersion);
    }

    protected function __destruct() {
        //error_reporting($this->_errror_reporting);
        ini_set('display_errors', $this->_display_errors);
    }

    static public function increaseCurlTimeout($handle) {
        curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 120 );
        curl_setopt( $handle, CURLOPT_TIMEOUT, 120 );
    }

    protected function _haveLicense() {
        if( ! empty($this->_license)) {
            return true;
        }
    }

    protected function _cacheListOfGames($cache_id, $games) {
        set_transient( $cache_id, gzcompress(serialize($games)), HOUR_IN_SECONDS);
    }

    protected function _clearCache($cache_id) {
        return delete_transient($cache_id);
    }

    /**
     * Fetch list of games from cache
     * @param string $cache_id
     * @return array Cached array of games
     */
    protected function _getCachedListOfGames($cache_id) {
        if($games = get_transient($cache_id)) {
            return unserialize(gzuncompress($games));
        }
        return Array();
    }

    /**
     * @param string $provider
     * @return string
     */
    protected function _getCacheId($name) {
        $cache_id = sprintf("vegashero_cached_list_of_games_from_%s", $name);
        return $this->_haveLicense() ? $cache_id : sprintf("%s_free", $cache_id);
    }

    ##
    # @return Boolean
    ##
    protected function _noGamesToImport($games) {
        return (property_exists($games, 'code') && $games->code == 'vegasgod_no_games');
    }

    protected function _getOperatorId($operator) {
        if( ! $operator_id = term_exists($operator, $this->_config->gameOperatorTaxonomy)){
            $operator_id = wp_insert_category(
                array(
                    'cat_name' => $operator,
                    'category_description' => 'VegasHero Game Operators',
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

    protected function _getProviderId($provider) {
        if( ! $provider_id = term_exists($provider, $this->_config->gameProviderTaxonomy)){
            $provider_id = wp_insert_category(
                array(
                    'cat_name' => $provider,
                    'category_description' => 'VegasHero Game Providers',
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

    protected  function _getCategoryId($category) {
        if( ! $category_id = term_exists($category, $this->_config->gameCategoryTaxonomy)){
            $category_id = wp_insert_category(
                array(
                    'cat_name' => $category,
                    'category_description' => 'VegasHero Game Categories',
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

    protected function _getPostsForGame($game) {
        $args = array(
            'post_type' => $this->_config->customPostType,
            'post_status' => 'any',
            'meta_key' => 'game_id',
            'meta_value' => $game->id,
            'meta_compare' => '='
        );
        return get_posts($args);
    }

    protected function _updateGameId($existing, $game) {
        $game_id = get_post_meta($existing->ID, $this->_config->postMetaGameId, true);
        if($game_id != $game->id) {
            update_post_meta($existing->ID, $this->_config->postMetaGameId, $game->id, $game_id);
        }
    }

    private function _updateGameSrc($existing, $game) {
        $game_src = get_post_meta($existing->ID, $this->_config->postMetaGameSrc, true);
        if($game_src != $game->src) {
            update_post_meta($existing->ID, $this->_config->postMetaGameSrc, $game->src, $game_src);
        }
    }

    private function _updateGameTitle($existing, $game) {
        $game_title = get_post_meta($existing->ID, $this->_config->postMetaGameTitle, true);
        if($game_title != sanitize_title(strtolower(trim($game->name)))) {
            update_post_meta($existing->ID, $this->_config->postMetaGameTitle, sanitize_title(strtolower(trim($game->name))), $game_title);
        }
    }

    protected function _updateExistingPostMeta($existing, $game) {
        $this->_updateGameSrc($existing, $game);
        $this->_updateGameTitle($existing, $game);
        //$game_img = get_post_meta($existing->ID, $this->_config->postMetaGameImg, true);
        //$providers = wp_get_post_terms($existing->ID, $this->_config->gameProviderTaxonomy);
    }

    /**
     * Set games to draft when status was 1, but changes to 0
     * Never set games to publish when status was draft
     *
     * @param object $existing
     * @param object $new Status property is 0 for draft and 1 for publish
     * @return null
     */
    protected function _updateStatus($existing, $new) {
        $new->status = $new->status ? 'publish' : 'draft';
        if($existing->post_status != 'draft') {
            if($existing->post_status != $new->status) {
                $existing->post_status = $new->status;
                $existing->edit_date = true;
                wp_update_post($existing, true);
            }
        }
    }

    protected function _groupTerms(array $term_ids, $term_group, $taxonomy) {
        if(count($term_ids)> 0) {
            foreach($term_ids as $term_id) {
                wp_update_term($term_id, $taxonomy, array(
                    'term_group' => $term_group
                ));
            }
        }
    }

}
