<?php

class Vegashero_Settings_Operators
{

    private $_operators;
    private $_operator;
    private $_config;

    public function __construct() {

        $this->_config = Vegashero_Config::getInstance();

        if(array_key_exists('page', $_GET)) {
            if($_GET['page'] === 'vegashero-operator-import' || 'vegashero-provider-import') {
                add_action('admin_head', array($this, 'loadOperatorStyles'));
            }
        }
        add_action('admin_menu', array($this, 'addSettingsMenu'));

        if(@$_GET['page'] === 'vegashero-dashboard' && @$_GET['vegashero-import'] === 'queued') {
            add_action( 'admin_notices', array($this, 'vegashero_admin_import_notice'));
        }
        add_action('admin_init', array($this, 'registerSettings'));

    }

    public function loadOperatorStyles() {
        $url = plugin_dir_url( __FILE__ ) . 'templates/operators.css';
        echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen">';
    }

    public function vegashero_admin_import_notice() {
      $vegas_gameslist_page = admin_url( "edit.php?post_type=vegashero_games" );
        echo '<div class="notice notice-success is-dismissible below-h2"><p>';
        echo _e( 'Your import has been queued. Please head over to the <a href="'.$vegas_gameslist_page.'">Vegas Hero Games</a> area to view/edit the games.' );
        echo '</p></div>';
    }


    public function registerSettings() {
        $endpoint = sprintf('%s/vegasgod/operators', $this->_config->apiUrl);
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
    }

    public function addSettingsMenu() {
        add_submenu_page(
            'vegashero-dashboard',         // Register this submenu with the menu defined above
            'Operator Imports',          // The text to the display in the browser when this menu item is active
            'Operator imports',                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-operator-import',          // The unique ID - the slug - for this menu item
            array($this, 'createSettingsPage')   // The function used to render this menu's page to the screen
        );
    }

    private function _getUpdateBtn($operator) {
        $markup = "<a href='";
        $update_url = plugins_url('queue.php', __FILE__);
        $markup .= "$update_url?operator=$operator'";
        $markup .= " class='button button-primary'";
        $markup .= ">Import games</a>";
        return $markup;
    }

    public function createSettingsPage() {
        include dirname(__FILE__) . '/templates/operators.php';
    }

}
