<?php

namespace VegasHero\Widgets;

use VegasHero\Config;

use WP_Widget;

class LatestGames extends WP_Widget {

	protected static $instance = null;

	protected function __construct() {
		$this->_config  = Config::getInstance();
		$widget_id      = 'vh_lastest_games_widget';
		$widget_name    = 'VegasHero Games Widget';
		$widget_options = array(
			'classname'   => 'Widget_vh_recent_games',
			'description' => wp_strip_all_tags( __( 'Display games with thumbnails from the VegasHero Plugin.', 'vegashero' ) ),
			'title'       => wp_strip_all_tags( __( 'Latest Casino Games', 'vegashero' ) ),
			'maxgames'    => 6,
			'orderby'     => 'date',
		);
		parent::__construct( $widget_id, $widget_name, $widget_options );
	}

	public static function addActions() {
		add_action(
			'widgets_init',
			function () {
				register_widget( self::getInstance() );
			}
		);
	}

	public static function getInstance(): LatestGames {
		if ( null === self::$instance ) {
			self::$instance = new LatestGames();
		}
		return self::$instance;
	}

	public function form( $instance ) {
		$title     = ! empty( $instance['title'] ) ? $instance['title'] : wp_strip_all_tags( __( 'Latest Games', 'vegashero' ) );
		$post_type = 'vegashero_games';
		$maxgames  = ! empty( $instance['maxgames'] ) ? $instance['maxgames'] : 6;
		$orderby   = ! empty( $instance['orderby'] ) ? $instance['orderby'] : wp_strip_all_tags( __( 'date', 'vegashero' ) );
		include 'LatestGamesFormTemplate.php';
	}

	/**
	 * @param array<array> $options
	 * @return string
	 */
	private static function _getOptionsMarkup( $options ) {
		$markup = '';
		foreach ( $options as $option ) {
			$markup .= self::_getOptionMarkup( $option['value'], $option['text'], $option['orderby'] );
		}
		return $markup;
	}

	/**
	 * @param string $value
	 * @param string $text
	 * @param string $order_by
	 * @return string
	 */
	private static function _getOptionMarkup( $value, $text, $order_by ) {
		$markup = "<option value='$value'";
		if ( $value == $order_by ) {
			$markup .= "selected='true' ";
		}
		$markup .= ">$text</option>";
		return $markup;
	}

	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	/**
	 * @return string
	 */
	private static function _getItem( $instance, $key ) {
		if ( array_key_exists( $key, $instance ) ) {
			$attribute = esc_attr( $instance[ $key ] );
			return empty( $attribute ) ? '' : $attribute;
		}
		return '';
	}

	private static function _getOrderBy( $orderby ) {
		switch ( $orderby ) {
			case 'datenewest':
			case 'dateoldest':
				return 'date';

			case 'titleaz':
			case 'titleza':
				return 'title';

			default:
				return 'rand';
		}
	}

	private static function _getSortOrder( $orderby ) {
		switch ( $orderby ) {
			case 'datenewest':
				return 'DESC';
			case 'dateoldest':
				return 'ASC';
			case 'titleaz':
				return 'ASC';
			case 'titleza':
				return 'DESC';
			default:
				return 'DESC';
		}
	}

	private static function _getEmptyMarkup( $args ) {
		extract( $args );
		$nogamemsg = wp_strip_all_tags( __( 'No games to display...', 'vegashero' ) );
		$markup    = '';
		$markup   .= $before_widget;
		if ( $title ) {
			$markup .= $before_title . $title . $after_title;
		}
		$markup .= "<span class=\"nogames-mgs\">$nogamemsg</span>";
		$markup .= $after_widget;
		return $markup;
	}

	private static function _getGamesMarkup( $items, $args, $image_url ) {
		extract( $args );
		$current_post_id = get_the_ID();
		$output          = '';
		$output         .= $before_widget;
		if ( $title ) {
			$output .= $before_title . $title . $after_title;
		}
		$output .= '<ul>';
		foreach ( $items as $post ) {
			$post_title = $post->post_title;
			$post_id    = $post->ID;
			$post_link  = get_permalink( $post_id );
			$cpi        = '';
			if ( $current_post_id == $post_id ) {
				$cpi = ' current_page_item';
			}
			$providers  = wp_get_post_terms( $post_id, 'game_provider', array( 'fields' => 'all' ) );
			$thumbnail  = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'vegashero-thumb' );
			$mypostslug = get_post_meta( $post_id, 'game_title', true );
			if ( $thumbnail ) {
				$thumbnail_new = $thumbnail[0];
			} elseif ( ! $thumbnail_new = get_post_meta( $post_id, 'game_img', true ) ) {
					$thumbnail_new = $image_url . '/' . $providers[0]->slug . '/' . sanitize_title( $mypostslug ) . '/cover.jpg';
			} elseif ( get_option( 'vh_lobbywebp' ) === 'on' ) {
					$imgpathtemp   = get_post_meta( $post_id, 'game_img', true );
					$webpimgpath   = str_replace( 'cover.jpg', 'cover.webp', $imgpathtemp );
					$thumbnail_new = $webpimgpath;
			} else {
				$thumbnail_new = get_post_meta( $post_id, 'game_img', true );
			}
			$output .= "\r\n<li class=\"vh-games-widget-item vh_recent_games_$post_id $cpi\"><a href=\"$post_link\" title=\"$post_title\" class=\"vh_recent_games_item_$post_id $cpi\" ><img width=\"376\" height=\"250\" alt=\"$post_title\" src=\"$thumbnail_new\"/><h3>$post_title</h3></a></li>";
		}//end foreach

		$output .= '</ul>';
		$output .= $after_widget;
		return $output;
	}

	public function widget( $args, $instance ) {
		$args['title']    = self::_getItem( $instance, 'title' );
		$args['orderby']  = self::_getItem( $instance, 'orderby' );
		$args['maxgames'] = self::_getItem( $instance, 'maxgames' );
		extract( $args );

		$options = array(
			'orderby'        => self::_getOrderBy( $orderby ),
			'order'          => self::_getSortOrder( $orderby ),
			'post_type'      => $this->_config->customPostType,
			'post_status'    => 'publish',
			'posts_per_page' => $maxgames,
		);

		if ( $items = get_posts( $options ) ) {
			echo self::_getGamesMarkup( $items, $args, $this->_config->gameImageUrl );
		} else {
			echo self::_getEmptyMarkup( $args );
		}
	}
}
