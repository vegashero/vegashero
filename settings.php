<?php

class Vegashero_Settings 
{

    private $_sites;
    private $_site;

    public function __construct() {

        $vegasgod = $this->_getVegasgod();
        $this->_sites = $vegasgod->getSites();

        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));

    }

    private function _getVegasgod() {
        // $vegasgod_plugin = WP_PLUGIN_DIR . '/vegasgod/api.php';
        // if( ! file_exists($vegasgod_plugin)) {
        //     throw new Exception('Requires Vegas God Plugin');
        // }
        // require_once WP_PLUGIN_DIR . '/vegasgod/api.php';
        return new Vegasgod_Api;
    }

    public function registerSettings() {

        foreach($this->_sites as $site) {
            $this->_site = $site;
            $section = $this->_getSectionName($site);
            $page = $this->_getPageName($site);
            $field = sprintf('%s-affiliate-code', $site);
            add_settings_section($section, sprintf('%s Settings', ucfirst($site)), array($this, 'getDescriptionForSiteSettings'), $page);
            add_settings_field($field, 'Affiliate code', array($this, 'createAffiliateCodeInput'), $page, $section);
            $option_group = sprintf('vegashero_settings_group_%s', $site);
            $option_name = sprintf('vegashero_settings_name_%s', $site);
            $sanitize_callback = '';

            register_setting($option_group, $option_name, $sanitize_callback);
        }
    }

    private function _getPageName($site) {
        return sprintf('vegashero-plugin-%s-settings-page', $site);
    }

    private function _getSectionName($site) {
        return sprintf('%s-settings-section', $site);
    }

    public function getDescriptionForSiteSettings() {
        echo "<p>Site specific settings description goes here</p>";
    }

    public function createAffiliateCodeInput() {
        $key = sprintf('vegashero_plugin_%s_settings_key', $this->_site);
        $options = get_option($key);
        echo "<input name='".$key."[".$this->_site."]' size='40' type='text' value='' />";
    }

    public function addSettingsMenu() {
        add_options_page('Vegas Hero Plugin', 'Vegas Hero', 'manage_options', 'vegashero-plugin', array($this, 'createSettingsPage'));
    }

    public function createSettingsPage() {
        echo '<div class="wrap">';
        echo '<h2>Vegas Hero Settings</h2>';
        foreach($this->_sites as $site) {
            echo '<form method="post" action="options.php">';
            settings_fields(sprintf('vegashero_settings_group_%s', $site));
            $page = $this->_getPageName($site);
            do_settings_sections($page);
            echo "<input type='submit' name='submit' class='button button-primary' value='Apply code'>";
            $update_url = plugins_url('update.php', __FILE__);
            echo "&nbsp;&nbsp;<a href='$update_url' class='button button-primary'>Update games</a>";
            echo '</form>';
        }
        echo '</div>';

    }
}

