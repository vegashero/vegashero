<?php

class Vegashero_Settings
{

    private $_operators;
    private $_operator;
    private $_providers;
    private $_provider;
    private $_config;

    public function __construct() {

        $this->_config = new Vegashero_Config();

        if(@$_GET['page'] === 'vegashero-dashboard' && @$_GET['vegashero-import'] === 'queued') {
            add_action( 'admin_notices', array($this, 'vegashero_admin_import_notice'));
        }
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerOperatorSettings'));
        add_action('admin_init', array($this, 'registerProviderSettings'));

    }

    public function vegashero_admin_import_notice() {
      $vegas_gameslist_page = admin_url( "edit.php?post_type=vegashero_games" );
        echo '<div class="notice notice-success is-dismissible below-h2"><p>';
        echo _e( 'Your import has been queued. Please head over to the <a href="'.$vegas_gameslist_page.'">VegasHero Games</a> area to view/edit the games.' );
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

    public function registerProviderSettings() {
        $this->_config = new Vegashero_Config();
        $endpoint = sprintf('%s/vegasgod/providers', $this->_config->apiUrl);
        // this needs to be cached locally!!!!
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_providers = json_decode(json_decode($response), true);
        foreach($this->_providers as $provider) {
            $this->_provider = $provider;
            $section = $this->_getSectionName(sprintf('%s-provider', $provider));
            $page = $this->_getPageName(sprintf('%s-provider', $provider));
            //add_settings_section($section, sprintf('%s Settings', ucfirst($provider)), array($this, 'getDescriptionForSiteSettings'), $page);
            $option_group = $this->_getOptionGroup($provider);
            $option_name = $this->getOptionName($provider);
            register_setting($option_group, $option_name);
        }
    }

    public function registerOperatorSettings() {
        $this->_config = new Vegashero_Config();
        $endpoint = sprintf('%s/vegasgod/operators', $this->_config->apiUrl);
        // this needs to be cached locally!!!!
        $response = wp_remote_retrieve_body(wp_remote_get($endpoint));
        $this->_operators = json_decode(json_decode($response), true);
        foreach($this->_operators as $operator) {
            $this->_operator = $operator;
            $section = $this->_getSectionName(sprintf('%s-operator', $operator));
            $page = $this->_getPageName(sprintf('%s-operator', $operator));
            $field = $this->_getAffiliateCodeInputKey($operator);
            add_settings_section($section, sprintf('%s Settings', ucfirst($operator)), array($this, 'getDescriptionForSiteSettings'), $page);
            add_settings_field($field, 'Link', array($this, 'createAffiliateCodeInput'), $page, $section, array($operator));
            $option_group = $this->_getOptionGroup($operator);
            $option_name = $this->getOptionName($operator);
            register_setting($option_group, $option_name);
        }
    }

    private function _getPageName($name) {
        return sprintf('vegashero-%s-page', $name);
    }

    private function _getSectionName($name) {
        return sanitize_title(sprintf('vegashero-%s-section', $name));
    }

    public function getDescriptionForSiteSettings() {
        echo "<h1>HALLO HALLO HALLO</h1>";
        echo "<p>Add your full affiliate url here.</p>";
    }

    public function createAffiliateCodeInput($args) {
        echo "<pre>";
        print_r($args);
        echo "</pre>";
        $name = $args[0];
        $key = $this->_getAffiliateCodeInputKey($name);
        $name = $this->getOptionName($name);
        // for array of options
        // echo "<input name='".$name."[".$key."]' size='40' type='text' value='".get_option($name)."' />";
        // for single option
        echo "<input name='".$name."' size='30' type='text' value='".get_option($name)."' placeholder='http://your-affiliate-url.com' />";
    }



    public function addSettingsMenu() {
        add_menu_page(
            'VegasHero Settings', // The title to be displayed on this menu's corresponding page
            'VegasHero', // The text to be displayed for this actual menu item
            'manage_options', // Which type of users can see this menu
            'vegashero-dashboard', // The unique ID - that is, the slug - for this menu item
            array($this, 'createDashboardPage') // The name of the function to call when rendering this menu's page
        );

        add_submenu_page(
            'vegashero-dashboard',         // Register this submenu with the menu defined above
            'Import by Operator',          // The text to the display in the browser when this menu item is active
            'Import by Operator',                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-operator-import',          // The unique ID - the slug - for this menu item
            array($this, 'createOperatorImportPage')   // The function used to render this menu's page to the screen
        );

        add_submenu_page(
            'vegashero-dashboard',         // Register this submenu with the menu defined above
            'Import by Provider',          // The text to the display in the browser when this menu item is active
            'Import by Provider',                  // The text for this menu item
            'administrator',            // Which type of users can see this menu
            'vegashero-provider-import',          // The unique ID - the slug - for this menu item
            array($this, 'createProviderImportPage')   // The function used to render this menu's page to the screen
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

    public function createOperatorImportPage() {
        include dirname(__FILE__) . '/templates/settings/operator-import.php';
    }

    public function createProviderImportPage() {
        include dirname(__FILE__) . '/templates/settings/provider-import.php';
    }


    public function createDashboardPage() {
        include dirname(__FILE__) . '/templates/settings/dashboard.php';
    }
}

function affiliate_id_notice() {
  $vegas_settings_page = admin_url( "admin.php?page=vegashero-dashboard" );
?>
<div class="error notice is-dismissible importwarning">
    <p><?php echo "You can add your affiliate code and import VegasHero Games <a href='".$vegas_settings_page."'>here</a>."; ?></p>
</div>
<?php
}
add_action( 'admin_notices', 'affiliate_id_notice' );

add_action('admin_head', 'settings_page_styles');

function settings_page_styles() {
  $url = plugin_dir_url( __FILE__ ) . 'templates/css/settings-styles.css';
  echo '<link rel="stylesheet" href="'.$url.'" type="text/css" media="screen">';
}


/** Admin taxonomy filters for vegashero_games custom post type */
function add_game_category_taxonomy_filters() {
  global $typenow;

  $taxonomies = array('game_category');
  if( $typenow == 'vegashero_games' ){

    foreach ($taxonomies as $tax_slug) {
      $tax_obj = get_taxonomy($tax_slug);
      $tax_name = $tax_obj->labels->name;
      $terms = get_terms($tax_slug);
      if(count($terms) > 0) {
        echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
        echo "<option value=''>All $tax_name</option>";
        foreach ($terms as $term) {
          echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
        }
        echo "</select>";
      }
    }
  }
}
add_action( 'restrict_manage_posts', 'add_game_category_taxonomy_filters' );


function add_game_operator_taxonomy_filters() {
  global $typenow;

  $taxonomies = array('game_operator');
  if( $typenow == 'vegashero_games' ){

    foreach ($taxonomies as $tax_slug) {
      $tax_obj = get_taxonomy($tax_slug);
      $tax_name = $tax_obj->labels->name;
      $terms = get_terms($tax_slug);
      if(count($terms) > 0) {
        echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
        echo "<option value=''>All $tax_name</option>";
        foreach ($terms as $term) {
          echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
        }
        echo "</select>";
      }
    }
  }
}
add_action( 'restrict_manage_posts', 'add_game_operator_taxonomy_filters' );


function add_game_provider_taxonomy_filters() {
  global $typenow;

  $taxonomies = array('game_provider');
  if( $typenow == 'vegashero_games' ){

    foreach ($taxonomies as $tax_slug) {
      $tax_obj = get_taxonomy($tax_slug);
      $tax_name = $tax_obj->labels->name;
      $terms = get_terms($tax_slug);
      if(count($terms) > 0) {
        echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
        echo "<option value=''>All $tax_name</option>";
        foreach ($terms as $term) {
          echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
        }
        echo "</select>";
      }
    }
  }
}
add_action( 'restrict_manage_posts', 'add_game_provider_taxonomy_filters' );
