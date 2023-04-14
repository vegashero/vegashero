<?php

namespace VegasHero\Settings;

use VegasHero\Config;
use VegasHero\Helpers\Notice\Admin;
use VegasHero\Settings\Settings;

class Lobby extends Settings
{

    const MENU_SLUG = 'vh-lobby';
    const PAGE_SLUG = 'vh-lobby-page';

    private static $_config;
    protected static $instance = null;

    protected function __construct() {
        $this->_showUpdateNotification( self::MENU_SLUG );
        static::$_config = Config::getInstance();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public static function getInstance(): Lobby {
        if ( null === self::$instance ) {
            self::$instance = new Lobby();
        }
        return self::$instance;
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
        $text = wp_strip_all_tags(__("You can change the default name of the lobby filters dropdowns. Change the button text on the lobby thumbnails. Change the name of VegasHero posts displayed in your theme's breadcrumb. Text strings entered here will replace the default values.", 'vegashero'));
        echo "<p class='description'>$text</p>";
    }

    public function DescriptionGamePostSettings() {
        $text = wp_strip_all_tags(__("Change the default settings of the game posts here", 'vegashero'));
        echo "<p class='description'>$text</p>";
    }

    public function DescriptionSpacer() {
        $text = wp_strip_all_tags(__("___________________________", 'vegashero'));
        echo "<p class='description'>$text</p>";
    }

    public function inputLobbyFiltersOp() {
        $args = func_get_args();
        $id_op = $args[0]['id_op'];
        $default = wp_strip_all_tags(__('Filter by operator', 'vegashero'));
        ?><input name="<?=$id_op?>" id="<?=$id_op?>" type='text' value='<?= get_option($id_op) ? get_option($id_op) : $default ?>' /><?php
    }
    public function inputLobbyFiltersCat() {
        $args = func_get_args();
        $id_cat = $args[0]['id_cat'];
        $default = wp_strip_all_tags(__('Filter by category', 'vegashero'));
        ?><input name="<?=$id_cat?>" id="<?=$id_cat?>" type='text' value='<?= get_option($id_cat) ? get_option($id_cat) : $default ?>' /><?php
    }
    public function inputLobbyFiltersProv() {
        $args = func_get_args();
        $id_prov = $args[0]['id_prov'];
        $default = wp_strip_all_tags(__('Filter by provider', 'vegashero'));
        ?><input name="<?=$id_prov?>" id="<?=$id_prov?>" type='text' value='<?= get_option($id_prov) ? get_option($id_prov) : $default ?>' /><?php
    }

    public function inputPlayNowBtn() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = wp_strip_all_tags(__('Play Now', 'vegashero'));
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : $default ?>' /><?php
    }

