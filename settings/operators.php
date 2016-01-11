<?php

class Vegashero_Settings_Operators
{

    private $_operators;
    private $_operator;
    private $_config;

    public function __construct() {

        $this->_config = new Vegashero_Config();

        if(array_key_exists('page', $_GET)) {
            if($_GET['page'] === 'vegashero-operator-import') {
                add_action('admin_init', array($this, 'registerSettings'));
                add_action('admin_head', array($this, 'loadOperatorStyles'));
            }
        }
        add_action('admin_menu', array($this, 'addSettingsMenu'));

        if(@$_GET['page'] === 'vegashero-plugin' && @$_GET['vegashero-import'] === 'queued') {
            add_action( 'admin_notices', array($this, 'vegashero_admin_import_notice'));
        }

    }
     
    public function loadOperatorStyles() {
        $url = plugin_dir_url( __FILE__ ) . 'templates/operators.css';
        echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen">';
    }


    public function vegashero_admin_import_notice() {
      $vegas_gameslist_page = admin_url( "edit.php?post_type=vegashero_games" );
        echo '<div class="notice notice-success is-dismissible below-h2"><p>';
        echo _e( 'Your import has been queued. Please head over to the <a href="'.$vegas_gameslist_page.'">Vegas Hero Games</a> area to view/edit the games.' );
        echo '</p></div>';
    }

    private function _getOptionGroup($operator=null) {
        if(is_null($operator)) {
            $operator = $this->_operator;
        }
        return sprintf('vegashero_settings_group_%s', $operator);
    }

    public function getOptionName($operator=null) {
        if(is_null($operator)) {
            $operator = $this->_operator;
        }
        return sprintf('%s%s', $this->_config->settingsNamePrefix, $operator);
    }

    private function _getAffiliateCodeInputKey($operator=null) {
        if(is_null($operator)) {
            $operator = $this->_operator;
        }
        return sprintf('%s-affiliate-code', $operator);
    }

    public function registerSettings() {
        $this->_config = new Vegashero_Config();
        $endpoint = sprintf('%s/vegasgod/operators', $this->_config->apiUrl);
        // this needs to be cached locally!!!!
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
        foreach($this->_operators as $operator) {
            $this->_operator = $operator;
            $section = $this->_getSectionName($operator);
            $page = $this->_getPageName($operator);
            $field = $this->_getAffiliateCodeInputKey($operator);
            add_settings_section($section, sprintf('%s', ucfirst($operator)), array($this, 'getDescriptionForSiteSettings'), $page);
            add_settings_field($field, 'Link', array($this, 'createAffiliateCodeInput'), $page, $section, array($operator));
            $option_group = $this->_getOptionGroup($operator);
            $option_name = $this->getOptionName($operator);
            register_setting($option_group, $option_name);
        }
    }

    private function _getPageName($operator) {
        return sprintf('vegashero-operator-%s-page', $operator);
    }

    private function _getSectionName($operator) {
        return sanitize_title(sprintf('vegashero-operator-%s-section', $operator));
    }

    public function getDescriptionForSiteSettings() {
        echo "<p>Add your full affiliate url here.</p>";
    }

    public function createAffiliateCodeInput($args) {
        $operator = $args[0];
        $key = $this->_getAffiliateCodeInputKey($operator);
        $name = $this->getOptionName($operator);
        // for array of options
        // echo "<input name='".$name."[".$key."]' size='40' type='text' value='".get_option($name)."' />";
        // for single option
        echo "<input name='".$name."' size='30' type='text' value='".get_option($name)."' placeholder='http://your-affiliate-url.com' />";
    }

    public function addSettingsMenu() {
        add_submenu_page(
            'vegashero-plugin',         // Register this submenu with the menu defined above
            'Operator Imports',          // The text to the display in the browser when this menu item is active
            'Operator imports',                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-operator-import',          // The unique ID - the slug - for this menu item
            array($this, 'createSettingsPage')   // The function used to render this menu's page to the screen
        );
    }

    private function _getUpdateBtn($operator) {

        $markup = "&nbsp;&nbsp;<a href='";
        $option_name = $this->getOptionName($operator);
        $option = get_option($option_name);
        if( ! empty($option)) {
            $update_url = plugins_url('update.php', __FILE__);
            $markup .= "$update_url?operator=$operator'";
        } else {
            $markup .= "#' disabled='disabled'";
        }
        $markup .= " class='button button-primary'";
        $markup .= ">Import games</a>";
        return $markup;
    }

    public function createSettingsPage() {
        include dirname(__FILE__) . '/templates/operators.php';
    }

}



