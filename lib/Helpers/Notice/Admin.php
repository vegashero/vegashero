<?php
namespace VegasHero\Helpers\Notice;

/**
 * Helper class for showing admin notices
 */
final class Admin {

	private static $_msg;
	private static $_type;

	/**
	 * Green border
	 */
	public static function success( $msg ) {
		self::$_msg  = $msg;
		self::$_type = 'success';
		add_action( 'admin_notices', 'VegasHero\Helpers\Notice\Admin::show', 10, 1 );
	}

	/**
	 * Red border
	 */
	public static function error( $msg ) {
		self::$_msg  = $msg;
		self::$_type = 'error';
		add_action( 'admin_notices', 'VegasHero\Helpers\Notice\Admin::show', 10, 1 );
	}

	/**
	 * Blue border
	 */
	public static function info( $msg ) {
		self::$_msg  = $msg;
		self::$_type = 'info';
		add_action( 'admin_notices', 'VegasHero\Helpers\Notice\Admin::show', 10, 1 );
	}

	/**
	 * Yellow border
	 */
	public static function warning( $msg ) {
		self::$_msg  = $msg;
		self::$_type = 'warning';
		add_action( 'admin_notices', 'VegasHero\Helpers\Notice\Admin::show', 10, 1 );
	}

	public static function show() {
		printf( file_get_contents( __DIR__ . '/Template.php' ), sprintf( 'notice-%s', self::$_type ), self::$_msg );
	}
}
