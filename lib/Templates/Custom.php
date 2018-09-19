<?php

namespace VegasHero\Templates;

if ( ! defined( 'WPINC' ) ) {
    exit();
}

/**
 * Please see filters and hooks at bottom of file
 */
class Custom
{

    /**
     * @param string $custom_post_type 
     * @return boolean
     * https://developer.wordpress.org/themes/template-files-section/custom-post-type-template-files/
     */
    static function singleTemplateExists($custom_post_type) {
        $config = \Vegashero_Config::getInstance();
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
     * @return string
     */
    static function getIframeTemplate() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s../../templates/iframe.php", $plugin_dir);
    }

    /**
     * @param string $content
     * @param string $custom_post_type
     * @return string
     */
    static function wrapSingleCustomPostContent($content) {
        $post_id = get_the_ID();
        $config = \Vegashero_Config::getInstance();
        if ( get_post_type( $post_id ) == $config->customPostType) {
            $iframe_src = get_post_meta($post_id, 'game_src', true);
            $iframe_string = file_get_contents(\VegasHero\Templates\Custom::getIframeTemplate());
            $iframe_template = sprintf($iframe_string, $iframe_src);
            $single_game_widget_area = \VegasHero\Templates\Custom::getSingleGameWidgetArea();
            $content = sprintf("%s %s %s", $iframe_template, $content, $single_game_widget_area);
        }
        return $content;
    }

}

if( ! has_filter('the_content', array('\VegasHero\Templates\Custom', 'wrapSingleCustomPostContent'))) {
    add_filter( 'the_content', array('\VegasHero\Templates\Custom', 'wrapSingleCustomPostContent')); 
}
add_action( 'after_setup_theme', array($this, 'enableFeaturedImages' ));
add_action( 'after_setup_theme', array($this, 'registerImageSize'));
