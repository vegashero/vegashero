<?php 

namespace VegasHero\Settings;

use VegasHero\Config;
use VegasHero\Settings\{ Settings, Menu };

class License extends Settings
{

    const MENU_SLUG = 'vh-license';
    const PAGE_SLUG = 'vh-license-page';

    private static $_config;
    private static $_instance;

    public static function getInstance(): License {
        if (null === static::$_instance) {
            static::$_instance = new License();
        }
        return static::$_instance;
    }

    private function __clone() {
    }

    protected function __construct() {
        $this->_showUpdateNotification( Menu::MENU_SLUG );
        static::$_config = Config::getInstance();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function addSettingsMenu() {

        add_submenu_page(
            $parent_slug = Menu::MENU_SLUG, 
            $page_title = wp_strip_all_tags(__('License & Support', 'vegashero')),
            $menu_title = wp_strip_all_tags(__('License & Support', 'vegashero')),
            $capability = 'manage_options', 
            $menu_slug = Menu::MENU_SLUG, 
            $callback = array($this, 'createLicensePage') 
        );
    }

    public function createLicensePage() { 
        include dirname(__FILE__) . '/templates/license.php';
    }

    public function registerSettings() {
        add_settings_section(
            $id = 'vh-license-section',
            $title = wp_strip_all_tags(__('License & Support', 'vegashero')),
            $callback = array($this, 'sectionHeading'),
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_license', 
            $title = wp_strip_all_tags(__('License', 'vegashero')), 
            $callback = array($this, 'inputForLicense'), 
            $page = self::PAGE_SLUG, 
            $section = 'vh-license-section',
            $args = array(
                'id' => 'vh_license'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_license', 
            $sanitize_callback = array($this, 'activateLicense')
        );

        add_settings_field(
            $id = 'vh_license_status', 
            $title = 'Status', 
            $callback = array($this, 'licenseStatus'), 
            $page = self::PAGE_SLUG, 
            $section = 'vh-license-section',
            $args = array(
                'id' => 'vh_license_status'
            )
        );

        // do not register_setting!

    }


    public function activateLicense($license) {

        if($license !== get_option('vh_license')) {

            // data to send in our API request
            $api_params = array( 
                'edd_action'=> 'activate_license', 
                'license'   => trim($license),
                'item_name' => urlencode(static::$_config->eddItemName), // the name of our product in EDD,
                'url'       => home_url()
            );

            // Call the custom API.
            $response = wp_remote_post(static::$_config->eddStoreUrl, array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => $api_params
            ) );

            // make sure the response came back okay
            if (is_wp_error( $response )) {
                error_log($response->get_error_message());
                return false;
            }

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            // $license_data->license will be either "active" or "inactive"
            update_option('vh_license_status', $license_data->license);
        }

        return trim($license);

    }

    static public function getLicense() {
        return get_option('vh_license');
    }

    public function sectionHeading() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $title = $args[0]['title'];
        include_once( dirname( __FILE__ ) . '/templates/license/section-heading.php' );
    }

    public function inputForLicense() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $status = get_option('vh_license_status');
        include_once( dirname( __FILE__ ) . '/templates/license/license-input.php' );
    }

    public function licenseStatus() {
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/license/license-status.php' );
    }

    private function _createLicenseNonce() {
        return wp_nonce_field('vh_license_nonce', 'vh_license_nonce');
    }

    private function _isLicenseActive() {
        $license_status_name = sprintf('%s_activation_status', static::$_config->settingsLicensePrefix);
        $status = get_option($license_status_name);
        return $status !== false && $status == 'valid';
    }

    /*
     * We could validate license key here upon saving
     */
    public function validateLicenseKey($license_key) {
        return $license_key;
    }

}
