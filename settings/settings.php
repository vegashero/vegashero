<?php

abstract class Vegashero_Settings {

  public function enqueueAjaxScripts() {
    wp_enqueue_script('vegashero-import', plugins_url( '/js/vegashero-import.js', __FILE__ ), array('jquery'), null, true);
  }

  /**
   * Fetch list of providers from cache
   * @param string $cache_id
   * @return array Cached array of providers or operators
   */
  protected function _getCachedList($cache_id) {
    if($items = get_transient($cache_id)) {
      return $items;
    }
    return Array();
  }

  /**
   * @param string $cache_id
   * @param array $items Array of providers or operators
   * @return array Cached array of providers or operators
   */
  protected function _cacheList($cache_id, $items) {
    set_transient( $cache_id, $items, HOUR_IN_SECONDS);
  }

  /**
   * @param string $cache_id
   * @return boolean
   */
  protected function _clearCache($cache_id) {
    return delete_transient($cache_id);
  }

  /**
   * Fetch list of operators or providers from cache or remote server
   * @param string $endpoint
   * @return array|false Array of providers or operators
   */
  protected function _fetchList($endpoint) {
    $items = $this->_getCachedList($endpoint);
    if(empty($items)) {
      $response = wp_remote_get($endpoint);
      if( ! is_wp_error($response)) { //TODO: do something about error scenario
        $body = wp_remote_retrieve_body($response);
        if( ! is_wp_error($body)) { //TODO: do something about error scenario
          $items = json_decode(json_decode($body), true);
          if(count($items)) {
            $this->_cacheList($endpoint, $items);
          }
        }
      }
    }
    return $items;
  }


}

require_once( dirname( __FILE__ ) . '/license.php' );
$dashboard = Vegashero_Settings_License::getInstance();

require_once( dirname( __FILE__ ) . '/lobby.php' );
$lobby = Vegashero_Settings_Lobby::getInstance();

require_once( dirname( __FILE__ ) . '/permalinks.php' );
$lobby = Vegashero_Settings_Permalinks::getInstance();
$lobby->updateCustomPostTypeUrl();
$lobby->updateGameCategoryUrl();
$lobby->updateGameOperatorUrl();
$lobby->updateGameProviderUrl();

require_once( dirname( __FILE__ ) . '/operators.php' );
$operators = new Vegashero_Settings_Operators();

require_once( dirname( __FILE__ ) . '/providers.php' );
$providers = new Vegashero_Settings_Providers();

//require_once( dirname( __FILE__ ) . '/settings/affiliates.php' );
//$affiliates = new Vegashero_Settings_Affiliates();



