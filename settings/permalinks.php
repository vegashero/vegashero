<?php

class Vegashero_Settings_Permalinks
{

    private static $_config;
    private static $_instance;

    public static function getInstance() {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    private function __clone() {
    }

    protected function __construct() {
        static::$_config = Vegashero_Config::getInstance();
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function registerSettings() {
        $section = self::_getSectionName();
        $page = self::_getPageName();
        add_settings_section($section, $title=null, $description=null, $page);
        add_settings_field(self::_getLicenseInputKey(), 'License Key', array($this, 'createLicenseInput'), $page, $section);
        add_settings_field(self::_getLicenseActivationButtonKey(), 'Activate License', array($this, 'createLicenseActivationButton'), $page, $section);
        $option_group = self::_getOptionGroup();
        $option_name = self::_getOptionName();
        register_setting($option_group, $option_name, array($this, 'sanitizeLicense'));
    }

    private function _getOptionGroup() {
        return sprintf('%s_option_group', static::$_config->settingsLicensePrefix);
    }

    private function _getOptionName() {
        return sprintf('%s_option_name', static::$_config->settingsLicensePrefix);
    }

    private function _getLicenseInputKey() {
        return sprintf('%s_input_key', static::$_config->settingsLicensePrefix);
    }

    private function _getPageName() {
        return sprintf('%s_page', static::$_config->settingsLicensePrefix);
    }

    private function _getSectionName() {
        return sprintf('%s_section', static::$_config->settingsLicensePrefix);
    }

}

