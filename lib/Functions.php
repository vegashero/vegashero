<?php

namespace VegasHero;

class Functions {

    static function removeContentFilter() {
        if(has_filter('the_content', array('\VegasHero\Templates\Custom', 'wrapSingleCustomPostContent'))) {
            remove_filter( 'the_content', array('\VegasHero\Templates\Custom', 'wrapSingleCustomPostContent')); 
        }
    }

    static function renderGameFrame() {
        \VegasHero\Functions::removeContentFilter();
        if(get_option('vh_gameplaynowbtn') === 'on') {
            $iframe_file = sprintf("%s../templates/iframe_playdemobtn.php", plugin_dir_path(__FILE__));
        } else {
            $iframe_file = sprintf("%s../templates/iframe.php", plugin_dir_path(__FILE__));
        }
        if( ! file_exists($iframe_file)) {
            /* translators:  %s will be replaced by the iframe file name containing the game */
            error_log(sprintf(__('File not found %s', 'vegashero'), $iframe_file));
            return;
        }
        $post_id = get_the_ID();
        if( ! $post_id) {
            echo wp_strip_all_tags(__('renderGameFrame method can only be called within Wordpress loop.', 'vegashero'));
            return;
        }
        $iframe_src = get_post_meta($post_id, 'game_src', true);
        if( ! $iframe_src) {
            echo wp_strip_all_tags(__('Game source not found. Have you imported games?', 'vegashero'));
            return;
        }
        $iframe_string = file_get_contents($iframe_file, $iframe_src);
        echo sprintf($iframe_string, $iframe_src);
    }

    static function renderGameWidget() {
        \VegasHero\Functions::removeContentFilter();
        ob_start();
        dynamic_sidebar( 'single_game_widget_area' );
        $single_game_widget = ob_get_contents();
        ob_end_clean();
        if(empty($single_game_widget)) {
            echo wp_kses(__('<p><strong>Widget Area:</strong> Please add widgets via Appearance > Widgets</p>', 'vegashero'), ["p" => [], "strong" => []]);
            return;
        }
        echo $single_game_widget;
    }
}

