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

        if(@$_GET['page'] === 'vegashero-operator-import' && @$_GET['vegashero-import'] === 'queued') {
            add_action( 'admin_notices', array($this, 'importNotice'));
        }
        if(@$_GET['page'] === 'vegashero-operator-import') {
            add_action('admin_init', array($this, 'registerSettings'));
        }

    }

    public function loadOperatorStyles() {
        $url = plugin_dir_url( __FILE__ ) . 'templates/operators.css';
        echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen">';
    }

    public function importNotice() {
      $vegas_gameslist_page = admin_url( "edit.php?post_type=vegashero_games" );
      include_once dirname(__FILE__) . '/templates/import-notice.php';
    }

    public function registerSettings() {
        $endpoint = sprintf('%s/vegasgod/operators', $this->_config->apiUrl);
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
    }

    public function addSettingsMenu() {
        add_submenu_page(
            'vh-settings',         // Register this submenu with the menu defined above
            'Import by Operator',          // The text to the display in the browser when this menu item is active
            'Import by Operator',                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-operator-import',          // The unique ID - the slug - for this menu item
            array($this, 'createSettingsPage')   // The function used to render this menu's page to the screen
        );
    }

    private function _getUpdateBtn($operator) {
        $markup = "<a href='";
        $update_url = plugins_url('queue.php', __FILE__);
        $markup .= "$update_url?operator=$operator'";
        $markup .= " class='button";
        $markup .= wp_next_scheduled('vegashero_import_operator', array($operator)) ? "' disabled>Import queued" : " button-primary'>Import games";
        $markup .= "</a>";
        return $markup;
    }

    public function createSettingsPage() {
        include dirname(__FILE__) . '/templates/operators.php';
    }

}
