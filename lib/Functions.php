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
        $iframe_file = sprintf("%s../templates/%s.php", plugin_dir_path(__FILE__), get_option('vh_gameplaynowbtn') !== 'on' ? 'iframe' : 'iframe_playdemobtn');

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
        if(get_option('vh_lobbywebp') === 'on') {
            $game_thumb_bg = str_replace('cover.jpg', 'cover.webp', get_post_meta( $post_id, 'game_img', true ));
        } else {
            $game_thumb_bg = get_post_meta( $post_id, 'game_img', true );
        }
        $gamedemobtntext = ! get_option('vh_gameplaynowbtntext') ? wp_strip_all_tags(__('Play Demo', 'vegashero')) : get_option('vh_gameplaynowbtntext');
        $gameagegatetext = ! get_option('vh_gameagegatetext') ? wp_strip_all_tags(__('18+ Only. Play Responsibly.', 'vegashero')) : get_option('vh_gameagegatetext');
        echo sprintf($iframe_string, $iframe_src, $game_thumb_bg, $gamedemobtntext, $gameagegatetext);
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

