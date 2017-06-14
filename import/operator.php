<?php

class Vegashero_Import_Operator extends Vegashero_Import
{

    private $_license = '';

    public function __construct() {
        parent::__construct();
        $this->_config = Vegashero_Config::getInstance();
        $license = Vegashero_Settings_License::getInstance();
        $this->_license = $license->getLicense();

        // increase curl timeout
        add_action('http_api_curl', array('Vegashero_Import', 'increaseCurlTimeout'), 100, 1);

        // this action is scheduled in queue.php
        add_action('vegashero_import_operator', array($this, 'importGamesForOperator'));

        // custom wp api endpoint for importing providers via ajax
        // TODO: Add permissions callback for creating custom permissions
        add_action( 'rest_api_init', function () {
            $namespace = self::getApiNamespace($this->_config);
            $route = self::getApiRoute();
            register_rest_route( $namespace, $route . '(?P<operator>.+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'ajaxImportGamesForOperator')
            ));
        });
    }

    public function __destruct() {
        parent::__destruct();
    }

    static public function getApiNamespace($config) {
        return sprintf('%s/%s', $config->apiNamespace, $config->apiVersion);
    }

    static public function getApiRoute() {
        return '/import/operator/';
    }

    static public function getApiEndpoint($config) {
        return self::getApiNamespace($config) . self::getApiRoute();
    }

    public function ajaxImportGamesForOperator(WP_REST_Request $request) {
        $operator = $request['operator'];
        return $this->importGamesForOperator($operator);
    }

    private function _setOperators() {
        $endpoint = sprintf('%s/vegasgod/operators/v2', $this->_config->apiUrl);
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
        // $this->_operators = array_slice(array_keys((array)$game), 6, -2);
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
        try {
            error_log(sprintf("starting import for operator: %s", $operator));
            if( ! term_exists($operator, $this->_config->gameOperatorTaxonomy)){ 
                $endpoint = sprintf('%s/vegasgod/games/%s', $this->_config->apiUrl, $operator);
            } else {
                # get all games so we can remove operators
                $endpoint = sprintf('%s/vegasgod/games/', $this->_config->apiUrl);
            }
            if($this->_haveLicense()) {
                error_log(sprintf("license specified as: %s", $this->_license));
                $endpoint = sprintf('%s?license=%s&referer=%s', $endpoint, $this->_license, get_site_url());
            } else {
                error_log("no license specified");
            }

            error_log(sprintf("using the following endpoint for operator import: %s", $endpoint));

            $response = wp_remote_get($endpoint);
            if(is_wp_error($response)) {
                error_log(sprintf("error: %s", $response->get_error_message()));
                return $response;
            }

            $body = wp_remote_retrieve_body($response);
            if(is_wp_error($body)) {
                error_log(sprintf("wp_remote_retrieve_body() error: %s", $body->get_error_message()));
                return $body;
            }

            $games = json_decode($body);
            if(is_null($games)) {
                error_log("json_decode() returned NULL");
                return new WP_Error( 'json_decode_error', "json_decode() returned NULL", array( 'status' => 500 ) );
            }

            if($this->_noGamesToImport($games)) {
                error_log("warning: no games to import");
                return new WP_Error( 'no_games', 'No games to import', array( 'status' => 404 ) );
            } else {
                error_log("decoding games");
                $games = json_decode($games);
            }

            $successful_imports = 0;
            $newly_imported = 0;
            $games_updated = 0;

            if(count($games) > 0) {
                error_log(sprintf("Game count for operator %s is %s", $operator, count($games)));
                foreach($games as $game) {
                    // check if post exists for this game
                    //error_log("Checking if posts exists for game");
                    $posts = $this->_getPostsForGame($game);

                    $post_id = 0;
                    if(count($posts)) {
                        $post = $posts[0];
                        $post_id = $post->ID;
                    }

                    if( ! $post_id && $game->$operator) { // no existing post
                        //error_log(sprintf("Post %s does not exist", $post->ID));
                        //error_log(sprintf("Inserting game '%s'", $post->name));
                        $this->_insertNewGame($game, $operator);
                        $newly_imported++;
                    } else { 
                        //error_log(print_r($post, true));
                        //error_log($post->post_status);
                        $this->_updateExistingGame($post, $game, $operator);
                        $this->_updateExistingPostMeta($post, $game);
                        if($game->$operator) {
                          $games_updated++;
                        }
                    }

                    if($game->$operator) {
                      $successful_imports++;
                    }
                }
                return array(
                    "code" => "success",
                    "message" => "Import completed successfully",
                    "data" => array(
                        "game_source" => $operator,
                        "successful_imports" => $successful_imports,
                        "new_games_imported" => $newly_imported,
                        "existing_games_updated" => $games_updated
                    )
                );
            } else {
                error_log("warning: no games to import");
                return new WP_Error( 'no_games', 'No games to import', array( 'status' => 404 ) );
            }
        } catch(Exception $e) {
            error_log(sprintf("error: %s", $e->getMessage()));
            return new WP_Error( 'import_error', $e->getMessage(), array( 'status' => 500 ) );
        }
    }


}
