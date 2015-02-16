<?php
// If uninstall is not called from WordPress, exit
// if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
//     exit();
// }

require_once "vegashero.php";
$vegashero = new Vegashero();

echo sprintf("custom post type: %s", $vegashero->customPostType);
echo sprintf("meta key: %s", $vegashero->metaKey);
echo sprintf("term group: %s", $vegashero->termGroupId);
echo sprintf("taxonomy: %s", $vegashero->taxonomy);

global $wpdb;
$wpdb->query("DELETE FROM wp_postmeta WHERE meta_key = '$vegashero->metaKey'");
$wpdb->query("DELETE FROM wp_posts WHERE post_type = '$vegashero->customPostType'");
$wpdb->query("DELETE FROM wp_terms WHERE term_group = '$vegashero->termGroupId'");
$wpdb->query("DELETE FROM wp_term_taxonomy WHERE taxonomy = '$vegashero->taxonomy'");
// $wpdb->query("DELETE FROM wp_term_relationships ...");
