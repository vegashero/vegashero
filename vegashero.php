<?php

/**
 * Plugin Name: Vegas Hero
 * Plugin URI: http://vegashero.co
 * Description: Bulk import of slots, table and poker games from the biggest iGaming software providers. Instant lobby and games pages including options to add your affiliate links.
 * Version: 1.3.2
 * Author: Vegas Hero
 * Author URI: http://vegashero.co
 * License: GPL2
 */


require_once( dirname( __FILE__ ) . '/config.php' );
$config = Vegashero_Config::getInstance();

require_once( dirname( __FILE__ ) . '/custom_post_type.php' );
$operators = new Vegashero_Custom_Post_Type();

require_once( dirname( __FILE__ ) . '/settings/dashboard.php' );
$dashboard = Vegashero_Settings_Dashboard::getInstance();

require_once( dirname( __FILE__ ) . '/settings/lobby.php' );
$lobby = Vegashero_Settings_Lobby::getInstance();

require_once( dirname( __FILE__ ) . '/settings/permalinks.php' );
$lobby = Vegashero_Settings_Permalinks::getInstance();

require_once( dirname(__FILE__) . '/updater.php' );
$updater = new EDD_SL_Plugin_Updater($config->eddStoreUrl, __FILE__, 
    array(
        'version'   => '1.3.2',       // current version number
        'license'   => $dashboard->getLicense(),    // license key (used get_option above to retrieve from DB)
        'item_name'     => $config->eddDownloadName,    // name of this plugin
        'author'    => 'Vegas Hero', // author of this plugin
        'url'           => home_url()
    ) 
);

//require_once( dirname( __FILE__ ) . '/settings.php' );
//$dashboard = new Vegashero_Settings();

require_once( dirname( __FILE__ ) . '/settings/operators.php' );
$operators = new Vegashero_Settings_Operators();

require_once( dirname( __FILE__ ) . '/settings/providers.php' );
$providers = new Vegashero_Settings_Providers();

require_once( dirname( __FILE__ ) . '/settings/affiliates.php' );
$affiliates = new Vegashero_Settings_Affiliates();

//require_once( dirname( __FILE__ ) . '/settings/permalinks.php' );
//$permalinks = new Vegashero_Settings_Permalinks();

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


require_once( dirname( __FILE__ ) . '/ajax.php' );
$ajax = new Vegashero_Ajax();

