<?php

namespace VegasHero\Translations;

include_once(ABSPATH.'wp-admin/includes/plugin.php');

function load_textdomain() {
    load_plugin_textdomain( 'vegashero', false, 'vegashero/languages' );
}

function get_language() : string {
    if(is_plugin_active('polylang/polylang.php')) {
        if(function_exists('pll_languages_list')) {
            // https://polylang.pro/doc/function-reference/#pll_languages_list
            $languages = pll_languages_list(['hide_empty' => true]);
            if(count($languages)) {
                // https://polylang.pro/doc/function-reference/#pll_current_language
                if(function_exists('pll_current_language')) {
                    return pll_current_language();
                }                     }
        }
    }
    return ''; // returns all languages by default
}

