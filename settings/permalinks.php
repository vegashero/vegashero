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
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function settingsSectionHeaderDescriptionMarkup() {
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
            $title = 'Permalink section',
            $callback = array($this, 'settingsSectionHeaderDescriptionMarkup'),
            $page = 'vegashero-dashboard'
        );

        add_settings_field( 
            $id = 'vh_custom_post_type_url_slug', 
            $title = 'Game base', 
            $callback = array($this, 'inputForCustomPostTypeUrl'), 
            $page = 'vegashero-dashboard', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_custom_post_type_url_slug'
            )
        );

        register_setting(
            $option_group = 'vegashero-dashboard', // must match page slug name from add_settings_field
            $option_name = 'vh_custom_post_type_url_slug'
        );

        add_settings_field( 
            $id = 'vh_game_category_url_slug', 
            $title = 'Game category url slug', 
            $callback = array($this, 'inputForGameCategoryUrl'), 
            $page = 'vegashero-dashboard', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_game_category_url_slug'
            )
        );

        register_setting(
            $option_group = 'vegashero-dashboard', // must match page slug name from add_settings_field
            $option_name = 'vh_game_category_url_slug'
        );

        add_settings_field( 
            $id = 'vh_game_operator_url_slug', 
            $title = 'Game operator url slug', 
            $callback = array($this, 'inputForGameOperatorUrl'), 
            $page = 'vegashero-dashboard', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_game_operator_url_slug'
            )
        );

        register_setting(
            $option_group = 'vegashero-dashboard', // must match page slug name from add_settings_field
            $option_name = 'vh_game_operator_url_slug'
        );

        add_settings_field( 
            $id = 'vh_game_provider_url_slug', 
            $title = 'Game provider url slug', 
            $callback = array($this, 'inputForGameProviderUrl'), 
            $page = 'vegashero-dashboard', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vh_game_provider_url_slug'
            )
        );

        register_setting(
            $option_group = 'vegashero-dashboard', // must match page slug name from add_settings_field
            $option_name = 'vh_game_provider_url_slug'
        );

    }

}

