<?php

require_once( dirname( __FILE__ ) . '/license.php' );
$dashboard = Vegashero_Settings_License::getInstance();

require_once( dirname( __FILE__ ) . '/lobby.php' );
$lobby = Vegashero_Settings_Lobby::getInstance();

require_once( dirname( __FILE__ ) . '/permalinks.php' );
$lobby = Vegashero_Settings_Permalinks::getInstance();

require_once( dirname( __FILE__ ) . '/operators.php' );
$operators = new Vegashero_Settings_Operators();

require_once( dirname( __FILE__ ) . '/providers.php' );
$providers = new Vegashero_Settings_Providers();

//require_once( dirname( __FILE__ ) . '/settings/affiliates.php' );
//$affiliates = new Vegashero_Settings_Affiliates();



