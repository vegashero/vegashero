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
            // TODO: why vegashero-provider-import?
            if($_GET['page'] === 'vegashero-operator-import' || 'vegashero-provider-import') {
                add_action('admin_head', array($this, 'loadOperatorStyles'));
            }
            if($_GET['page'] === 'vegashero-operator-import') {
                add_action('admin_enqueue_scripts', array($this, 'enqueueAjaxScripts'));
                add_action('admin_init', array($this, 'registerSettings'));
            }
        }
        add_action('admin_menu', array($this, 'addSettingsMenu'));
    }

    public function loadOperatorStyles() {
        $url = plugins_url('vegashero/lib/Settings/templates/operators.css');
        echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen">';
    }

    public function registerSettings() {
        $endpoint = sprintf('%s/vegasgod/operators/v3', $this->_config->apiUrl);
        $this->_operators = $this->_fetchList($endpoint);
    }

    public function addSettingsMenu() {
        add_submenu_page(
            'vh-settings',         // Register this submenu with the menu defined above
            wp_strip_all_tags(__('Import by Operator', 'vegashero')),          // The text to the display in the browser when this menu item is active
            wp_strip_all_tags(__('Import by Operator', 'vegashero')),                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-operator-import',          // The unique ID - the slug - for this menu item
            array($this, 'createSettingsPage')   // The function used to render this menu's page to the screen
        );
    }

    private function _getAjaxUpdateBtn($operator) {
        $markup = "<button";
        $markup .= " class='button button-primary vh-import'";
        $markup .= sprintf(" data-fetch='%s/wp-json/%s%s%s?_wpnonce=%s'", site_url(), \Vegashero\Import\Operator::getApiNamespace($this->_config), \Vegashero\Import\Operator::getFetchApiRoute(), $operator, wp_create_nonce('wp_rest'));
        $markup .= sprintf(" data-import='%s/wp-json/%s%s%s?_wpnonce=%s'", site_url(), \Vegashero\Import\Operator::getApiNamespace($this->_config), \Vegashero\Import\Operator::getImportApiRoute(), $operator, wp_create_nonce('wp_rest'));
        $markup .= ">";
        $markup .= wp_strip_all_tags(__("Import games", 'vegashero'));
        $markup .= "</button>";
        return $markup;
    }

    public function createSettingsPage() {
        include dirname(__FILE__) . '/templates/operators.php';
    }
}
