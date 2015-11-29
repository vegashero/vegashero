<?php

/**
 * Plugin Name: Vegas Hero
 * Plugin URI: http://vegashero.co
 * Description: Bulk import of slots, table and poker games from the biggest iGaming software providers. Instant lobby and games pages including options to add your affiliate links.
 * Version: 1.3.0
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
 			'version' 	=> '1.3.0', 				// current version number
 			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
 			'item_name' => EDD_SAMPLE_ITEM_NAME, 	// name of this plugin
 			'author' 	=> 'Vegas Hero'  // author of this plugin
 		)
 	);

 }
 add_action( 'admin_init', 'edd_sl_sample_plugin_updater', 0 );

if ( ! defined( 'WPINC' ) ) {
    exit();
}


require_once "import.php";
$import = new Vegashero_Import();

require_once "template.php";
$template = new Vegashero_Template();

require_once "settings.php";
$settings = new Vegashero_Settings();

require_once "stylesheet.php";
$stylesheet = new Vegashero_Stylesheet();

require_once "shortcodes.php";
$shortcode = new Vegashero_Shortcodes();

require_once "ajax.php";
$ajax = new Vegashero_Ajax();
// $taxonomy = new Vegashero_Taxonomy();
