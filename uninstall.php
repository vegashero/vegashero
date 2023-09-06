<?php
// if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

spl_autoload_register(
	function( $classname ) {
		$segments = explode( '_', $classname );
		if ( $segments[0] == 'Vegashero' ) {
			$filename = plugin_dir_path( __FILE__ ) . strtolower( $segments[1] ) . '.php';
			if ( file_exists( $filename ) ) {
				include_once $filename;
			}
		}
	}
);

require_once __DIR__ . '/config.php';
$config = \VegasHero\Config::getInstance();

global $wpdb;
$wpdb->query( "DELETE FROM wp_postmeta WHERE meta_key = '$config->postMetaGameId'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE meta_key = '$config->postMetaGameSrc'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE meta_key = '$config->postMetaGameTitle'" );
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = '$config->customPostType'" );
$wpdb->query( "DELETE FROM wp_terms WHERE term_group = '$config->gameCategoryTermGroupId' OR term_group = '$config->gameOperatorTermGroupId' OR term_group = '$config->gameProviderTermGroupId'" );
$wpdb->query( "DELETE FROM wp_term_taxonomy WHERE taxonomy = '$config->gameCategoryTaxonomy' OR taxonomy = '$config->gameProviderTaxonomy' OR taxonomy = '$config->gameOperatorTaxonomy'" );
$wpdb->query( 'DELETE FROM wp_term_relationships ...' );

