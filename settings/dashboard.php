<?php

class Vegashero_Settings_Dashboard
{

    private $_config;

    public function __construct() {

        $this->_config = new Vegashero_Config();
        add_action('admin_menu', array($this, 'addSettingsMenu'));

    }

    public function addSettingsMenu() {
        add_menu_page(
            'VegasHero Settings', // The title to be displayed on this menu's corresponding page
            'Vegas Hero', // The text to be displayed for this actual menu item
            'manage_options', // Which type of users can see this menu
            'vegashero-plugin', // The unique ID - that is, the slug - for this menu item
            array($this, 'createDashboardPage') // The name of the function to call when rendering this menu's page
        );

    }

    public function createDashboardPage() { 
        include dirname(__FILE__) . '/templates/dashboard.php';
    }
}
