<?php

namespace VegasHero\Settings;

require_once( "Settings.php" );
require_once(sprintf("%swp-content/plugins/vegashero/lib/Helpers/Notice/Admin.php", ABSPATH));

class Lobby extends \VegasHero\Settings
{

    const MENU_SLUG = 'vh-lobby';
    const PAGE_SLUG = 'vh-lobby-page';

    private static $_config;
    private static $_instance;

    public function __construct() {
        $this->_showUpdateNotification(self::MENU_SLUG);
        static::$_config = \VegasHero\Config::getInstance();
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
        $text = __("You can change the default name of the lobby filters dropdowns. Change the button text on the lobby thumbnails. Change the name of VegasHero posts displayed in your theme's breadcrumb. Text strings entered here will replace the default values.", 'vegashero');
        echo "<p class='description'>$text</p>";
    }

    public function inputLobbyFiltersOp() {
        $args = func_get_args();
        $id_op = $args[0]['id_op'];
        $default = __('Filter by operator', 'vegashero');
        ?><input name="<?=$id_op?>" id="<?=$id_op?>" type='text' value='<?= get_option($id_op) ? get_option($id_op) : $default ?>' /><?php
    }
    public function inputLobbyFiltersCat() {
        $args = func_get_args();
        $id_cat = $args[0]['id_cat'];
        $default = __('Filter by category', 'vegashero');
        ?><input name="<?=$id_cat?>" id="<?=$id_cat?>" type='text' value='<?= get_option($id_cat) ? get_option($id_cat) : $default ?>' /><?php
    }
    public function inputLobbyFiltersProv() {
        $args = func_get_args();
        $id_prov = $args[0]['id_prov'];
        $default = __('Filter by provider', 'vegashero');
        ?><input name="<?=$id_prov?>" id="<?=$id_prov?>" type='text' value='<?= get_option($id_prov) ? get_option($id_prov) : $default ?>' /><?php
    }

