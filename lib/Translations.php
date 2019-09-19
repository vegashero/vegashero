<?php

namespace VegasHero\Translations;

function load_textdomain() {
    load_plugin_textdomain( 'vegashero', FALSE, sprintf("%s/vegashero/languages/", WP_PLUGIN_DIR) );
}
