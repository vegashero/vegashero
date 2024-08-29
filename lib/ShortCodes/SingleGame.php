<?php

namespace VegasHero\ShortCodes;

use WP_Query;
use InvalidArgumentException;

class SingleGame {


	private $wp_query;
	protected static $instance = null;

	public function __construct() {
		$this->wp_query = new WP_Query();
	}

	public static function getInstance(): SingleGame {
		if ( null === self::$instance ) {
			self::$instance = new SingleGame();
		}
		return self::$instance;
	}

	/**
	 * Finds game by id and renders iframe markup
	 *
	 * @param int    $game_id Game id set on post meta
	 * @param string $class Value for iframe class attribute
	 * @return string
	 */
	public function render( $game_id, $class = 'singlegame-iframe' ) {
		$iframe_src         = $this->_getIframeSrc( $game_id );
		$template           = self::getTemplate();
		$game_thumb_bg      = $this->_getGameImg( $game_id );
		$game_demo_btn_text = ! get_option( 'vh_gameplaynowbtntext' ) ? wp_strip_all_tags( __( 'Play Demo', 'vegashero' ) ) : get_option( 'vh_gameplaynowbtntext' );
		$game_age_gate_text = ! get_option( 'vh_gameagegatetext' ) ? wp_strip_all_tags( __( '18+ Only. Play Responsibly.', 'vegashero' ) ) : get_option( 'vh_gameagegatetext' );
		return sprintf( $template, $iframe_src, $game_thumb_bg, $game_demo_btn_text, $game_age_gate_text, $class );
	}

	public static function getTemplate() {

		wp_register_script( 'vegashero_fullscreen_utils', plugins_url( 'vegashero/templates/js/fullscreen-utils.js' ), [ 'jquery' ], null, true );
		wp_register_script( 'vegashero_fullscreen', plugins_url( 'vegashero/templates/js/fullscreen.js' ), [ 'jquery', 'vegashero_fullscreen_utils' ], null, true );
		wp_register_script( 'vegashero_single_game_iframe', plugins_url( 'vegashero/templates/js/iframe.js' ), [ 'jquery', 'vegashero_fullscreen' ], null, true );
		wp_enqueue_script( 'vegashero_fullscreen' );
		if ( get_option( 'vh_gameplaynowbtn' ) === 'on' ) {
			wp_enqueue_script( 'vegashero_single_game_iframe' );
		}
		$plugin_dir  = plugin_dir_path( __FILE__ );
		$iframe_file = sprintf( '%s../../templates/iframe.php', $plugin_dir );
		return file_get_contents( $iframe_file );
	}

	/**
	 * Finds iframe src post meta
	 *
	 * @param int $game_id Game id set on post meta
	 * @return string
	 */
	private function _getIframeSrc( $game_id ) {
		$this->wp_query->query(
			array(
				'post_type'  => 'vegashero_games',
				'meta_query' => array(
					array(
						'key'   => 'game_id',
						'value' => $game_id,
					),
				),
			)
		);
		$posts = (array) $this->wp_query->get_posts();
		if ( ! count( $posts ) ) {
			/* translators: %d will be replaced by the game id */
			throw new InvalidArgumentException( sprintf( __( 'Game with id %d not found', 'vegashero' ), $game_id ) );
		}
		return get_post_meta( $posts[0]->ID, 'game_src', true );
	}

	/**
	 * Finds game img post meta
	 *
	 * @param int $game_id Game id set on post meta
	 * @return string
	 */
	private function _getGameImg( $game_id ) {
		$this->wp_query->query(
			array(
				'post_type'  => 'vegashero_games',
				'meta_query' => array(
					array(
						'key'   => 'game_id',
						'value' => $game_id,
					),
				),
			)
		);
		$posts = (array) $this->wp_query->get_posts();
		if ( ! count( $posts ) ) {
			/* translators: %d will be replaced by the game id */
			throw new \InvalidArgumentException( sprintf( __( 'Game with id %d not found', 'vegashero' ), $game_id ) );
		}

		$post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id( $posts[0]->ID ), 'vegashero-thumb');
        if($post_thumb) {
            $game_thumb_bg = $post_thumb[0];
        } else {
			if ( get_option( 'vh_lobbywebp' ) === 'on' ) {
				$game_thumb_bg = str_replace( 'cover.jpg', 'cover.webp', get_post_meta( $posts[0]->ID, 'game_img', true ) );
			} else {
				$game_thumb_bg = get_post_meta( $posts[0]->ID, 'game_img', true );
			}
		}

		return $game_thumb_bg;

	}
}
