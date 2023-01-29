<?php

namespace VegasHero\Widgets;

class SingleGameArea {

    public static $id = 'single_game_widget_area';

    public static function addActions() {
        add_action( 'widgets_init', array(self::class, 'custom_sidebars'));
    }

    /**
     * Register sidebar widget area for single games page - widgets accepts shortcode, HTML banners codes etc
     */ 
    public static function custom_sidebars() {

        $args = array(
            'id'            => self::$id,
            'class'         => 'single_game_widget_area',
            'name'          => wp_strip_all_tags(__( 'Single Game Widget Area', 'vegashero' )),
            'description'   => wp_strip_all_tags(__( 'Add widgets / shortcodes under VegasHero games', 'vegashero' )),
            'before_title'  => '<h2 class="singlegame_widget_title">',
            'after_title'   => '</h2>',
            'before_widget' => '<div id="%1$s" class="widget singlegame_widget %2$s">',
            'after_widget'  => '</div>',
        );
        register_sidebar( $args );
    }

}
