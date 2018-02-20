<?php

namespace VegasHero\Widgets;

class SingleGameArea {

    public $id;

    public function __construct() {
        $this->id = "single_game_widget_area";
        add_action( 'widgets_init', array($this, 'custom_sidebars'));
    }

    /**
     * Register sidebar widget area for single games page - widgets accepts shortcode, HTML banners codes etc
     */ 
    public function custom_sidebars() {

        $args = array(
            'id'            => $this->id,
            'class'         => 'single_game_widget_area',
            'name'          => __( 'Single Game Widget Area', 'text_domain' ),
            'description'   => __( 'Add widgets / shortcodes under VegasHero games', 'text_domain' ),
            'before_title'  => '<h2 class="singlegame_widget_title">',
            'after_title'   => '</h2>',
            'before_widget' => '<div class="singlegame_widget">',
            'after_widget'  => '</div>',
        );
        register_sidebar( $args );

    }

}
