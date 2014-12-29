<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$custom_post_type = 'vegashero_game';
$taxonomy = 'vegashero_game_categories';

global $wpdb;
$wpdb->query("DELETE FROM wp_posts WHERE post_type = '$custom_post_type'");
$wpdb->query("DELETE FROM wp_terms WHERE name = '$taxonomy'");
