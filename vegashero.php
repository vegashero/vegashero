<?php

/**
 * Plugin Name: VegasHero Casino Affiliate Plugin
 * Plugin URI: https://vegashero.co
 * Description: The VegasHero plugin adds powerful features to your igaming affiliate site. Bulk import free casino & slots games, flexible options to add your own games. Display games in a responsive lobby grid. Easily add and manage your affiliate links through an elegant editable table. Option to customize game titles and content to maximize your SEO. Check out our premium <a target="_blank" href="https://vegashero.co/downloads/vegashero-theme/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=plugin%20description%20link">Casino Wordpress Theme</a> that is purpose built to showcase the games and your affiliate links.
 * Version: 1.5.0
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
        'version'   => '1.5.0',       // current version number
        'license'   => $dashboard->getLicense(),    // license key (used get_option above to retrieve from DB)
        'item_name' => $config->eddDownloadName,    // name of this plugin
        'author'    => 'VegasHero', // author of this plugin
        'url'       => site_url()
    ) 
);

// TODO: load via psr4
require_once('lib/Import/Import.php');
require_once('lib/Import/Operator.php');
$import_operator = new Vegashero\Import\Operator();
require_once('lib/Import/Provider.php');
$import_provider = new Vegashero\Import\Provider();

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


