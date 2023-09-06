<?php

namespace VegasHero\Settings;

use VegasHero\Helpers\Notice\Admin;

abstract class Settings {

	protected function _isSettingsUpdated() {
		return ( array_key_exists( 'settings-updated', $_GET ) && ( $_GET['settings-updated'] == 'true' ) );
	}

	protected function _isSettingsPage( $menu_slug = '' ) {
		if ( array_key_exists( 'page', $_GET ) && $_GET['page'] == $menu_slug ) {
			return true;
		}
		return false;
	}

	protected function _showUpdateNotification( $menu_slug ) {
		if ( $this->_isSettingsUpdated() && $this->_isSettingsPage( $menu_slug ) ) {
			$text = wp_strip_all_tags( __( 'Settings saved', 'vegashero' ) );
			\VegasHero\Helpers\Notice\Admin::success( "<strong>$text</strong>" );
		}
	}


}






