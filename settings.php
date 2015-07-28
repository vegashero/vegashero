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
      $vegas_gameslist_page = admin_url( "edit.php?post_type=vegashero_games" );
        echo '<div class="updated"><p>';
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
            add_settings_section($section, sprintf('%s Settings', ucfirst($operator)), array($this, 'getDescriptionForSiteSettings'), $page);
            add_settings_field($field, 'Link', array($this, 'createAffiliateCodeInput'), $page, $section, array($operator));
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
            echo "<input type='submit' name='submit' class='button button-primary' value='Apply Link'>";
            echo $this->_getUpdateBtn($operator);
            echo '<div class="provider-img"><img src="http://cdn.vegasgod.com/operators/' . $operator . '.png" /></div>';
            echo '</div></div>';
            echo '</form>';
            echo '</li>';
        }

        ?>
        </ul>
        
        <!-- Custom Shortcode Settings -->

        <div class="clear"></div>
        <h3>Use Custom Shortcode to Display Operators</h3>
        <input id="custoptable" type="checkbox"><label for="custoptable">Enable Custom Operators Table</label>
        <p>Use below shortcode to replace the above operator options with your own list of operators/casinos.</p>
        <p class="shortcode-hint">
          [vh_table vh_tname="Table Title Here"]<br/>
            <span class="shortcode-hint-row">[vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]<br/></span>
            <span class="shortcode-hint-row">[vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]<br/></span>
            <span class="shortcode-hint-row">...<br/></span>
          [/vh_table]
        </p>
        <textarea class="operator-shortcode-settings" name="operator_shortcode_override" placeholder="[vh_table vh_tname='Table Title Here'] Your Custom Table Rows Here... [/vh_table]"></textarea>
        <input type='submit' name='shortcodeSubmit' class='button button-primary' value='Save Shortcode Settings'>



        <div class="clear"></div>
        <h3>Lobby Setup</h3>
        <ul class="instructions">
          <li>
            <ul>
              <li><b>1.</b> Add your affiliate code</li>
              <li><b>2.</b> Click "Apply Link"</li>
              <li><b>3.</b> Then click "Import games"</li>
              <li><b>4.</b> Create a new page</li>
              <li><b>5.</b> Add in this shortcode <span style="background:#f3f3f3; padding:3px 8px;">[vegashero-lobby]</span> </li>
            </ul>
            <div class="clear"></div>
          </li>

        </ul>
        <div class="clear"></div>
        <!-- <h3>Other Products & Support</h3>
        <ul class="products">
          <li class="support-upsell">

            <p>Having trouble with your import? <a mailto="neil@vegashero.co">Mail us</a> with your issue and we will get right back to you.</p>
            <hr>
            <h3>Check out our premium services</h3>

            <ul class="gold">
              <li class="package-title"><h3>Gold package deal <span>just $49 a month</span></h3></li>
              <li><hr></li>
              <li>&#10003; Unique content for 10 games every month</li>
              <li>&#10003; SEO evaluation from our expert <b>Add-on</b></li>
              <li>&#10003; Custom styling for your lobby <b>Add-on</b></li>
              <li>&#10003; 10 themed game pages <b>Add-on</b></li>
              <li><a href="" class="signup">Sign up</a></li>
            </ul>

            <ul class="silver">
              <li class="package-title"><h3>Silver package deal <span>just $89 a month</span></h3></li>
              <li><hr></li>
              <li>&#10003; Unique content for 20 games every month</li>
              <li>&#10003; Quality Backlinks for each game on our trusted networks every month</li>
              <li>&#10003; Premium support, fix any bugs you have on your site *</li>
              <li>&#10003; SEO evaluation from our expert <b>Add-on</b></li>
              <li>&#10003; Custom styling for your lobby <b>Add-on</b></li>
              <li>&#10003; 10 themed game pages <b>Add-on</b></li>
              <li><a href="" class="signup">Sign up</a></li>
            </ul>
            <div class="clear"></div>
            <p>* Wordpress only, features are not included.</p> -->
            <!-- <h3>Add-ons</h3>
            <ul class="addon">
              <li><h3>Custom styling for your lobby <span>$79</span></h3><li>
              <li><hr></li>
              <li><p>We will design and build a lobby that will look amazing, custom hover cards for your games and all responsive.</p>
                <p>You will get a professional mockup which you will approve and custom HTML/CSS.</p>
              </li>
              <li><a href="" class="button-primary">Buy now</a></li>
            </ul>
            <ul class="addon">
              <li><h3>Order quality backlinks <span>$49</span></h3><li>
              <li><hr></li>
              <li><p>We have built a network of quality backlinks that will give you better site authority.</p>
              <p>You will get first hand help to take your site to the next level. Ask him any question.</li>
              <li><a href="" class="button-primary">Buy now</a></li>
            </ul>
            <ul class="addon">
              <li><h3>SEO evaluation from our expert <span>$49</span></h3><li>
              <li><hr></li>
              <li><p>Our SEO Specialist (iGaming industry expert) will supply you with an SEO health report.</p>
              <p>You will get first hand help to take your site to the next level. Ask him any question.</li>
              <li><a href="" class="button-primary">Buy now</a></li>
            </ul> -->
            <div class="clear"></div>
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