<?php

namespace Vegashero;


class FeaturedImage {

    public static function after_setup_theme() {
        add_theme_support('post-thumbnails');
    }

    public static function post_thumbnail_html() {
        $args = func_get_args();
        $config = \VegasHero\Config::getInstance();
        if( ! $args[0] && get_post_type() == $config->customPostType) { // and vegashero custom post type
            $game_img = get_post_meta(get_the_ID(), $config->postMetaGameImg, true);
            return sprintf("<img src='%s' class='wp-post-image' />", $game_img);
        }
        return $args[0];
    }

}


