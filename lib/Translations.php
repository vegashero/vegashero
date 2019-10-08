<?php

namespace VegasHero\Translations;

function load_textdomain() {
    load_plugin_textdomain( 'vegashero', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
