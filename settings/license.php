<?php

class Vegashero_Settings_License
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
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
        //add_action('admin_init', array($this, 'activateLicense'));
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
                echo '<h3>Error</h3>';
                echo "<pre>";
                print_r($response);
                echo "</pre>";
                return false;
            }

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            // $license_data->license will be either "active" or "inactive"
            update_option('vh_license_status', $license_data->license);
        }

        return trim($license);

    }

    public function getLicense() {
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

    public function registerSettings() {
        add_settings_section(
            $id = 'vh-license-section',
            $title = 'License & Support',
            $callback = array($this, 'sectionHeading'),
            $page = 'vh-license-page'
        );

        add_settings_field(
            $id = 'vh_license', 
            $title = 'License', 
            $callback = array($this, 'inputForLicense'), 
            $page = 'vh-license-page', 
            $section = 'vh-license-section',
            $args = array(
                'id' => 'vh_license'
            )
        );

        register_setting(
            $option_group = 'vh-license-page', 
            $option_name = 'vh_license', 
            $sanitize_callback = array($this, 'activateLicense')
        );

        add_settings_field(
            $id = 'vh_license_status', 
            $title = 'Status', 
            $callback = array($this, 'licenseStatus'), 
            $page = 'vh-license-page', 
            $section = 'vh-license-section',
            $args = array(
                'id' => 'vh_license_status'
            )
        );

        // do not register_setting!

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

    public function addSettingsMenu() {
        add_menu_page(
            $page_title = 'VegasHero Settings',
            $menu_title = 'VegasHero',
            $capability = 'manage_options',
            $menu_slug = 'vh-settings',
            $callback = '',
            $icon_url = '',
            $position = null
        );

        add_submenu_page(
            $parent_slug = 'vh-settings', 
            $page_title = 'License & Support', 
            $menu_title = 'License & Support', 
            $capability = 'manage_options', 
            $menu_slug = 'vh-settings', 
            $callback = array($this, 'createLicensePage') 
        );
        //add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
        //add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
        //add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
    }

    public function createLicensePage() { 
        include dirname(__FILE__) . '/templates/license.php';
    }

}
