<?php

class Vegashero_Settings_Providers extends Vegashero_Settings
{

    private $_providers;
    private $_provider;
    private $_config;

    public function __construct() {
        $this->_config = \Vegashero_Config::getInstance();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        if(@$_GET['page'] === 'vegashero-provider-import') {
            add_action('admin_enqueue_scripts', array($this, 'enqueueAjaxScripts'));
            add_action('admin_init', array($this, 'registerSettings'));
        }
    }

    private function _getOptionGroup($provider=null) {
        if(is_null($provider)) {
            $provider = $this->_provider;
        }
        return sprintf('vegashero_settings_group_%s', $provider);
    }

    public function getOptionName($provider=null) {
        if(is_null($provider)) {
            $provider = $this->_provider;
        }
        return sprintf('%s%s', $this->_config->settingsNamePrefix, $provider);
    }

    private function _getAffiliateCodeInputKey($provider=null) {
        if(is_null($provider)) {
            $provider = $this->_provider;
        }
        return sprintf('%s-affiliate-code', $provider);
    }

    public function registerSettings() {
        $endpoint = sprintf('%s/vegasgod/providers/v2', $this->_config->apiUrl);
        $this->_providers = $this->_fetchList($endpoint);
    }

    private function _getPageName($name) {
        return sprintf('vegashero-%s-page', $name);
    }

    private function _getSectionName($name) {
        return sanitize_title(sprintf('vegashero-%s-section', $name));
    }

    public function getProviderDescription() {
        //echo "<p>This is a provider import description.</p>";
    }

    public function addSettingsMenu() {
        add_submenu_page(
            'vh-settings',         // Register this submenu with the menu defined above
            'Import by Provider',          // The text to the display in the browser when this menu item is active
            'Import by Provider',                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-provider-import',          // The unique ID - the slug - for this menu item
            array($this, 'createSettingsPage')   // The function used to render this menu's page to the screen
        );
    }

    private function _getGameCount($count) {
        if(get_option('vh_license_status') === 'valid') { 
            return "<span class='right gamecount'>Games available: <strong>$count</strong></span>";
        }
        else { 
            return "<span class='right gamecount' title='Purchase a license key to unlock access to all the games'>Games available: <strong>2</strong> / $count <span class='dashicons dashicons-lock'></span></span>";
        }
    }

    private function _getAjaxUpdateBtn($provider) {
        $markup = "<button";
        $markup .= " class='button button-primary vh-import'";
        $markup .= sprintf(" data-fetch='%s/wp-json/%s%s%s?_wpnonce=%s'", site_url(), \Vegashero\Import\Provider::getApiNamespace($this->_config), \Vegashero\Import\Provider::getFetchApiRoute(), $provider, wp_create_nonce('wp_rest'));
        $markup .= sprintf(" data-import='%s/wp-json/%s%s?_wpnonce=%s'", site_url(), \Vegashero\Import\Provider::getApiNamespace($this->_config), \Vegashero\Import\Provider::getImportApiRoute(), wp_create_nonce('wp_rest'));
        $markup .= ">Import games";
        $markup .= "</button>";
        return $markup;
    }

    public function createSettingsPage() {
        include dirname(__FILE__) . '/templates/providers.php';
    }

}
