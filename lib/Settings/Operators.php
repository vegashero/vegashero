<?php

namespace VegasHero\Settings;

require_once("Import.php");

class Operators extends \VegasHero\Settings\Import
{

    private $_operators;
    private $_operator;
    private $_config;

    public function __construct() {

        $this->_config = \VegasHero\Config::getInstance();

        if(array_key_exists('page', $_GET)) {
            if($_GET['page'] === 'vegashero-operator-import' || 'vegashero-provider-import') {
                add_action('admin_head', array($this, 'loadOperatorStyles'));
            }
        }
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        if(@$_GET['page'] === 'vegashero-operator-import') {
            add_action('admin_enqueue_scripts', array($this, 'enqueueAjaxScripts'));
            add_action('admin_init', array($this, 'registerSettings'));
        }

    }

    public function loadOperatorStyles() {
        $url = plugin_dir_url( __FILE__ ) . 'templates/operators.css';
        echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen">';
    }

    public function registerSettings() {
        $endpoint = sprintf('%s/vegasgod/operators/v2', $this->_config->apiUrl);
        $this->_operators = $this->_fetchList($endpoint);
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

    private function _getAjaxUpdateBtn($operator, $count) {
        $markup = "<button";
        $markup .= " class='button button-primary vh-import'";
        $markup .= sprintf(" data-fetch='%s/wp-json/%s%s%s?_wpnonce=%s'", site_url(), \Vegashero\Import\Operator::getApiNamespace($this->_config), \Vegashero\Import\Operator::getFetchApiRoute(), $operator, wp_create_nonce('wp_rest'));
        $markup .= sprintf(" data-import='%s/wp-json/%s%s%s?total=%d&_wpnonce=%s'", site_url(), \Vegashero\Import\Operator::getApiNamespace($this->_config), \Vegashero\Import\Operator::getImportApiRoute(), $operator, $count, wp_create_nonce('wp_rest'));
        $markup .= ">Import games";
        $markup .= "</button>";
        return $markup;
    }


    private function _getGameCount($count) {
        if(get_option('vh_license_status') === 'valid') { 
            return "<span class='right gamecount'>Games available: <strong>$count</strong></span>";
        }
        else { 
            return "<span class='right gamecount' title='Purchase a license key to unlock access to all the games'>Games available: <strong>2</strong> / $count <span class='dashicons dashicons-lock'></span></span>";
        }
    }

    public function createSettingsPage() {
        include dirname(__FILE__) . '/templates/operators.php';
    }

}
