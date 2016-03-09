<?php

class Vegashero_Settings_Providers
{

    private $_providers;
    private $_provider;
    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        if(@$_GET['page'] === 'vegashero-provider-import' && @$_GET['vegashero-import'] === 'queued') {
            add_action( 'admin_notices', array($this, 'importNotice'));
        }
        if(@$_GET['page'] === 'vegashero-provider-import') {
            add_action('admin_init', array($this, 'registerSettings'));
        }
    }

    public function importNotice() {
      $vegas_gameslist_page = admin_url( "edit.php?post_type=vegashero_games" );
      include_once dirname(__FILE__) . '/templates/import-notice.php';
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
        if(count($this->_providers)) {
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

    private function _getUpdateBtn($provider) {
        $markup = "<a href='";
        $update_url = plugins_url('queue.php', __FILE__);
        $markup .= "$update_url?provider=$provider'";
        $markup .= " class='button";
        $markup .= wp_next_scheduled('vegashero_import_provider', array($provider)) ? "' disabled>Import queued" : " button-primary'>Import games";
        $markup .= "</a>";
        return $markup;
    }

    public function createSettingsPage() {
        include dirname(__FILE__) . '/templates/providers.php';
    }


}
