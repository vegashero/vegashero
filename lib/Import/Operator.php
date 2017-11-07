<?php

namespace VegasHero\Import;

class Operator extends Import
{

    public function __construct() {
        parent::__construct();
        $this->_config = \Vegashero_Config::getInstance();
        $license = \Vegashero_Settings_License::getInstance();
        $this->_license = $license->getLicense();

        // increase curl timeout
        add_action('http_api_curl', array('Vegashero_Import', 'increaseCurlTimeout'), 100, 1);

        // custom wp api endpoint for importing providers via ajax
        add_action( 'rest_api_init', function () {
            $namespace = self::getApiNamespace($this->_config);
            register_rest_route( $namespace, self::getFetchApiRoute() . '(?P<operator>.+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'fetchGames')
            ));
            register_rest_route( $namespace, self::getImportApiRoute() . '(?P<operator>.+)', array(
                'methods' => 'POST',
                'callback' => array($this, 'importGames')
            ));
        });

    }

    public function __destruct() {
        parent::__destruct();
    }

    static public function getFetchApiRoute() {
        return '/fetch/operator/';
    }

    static public function getImportApiRoute() {
        return '/import/operator/';
    }

    private function _setOperators() {
        $endpoint = sprintf('%s/vegasgod/operators/v2', $this->_config->apiUrl);
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
        // $this->_operators = array_slice(array_keys((array)$game), 6, -2);
    }

    /**
     * Insert new game only when operator is true
     * @param object $game
     * @param string $operator
     */
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
            'post_content'   => '',
            'post_name'      => sanitize_title($game->name),
            'post_title'     => ucfirst($game->name),
            'post_status'    => $game->status ? 'publish' : 'draft',
            'post_type'      => $this->_config->customPostType,
            'post_excerpt'   => ''
        );
        $post_id = wp_insert_post($post);
        $category_id = $this->_getCategoryId(trim($game->category));
        $provider_id = $this->_getProviderId(trim($game->provider));
        $operator_id = $this->_getOperatorId(trim($operator));

        $game_title = sanitize_title(strtolower(trim($game->name)));

        $post_meta_game_id = add_post_meta($post_id, $this->_config->postMetaGameId, $game->id, true); // add post meta data
        $post_meta_game_src_id = add_post_meta($post_id, $this->_config->postMetaGameSrc, $game->src, true); // add post meta data
        $post_meta_game_title = add_post_meta($post_id, $this->_config->postMetaGameTitle, $game_title, true); // add post meta data
        $post_meta_game_img = add_post_meta($post_id, $this->_config->postMetaGameImg, sprintf("%s/%s/%s/cover.jpg", $this->_config->gameImageUrl, sanitize_title(strtolower(trim($game->provider))), $game_title), true); // add post meta data

        $game_category_term_id = wp_set_object_terms($post_id, $category_id, $this->_config->gameCategoryTaxonomy); // link category and post
        $game_provider_term_id = wp_set_object_terms($post_id, $provider_id, $this->_config->gameProviderTaxonomy); // link provider and post
        $game_operator_term_id = wp_set_object_terms($post_id, $operator_id, $this->_config->gameOperatorTaxonomy); // link operator and post

        $this->_groupTerms(array($category_id), $this->_config->gameCategoryTermGroupId, $this->_config->gameCategoryTaxonomy);
        $this->_groupTerms(array($provider_id), $this->_config->gameProviderTermGroupId, $this->_config->gameProviderTaxonomy);
        $this->_groupTerms(array($operator_id), $this->_config->gameOperatorTermGroupId, $this->_config->gameOperatorTaxonomy);
    }

    private function _getOperatorIds($operators) {
        $operator_ids = array();
        foreach($operators as $operator) {
            $operator_id = $this->_getOperatorId(trim($operator));
            array_push($operator_ids, $operator_id);
        }
        return $operator_ids;
    }

    /**
     * @param string $operator Game operator name
     * @return string Remote endpoint to import games from
     */
    private function _getEndpoint($operator) {
        $endpoint = sprintf('%s/vegasgod/games/%s', $this->_config->apiUrl, $operator);
        if($this->_haveLicense()) {
            $endpoint = sprintf('%s?license=%s&referer=%s', $endpoint, $this->_license, get_site_url());
        }
        return $endpoint;
    }

    /*
     * Fetch list of games from cache or remote server
     * @param string $operator Game operator name
     * @return Array|WP_Error of games or WP_Error object
     *   WP Rest API converts the objects to JSON for us
     */
    public function fetchGames(\WP_REST_Request $request) {
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

        try {
            $operator = $request['operator'];
            $cache_id = $this->_getCacheId($operator);
            $games = $this->_getCachedListOfGames($cache_id);
            if(empty($games)) { // fetch games from remote
                $endpoint = $this->_getEndpoint($operator);
                $response = wp_remote_get($endpoint);
                if(is_wp_error($response)) {
                    return $response;
                }
                $body = wp_remote_retrieve_body($response);
                if(is_wp_error($body)) {
                    return $body;
                }
                $games = json_decode($body);
                if(is_null($games)) {
                    return new \WP_Error( 'json_decode_error', "json_decode() returned NULL", array( 'status' => 500 ) );
                }
                if($this->_noGamesToImport($games)) {
                    return new \WP_Error( 'no_games', 'No games to import', array( 'status' => 404 ) );
                } else {
                    $games = json_decode($games);
                    $this->_cacheListOfGames($cache_id, $games);
                }
            }
            return $games;
        } catch(Exception $e) {
            return new \WP_Error( 'import_error', $e->getMessage(), array( 'status' => 500 ) );
        }
    }

    /**
     * Check if game has already been imported and exists
     * @param object $game
     * @return boolean
     */
    private function _gameExists($game) {
        $posts = $this->_getPostsForGame($game);
        return (boolean)count($posts);
    }

    /**
     * @param object $game
     * @return object Existing post object
     */
    private function _getExistingPost($game) {
        $posts = $this->_getPostsForGame($game);
        return $posts[0];
    }

    /**
     * Add the operator term to the game when the game is marked 1, but take no action when the game is marked 0
     * @param object $game
     * @pram string $operator
     * @return boolean
     */
    private function _operatorProvidesGame($game, $operator) {
        return $game->$operator;
    }

    private function _updateOperators($existing, $new, $operator) {
        $update = false;
        $operators = wp_get_post_terms($existing->ID, $this->_config->gameOperatorTaxonomy, array('fields' => 'names'));
        if( ! in_array($operator, $operators) && $new->{$operator}) {
            array_push($operators, $operator);
            $update = true;
        }  
    }

    /**
     * @param string $operator
     * @param object $post
     * @return array 
     */
    private function _getOperatorsForPost($post) {
        return wp_get_post_terms($post->ID, $this->_config->gameOperatorTaxonomy, array('fields' => 'names'));
    }

    /**
     * Overwrite all terms for game post
     * @param array $operators List of operators that offer the game
     * @param object $post The existing game post
     * @return null
     */
    private function _reassociateOperatorsWithPost($operators, $post) {
        $operator_ids = $this->_getOperatorIds($operators);
        $game_operator_term_id = wp_set_object_terms($post->ID, $operator_ids, $this->_config->gameOperatorTaxonomy); 
        $this->_groupTerms($operator_ids, $this->_config->gameOperatorTermGroupId, $this->_config->gameOperatorTaxonomy);
    }

    /**
     * Not being used at the moment, but potentially useful example code
     */
    private function _removePostTerms($operator, $operators) {
        if(($key = array_search($operator, $operators)) !== false) {
            unset($operators[$key]);
        }
        return $operators;
    }


    private function _addPostTerms($post, $operator) {
        $operators = $this->_getOperatorsForPost($post);
        if( ! in_array($operator, $operators)) {
            array_push($operators, $operator);
        }  
        return $operators;
    }

    /**
     * Add operator term to existing post
     * @param object $post Existing game post
     * @param string $operator Operator name
     * @return null
     */
    private function _updatePostTerms($post, $operator) {
        $operators = $this->_addPostTerms($post, $operator);
        $this->_reassociateOperatorsWithPost($operators, $post);
    }

    /**
     * @param string $games JSON string representing an array of games to import
     * @return array<string, string|array>
     */
    public function importGames(\WP_REST_Request $request) {
        $params = $request->get_url_params();
        $operator = $params['operator'];
        try {
            $games = json_decode($request->get_body());
            $successful_imports = 0;
            $newly_imported = 0;
            $games_updated = 0;

            if(count($games) > 0) {
                foreach($games as $game) {

                    if($this->_operatorProvidesGame($game, $operator)) {
                        if( ! $this->_gameExists($game)) {
                            $this->_insertNewGame($game, $operator);
                            $newly_imported++;
                        } else {
                            $post = $this->_getExistingPost($game);
                            $this->_updatePostTerms($post, $operator);
                            $this->_updateStatus($post, $game);
                            $this->_updateExistingPostMeta($post, $game);
                            $games_updated++;
                        }
                        $successful_imports++;
                    }
                }
                return array(
                    "code" => "success",
                    "message" => "Import completed successfully",
                    "data" => array(
                        "successful_imports" => $successful_imports,
                        "new_games_imported" => $newly_imported,
                        "existing_games_updated" => $games_updated
                    )
                );
            } else {
                return new \WP_Error( 'no_games', 'No games to import', array( 'status' => 404 ) );
            }

        } catch(Exception $e) {
            return new \WP_Error( 'import_error', $e->getMessage(), array( 'status' => 500 ) );
        }
    }
}
