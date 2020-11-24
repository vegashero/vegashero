<?php
/**
 * Plugin Name: VegasHero
 * Plugin URI: https://vegashero.co
 * Author: VegasHero
 * Text Domain: vegashero
 * Domain Path: /languages
 * Description: The VegasHero plugin adds powerful features to your igaming affiliate site. Bulk import free casino & slots games, flexible options to add your own games. Display games in a responsive lobby grid. Easily add and manage your affiliate links through an elegant editable table. Option to customize game titles and content to maximize your SEO. Check out our premium <a target="_blank" href="https://vegashero.co/downloads/vegashero-theme/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=plugin%20description%20link">Casino Wordpress Theme</a> that is purpose built to showcase the games and your affiliate links.
 * Version: 1.7.0
 * Author URI: https://vegashero.co
 * License: GPL2
 */

// NB: the order is important
require_once( dirname( __FILE__ ) . '/config.php' );
$config = \VegasHero\Config::getInstance();

require_once( sprintf("%slib/CustomPostType.php", plugin_dir_path(__FILE__)));
$operators = new \Vegashero\CustomPostType();
require_once( sprintf("%slib/Admin/AllGames.php", plugin_dir_path(__FILE__)));
new \VegasHero\Admin\AllGames();

if(is_admin()) {

    require_once( sprintf("%slib/Settings/Menu.php", plugin_dir_path(__FILE__)));
    $settings_menu = new \VegasHero\Settings\Menu();

    require_once( sprintf("%slib/Settings/License.php", plugin_dir_path(__FILE__)));
    $license = \VegasHero\Settings\License::getInstance();

    require_once( sprintf("%slib/Settings/Lobby.php", plugin_dir_path(__FILE__)));
    $lobby = new \VegasHero\Settings\Lobby();

    require_once( sprintf("%slib/Settings/Permalinks.php", plugin_dir_path(__FILE__)));
    $permalinks = new \VegasHero\Settings\Permalinks();
    $permalinks->updateCustomPostTypeUrl();
    $permalinks->updateGameCategoryUrl();
    $permalinks->updateGameOperatorUrl();
    $permalinks->updateGameProviderUrl();

    require_once( sprintf("%slib/Settings/Operators.php", plugin_dir_path(__FILE__)));
    $operators = new \VegasHero\Settings\Operators();

    require_once( sprintf("%slib/Settings/Providers.php", plugin_dir_path(__FILE__)));
    $providers = new \VegasHero\Settings\Providers();

    require_once( dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php' );
    $updater = new EDD_SL_Plugin_Updater($config->eddStoreUrl, __FILE__,
        array(
            'version'   => '1.7.0',       // current version number
            'license'   => \VegasHero\Settings\License::getLicense(),    // license key (used get_option above to retrieve from DB)
            'item_name' => $config->eddDownloadName,    // name of this plugin
            'author'    => 'VegasHero', // author of this plugin
            'url'       => site_url()
        ) 
    );
}

/**
 * TODO: autoload
 */
require_once('lib/Import/Utils.php');
require_once('lib/Import/Import.php');
require_once('lib/Import/Operator.php');
$import_operator = new VegasHero\Import\Operator();
require_once('lib/Import/Provider.php');
$import_provider = new VegasHero\Import\Provider();

/**
 * Autoloader for calling VegasHero\Functions from theme templates
 */
spl_autoload_register(function($class_name) {
    if(strpos($class_name, 'VegasHero\Functions') !== false) {
        $functions_file = sprintf("%slib/Functions.php", plugin_dir_path(__FILE__));
        if ( ! file_exists($functions_file)) {
            error_log(sprintf("File not found when attempting autoload %s", $function_file));
            return;
        }
        require_once($functions_file);
    }
});

// widgets
require_once("lib/Widgets/SingleGameArea.php");
$widget_area = new VegasHero\Widgets\SingleGameArea();

require_once("lib/Widgets/LatestGames.php");
$latest_games_widget = new VegasHero\Widgets\LatestGames();

// templates
require_once('lib/Templates/Custom.php');

require_once( sprintf("%slib/Stylesheets.php", plugin_dir_path(__FILE__)));
$stylesheet = new VegasHero\Stylesheets();

require_once( sprintf("%slib/ShortCodes/ShortCodes.php", plugin_dir_path(__FILE__)));
$shortcode = new VegasHero\ShortCodes\ShortCodes();

require_once( dirname( __FILE__ ) . '/lib/Translations.php');
add_action( 'plugins_loaded', 'VegasHero\Translations\load_textdomain' );

require_once( dirname( __FILE__ ) . '/ajax.php' );
$ajax = new Vegashero_Ajax();
