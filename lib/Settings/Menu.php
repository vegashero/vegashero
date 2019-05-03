<?php

namespace VegasHero\Settings;

class Menu {

    const MENU_SLUG = 'vh-settings';

    public function __construct() {
        add_action('admin_menu', array($this, 'addSettingsMenu'));
    }

    public function addSettingsMenu() {
        add_menu_page(
            $page_title = 'VegasHero Settings',
            $menu_title = 'VegasHero',
            $capability = 'manage_options',
            $menu_slug = self::MENU_SLUG,
            $callback = '',
            $icon_url = '',
            $position = null
        );
    }

}
