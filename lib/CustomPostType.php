<?php

namespace VegasHero;

use VegasHero\Config;

class CustomPostType {


	protected static $instance = null;
	private $_config;

	protected function __construct() {
		$this->_config = Config::getInstance();
		add_action( 'init', [ $this, 'registerGameCategoryTaxonomy' ] );
		add_action( 'init', [ $this, 'registerGameOperatorTaxonomy' ] );
		add_action( 'init', [ $this, 'registerGameProviderTaxonomy' ] );
		add_action( 'init', [ $this, 'registerCustomPostType' ] );
	}

	public static function getInstance(): CustomPostType {
		if ( null === self::$instance ) {
			self::$instance = new CustomPostType();
		}
		return self::$instance;
	}

	public function registerGameOperatorTaxonomy() {
		$labels = array(
			'name'          => wp_strip_all_tags( __( 'Game Operators', 'vegashero' ) ),
			'singular_name' => wp_strip_all_tags( __( 'Game Operator', 'vegashero' ) ),
			'search_items'  => wp_strip_all_tags( __( 'Search Game Operators', 'vegashero' ) ),
			'all_items'     => wp_strip_all_tags( __( 'All Games Operators', 'vegashero' ) ),
			'edit_item'     => wp_strip_all_tags( __( 'Edit Game Operator', 'vegashero' ) ),
			'update_item'   => wp_strip_all_tags( __( 'Update Game Operator', 'vegashero' ) ),
			'add_new_item'  => wp_strip_all_tags( __( 'Add New Game Operator', 'vegashero' ) ),
			'new_item_name' => wp_strip_all_tags( __( 'New Game Operator', 'vegashero' ) ),
			'menu_name'     => wp_strip_all_tags( __( 'Game Operators', 'vegashero' ) ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			// TODO refactor into a method
			'query_var'         => get_option( 'vh_custom_post_type_url_slug' ) ? sprintf( '%s-%s', get_option( 'vh_custom_post_type_url_slug' ), get_option( 'vh_game_operator_url_slug' ) ) : get_option( 'vh_game_operator_url_slug' ),
			// 'rewrite'           => true
			'rewrite'           => array(
				'slug'       => get_option( 'vh_custom_post_type_url_slug' ) ? sprintf( '%s/%s', get_option( 'vh_custom_post_type_url_slug' ), get_option( 'vh_game_operator_url_slug' ) ) : get_option( 'vh_game_operator_url_slug' ),
				'with_front' => true,
			),
		);

		register_taxonomy( $this->_config->gameOperatorTaxonomy, array( $this->_config->customPostType ), $args );
		register_taxonomy_for_object_type( $this->_config->gameOperatorTaxonomy, $this->_config->customPostType );
		flush_rewrite_rules();
	}

	public function registerGameProviderTaxonomy() {
		// require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
		$labels = array(
			'name'          => wp_strip_all_tags( __( 'Game Providers', 'vegashero' ) ),
			'singular_name' => wp_strip_all_tags( __( 'Game Provider', 'vegashero' ) ),
			'search_items'  => wp_strip_all_tags( __( 'Search Game Providers', 'vegashero' ) ),
			'all_items'     => wp_strip_all_tags( __( 'All Games Providers', 'vegashero' ) ),
			'edit_item'     => wp_strip_all_tags( __( 'Edit Game Provider', 'vegashero' ) ),
			'update_item'   => wp_strip_all_tags( __( 'Update Game Provider', 'vegashero' ) ),
			'add_new_item'  => wp_strip_all_tags( __( 'Add New Game Provider', 'vegashero' ) ),
			'new_item_name' => wp_strip_all_tags( __( 'New Game Provider', 'vegashero' ) ),
			'menu_name'     => wp_strip_all_tags( __( 'Game Providers', 'vegashero' ) ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => get_option( 'vh_custom_post_type_url_slug' ) ? sprintf( '%s-%s', get_option( 'vh_custom_post_type_url_slug' ), get_option( 'vh_game_provider_url_slug' ) ) : get_option( 'vh_game_provider_url_slug' ),
			'rewrite'           => array(
				'slug'       => get_option( 'vh_custom_post_type_url_slug' ) ? sprintf( '%s/%s', get_option( 'vh_custom_post_type_url_slug' ), get_option( 'vh_game_provider_url_slug' ) ) : get_option( 'vh_game_provider_url_slug' ),
				'with_front' => true,
			),
		);

		register_taxonomy( $this->_config->gameProviderTaxonomy, array( $this->_config->customPostType ), $args );
		register_taxonomy_for_object_type( $this->_config->gameProviderTaxonomy, $this->_config->customPostType );
		flush_rewrite_rules();
	}

	// public function setPermalinkStructure() {
	// global $wp_rewrite;
	// $wp_rewrite->set_permalink_structure('/%postname%/');
	// }

	public function registerGameCategoryTaxonomy() {
		// require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
		$labels = array(
			'name'          => wp_strip_all_tags( __( 'Game Categories', 'vegashero' ) ),
			'singular_name' => wp_strip_all_tags( __( 'Game Category', 'vegashero' ) ),
			'search_items'  => wp_strip_all_tags( __( 'Search Game Category', 'vegashero' ) ),
			'all_items'     => wp_strip_all_tags( __( 'All Game Categories', 'vegashero' ) ),
			'edit_item'     => wp_strip_all_tags( __( 'Edit Category', 'vegashero' ) ),
			'update_item'   => wp_strip_all_tags( __( 'Update Category', 'vegashero' ) ),
			'add_new_item'  => wp_strip_all_tags( __( 'Add New Game Category', 'vegashero' ) ),
			'new_item_name' => wp_strip_all_tags( __( 'New Category', 'vegashero' ) ),
			'menu_name'     => wp_strip_all_tags( __( 'Game Categories', 'vegashero' ) ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			// TODO refactor into a method
			'query_var'         => get_option( 'vh_custom_post_type_url_slug' ) ? sprintf( '%s-%s', get_option( 'vh_custom_post_type_url_slug' ), get_option( 'vh_game_category_url_slug' ) ) : get_option( 'vh_game_category_url_slug' ),
			// 'query_var' => get_option('vh_game_category_url_slug'),
			'rewrite'           => array(
				'slug'       => get_option( 'vh_custom_post_type_url_slug' ) ? sprintf( '%s/%s', get_option( 'vh_custom_post_type_url_slug' ), get_option( 'vh_game_category_url_slug' ) ) : get_option( 'vh_game_category_url_slug' ),
				'with_front' => true,
			),
		);

		register_taxonomy( $this->_config->gameCategoryTaxonomy, array( $this->_config->customPostType ), $args );
		register_taxonomy_for_object_type( $this->_config->gameCategoryTaxonomy, $this->_config->customPostType );
		flush_rewrite_rules();
	}

	public function registerCustomPosttype() {
		$cptnamevalue = get_option( 'vh_cptname' );

		if ( $cptnamevalue == '' ) {
			$cptnamevalue = wp_strip_all_tags( __( 'VegasHero Games', 'vegashero' ) );
		} else {
			$cptnamevalue = get_option( 'vh_cptname' );
		}

		$options = array(
			'labels'              => array(
				'name'          => $cptnamevalue,
				'singular_name' => $cptnamevalue,
				'search_items'  => wp_strip_all_tags( __( 'Search Game', 'vegashero' ) ),
				'all_items'     => wp_strip_all_tags( __( 'All Games', 'vegashero' ) ),
				'edit_item'     => wp_strip_all_tags( __( 'Edit Game', 'vegashero' ) ),
				'update_item'   => wp_strip_all_tags( __( 'Update Game', 'vegashero' ) ),
				'add_new_item'  => wp_strip_all_tags( __( 'Add New Game', 'vegashero' ) ),
				'new_item_name' => wp_strip_all_tags( __( 'New Game', 'vegashero' ) ),
				'menu_name'     => wp_strip_all_tags( __( 'VegasHero Games', 'vegashero' ) ),
			),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'has_archive'         => get_option( 'vh_custom_post_type_url_slug' ),
			'query_var'           => get_option( 'vh_custom_post_type_url_slug' ),
			'hierarchical'        => false,
			'taxonomies'          => [
				$this->_config->gameProviderTaxonomy,
				$this->_config->gameCategoryTaxonomy,
				$this->_config->gameOperatorTaxonomy,
				'post_tag',
			],
			'show_ui'             => true,
			'can_export'          => false,
			'rewrite'             => array(
				'slug' => sprintf( '%s', get_option( 'vh_custom_post_type_url_slug' ) ),
			),
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions', 'author' ),
		);
		register_post_type( $this->_config->customPostType, $options );
		flush_rewrite_rules();
	}
}


/** Admin taxonomy filters for vegashero_games custom post type */

function add_game_category_taxonomy_filters() {
	global $typenow;
	$post_type               = 'vegashero_games';
	$taxonomy                = 'game_category';
	$posttype_slug           = get_option( 'vh_custom_post_type_url_slug' );
	$category_slug           = get_option( 'vh_game_category_url_slug' );
	$taxonomy_permalink_slug = $posttype_slug . '-' . $category_slug;
	if ( $typenow == $post_type ) {
		$selected      = isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
		$info_taxonomy = get_taxonomy( $taxonomy );
		wp_dropdown_categories(
			array(
				'show_option_all' => wp_strip_all_tags( sprintf( __( 'All %s' ), $info_taxonomy->label ) ),
				'taxonomy'        => $taxonomy,
				'name'            => $taxonomy_permalink_slug,
				'orderby'         => 'name',
				'selected'        => $selected,
				'show_count'      => true,
				'hide_if_empty'   => true,
				'value_field'     => 'slug',
			)
		);
	}
}

add_action( 'restrict_manage_posts', 'VegasHero\add_game_category_taxonomy_filters' );


function add_game_operator_taxonomy_filters() {
	global $typenow;
	$post_type               = 'vegashero_games';
	$taxonomy                = 'game_operator';
	$posttype_slug           = get_option( 'vh_custom_post_type_url_slug' );
	$operator_slug           = get_option( 'vh_game_operator_url_slug' );
	$taxonomy_permalink_slug = $posttype_slug . '-' . $operator_slug;
	if ( $typenow == $post_type ) {
		$selected      = isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
		$info_taxonomy = get_taxonomy( $taxonomy );
		wp_dropdown_categories(
			array(
				'show_option_all' => wp_strip_all_tags( sprintf( __( 'All %s' ), $info_taxonomy->label ) ),
				'taxonomy'        => $taxonomy,
				'name'            => $taxonomy_permalink_slug,
				'orderby'         => 'name',
				'selected'        => $selected,
				'show_count'      => true,
				'hide_if_empty'   => true,
				'value_field'     => 'slug',
			)
		);
	}
}

add_action( 'restrict_manage_posts', 'VegasHero\add_game_operator_taxonomy_filters' );


function add_game_provider_taxonomy_filters() {
	global $typenow;
	$post_type               = 'vegashero_games';
	$taxonomy                = 'game_provider';
	$posttype_slug           = get_option( 'vh_custom_post_type_url_slug' );
	$provider_slug           = get_option( 'vh_game_provider_url_slug' );
	$taxonomy_permalink_slug = $posttype_slug . '-' . $provider_slug;
	if ( $typenow == $post_type ) {
		$selected      = isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
		$info_taxonomy = get_taxonomy( $taxonomy );
		wp_dropdown_categories(
			array(
				/* translators: %s will be replaced by the relevant WordPress taxonomy label eg.. providers, operators, categories */
				'show_option_all' => wp_strip_all_tags( sprintf( __( 'All %s', 'vegashero' ), $info_taxonomy->label ) ),
				'taxonomy'        => $taxonomy,
				'name'            => $taxonomy_permalink_slug,
				'orderby'         => 'name',
				'selected'        => $selected,
				'show_count'      => true,
				'hide_if_empty'   => true,
				'value_field'     => 'slug',
			)
		);
	}
}

add_action( 'restrict_manage_posts', 'VegasHero\add_game_provider_taxonomy_filters' );



/**
 * option to disable the games archives page and gives priority to posts/pages with same URL slug
 * Use case: for example your game base url is youdomain.com/game/game-title/ and you want to have a lobby page like yourdomain.com/game/
 */
if ( get_option( 'vh_disablegamesarchive' ) === 'on' ) {
	function vhero_disable_games_archive( $args, $post_type ) {
		// If not vegashero_games CPT, don't appy
		if ( 'vegashero_games' !== $post_type ) {
			return $args;
		}
		// disable vegashero post type archive page option
		$vh_args = array(
			'has_archive' => false,
		);
		return array_merge( $args, $vh_args );
	}
	add_filter( 'register_post_type_args', 'VegasHero\vhero_disable_games_archive', 10, 2 );
}
