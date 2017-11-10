<?php

class Vegashero_Widgets {


    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        add_action( 'widgets_init', array($this, 'custom_sidebars'));
    }

    /** Register sidebar widget area for single games page - widgets accepts shortcode, HTML banners codes etc */

    public function custom_sidebars() {

        $args = array(
            'id'            => 'single_game_widget_area',
            'class'         => 'single_game_widget_area',
            'name'          => __( 'Single Game Widget Area', 'text_domain' ),
            'description'   => __( 'Add widgets / shortcodes under VegasHero games', 'text_domain' ),
            'before_title'  => '<h2 class="singlegame_widget_title">',
            'after_title'   => '</h2>',
            'before_widget' => '<div class="singlegame_widget"><style>.preset-providers{display:none!important;}</style>',
            'after_widget'  => '</div>',
        );
        register_sidebar( $args );

    }

}

/** VegasHero Games Widget, configurable game sorting and count */
class Widget_vh_recent_games extends WP_Widget {

    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        $widget_ops = array( 
            'classname' => 'Widget_vh_recent_games',
            'description' => 'Display games with thumbnails from the VegasHero Plugin.',
            'title' => 'Latest Casino Games',
            'maxgames' => 5,
            'orderby' => 'date',
        );
        parent::__construct( 'Widget_vh_recent_games', 'VegasHero Games Widget', $widget_ops );
    }


    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Latest Games', 'text_domain' );
        $post_type = 'vegashero_games';
        $maxgames = ! empty( $instance['maxgames'] ) ? $instance['maxgames'] : __( '5', 'text_domain' );
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : __( 'date', 'text_domain' );

?>

<br/>
<fieldset><legend>Widget Title:</legend>   
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</fieldset>
<br/>

<fieldset><legend>Game Count:</legend>  
    <input id="<?php echo $this->get_field_id('maxgames'); ?>" type="number" placeholder="5" value="<?php echo $maxgames; ?>" name="<?php echo $this->get_field_name('maxgames'); ?>">
</fieldset>
<br/>

<fieldset><legend>Sort Order:</legend> 
    <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
        <option value="datenewest"<?php if ($orderby=="datenewest") echo ' selected="true"';  ?>>Date (Newest first)</option>
        <option value="dateoldest"<?php if ($orderby=="dateoldest") echo ' selected="true"';  ?>>Date (Oldest first)</option>
        <option value="titleaz"<?php if ($orderby=="titleaz") echo ' selected="true"';  ?>>Alphabetical Title (A-Z)</option>
        <option value="titleza"<?php if ($orderby=="titleza") echo ' selected="true"';  ?>>Alphabetical Title (Z-A)</option>
        <option value="random"<?php if ($orderby=="random") echo ' selected="true"';  ?>>Random</option>
    </select>   
</fieldset>
<br/>

<?php
    }

    public function update($new_instance, $old_instance) {
        return $new_instance;
    }

    public function widget($args, $instance) { 
        echo "\r\n<!-- Start of VegasHero Games Widget -->\r\n";
        // outputs the content of the widget
        extract( $args );
        $title = esc_attr($instance['title']);
        $post_type = 'vegashero_games';
        $orderby = esc_attr($instance['orderby']);
        $maxgames = esc_attr($instance['maxgames']);

        if (empty($title)) $title='';
        if (empty($post_type)) $post_type='post';
        if (empty($orderby)) $pfunc='date';
        if (empty($maxgames)) $maxgames=5;

        $orderbynew = 'date';
        $sort = 'DESC';

        if ($orderby=="datenewest") {
            $orderbynew = 'date';
            $sort = 'DESC';
        }
        if ($orderby=="dateoldest") {
            $orderbynew = 'date';
            $sort = 'ASC';
        }
        if ($orderby=="titleaz") {
            $orderbynew = 'title';
            $sort = 'ASC';
        }
        if ($orderby=="titleza") {
            $orderbynew = 'title';
            $sort = 'DESC';
        }
        if ($orderby=="random") {
            $orderbynew = 'rand';
            $sort = 'DESC';
        }

        $args = array(
            'orderby' => $orderbynew,
            'order'    => $sort,
            'post_type' => 'vegashero_games',
            'post_status' => 'publish',
            'posts_per_page' => $maxgames
        );
        $items = query_posts( $args );

        if (empty($items)) {
            echo $before_widget;
            if ( $title) {
                echo $before_title . $title . $after_title;
            }
            echo '<span class="nogames-mgs">No games to display...</span>';
            return;
        }

        $out='';
        global $wp_query;
        $thePostID = $wp_query->post->ID;

        foreach ($items as $post) {
            $post_title = $post->post_title;
            $ID = $post->ID;
            $cpi = '';
            if ($thePostID==$ID) {
                $cpi=' current_page_item';
            }
            $providers = wp_get_post_terms($ID, 'game_provider', array("fields" => "all"));            
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id( $ID ), 'vegashero-thumb');
            $mypostslug = get_post_meta( $ID, 'game_title', true );
            if($thumbnail) {
                $thumbnail_new = $thumbnail[0];
            } else {
                if( ! $thumbnail_new = get_post_meta( $ID, 'game_img', true )) {
                    $thumbnail_new = $this->_config->gameImageUrl . '/' . $providers[0]->slug . '/' . sanitize_title($mypostslug) . '/cover.jpg';
                }
            }
            $post_link=get_permalink($ID);
            $out.= "\r\n<li class=\"vh-games-widget-item vh_recent_games_$cpi\"><a href=\"$post_link\" title=\"$post_title\" class=\"vh_recent_games_item_$cpi\" ><img alt=\"$post_title\" src=\"$thumbnail_new\"/><h3>$post_title</h3></a></li>";
        }

        if ( !empty( $out ) ) {
            echo $before_widget;
            if ( $title) {
                echo $before_title . $title . $after_title;
            }
            echo "<ul>"; 
            echo $out;
            echo "</ul>";
            echo $after_widget;
        }
        echo "\r\n<!-- end of VegasHero Games Widget -->\r\n";

    }

}

add_action('widgets_init', create_function('', 'return register_widget("Widget_vh_recent_games");'));





