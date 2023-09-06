<?php
/**
 * Plugin Name: VegasHero Casino Affiliate Plugin
 * Plugin URI: https://vegashero.co
 * Author: VegasHero
 * Text Domain: vegashero
 * Domain Path: /languages
 * Description: The VegasHero plugin adds powerful features to your igaming affiliate site. Bulk import free casino & slots games.
 * Display games in a responsive lobby grid. Easily add and manage your affiliate links through an elegant editable table.
 * Customize game titles and content to maximize your SEO.
 * Check out our premium <a target="_blank" href="https://vegashero.co/downloads/vegashero-theme/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=plugin%20description%20link">Casino WordPress Theme</a> that is purpose built to showcase the games and your affiliate links.
 * Version: 1.8.2
 * Author URI: https://vegashero.co
 * License: GPL2
 */

namespace VegasHero;

require_once 'vendor/autoload.php';

use VegasHero\{ Config, CustomPostType, PluginUpdater, Translations, Ajax };
use VegasHero\Admin\{ AllGames };
use VegasHero\Archive;
use VegasHero\Settings\{ Menu, License, Lobby, Permalinks, Operators, Providers };
use VegasHero\Import\{ Operator, Provider };
use VegasHero\Widgets\{ SingleGameArea, LatestGames };
use VegasHero\ShortCodes\{ ShortCodes };
use VegasHero\Templates\Custom;

$config    = Config::getInstance();
$operators = CustomPostType::getInstance();
$games     = AllGames::getInstance();

if ( is_admin() ) {
	$settings_menu = Menu::getInstance();
	$license       = License::getInstance();
	$lobby         = Lobby::getInstance();

	$permalinks = Permalinks::getInstance();
	$permalinks->updateCustomPostTypeUrl();
	$permalinks->updateGameCategoryUrl();
	$permalinks->updateGameOperatorUrl();
	$permalinks->updateGameProviderUrl();

	$operators = Operators::getInstance();
	$providers = Providers::getInstance();

	$updater = new PluginUpdater(
		$config->eddStoreUrl,
		__FILE__,
		array(
			'version'   => '1.8.2',       // current version number
			'license'   => License::getLicense(),    // license key (used get_option above to retrieve from DB)
			'item_name' => $config->eddDownloadName,    // name of this plugin
			'author'    => 'VegasHero', // author of this plugin
			'url'       => site_url(),
		)
	);
}

$import_provider = Provider::getInstance();
$import_operator = Operator::getInstance();

/**
 * Autoloader for calling VegasHero\Functions from theme templates
 */
spl_autoload_register(
	function( $class_name ) {
		if ( strpos( $class_name, 'VegasHero\Functions' ) !== false ) {
			$functions_file = sprintf( '%slib/Functions.php', plugin_dir_path( __FILE__ ) );
			if ( ! file_exists( $functions_file ) ) {
				error_log( sprintf( 'File not found when attempting autoload %s', $function_file ) );
				return;
			}
			require_once $functions_file;
		}
	}
);

Custom::addFilters();
Custom::addActions();

// widgets
SingleGameArea::addActions();
LatestGames::addActions();

// enqueue assets
Stylesheets::addActions();

ShortCodes::addShortCodes();
ShortCodes::addFilters();

Translations::addActions();

Ajax::getInstance();

/**
 * Custom archive page queries
 */
Archive::getInstance();