    public function inputPlayNowBtn() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = __('Play Now', 'vegashero');
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : $default ?>' /><?php
    }

    public function inputPaginationPrev() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = __('Previous', 'vegashero');
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : "« $default" ?>' /><?php
    }

    public function inputPaginationNext() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = __('Next', 'vegashero');
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : "$default »" ?>' /><?php
    }

    public function inputCustomPostTypeName() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = __('VegasHero Games', 'vegashero');
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : $default ?>' /><?php
    }

    public function tickboxLobbySearch() { 
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/lobby-search-tickbox.php' );
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
            $title = __('Lobby Settings', 'vegashero'), 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_lobby_games_per_page', 
            $title = __('Number of games to show', 'vegashero'), 
            $callback = array($this, 'inputForGamesPerPage'), 
            $page = self::PAGE_SLUG, 
            $section = 'vh-lobby-section',
            $args = array(
                'id' => 'vh_lobby_games_per_page',
                'vh_lobby_games_per_page' => 20
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobby_games_per_page' 
        );

        add_settings_field(
            $id = 'vh_lobby_games_sort',
            $title = __('Sort lobby games by', 'vegashero'),
            $callback = array($this, 'selectLobbySorting'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobby-section',
            $args = array(
                'id' => 'vh_lobby_games_sort',
                'vh_lobby_games_sort' => 'DESC'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobby_games_sort' 
        );

        // lobby filters custom text
        add_settings_section(
            $id = 'vh-lobbyfilters-section', 
            $title = __('Lobby Custom Text', 'vegashero'), 
            $callback = array($this, 'DescriptionLobbyFilters'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id_op = 'vh_lobby_filterstext_op',
            $title = __('Operator Filter Text', 'vegashero'),
            $callback = array($this, 'inputLobbyFiltersOp'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_op' => 'vh_lobby_filterstext_op',
                'vh_lobby_filterstext_op' => 'Filter by operator'
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobby_filterstext_op' 
        );

        add_settings_field(
            $id_cat = 'vh_lobby_filterstext_cat',
            $title = __('Category Filter Text', 'vegashero'),
            $callback = array($this, 'inputLobbyFiltersCat'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_cat' => 'vh_lobby_filterstext_cat',
                'vh_lobby_filterstext_cat' => 'Filter by category'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobby_filterstext_cat' 
        );

        add_settings_field(
            $id_prov = 'vh_lobby_filterstext_prov',
            $title = __('Provider Filter Text', 'vegashero'),
            $callback = array($this, 'inputLobbyFiltersProv'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_prov' => 'vh_lobby_filterstext_prov',
                'vh_lobby_filterstext_prov' => 'Filter by provider'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobby_filterstext_prov' 
        );

        // Custom post type name text for breadcrumbs - Overwrites "VegasHero Games"
        add_settings_section(
            $id = 'vh-cptname-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_cptname',
            $title = __('VegasHero Games Custom Text (shows in breadcrumbs)', 'vegashero'),
            $callback = array($this, 'inputCustomPostTypeName'),
            $page = self::PAGE_SLUG,
            $section = 'vh-cptname-section',
            $args = array(
                'id' => 'vh_cptname',
                'vh_cptname' => 'VegasHero Games'
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_cptname' 
        );

        // Custom text for Play Now button on game thumbs - Overwrites "Play Now"
        add_settings_section(
            $id = 'vh-playnow-btn-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_playnow_btn',
            $title = __('Play Now Button Custom Text (shows on game thumbnails)', 'vegashero'),
            $callback = array($this, 'inputPlayNowBtn'),
            $page = self::PAGE_SLUG,
            $section = 'vh-playnow-btn-section',
            $args = array(
                'id' => 'vh_playnow_btn',
                'vh_playnow_btn' => 'Play Now'
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_playnow_btn' 
        );

        // Custom text paginaton previous button
        add_settings_section(
            $id = 'vh-pagination-prev-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_pagination_prev',
            $title = __('Pagination Previous button Custom Text', 'vegashero'),
            $callback = array($this, 'inputPaginationPrev'),
            $page = self::PAGE_SLUG,
            $section = 'vh-pagination-prev-section',
            $args = array(
                'id' => 'vh_pagination_prev',
                'vh_pagination_prev' => '« Previous'
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_pagination_prev' 
        );

        // Custom text paginaton next button
        add_settings_section(
            $id = 'vh-pagination-next-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_pagination_next',
            $title = __('Pagination Next button Custom Text', 'vegashero'),
            $callback = array($this, 'inputPaginationNext'),
            $page = self::PAGE_SLUG,
            $section = 'vh-pagination-next-section',
            $args = array(
                'id' => 'vh_pagination_next',
                'vh_pagination_next' => 'Next »'
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_pagination_next' 
        );

        // lobby search
        add_settings_section(
            $id = 'vh-lobbysearch-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_lobbysearch',
            $title = __('Display Games Search?', 'vegashero'),
            $callback = array($this, 'tickboxLobbySearch'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbysearch-section',
            $args = array(
                'id' => 'vh_lobbysearch',
                'vh_lobbysearch' => 'off'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobbysearch' 
        );

        // lobby link love
        add_settings_section(
            $id = 'vh-lobbylink-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_lobbylink',
            $title = __('Display VegasHero link?', 'vegashero'),
            $callback = array($this, 'tickboxLobbyLink'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbylink-section',
            $args = array(
                'id' => 'vh_lobbylink',
                'vh_lobbylink' => 'off'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobbylink' 
        );
    }

    public function createLobbyPage() {
        include_once( dirname( __FILE__ ) . '/templates/lobby.php' );
    }

    public function addSettingsMenu() {
        add_submenu_page(
            $parent_slug = \VegasHero\Settings\Menu::MENU_SLUG, 
            $page_title = __('Lobby', 'vegashero'), 
            $menu_title = __('Lobby', 'vegashero'), 
            $capability = 'manage_options', 
            $menu_slug = self::MENU_SLUG, 
            $callback = array($this, 'createLobbyPage') 
        );
        //add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
        //add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
        //add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
    }
}
