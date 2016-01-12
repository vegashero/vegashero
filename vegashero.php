<?php

/**
 * Plugin Name: Vegas Hero
 * Plugin URI: http://vegashero.co
 * Description: Bulk import of slots, table and poker games from the biggest iGaming software providers. Instant lobby and games pages including options to add your affiliate links.
 * Version: 1.3.1
 * Author: Vegas Hero
 * Author URI: http://vegashero.co
 * License: GPL2
 */

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'EDD_SAMPLE_STORE_URL', 'http://vegashero.co' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define( 'EDD_SAMPLE_ITEM_NAME', 'Vegas Hero' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/updater.php' );
}

function edd_sl_sample_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'edd_sample_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( EDD_SAMPLE_STORE_URL, __FILE__, array(
		'version' 	=> '1.3.1', 				// current version number
		'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
		'item_name' => EDD_SAMPLE_ITEM_NAME, 	// name of this plugin
		'author' 	=> 'Vegas Hero'  // author of this plugin
	)
);

}
add_action( 'admin_init', 'edd_sl_sample_plugin_updater', 0 );

require_once( dirname( __FILE__ ) . '/config.php' );
require_once( dirname( __FILE__ ) . '/import.php' );
$import = new Vegashero_Import();

require_once( dirname( __FILE__ ) . '/template.php' );
$template = new Vegashero_Template();

//require_once( dirname( __FILE__ ) . '/settings.php' );
//$dashboard = new Vegashero_Settings();

require_once( dirname( __FILE__ ) . '/settings/dashboard.php' );
$dashboard = new Vegashero_Settings_Dashboard();

require_once( dirname( __FILE__ ) . '/settings/operators.php' );
$operators = new Vegashero_Settings_Operators();

require_once( dirname( __FILE__ ) . '/settings/providers.php' );
$providers = new Vegashero_Settings_Providers();

require_once( dirname( __FILE__ ) . '/stylesheet.php' );
$stylesheet = new Vegashero_Stylesheet();

require_once( dirname( __FILE__ ) . '/shortcodes.php' );
$shortcode = new Vegashero_Shortcodes();

require_once( dirname( __FILE__ ) . '/ajax.php' );
$ajax = new Vegashero_Ajax();

