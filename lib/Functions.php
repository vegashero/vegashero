<?php

namespace VegasHero;

class Functions {
    static function renderGameFrame() {
        $iframe_file = sprintf("%s../templates/iframe.php", plugin_dir_path(__FILE__));
        if( ! file_exists($iframe_file)) {
            error_log(sprintf("File not found %s", $iframe_file));
            return;
        }
        $post_id = get_the_ID();
        if( ! $post_id) {
            echo "renderGameFrame method can only be called within Wordpress loop.";
            return;
        }
        $iframe_src = get_post_meta($post_id, 'game_src', true);
        if( ! $iframe_src) {
            echo "Game source not found. Have you imported games?";
            return;
        }
        $iframe_string = file_get_contents($iframe_file, $iframe_src);
        echo sprintf($iframe_string, $iframe_src);
    }

    static function renderGameWidget() {
        ob_start();
        dynamic_sidebar( 'single_game_widget_area' );
        $single_game_widget = ob_get_contents();
        ob_end_clean();
        if(empty($single_game_widget)) {
            echo "<p><strong>Widget Area:</strong> Please add widgets via Appearance > Widgets</p>";
            return;
        }
        echo $single_game_widget;
    }
}

