<?php

namespace VegasHero\Settings;

require_once("Settings.php");

class Permalinks extends \VegasHero\Settings
{

    const MENU_SLUG = 'vh-permalinks';
    const PAGE_SLUG = 'vh-permalinks-page';

    private static $_config;
    private static $_instance;

    public function __construct() {
        $this->_showUpdateNotification(self::MENU_SLUG);
        static::$_config = \VegasHero\Config::getInstance();
        add_action('admin_menu', array($this, 'addSettingsMenu'));
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function sanitize($input) {
        return strtolower(sanitize_title(sanitize_text_field($input)));
    }

    public function addSettingsMenu() {
        add_submenu_page(
            $parent_slug = \VegasHero\Settings\Menu::MENU_SLUG, // The title to be displayed on this menu's corresponding page
            $page_title = wp_strip_all_tags(__('Permalinks', 'vegashero')), // The text to be displayed for this actual menu item
            $menu_title = wp_strip_all_tags(__('Permalinks', 'vegashero')), // The text to be displayed for this actual menu item
            $capability = 'manage_options', // Which type of users can see this menu
            $menu_slug = self::MENU_SLUG, // The unique ID - that is, the slug - for this menu item
            $callback = array($this, 'createPermalinksPage') // The name of the function to call when rendering this menu's page
        );
        //add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
        //add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
        //add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
    }

    public function createPermalinksPage() { 
        include_once(dirname(__FILE__) . '/templates/permalinks.php');
    }

    public function sectionHeading() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $title = $args[0]['title'];
        include_once( dirname( __FILE__ ) . '/templates/permalinks/section-heading.php' );
    }

    private function permalinksEnabled() {
        return get_option('permalink-structure');
    }

    private function getPermalinkTagBase() {
        return get_option('tag_base');
    }

    private function getPermalinkCategoryBase() {
        return get_option('category_base');
    }

    public function updateCustomPostTypeUrl() {
        if( ! $option = get_option('vh_custom_post_type_url_slug')) {
            update_option('vh_custom_post_type_url_slug', static::$_config->customPostTypeUrlSlug);
        }
    }

    public function inputForCustomPostTypeUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $this->updateCustomPostTypeUrl();
        $option = get_option('vh_custom_post_type_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/custom-post-type-url-input.php' );
    }

    public function updateGameCategoryUrl() {
        if( ! $option = get_option('vh_game_category_url_slug')) {
            update_option('vh_game_category_url_slug', static::$_config->gameCategoryUrlSlug);
        }
    }

    public function inputForGameCategoryUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $this->updateGameCategoryUrl();
        $option = get_option('vh_game_category_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/game-category-url-input.php' );
    }

    public function updateGameOperatorUrl() {
        if( ! $option = get_option('vh_game_operator_url_slug')) {
            update_option('vh_game_operator_url_slug', static::$_config->gameOperatorUrlSlug);
        }
    }

    public function inputForGameOperatorUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $this->updateGameOperatorUrl();
        $option = get_option('vh_game_operator_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/game-operator-url-input.php' );
    }

    public function updateGameProviderUrl() {
        if( ! $option = get_option('vh_game_provider_url_slug')) {
            update_option('vh_game_provider_url_slug', static::$_config->gameProviderUrlSlug);
        }
    }

    public function inputForGameProviderUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $this->updateGameProviderUrl();
        $option = get_option('vh_game_provider_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/game-provider-url-input.php' );
    }

    public function registerSettings() {

        add_settings_section(
            $id = 'vh-permalinks-section',
            $title = wp_strip_all_tags(__('Permalink Settings', 'vegashero')),
            $callback = array($this, 'sectionHeading'),
            $page = self::PAGE_SLUG
        );

        add_settings_field( 
            $id = 'vh_custom_post_type_url_slug', 
            $title = wp_strip_all_tags(__('Game base', 'vegashero')), 
            $callback = array($this, 'inputForCustomPostTypeUrl'), 
            $page = self::PAGE_SLUG, 
            $section = 'vh-permalinks-section',
            $args = array(
                'id' => 'vh_custom_post_type_url_slug'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, // must match page slug name from add_settings_field
            $option_name = 'vh_custom_post_type_url_slug',
            array($this, 'sanitize')
        );

        add_settings_field( 
            $id = 'vh_game_category_url_slug', 
            $title = wp_strip_all_tags(__('Category base', 'vegashero')), 
            $callback = array($this, 'inputForGameCategoryUrl'), 
            $page = self::PAGE_SLUG, 
            $section = 'vh-permalinks-section',
            $args = array(
                'id' => 'vh_game_category_url_slug'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, // must match page slug name from add_settings_field
            $option_name = 'vh_game_category_url_slug',
            array($this, 'sanitize')
        );

        add_settings_field( 
            $id = 'vh_game_operator_url_slug', 
            $title = wp_strip_all_tags(__('Operator base', 'vegashero')), 
            $callback = array($this, 'inputForGameOperatorUrl'), 
            $page = self::PAGE_SLUG, 
            $section = 'vh-permalinks-section',
            $args = array(
                'id' => 'vh_game_operator_url_slug'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, // must match page slug name from add_settings_field
            $option_name = 'vh_game_operator_url_slug',
            array($this, 'sanitize')
        );

        add_settings_field( 
            $id = 'vh_game_provider_url_slug', 
            $title = wp_strip_all_tags(__('Provider base', 'vegashero')), 
            $callback = array($this, 'inputForGameProviderUrl'), 
            $page = self::PAGE_SLUG, 
            $section = 'vh-permalinks-section',
            $args = array(
                'id' => 'vh_game_provider_url_slug'
            )
        );

        register_setting(
            $option_group = self::MENU_SLUG, // must match page slug name from add_settings_field
            $option_name = 'vh_game_provider_url_slug',
            array($this, 'sanitize')
        );

    }

}

