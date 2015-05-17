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
            add_settings_section($section, sprintf('%s Settings', ucfirst($operator)), array($this, 'getDescriptionForSiteSettings'), $page);
            add_settings_field($field, 'Affiliate code', array($this, 'createAffiliateCodeInput'), $page, $section, array($operator));
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
        echo "<p>Your Affiliate ID goes here.</p>";
    }

    public function createAffiliateCodeInput($args) {
        $operator = $args[0];
        $key = $this->_getAffiliateCodeInputKey($operator);
        $name = $this->getOptionName($operator);
        // for array of options
        // echo "<input name='".$name."[".$key."]' size='40' type='text' value='".get_option($name)."' />";
        // for single option
        echo "<input name='".$name."' size='20' type='text' value='".get_option($name)."' />";
    }

    public function addSettingsMenu() {
        add_menu_page('VegasHero Settings', 'Vegas Hero', 'manage_options', 'vegashero-plugin', array($this, 'createSettingsPage'));

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

    public function createSettingsPage() { ?>

      <div class="wrap about-wrap">
        <h1>Welcome to Vegas Hero Games</h1>
        <div class="about-text">
			  Install a whole ton of games in an instant, add your affiliate codes from multiple operators.
        </div>
        <!-- <div class="vh-badge">Version 1.0</div> -->
        <hr>
        <h3>Operators available to install</h3>
        <ul class="operator-cards">
        <?php

        foreach($this->_operators as $operator) {
            echo '<li>';
            echo '<div class="desc">';
            echo '<form method="post" action="options.php">';
            settings_fields($this->_getOptionGroup($operator));
            $page = $this->_getPageName($operator);
            do_settings_sections($page);
            echo '<div class="btn-area">';
            echo "<input type='submit' name='submit' class='button button-primary' value='Apply code'>";
            echo $this->_getUpdateBtn($operator);
            echo '<div class="provider-img"><img src="' . plugin_dir_url( __FILE__ ) . 'templates/img/' . $operator . '_thumb.jpg" /></div>';
            echo '</div></div>';
            echo '</form>';
            echo '</li>';
        }

        ?>
        <li class="support-upsell">
          <h4>Support</h4>
          <p>Having trouble with your import? <a mailto="neil@vegashero.co">Mail us</a> with your issue and we will get right back to you.</p>
          <hr>
          <h4>Check out our premium services</h4>

          <ul>
            <li><strong>Gold package</strong> just $49 a month</li>
            <li>Custom styling for your lobby</li>
            <li>Unique content for 10 games a month</li>
            <li>Custom game page designs for 10 of your best games</li>
            <li>SEO evaluation from our expert</li>
          </ul>

        </li>
        </ul>

      </div>
        <?php
    }
}

function affiliate_id_notice() {
  $vegas_settings_page = admin_url( "admin.php?page=vegashero-plugin" );
?>
<div class="error">
    <p><?php echo "Please add your affiliate code <a href='".$vegas_settings_page."'>here</a> to import your VegasHero Games"; ?></p>
</div>
<?php
}
add_action( 'admin_notices', 'affiliate_id_notice' );

add_action('admin_head', 'settings_page_styles');

function settings_page_styles() {
  $url = plugin_dir_url( __FILE__ ) . 'templates/css/settings-styles.css';
  echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen">';
}
