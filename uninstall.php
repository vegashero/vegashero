<?php
// If uninstall is not called from WordPress, exit
// if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
//     exit();
// }

$config = new Vegashero_Config();

global $wpdb;
$wpdb->query("DELETE FROM wp_postmeta WHERE meta_key = '$config->metaKey'");
$wpdb->query("DELETE FROM wp_posts WHERE post_type = '$config->customPostType'");
$wpdb->query("DELETE FROM wp_terms WHERE term_group = '$config->gameCategoryTermGroupId' OR term_group = '$config->gameOperatorTermGroupId' OR term_group = '$config->gameProviderTermGroupId'");
$wpdb->query("DELETE FROM wp_term_taxonomy WHERE taxonomy = '$config->gameCategoryTaxonomy' OR taxonomy = '$config->gameProviderTaxonomy' OR taxonomy = '$config->gameOperatorTaxonomy'");
// $wpdb->query("DELETE FROM wp_term_relationships ...");
