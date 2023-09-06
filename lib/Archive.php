<?php

namespace VegasHero;

use WP_Query;

/**
 * @example Tag archive page URL /tag/<tag-slug>
 * @example Game category archive page URL /game/category/<category-slug>
 * @example Game provider archive page URL /game/provider/<provider-slug>
 * @example Game operator archive page URL /game/operator/<operator-slug>
 */
class Archive {


	protected static $instance = null;

	protected function __construct() {
		self::add_actions();
	}

	private function add_actions(): void {
		add_action( 'pre_get_posts', array( self::class, 'set_tag_archive_query' ) );
	}

	public static function getInstance(): Archive {
		if ( null === self::$instance ) {
			self::$instance = new Archive();
		}
		return self::$instance;
	}

	/**
	 * @see https://developer.wordpress.org/reference/classes/wp_query/#post-type-parameters
	 * @example $query->set( 'post_type', [ 'post', 'vegashero_games' ] );
	 */
	public static function set_tag_archive_query( WP_Query $query ): void {
		if ( $query->is_main_query() && ! is_admin() && $query->is_tag ) {
			$query->set( 'post_type', 'any' );
		}
	}

}
