<?php

namespace VegasHero\Templates;

use VegasHero\ShortCodes\SingleGame;
use VegasHero\Config;

if ( ! defined( 'WPINC' ) ) {
    exit();
}

/**
 * Please see filters and hooks at bottom of file
 */
class Custom
{
    public static function addActions() {
        add_action( 'after_setup_theme', [ self::class, 'enableFeaturedImages'] );
        add_action( 'after_setup_theme', [ self::class, 'registerImageSize'] );
    }

    public static function addFilters() {
        if( ! has_filter('the_content', [ self::class, 'wrapSingleCustomPostContent' ] ) ) {
            add_filter( 'the_content', [ self::class, 'wrapSingleCustomPostContent' ] ); 
        }
        // show custom fields if ACF is used
        add_filter('acf/settings/remove_wp_meta_box', '__return_false');
    }

    /**
     * @param string $custom_post_type 
     * @return boolean
     * https://developer.wordpress.org/themes/template-files-section/custom-post-type-template-files/
     */
    static function singleTemplateExists($custom_post_type) {
        $config = Config::getInstance();
        return file_exists(sprintf("%s/single-%s.php", get_stylesheet_directory(), $custom_post_type));
    }

    static function enableFeaturedImages() {
        add_theme_support('post-thumbnails');
    }

    static function registerImageSize() {
        if ( function_exists( 'add_image_size' ) ) { 
            add_image_size( 'vegashero-thumb', 376, 250, true );
        }
    }

    /**
     * @return string
     */
    static function getSingleGameWidgetArea() {
        ob_start();
        dynamic_sidebar( 'single_game_widget_area' );
        $single_game_widget = ob_get_contents();
        ob_end_clean();
        return $single_game_widget;
    }

    /**
     * @param string $content
     * @param string $custom_post_type
     * @return string
     */
    static function wrapSingleCustomPostContent($content) {
        if( is_singular() ) {
            $post_id = get_the_ID();
            $config = Config::getInstance();
            if ( get_post_type( $post_id ) == $config->customPostType) {
                $iframe_src = get_post_meta($post_id, 'game_src', true);

                $iframe_string = SingleGame::getTemplate();

                if(get_option('vh_lobbywebp') === 'on') {
                    $game_thumb_bg = str_replace('cover.jpg', 'cover.webp', get_post_meta( $post_id, 'game_img', true ));
                } else {
                    $game_thumb_bg = get_post_meta( $post_id, 'game_img', true );
                }

                $game_demo_btn_text = ! get_option('vh_gameplaynowbtntext') ? wp_strip_all_tags(__('Play Demo', 'vegashero')) : get_option('vh_gameplaynowbtntext');
                $game_age_gate_text = ! get_option('vh_gameagegatetext') ? wp_strip_all_tags(__('18+ Only. Play Responsibly.', 'vegashero')) : get_option('vh_gameagegatetext');
                $iframe_template = sprintf($iframe_string, $iframe_src, $game_thumb_bg, $game_demo_btn_text, $game_age_gate_text, "");
                $single_game_widget_area = self::getSingleGameWidgetArea();

                if(get_option('vh_gamewidgettop') === 'on') {
                    $content = sprintf("%s %s %s", $iframe_template, $single_game_widget_area, $content);
                } else {
                    $content = sprintf("%s %s %s", $iframe_template, $content, $single_game_widget_area);
                }
            }
        }
        return $content;
    }

}

