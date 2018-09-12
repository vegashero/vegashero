<?php

namespace VegasHero\Templates;

if ( ! defined( 'WPINC' ) ) {
    exit();
}

class Custom
{

    private $_config;

    public function __construct() {

        $this->_config = \Vegashero_Config::getInstance();

        //add_filter( 'the_content', array($this, 'wrapSingleCustomPostContent')); 
        add_action( 'after_setup_theme', array($this, 'enableFeaturedImages' ));
        add_action( 'after_setup_theme', array($this, 'registerImageSize'));
    }

    /**
     * @deprecated
     * @todo remove
     * https://developer.wordpress.org/themes/template-files-section/custom-post-type-template-files/
     */
    private function _singleTemplateExists() {
        return file_exists(sprintf("%s/single-%s.php", get_stylesheet_directory(), $this->_config->customPostType));
    }

    public function enableFeaturedImages() {
        add_theme_support('post-thumbnails');
    }

    public function registerImageSize() {
        if ( function_exists( 'add_image_size' ) ) { 
            add_image_size( 'vegashero-thumb', 376, 250, true );
        }
    }

    /**
     * @deprecated
     * @todo remove
     */
    public function getSingleGameWidgetArea() {
        ob_start();
        dynamic_sidebar( 'single_game_widget_area' );
        $single_game_widget = ob_get_contents();
        ob_end_clean();
        return $single_game_widget;
    }

    /**
     * @deprecated
     * @todo remove
     */
    private function _getIframeTemplate() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s../../templates/iframe.php", $plugin_dir);
    }

    /**
     * @deprecated
     * @todo remove
     */
    public function wrapSingleCustomPostContent($content) {
        error_log('Deprecated in favour of \VegasHero\Functions::renderGameFrame() and \VegasHero\Functions::renderGameWidget()');
        $post_id = get_the_ID();
        if ( get_post_type( $post_id ) == $this->_config->customPostType ) {
            $iframe_src = get_post_meta($post_id, 'game_src', true);
            $iframe_string = file_get_contents($this->_getIframeTemplate());
            $iframe_template = sprintf($iframe_string, $iframe_src);
            $single_game_widget_area = $this->getSingleGameWidgetArea();
            $content = sprintf("%s %s %s", $iframe_template, $content, $single_game_widget_area);
        }
        return $content;
    }

}
