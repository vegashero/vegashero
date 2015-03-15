<?php

class Vegashero_Settings
{

    private $_operators;
    private $_operator;
    private $_config;

    public function __construct() {

        $this->_config = new Vegashero_Config();

        if(@$_GET['page'] === 'vegashero-plugin' && @$_GET['vegashero-import'] === 'queued') {
            add_action( 'admin_notices', array($this, 'vegashero_admin_import_notice'));
        }
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));

    }

    public function vegashero_admin_import_notice() {
        echo '<div class="updated">';
        echo '<p>' . _e( 'Your import has been queued' ) .'</p>';
        echo '</div>';
    }

    private function _getOptionGroup($operator=null) {
        if( ! $operator) {
            $operator = $this->_operator;
        }
        return sprintf('vegashero_settings_group_%s', $operator);
    }

    public function getOptionName($operator=null) {
        if( ! $operator) {
            $operator = $this->_operator;
        }
        return sprintf('%s%s', $this->_config->settingsNamePrefix, $operator);
    }

    private function _getAffiliateCodeInputKey($operator=null) {
        if( ! $operator) {
            $operator = $this->_operator;
        }
        return sprintf('%s-affiliate-code', $operator);
    }

    public function registerSettings() {

        $this->_config = new Vegashero_Config();
        $response = wp_remote_get(sprintf('%s/wp-json/vegasgod/operators', $this->_config->apiUrl));
        $this->_operators = json_decode(json_decode($response['body']), true);

        foreach($this->_operators as $operator) {
            $this->_operator = $operator;
            $section = $this->_getSectionName($operator);
            $page = $this->_getPageName($operator);
            $field = $this->_getAffiliateCodeInputKey($operator);
            add_settings_section($section, sprintf('%s Settings', ucfirst($operator)), array($this, 'getDescriptionForSiteSettings'), $page);
            add_settings_field($field, 'Affiliate code', array($this, 'createAffiliateCodeInput'), $page, $section);
            $option_group = $this->_getOptionGroup($operator);
            $option_name = $this->getOptionName($operator);

            register_setting($option_group, $option_name);
        }
    }

    private function _getPageName($operator) {
        return sprintf('vegashero-plugin-%s-settings-page', $operator);
    }

    private function _getSectionName($operator) {
        return sprintf('%s-settings-section', $operator);
    }

    public function getDescriptionForSiteSettings() {
        echo "<p>Site specific settings description goes here</p>";
    }

    public function createAffiliateCodeInput() {
        $key = $this->_getAffiliateCodeInputKey();
        $name = $this->getOptionName();
        // for array of options
        // echo "<input name='".$name."[".$key."]' size='40' type='text' value='".get_option($name)."' />";
        // for single option
        echo "<input name='".$name."' size='40' type='text' value='".get_option($name)."' />";
    }

    public function addSettingsMenu() {
        add_options_page('Vegas Hero Plugin', 'Vegas Hero', 'manage_options', 'vegashero-plugin', array($this, 'createSettingsPage'));
    }

    private function _getUpdateBtn($operator) {

        $markup = "&nbsp;&nbsp;<a href='";
        if(get_option($this->getOptionName())) {
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
        echo '<div class="wrap">';
        echo '<h2>Vegas Hero Settings</h2>';
        foreach($this->_operators as $operator) {
            echo '<form method="post" action="options.php">';
            settings_fields($this->_getOptionGroup($operator));
            $page = $this->_getPageName($operator);
            do_settings_sections($page);
            echo "<input type='submit' name='submit' class='button button-primary' value='Apply code'>";
            echo $this->_getUpdateBtn($operator);
            echo '</form>';
        }
        echo '</div>';

    }

}
function affiliate_id_notice() {
  $vegas_settings_page = admin_url( "admin.php?page=vegashero-plugin" );
?>
<div class="error">
    <p><?php echo "Please add your affiliate code <a href='".$vegas_settings_page."'>here</a> to import your VegasHero Games" ?></p>
</div>
<?php
}
add_action( 'admin_notices', 'affiliate_id_notice' );
