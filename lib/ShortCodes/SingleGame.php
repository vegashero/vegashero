<?php

namespace VegasHero\ShortCodes;

final class SingleGame {


	private $wp_query;

	public function __construct() {
		$this->wp_query = new \WP_Query();
	}

	/**
	 * Finds game by id and renders iframe markup
	 *
	 * @param int    $game_id Game id set on post meta
	 * @param string $class Value for iframe class attribute
	 * @return string
	 */
	public function render( $game_id, $class = 'singlegame-iframe' ) {
		$iframe_src      = $this->_getIframeSrc( $game_id );
		$template        = $this->getTemplate();
		$game_thumb_bg   = $this->_getGameImg( $game_id );
		$gamedemobtntext = ! get_option( 'vh_gameplaynowbtntext' ) ? wp_strip_all_tags( __( 'Play Demo', 'vegashero' ) ) : get_option( 'vh_gameplaynowbtntext' );
		$gameagegatetext = ! get_option( 'vh_gameagegatetext' ) ? wp_strip_all_tags( __( '18+ Only. Play Responsibly.', 'vegashero' ) ) : get_option( 'vh_gameagegatetext' );
		return sprintf( $template, $class, $iframe_src, $game_thumb_bg, $gamedemobtntext, $gameagegatetext );
	}

	public function getTemplate() {
		if ( get_option( 'vh_gameplaynowbtn' ) === 'on' ) {
			return <<<MARKUP
            <div class="iframe_kh_wrapper">
              <div class="embed-bg-wrapper" style="background-image:url(%3\$s);"></div>
              <div class="embed-overlay"><button class="play-demo-btn">%4\$s</button><div class="age-gate-text">%5\$s</div>
              </div>
              <div class="kh-no-close"></div>
                <iframe width="" height="" class="%1\$s" frameborder="0" scrolling="no" allowfullscreen src="about:blank" data-srcurl="%2\$s" sandbox="allow-same-origin allow-scripts allow-popups allow-forms"></iframe>
                <script>
                jQuery(document).ready(function() {
                  jQuery('.singlegame-iframe').hide();
                // load iframe with play now button and remove overlay elements
                    jQuery('.play-demo-btn').on('click', function() {
                        jQuery('.embed-overlay').remove();
                        jQuery('.embed-bg-wrapper').remove();
                        jQuery('.singlegame-iframe').show();
                        jQuery('.singlegame-iframe').attr('src', jQuery('.singlegame-iframe').attr('data-srcurl'));
                        jQuery('.singlegame-iframe').css('background-color', 'black');
                    });
                }); 
                </script>
            </div>
            MARKUP;
		} else {
			return <<<MARKUP
            <div class="iframe_kh_wrapper">
                <div class="kh-no-close"></div>
                <iframe width="" height="" class="%s" frameborder="0" scrolling="no" allowfullscreen="" src="%s" sandbox="allow-same-origin allow-scripts allow-popups allow-forms"></iframe>
            </div>
            MARKUP;
		}
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
			throw new \InvalidArgumentException( sprintf( __( 'Game with id %d not found', 'vegashero' ), $game_id ) );
		}
		return \get_post_meta( $posts[0]->ID, 'game_src', true );
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
		if ( get_option( 'vh_lobbywebp' ) === 'on' ) {
			$game_thumb_bg = str_replace( 'cover.jpg', 'cover.webp', get_post_meta( $posts[0]->ID, 'game_img', true ) );
		} else {
			$game_thumb_bg = get_post_meta( $posts[0]->ID, 'game_img', true );
		}
		return $game_thumb_bg;
	}
}
