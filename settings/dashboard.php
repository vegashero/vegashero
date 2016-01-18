<?php

class Vegashero_Settings_Dashboard
{

    private $_config;

    public function __construct() {
        $this->_config = new Vegashero_Config();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
        add_action('admin_init', array($this, 'activateLicense'));
    }

    public function activateLicense() {
        $license_activation = sprintf('%s_activation', $this->_config->settingsLicensePrefix);
        if(isset($_POST[$license_activation])) {

            // run a quick security check 
            if( ! check_admin_referer($this->_getLicenseNonceName(), $this->_getLicenseNonceName())) {
                return; // get out if we didn't click the Activate button
            }

            // retrieve the license from the database
            $option_name = $this->getOptionName();
            $license = get_option($option_name);

            // data to send in our API request
            $api_params = array( 
                'edd_action'=> 'activate_license', 
                'license'   => $license, 
                'item_name' => urlencode($this->_config->eddItemName), // the name of our product in EDD,
                'url'       => home_url()
            );

            // Call the custom API.
            $response = wp_remote_post($this->_config->eddStoreUrl, array(
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

            $license_status_name = sprintf('%s_activation_status', $this->_config->settingsLicensePrefix);
            update_option($license_status_name, $license_data->license );

        }
    }

    public function sanitizeLicense($new) {
        //echo sprintf('<h3>sanitizing license</h3>', '');
        $name = $this->getOptionName();
        $old = get_option($name);
        if( $old && $old != $new ) {
            delete_option(sprintf('%s_activation_status', $this->_config->settingsLicensePrefix)); // new license has been entered, so must reactivate
        }
        return $new;
    }

    public function registerSettings() {
        $section = $this->_getSectionName();
        $page = $this->_getPageName();
        add_settings_section($section, $title=null, $description=null, $page);
        add_settings_field($this->_getLicenseInputKey(), 'License Key', array($this, 'createLicenseInput'), $page, $section);
        add_settings_field($this->_getLicenseActivationButtonKey(), 'Activate License', array($this, 'createLicenseActivationButton'), $page, $section);
        $option_group = $this->_getOptionGroup();
        $option_name = $this->getOptionName();
        register_setting($option_group, $option_name, array($this, 'sanitizeLicense'));
    }

    private function _getLicenseNonceName() {
        return sprintf('%s_nonce', $this->_config->settingsLicensePrefix);
    }

    private function _createLicenseNonce() {
        return wp_nonce_field($this->_getLicenseNonceName(), $this->_getLicenseNonceName());
    }

    private function _getLicenseActivationButtonKey() {
        return sprintf('%s_activation_button_key', $this->_config->settingsLicensePrefix);
    }

    public function createLicenseActivationButton() {
        $name = sprintf('%s_activation', $this->_config->settingsLicensePrefix);
        //echo sprintf('is license active? %s', (int)$this->_isLicenseActive());
        if($this->_isLicenseActive()) {
            echo "<span style='color:green;'>active</span>";
        } else {
            echo $this->_createLicenseNonce();
            echo "<input name='$name' type='submit' class='button-secondary' value='Activate License'/>";
        }
    }

    private function _isLicenseActive() {
        $license_status_name = sprintf('%s_activation_status', $this->_config->settingsLicensePrefix);
        $status = get_option($license_status_name);
        return $status !== false && $status == 'valid';
    }


    public function createLicenseInput($args) {
        $key = $this->_getLicenseInputKey();
        $name = $this->getOptionName();
        // for array of options
        // echo "<input name='".$name."[".$key."]' size='40' type='text' value='".get_option($name)."' />";
        // for single option
        echo "<input id='$name' name='$name' size='30' type='text' class='regular-text' value='".get_option($name)."' placeholder='enter your license key here' />";
    }


    /*
     * We could validate license key here upon saving
     */
    public function validateLicenseKey($license_key) {
        return $license_key;
    }

    private function _getOptionGroup() {
        return sprintf('%s_option_group', $this->_config->settingsLicensePrefix);
    }

    public function getOptionName() {
        return sprintf('%s_option_name', $this->_config->settingsLicensePrefix);
    }

    private function _getLicenseInputKey() {
        return sprintf('%s_input_key', $this->_config->settingsLicensePrefix);
    }

    private function _getPageName() {
        return sprintf('%s_page', $this->_config->settingsLicensePrefix);
    }

    private function _getSectionName() {
        return sprintf('%s_section', $this->_config->settingsLicensePrefix);
    }

    public function addSettingsMenu() {
        add_menu_page(
            'Vegas Hero',
            'Vegas Hero',
            'manage_options',
            'vegashero-dashboard'
        );

        add_submenu_page(
            'vegashero-dashboard', // The title to be displayed on this menu's corresponding page
            'Dashboard', // The text to be displayed for this actual menu item
            'Dashboard', // The text to be displayed for this actual menu item
            'manage_options', // Which type of users can see this menu
            'vegashero-dashboard', // The unique ID - that is, the slug - for this menu item
            array($this, 'createDashboardPage') // The name of the function to call when rendering this menu's page
        );
        //add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
        //add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
        //add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
    }

    public function createDashboardPage() { 
        include dirname(__FILE__) . '/templates/dashboard.php';
    }

}
