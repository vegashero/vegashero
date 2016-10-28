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

    public function selectLobbySorting() { 
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/lobby-sorting-select.php' );
    }

    public function DescriptionLobbyFilters() {
        ?><p class='description'>You can change the default name of the lobby filters dropdowns. Change the button text on the lobby thumbnails. Change the name of VegasHero posts displayed in your theme's breadcrumb. Text strings entered here will replace the default values.</p><?php
    }
    public function inputLobbyFiltersOp() {
        $args = func_get_args();
        $id_op = $args[0]['id_op'];
        ?><input name="<?=$id_op?>" id="<?=$id_op?>" type='text' value='<?=get_option($id_op)?get_option($id_op):'Filter by operator'?>' /><?php
    }
    public function inputLobbyFiltersCat() {
        $args = func_get_args();
        $id_cat = $args[0]['id_cat'];
        ?><input name="<?=$id_cat?>" id="<?=$id_cat?>" type='text' value='<?=get_option($id_cat)?get_option($id_cat):'Filter by category'?>' /><?php
    }
    public function inputLobbyFiltersProv() {
        $args = func_get_args();
        $id_prov = $args[0]['id_prov'];
        ?><input name="<?=$id_prov?>" id="<?=$id_prov?>" type='text' value='<?=get_option($id_prov)?get_option($id_prov):'Filter by provider'?>' /><?php
    }

    public function inputPlayNowBtn() {
        $args = func_get_args();
        $id = $args[0]['id'];
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?=get_option($id)?get_option($id):'Play Now'?>' /><?php
    }

    public function inputCustomPostTypeName() {
        $args = func_get_args();
        $id = $args[0]['id'];
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?=get_option($id)?get_option($id):'VegasHero Games'?>' /><?php
    }

    public function tickboxLobbyLink() { 
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/lobby-link-tickbox.php' );
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
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_lobby_games_per_page' 
        );

        // lobby default sorting settings
        add_settings_section(
            $id = 'vh-lobbysort-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = 'vh-lobby-page'
        );

        add_settings_field(
            $id = 'vh_lobby_games_sort',
            $title = 'Sort lobby games by',
            $callback = array($this, 'selectLobbySorting'),
            $page = 'vh-lobby-page',
            $section = 'vh-lobbysort-section',
            $args = array(
                'id' => 'vh_lobby_games_sort',
                'vh_lobby_games_sort' => 'DESC'
            )
        );

        register_setting(
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_lobby_games_sort' 
        );

        // lobby filters custom text
        add_settings_section(
            $id = 'vh-lobbyfilters-section', 
            $title = 'Lobby Custom Text', 
            $callback = array($this, 'DescriptionLobbyFilters'), 
            $page = 'vh-lobby-page'
        );

        add_settings_field(
            $id_op = 'vh_lobby_filterstext_op',
            $title = 'Operator Filter Text: ',
            $callback = array($this, 'inputLobbyFiltersOp'),
            $page = 'vh-lobby-page',
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_op' => 'vh_lobby_filterstext_op',
                'vh_lobby_filterstext_op' => 'Filter by operator'
            )
        );
        
        register_setting(
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_lobby_filterstext_op' 
        );

        add_settings_field(
            $id_cat = 'vh_lobby_filterstext_cat',
            $title = 'Category Filter Text: ',
            $callback = array($this, 'inputLobbyFiltersCat'),
            $page = 'vh-lobby-page',
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_cat' => 'vh_lobby_filterstext_cat',
                'vh_lobby_filterstext_cat' => 'Filter by category'
            )
        );

        register_setting(
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_lobby_filterstext_cat' 
        );

        add_settings_field(
            $id_prov = 'vh_lobby_filterstext_prov',
            $title = 'Provider Filter Text: ',
            $callback = array($this, 'inputLobbyFiltersProv'),
            $page = 'vh-lobby-page',
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_prov' => 'vh_lobby_filterstext_prov',
                'vh_lobby_filterstext_prov' => 'Filter by provider'
            )
        );

        register_setting(
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_lobby_filterstext_prov' 
        );

        // Custom post type name text for breadcrumbs - Overwrites "VegasHero Games"
        add_settings_section(
            $id = 'vh-cptname-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = 'vh-lobby-page'
        );

        add_settings_field(
            $id = 'vh_cptname',
            $title = 'VegasHero Games Custom Text (shows in breadcrumbs)',
            $callback = array($this, 'inputCustomPostTypeName'),
            $page = 'vh-lobby-page',
            $section = 'vh-cptname-section',
            $args = array(
                'id' => 'vh_cptname',
                'vh_cptname' => 'VegasHero Games'
            )
        );
        
        register_setting(
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_cptname' 
        );

        // Custom text for Play Now button on game thumbs - Overwrites "Play Now"
        add_settings_section(
            $id = 'vh-playnow-btn-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = 'vh-lobby-page'
        );

        add_settings_field(
            $id = 'vh_playnow_btn',
            $title = 'Play Now Button Custom Text (shows on game thumbnails)',
            $callback = array($this, 'inputPlayNowBtn'),
            $page = 'vh-lobby-page',
            $section = 'vh-playnow-btn-section',
            $args = array(
                'id' => 'vh_playnow_btn',
                'vh_playnow_btn' => 'Play Now'
            )
        );
        
        register_setting(
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_playnow_btn' 
        );

        // lobby link love
        add_settings_section(
            $id = 'vh-lobbylink-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = 'vh-lobby-page'
        );

        add_settings_field(
            $id = 'vh_lobbylink',
            $title = 'Display VegasHero link?',
            $callback = array($this, 'tickboxLobbyLink'),
            $page = 'vh-lobby-page',
            $section = 'vh-lobbylink-section',
            $args = array(
                'id' => 'vh_lobbylink',
                'vh_lobbylink' => 'off'
            )
        );

        register_setting(
            $option_group = 'vh-lobby-settings', 
            $option_name = 'vh_lobbylink' 
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
