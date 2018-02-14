<?php 

namespace VegasHero\Widgets;

class LatestGames extends \WP_Widget {

	public function __construct() {
        $this->_config = \Vegashero_Config::getInstance();
        $widget_id = "vh_lastest_games_widget";
        $widget_name = "VegasHero Games Widget";
        $widget_options = array( 
            'classname' => 'Widget_vh_recent_games',
            'description' => 'Display games with thumbnails from the VegasHero Plugin.',
            'title' => 'Latest Casino Games',
            'maxgames' => 5,
            'orderby' => 'date',
        );
        parent::__construct($widget_id, $widget_name, $widget_options);
    }

    public function form($instance) {
        $title = ! empty($instance['title']) ? $instance['title'] : __( 'Latest Games', 'text_domain' );
        $post_type = $this->_config->customPostType;
        $maxgames = ! empty( $instance['maxgames'] ) ? $instance['maxgames'] : __( '5', 'text_domain' );
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : __( 'date', 'text_domain' );
        $options = LatestGames::_getOptionsMarkup(
            array(
                array( "value" => "datenewest", "text" => "Date (Newest first)", "orderby" => $order_by),
                array( "value" => "dateoldest", "text" => "Date (Oldest first)", "orderby" => $order_by),
                array( "value" => "tileaz", "text" => "Alphabetical Title (A-Z)", "orderby" => $order_by),
                array("value" => "tileza", "text" => "Alphabetical Title (Z-A)", "orderby" => $order_by),
                array("value" => "random", "text" => "Random", "orderby" => $order_by)
            ) 
        );
        echo <<<MARKUP
<br/>
<fieldset><legend>Widget Title:</legend>   
    <input class="widefat" id="$this->get_field_id('title')" name="$this->get_field_name('title')" type="text" value="$title" />
</fieldset>
<br/>

<fieldset><legend>Game Count:</legend>  
    <input id="$this->get_field_id('maxgames')" type="number" placeholder="5" value="$maxgames" name="$this->get_field_name('maxgames')">
</fieldset>
<br/>

<fieldset><legend>Sort Order:</legend> 
    <select id="$this->get_field_id('orderby')" name="$this->get_field_name('orderby')">
        ${options}
    </select>   
</fieldset>
<br/>
MARKUP;
    }

    /**
     * @param array<array> $options
     * @return string
     */
    static private function _getOptionsMarkup($options) {
        $markup = "";
        foreach($options as $option) {
            $markup .= LatestGames::_getOptionMarkup($option['value'], $option['text'], $option['orderby']);
        }
        return $markup;
    }

    /**
     * @param string $value
     * @param string $text
     * @param string $order_by
     * @return string
     */
    static private function _getOptionMarkup($value, $text, $order_by) {
        $markup = "<option value='$value'";
        if($value == $order_by) 
            $markup .= "selected='true' ";
        $markup .= ">$text</option>";
        return $markup;
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
        $items = get_posts( $args );

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

add_action( 'widgets_init', function() { 
    register_widget( 'VegasHero\Widgets\LatestGames' ); 
});
