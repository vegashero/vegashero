<?php

class Vegashero_Settings_Dashboard
{

    private $_config;

    public function __construct() {
        $this->_config = new Vegashero_Config();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function registerSettings() {
        $section = $this->_getSectionName();
        $page = $this->_getPageName();
        $field = $this->_getAffiliateCodeInputKey();
        $title = 'This is the license page';
        add_settings_section($section, $title, array($this, 'getDescriptionForSiteSettings'), $page);
        add_settings_field($field, 'License key', array($this, 'createAffiliateCodeInput'), $page, $section);
        $option_group = $this->_getOptionGroup();
        $option_name = $this->getOptionName();
        register_setting($option_group, $option_name, array($this, 'validateLicenseKey'));
    }

    /*
     * We could validate license key here upon saving
     */
    public function validateLicenseKey($license_key) {
        return $license_key;
    }

    private function _getOptionGroup() {
        return $this->_config->settingsLicenseName;
    }

    public function getOptionName() {
        return $this->_config->settingsLicenseName;
    }

    private function _getAffiliateCodeInputKey() {
        return $this->_config->settingsLicenseName;
    }

    private function _getPageName() {
        return 'vegashero-dashboard-settings-page';
    }

    private function _getSectionName() {
        return 'vegashero-dashboard-settings-section';
    }

    public function createAffiliateCodeInput($args) {
        $key = $this->_getAffiliateCodeInputKey();
        $name = $this->getOptionName();
        // for array of options
        // echo "<input name='".$name."[".$key."]' size='40' type='text' value='".get_option($name)."' />";
        // for single option
        echo "<input name='".$name."' size='30' type='text' value='".get_option($name)."' placeholder='enter your license key here' />";
    }

    public function addSettingsMenu() {
        add_menu_page(
            'VegasHero Settings', // The title to be displayed on this menu's corresponding page
            'Vegas Hero', // The text to be displayed for this actual menu item
            'manage_options', // Which type of users can see this menu
            'vegashero-dashboard', // The unique ID - that is, the slug - for this menu item
            array($this, 'createDashboardPage') // The name of the function to call when rendering this menu's page
        );
    }

    public function createDashboardPage() { 
        include dirname(__FILE__) . '/templates/dashboard.php';
    }

    public function getDescriptionForSiteSettings() {
        echo "<p>Add your full affiliate url here.</p>";
    }

}
