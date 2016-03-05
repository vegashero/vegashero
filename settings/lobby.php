<?php

class Vegashero_Settings_Lobby
{

    private static $_config;
    private static $_instance;

    public static function getInstance() {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    private function __clone() {
    }

    protected function __construct() {
        static::$_config = Vegashero_Config::getInstance();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function sectionHeading() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $title = $args[0]['title'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/section-heading.php' );
    }

    public function inputForGamesPerPage() { 
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/games-per-page-input.php' );
    }

    public function registerSettings() {

        // lobby settings
        add_settings_section(
            $id = 'vh-lobby-section', 
            $title = 'Lobby Settings', 
            $callback = array($this, 'sectionHeading'), 
            $page = 'vh-lobby-page'
        );

        add_settings_field(
            $id = 'vh_lobby_games_per_page', 
            $title = 'Number of games to show', 
            $callback = array($this, 'inputForGamesPerPage'), 
            $page = 'vh-lobby-page', 
            $section = 'vh-lobby-section',
            $args = array(
                'id' => 'vh_lobby_games_per_page',
                'vh_lobby_games_per_page' => 20
            )
        );

        register_setting(
            $option_group = 'vh-lobby-page', 
            $option_name = 'vh_lobby_games_per_page' 
        );
    }

    public function createLobbyPage() {
        include_once( dirname( __FILE__ ) . '/templates/lobby.php' );
    }

    public function addSettingsMenu() {
        add_submenu_page(
            $parent_slug = 'vh-settings', 
            $page_title = 'Lobby', 
            $menu_title = 'Lobby', 
            $capability = 'manage_options', 
            $menu_slug = 'vh-lobby', 
            $callback = array($this, 'createLobbyPage') 
        );
        //add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
        //add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
        //add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
    }
}
