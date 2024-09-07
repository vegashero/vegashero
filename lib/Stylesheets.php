<?php

namespace VegasHero;

class Stylesheets {


	public static function addActions() {
		add_action( 'wp_enqueue_scripts', [ self::class, 'loadStyles' ] );
	}

	public static function loadStyles() {
		wp_enqueue_style( 'lobby-styles', plugins_url( 'vegashero/templates/css/vh-lobby.css' ) );
		wp_enqueue_style( 'page-styles', plugins_url( 'vegashero/templates/css/vh-game.css' ) );

		/**
		 * Make dashicons available outside wp-admin
		 */
		if ( ! is_admin() && (is_single() || is_page() ) ) {
			wp_enqueue_style( 'dashicons', ABSPATH . WPINC . 'wp-includes/css/dashicons.min.css' );
		}
	}
}
