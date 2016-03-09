<?php
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

abstract class Vegashero_Import
{

    protected $_config;

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

    protected function _updateExistingPostMeta($existing, $game) {
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
    
    protected function _updateStatus($existing, $new) {
        $new->status = $new->status ? 'publish' : 'draft';
        if($existing->post_status != $new->status) {
            $existing->post_status = $new->status;
            $existing->edit_date = true;
            wp_update_post($existing, true);
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
