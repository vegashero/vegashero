<?php

namespace VegasHero\Admin;

use WP_Meta_Query;

use VegasHero\Config;

class AllGames {

	protected static $instance = null;
	const GAME_TYPES           = [ 'html5', 'flash' ];

	protected function __construct() {
		$this->_config = Config::getInstance();
		add_filter( sprintf( 'manage_%s_posts_columns', $this->_config->customPostType ), [ $this, 'addGameTypeColumn' ] );
		add_action( sprintf( 'manage_%s_posts_custom_column', $this->_config->customPostType ), [ $this, 'addGameTypeColumnValues' ], 10, 2 );

		add_action( 'restrict_manage_posts', [ $this, 'addGameTypeFilter' ], 10, 1 );
		add_filter( 'parse_query', [ $this, 'filterGamesByGameType' ], 10 );
	}

	public static function getInstance(): AllGames {
		if ( null === self::$instance ) {
			self::$instance = new AllGames();
		}
		return self::$instance;
	}

	public function addGameTypeColumnValues( $column, $post_id ) {
		switch ( $column ) {
			case $this->_config->postMetaGameType:
				echo get_post_meta( $post_id, $column, true );
				break;
		}
	}

	/**
	 * Adds game type filter to VH game custom post type list view in WP admin
	 */
	public function addGameTypeColumn( $columns ) {
		$columns[ $this->_config->postMetaGameType ] = __( 'Game Type', 'vegashero' );
		return $columns;
	}

	public function addGameTypeFilter( $post_type ) {
		if ( $this->_config->customPostType !== $post_type ) {
			return;
		}
		$meta_key = $this->_config->postMetaGameType;
		echo self::getGameTypeDropdown( $meta_key );
	}

	public static function getGameTypeDropdown( string $meta_key ): string {
		$select  = '';
		$select .= "<select id='$meta_key' name='$meta_key'>";
		$select .= '<option value="0">' . __( 'All Game Types', 'vegashero' ) . ' </option>';
		foreach ( self::GAME_TYPES as $type ) {
			$select .= sprintf( "<option value='$type' %s>$type</option>", isset( $_REQUEST[ $meta_key ] ) && $_REQUEST[ $meta_key ] === $type ? 'selected' : '' );
		}
		$select .= '</select>';
		return $select;
	}

	public function filterGamesByGameType( $query ) {
		// modify the query only if it admin and main query.
		if ( ! ( is_admin() and $query->is_main_query() ) ) {
			return $query;
		}
		if ( ! ( $this->_config->customPostType === $query->query['post_type'] && array_key_exists( $this->_config->postMetaGameType, $_REQUEST ) ) ) {
			return $query;
		}
		if ( $_REQUEST[ $this->_config->postMetaGameType ] === '0' ) {
			return $query;
		}
		// modify the query_vars.
		$query->query_vars['meta_query'] = [
			[
				'key'     => $this->_config->postMetaGameType,
				'value'   => trim( $_REQUEST[ $this->_config->postMetaGameType ] ),
				'type'    => 'CHAR',
				'compare' => '=',
			],
		];
		return $query;
	}
}




