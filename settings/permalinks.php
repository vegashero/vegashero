<?php

class Vegashero_Settings_Permalinks
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

    public function addSettingsMenu() {

        add_submenu_page(
            'vh-settings', // The title to be displayed on this menu's corresponding page
            'Permalinks', // The text to be displayed for this actual menu item
            'Permalinks', // The text to be displayed for this actual menu item
            'manage_options', // Which type of users can see this menu
            'vh-permalinks', // The unique ID - that is, the slug - for this menu item
            array($this, 'createPermalinksPage') // The name of the function to call when rendering this menu's page
        );
        //add_menu_page('My Page Title', 'My Menu Title', 'manage_options', 'my-menu', 'my_menu_output' );
        //add_submenu_page('my-menu', 'Submenu Page Title', 'Whatever You Want', 'manage_options', 'my-menu' );
        //add_submenu_page('my-menu', 'Submenu Page Title2', 'Whatever You Want2', 'manage_options', 'my-menu2' );
    }

    public function createPermalinksPage() { 
        include dirname(__FILE__) . '/templates/permalinks.php';
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

    public function inputForCustomPostTypeUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        if( ! $option = get_option('vh_custom_post_type_url_slug')) {
            update_option('vh_custom_post_type_url_slug', static::$_config->customPostTypeUrlSlug);
        }
        $option = get_option('vh_custom_post_type_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/custom-post-type-url-input.php' );
    }

    public function inputForGameCategoryUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        if( ! $option = get_option('vh_game_category_url_slug')) {
            update_option('vh_game_category_url_slug', static::$_config->gameCategoryUrlSlug);
        }
        $option = get_option('vh_game_category_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/game-category-url-input.php' );
    }

    public function inputForGameOperatorUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        if( ! $option = get_option('vh_game_operator_url_slug')) {
            update_option('vh_game_operator_url_slug', static::$_config->gameOperatorUrlSlug);
        }
        $option = get_option('vh_game_operator_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/game-operator-url-input.php' );
    }

    public function inputForGameProviderUrl() {
        $args = func_get_args();
        $id = $args[0]['id'];
        if( ! $option = get_option('vh_game_provider_url_slug')) {
            update_option('vh_game_provider_url_slug', static::$_config->gameProviderUrlSlug);
        }
        $option = get_option('vh_game_provider_url_slug');
        include_once( dirname( __FILE__ ) . '/templates/permalinks/game-provider-url-input.php' );
    }

    public function registerSettings() {

        add_settings_section(
            $id = 'vegashero-permalink-section',
            $title = 'Permalink Settings',
            $callback = array($this, 'sectionHeading'),
            $page = 'vh-permalinks'
        );

        add_settings_field( 
            $id = 'vh_custom_post_type_url_slug', 
            $title = 'Game base', 
            $callback = array($this, 'inputForCustomPostTypeUrl'), 
            $page = 'vh-permalinks', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_custom_post_type_url_slug'
            )
        );

        register_setting(
            $option_group = 'vh-permalinks', // must match page slug name from add_settings_field
            $option_name = 'vh_custom_post_type_url_slug'
        );

        add_settings_field( 
            $id = 'vh_game_category_url_slug', 
            $title = 'Category base', 
            $callback = array($this, 'inputForGameCategoryUrl'), 
            $page = 'vh-permalinks', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_game_category_url_slug'
            )
        );

        register_setting(
            $option_group = 'vh-permalinks', // must match page slug name from add_settings_field
            $option_name = 'vh_game_category_url_slug'
        );

        add_settings_field( 
            $id = 'vh_game_operator_url_slug', 
            $title = 'Operator base', 
            $callback = array($this, 'inputForGameOperatorUrl'), 
            $page = 'vh-permalinks', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_game_operator_url_slug'
            )
        );

        register_setting(
            $option_group = 'vh-permalinks', // must match page slug name from add_settings_field
            $option_name = 'vh_game_operator_url_slug'
        );

        add_settings_field( 
            $id = 'vh_game_provider_url_slug', 
            $title = 'Provider base', 
            $callback = array($this, 'inputForGameProviderUrl'), 
            $page = 'vh-permalinks', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_game_provider_url_slug'
            )
        );

        register_setting(
            $option_group = 'vh-permalinks', // must match page slug name from add_settings_field
            $option_name = 'vh_game_provider_url_slug'
        );

    }

}

