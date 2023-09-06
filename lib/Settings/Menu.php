<?php

namespace VegasHero\Settings;

class Menu {

	protected static $instance = null;
	const MENU_SLUG            = 'vh-settings';

	protected function __construct() {
		add_action( 'admin_menu', array( $this, 'addSettingsMenu' ) );
	}

	public static function getInstance(): Menu {
		if ( null === self::$instance ) {
			self::$instance = new Menu();
		}
		return self::$instance;
	}

	public function addSettingsMenu() {
		add_menu_page(
			$page_title = wp_strip_all_tags( __( 'VegasHero Settings', 'vegashero' ) ),
			$menu_title = wp_strip_all_tags( __( 'VegasHero', 'vegashero' ) ),
			$capability = 'manage_options',
			$menu_slug  = self::MENU_SLUG,
			$callback   = '',
			$icon_url   = '',
			$position   = null
		);
	}

}
