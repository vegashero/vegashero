<?php
// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(-1);

$current_dir = dirname(__FILE__);
$root_dir = realpath("$current_dir/../../../");
require_once "$root_dir/wp-load.php";
$location = $_SERVER['HTTP_REFERER'];
$parsed = parse_url($location);
parse_str($parsed['query'], $dirty);
$clean = http_build_query($dirty);
$location = sprintf('%s://%s%s?%s', $parsed['scheme'], $parsed['host'], $parsed['path'], $clean);

wp_redirect($location);
exit();

