<?php

class Vegashero_Settings_Affiliates
{

    private $_providers;
    private $_provider;
    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        //add_action('admin_init', array($this, 'registerSettings'));
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
        $endpoint = sprintf('%s/vegasgod/providers', $this->_config->apiUrl);
        // this needs to be cached locally!!!!
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_providers = json_decode(json_decode($response), true);
        foreach($this->_providers as $provider) {
            $this->_provider = $provider;
            $section = $this->_getSectionName($provider);
            $page = $this->_getPageName($provider);
            add_settings_section($section, sprintf('%s', ucfirst($provider)), array($this, 'getProviderDescription'), $page);
            $option_group = $this->_getOptionGroup($provider);
            $option_name = $this->getOptionName($provider);
            register_setting($option_group, $option_name);
        }
    }

    private function _getPageName($name) {
        return sprintf('vegashero-%s-page', $name);
    }

    private function _getSectionName($name) {
        return sanitize_title(sprintf('vegashero-%s-section', $name));
    }

    public function getProviderDescription() {
        echo "<p>This is a provider import description.</p>";
    }

    public function addSettingsMenu() {
        add_submenu_page(
            'vegashero-dashboard',         // Register this submenu with the menu defined above
            'Affiliate Links',          // The text to the display in the browser when this menu item is active
            'Affiliate links',                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-affiliates',          // The unique ID - the slug - for this menu item
            array($this, 'createAffiliatesPage')   // The function used to render this menu's page to the screen
        );
    }

    private function _getUpdateBtn($provider) {

        $markup = "&nbsp;&nbsp;<a href='";
        $update_url = plugins_url('queue.php', __FILE__);
        $markup .= "$update_url?provider=$provider'";
        $markup .= " class='button button-primary'";
        $markup .= ">Import games</a>";
        return $markup;
    }

    public function createAffiliatesPage() {
        include dirname(__FILE__) . '/templates/affiliates.php';
    }


}
