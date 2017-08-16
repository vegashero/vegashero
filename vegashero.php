<?php

/**
 * Plugin Name: VegasHero Casino Affiliate Plugin
 * Plugin URI: https://vegashero.co
 * Description: The VegasHero plugin adds powerful features to your igaming affiliate site. Bulk import free casino and slots games and option to add your own games. Display games in a responsive lobby grid. Easily add and manage your affiliate links through an elegant editable table. Option to customize game titles and content to maximize your SEO.
 * Version: 1.4.5
 * Author: VegasHero
 * Author URI: https://vegashero.co
 * License: GPL2
 */


require_once( dirname( __FILE__ ) . '/config.php' );
$config = Vegashero_Config::getInstance();

require_once( dirname( __FILE__ ) . '/custom_post_type.php' );
$operators = new Vegashero_Custom_Post_Type();

require_once(dirname(__FILE__) . '/settings/settings.php');

require_once( dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php' );
$updater = new VH_EDD_SL_Plugin_Updater($config->eddStoreUrl, __FILE__,
    array(
        'version'   => '1.4.5',       // current version number
        'license'   => $dashboard->getLicense(),    // license key (used get_option above to retrieve from DB)
        'item_name' => $config->eddDownloadName,    // name of this plugin
        'author'    => 'VegasHero', // author of this plugin
        'url'       => home_url()
    ) 
);

//require_once( dirname( __FILE__ ) . '/settings.php' );
//$dashboard = new Vegashero_Settings();

require_once( dirname( __FILE__ ) . '/import/import.php' );
require_once( dirname( __FILE__ ) . '/import/operator.php' );
$import_operator = new Vegashero_Import_Operator();
require_once( dirname( __FILE__ ) . '/import/provider.php' );
$import_provider = new Vegashero_Import_Provider();

require_once( dirname( __FILE__ ) . '/template.php' );
$template = new Vegashero_Template();

require_once( dirname( __FILE__ ) . '/stylesheet.php' );
$stylesheet = new Vegashero_Stylesheet();

require_once( dirname( __FILE__ ) . '/shortcodes.php' );
$shortcode = new Vegashero_Shortcodes();

require_once( dirname( __FILE__ ) . '/widgets.php' );
$shortcode = new Vegashero_Widgets();

require_once( dirname( __FILE__ ) . '/ajax.php' );
$ajax = new Vegashero_Ajax();


