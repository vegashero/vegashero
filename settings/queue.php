<?php
// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(-1);

$current_dir = dirname(__FILE__);
$root_dir = realpath("$current_dir/../../../../");
require_once "$root_dir/wp-load.php";
$location = @$_SERVER['HTTP_REFERER'];

if( ! empty($location)) {

    if(isset($_GET['operator'])) {
        // TODO: add whitelist and check
        $whitelist = array();
        $import_type = 'operator';
        $identifier = trim($_GET['operator']);
    } elseif (isset($_GET['provider'])) {
        // TODO: add whitelist and check
        $whitelist = array();
        $import_type = 'provider';
        $identifier = trim($_GET['provider']);
    } 

    $parsed = parse_url($location);
    parse_str($parsed['query'], $dirty);
    $clean = http_build_query($dirty);
    $location = sprintf('%s://%s', $parsed['scheme'], $parsed['host']);
    if(array_key_exists('port', $parsed)) {
        $port = $parsed['port'];
        $location .= ":$port";
    }
    $location .= $parsed['path']."?$clean&vegashero-import=queued";
    // if(getenv('VEGASHERO_ENV') == 'production') {
    //     $location = sprintf('%s://%s%s?%s&vegashero-import=queued', $parsed['scheme'], $parsed['host'], $parsed['path'], $clean);
    // } else {
    //     $location = sprintf('%s://%s:%d%s?%s&vegashero-import=queued', $parsed['scheme'], $parsed['host'], $parsed['port'], $parsed['path'], $clean);
    // }

    // schedule import of games for the specific operator
    if( ! wp_next_scheduled(sprintf('vegashero_import_%s', $import_type), array($identifier))) {
        wp_schedule_single_event(time(), sprintf('vegashero_import_%s', $import_type), array($identifier));
    }

} else {
    $location = site_url();
}

wp_redirect($location);
exit();

