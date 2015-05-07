<?php

/**
 * Plugin Name: Vegas Hero
 * Plugin URI: http://vegashero.co
 * Description: Bulk import of gambling games
 * Version: 0.0.0
 * Author: Vegas Heroes
 * Author URI: http://vegashero.co
 * License: GPL2
 */

if ( ! defined( 'WPINC' ) ) {
    exit();
}


spl_autoload_register(function($classname) {
    $segments = explode('_', $classname);
    if($segments[0] == 'Vegashero') {
        $filename = plugin_dir_path(__FILE__) . strtolower($segments[1]) .".php";
        if(file_exists($filename)) {
            include_once($filename);
        }
    }
});



$import = new Vegashero_Import();
$template = new Vegashero_Template();
$settings = new Vegashero_Settings();
$stylesheet = new Vegashero_Stylesheet();
$shortcode = new Vegashero_Shortcodes();
// $taxonomy = new Vegashero_Taxonomy();