    public function inputPaginationPrev() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = wp_strip_all_tags(__('Previous', 'vegashero'));
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : "$default" ?>' /><?php
    }

    public function inputPaginationNext() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = wp_strip_all_tags(__('Next', 'vegashero'));
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : "$default" ?>' /><?php
    }

    public function inputCustomPostTypeName() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = wp_strip_all_tags(__('VegasHero Games', 'vegashero'));
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : $default ?>' /><?php
    }

    public function inputLobbySearchText() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = wp_strip_all_tags(__('search', 'vegashero'));
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : "$default" ?>' /><?php
    }

    public function inputGamePlaynowBtnText() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = wp_strip_all_tags(__('Play Demo', 'vegashero'));
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : "$default" ?>' /><?php
    }

    public function inputGameAgeGateText() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $default = wp_strip_all_tags(__('18+ Only. Play Responsibly.', 'vegashero'));
        ?><input name="<?=$id?>" id="<?=$id?>" type='text' value='<?= get_option($id) ? get_option($id) : "$default" ?>' /><?php
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

    public function tickboxLobbyWebp() { 
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/lobby-webp-tickbox.php' );
    }
    public function tickboxPlayDemoBtn() { 
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/game-playdemobtn-tickbox.php' );
    }
    public function tickboxGameWidgetTop() { 
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/lobby/game-GameWidgetTop-tickbox.php' );
    }

    public function registerSettings() {

        // lobby settings
        add_settings_section(
            $id = 'vh-lobby-section', 
            $title = wp_strip_all_tags(__('Lobby & Games Settings', 'vegashero')), 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_lobby_games_per_page', 
            $title = wp_strip_all_tags(__('Number of games to show', 'vegashero')), 
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
            $title = wp_strip_all_tags(__('Sort lobby games by', 'vegashero')),
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

        // webp format images
        add_settings_section(
            $id = 'vh-lobbywebp-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_lobbywebp',
            $title = wp_strip_all_tags(__('Use .webp images instead of .jpeg?', 'vegashero')),
            $callback = array($this, 'tickboxLobbyWebp'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbywebp-section',
            $args = array(
                'id' => 'vh_lobbywebp',
                'vh_lobbywebp' => 'off'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobbywebp' 
        );


        // lobby filters custom text
        add_settings_section(
            $id = 'vh-lobbyfilters-section', 
            $title = wp_strip_all_tags(__('Lobby Custom Text', 'vegashero')), 
            $callback = array($this, 'DescriptionLobbyFilters'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id_op = 'vh_lobby_filterstext_op',
            $title = wp_strip_all_tags(__('Operator Filter Text', 'vegashero')),
            $callback = array($this, 'inputLobbyFiltersOp'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_op' => 'vh_lobby_filterstext_op',
                'vh_lobby_filterstext_op' => wp_strip_all_tags(__('Filter by operator', 'vegashero'))
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobby_filterstext_op' 
        );

        add_settings_field(
            $id_cat = 'vh_lobby_filterstext_cat',
            $title = wp_strip_all_tags(__('Category Filter Text', 'vegashero')),
            $callback = array($this, 'inputLobbyFiltersCat'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_cat' => 'vh_lobby_filterstext_cat',
                'vh_lobby_filterstext_cat' => wp_strip_all_tags(__('Filter by category', 'vegashero'))
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobby_filterstext_cat' 
        );

        add_settings_field(
            $id_prov = 'vh_lobby_filterstext_prov',
            $title = wp_strip_all_tags(__('Provider Filter Text', 'vegashero')),
            $callback = array($this, 'inputLobbyFiltersProv'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbyfilters-section',
            $args = array(
                'id_prov' => 'vh_lobby_filterstext_prov',
                'vh_lobby_filterstext_prov' => wp_strip_all_tags(__('Filter by provider', 'vegashero'))
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
            $title = wp_strip_all_tags(__('VegasHero Games Custom Text (shows in breadcrumbs)', 'vegashero')),
            $callback = array($this, 'inputCustomPostTypeName'),
            $page = self::PAGE_SLUG,
            $section = 'vh-cptname-section',
            $args = array(
                'id' => 'vh_cptname',
                'vh_cptname' => wp_strip_all_tags(__('VegasHero Games', 'vegashero'))
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
            $title = wp_strip_all_tags(__('Play Now Button Custom Text (shows on game thumbnails)', 'vegashero')),
            $callback = array($this, 'inputPlayNowBtn'),
            $page = self::PAGE_SLUG,
            $section = 'vh-playnow-btn-section',
            $args = array(
                'id' => 'vh_playnow_btn',
                'vh_playnow_btn' => wp_strip_all_tags(__('Play Now', 'vegashero'))
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
            $title = wp_strip_all_tags(__('Pagination Previous button Custom Text', 'vegashero')),
            $callback = array($this, 'inputPaginationPrev'),
            $page = self::PAGE_SLUG,
            $section = 'vh-pagination-prev-section',
            $args = array(
                'id' => 'vh_pagination_prev',
                'vh_pagination_prev' => wp_strip_all_tags(__('Previous', 'vegashero'))
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
            $title = wp_strip_all_tags(__('Pagination Next button Custom Text', 'vegashero')),
            $callback = array($this, 'inputPaginationNext'),
            $page = self::PAGE_SLUG,
            $section = 'vh-pagination-next-section',
            $args = array(
                'id' => 'vh_pagination_next',
                'vh_pagination_next' => wp_strip_all_tags(__('Next', 'vegashero'))
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_pagination_next' 
        );

        // Custom text lobby search input field placeholder
        add_settings_section(
            $id = 'vh-lobbysearchtext-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_lobbysearchtext',
            $title = wp_strip_all_tags(__('Lobby Search Input Field Custom Text', 'vegashero')),
            $callback = array($this, 'inputLobbySearchText'),
            $page = self::PAGE_SLUG,
            $section = 'vh-lobbysearchtext-section',
            $args = array(
                'id' => 'vh_lobbysearchtext',
                'vh_lobbysearchtext' => wp_strip_all_tags(__('search', 'vegashero'))
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_lobbysearchtext' 
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
            $title = wp_strip_all_tags(__('Display Games Search?', 'vegashero')),
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
            $title = wp_strip_all_tags(__('Display VegasHero link?', 'vegashero')),
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

        // Spacer
        add_settings_section(
            $id = 'vh-spacer-section', 
            $title = wp_strip_all_tags(__(' ', 'vegashero')), 
            $callback = array($this, 'DescriptionSpacer'), 
            $page = self::PAGE_SLUG
        );

        // Game page section title
        add_settings_section(
            $id = 'vh-gamesettings-section', 
            $title = wp_strip_all_tags(__('Game Post Settings', 'vegashero')), 
            $callback = array($this, 'DescriptionGamePostSettings'), 
            $page = self::PAGE_SLUG
        );

        // Show the Single Game widget area directly under the game iframe
        add_settings_section(
            $id = 'vh-gamewidgettop-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_gamewidgettop',
            $title = wp_strip_all_tags(__('Display Single Game widget area above content', 'vegashero')),
            $callback = array($this, 'tickboxGameWidgetTop'),
            $page = self::PAGE_SLUG,
            $section = 'vh-gamewidgettop-section',
            $args = array(
                'id' => 'vh_gamewidgettop',
                'vh_gamewidgettop' => 'off'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_gamewidgettop' 
        );

        // disable iframe autoload and enable play demo button with image background
        add_settings_section(
            $id = 'vh-gameplaydemobtn-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_gameplaynowbtn',
            $title = wp_strip_all_tags(__('Disable iframe autoload and display play demo button', 'vegashero')),
            $callback = array($this, 'tickboxPlayDemoBtn'),
            $page = self::PAGE_SLUG,
            $section = 'vh-gameplaydemobtn-section',
            $args = array(
                'id' => 'vh_gameplaynowbtn',
                'vh_gameplaynowbtn' => 'off'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_gameplaynowbtn' 
        );

        // Custom text for play demo btn
        add_settings_section(
            $id = 'vh-gameplaynowbtntext-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_gameplaynowbtntext',
            $title = wp_strip_all_tags(__('Play Demo Button Custom Text', 'vegashero')),
            $callback = array($this, 'inputGamePlaynowBtnText'),
            $page = self::PAGE_SLUG,
            $section = 'vh-gameplaynowbtntext-section',
            $args = array(
                'id' => 'vh_gameplaynowbtntext',
                'vh_gameplaynowbtntext' => wp_strip_all_tags(__('Play Demo', 'vegashero'))
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_gameplaynowbtntext' 
        );

        // Custom text for 18+ text
        add_settings_section(
            $id = 'vh-gameagegatetext-section', 
            $title = '', 
            $callback = array($this, 'sectionHeading'), 
            $page = self::PAGE_SLUG
        );

        add_settings_field(
            $id = 'vh_gameagegatetext',
            $title = wp_strip_all_tags(__('Under Button Compliance Custom Text', 'vegashero')),
            $callback = array($this, 'inputGameAgeGateText'),
            $page = self::PAGE_SLUG,
            $section = 'vh-gameagegatetext-section',
            $args = array(
                'id' => 'vh_gameagegatetext',
                'vh_gameagegatetext' => wp_strip_all_tags(__('18+ Only. Play Responsibly.', 'vegashero'))
            )
        );
        
        register_setting(
            $option_group = self::MENU_SLUG, 
            $option_name = 'vh_gameagegatetext' 
        );
    }

    public function createLobbyPage() {
        include_once( dirname( __FILE__ ) . '/templates/lobby.php' );
    }

    public function addSettingsMenu() {
        add_submenu_page(
            $parent_slug = Menu::MENU_SLUG, 
            $page_title = wp_strip_all_tags(__('Lobby & Games', 'vegashero')), 
            $menu_title = wp_strip_all_tags(__('Lobby & Games', 'vegashero')), 
            $capability = 'manage_options', 
            $menu_slug = self::MENU_SLUG, 
            $callback = array($this, 'createLobbyPage') 
        );
        //add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
        //add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
        //add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
    }
}
