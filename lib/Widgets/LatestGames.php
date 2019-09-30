<?php 

namespace VegasHero\Widgets;

class LatestGames extends \WP_Widget {

	public function __construct() {
        $this->_config = \VegasHero\Config::getInstance();
        $widget_id = "vh_lastest_games_widget";
        $widget_name = "VegasHero Games Widget";
        $widget_options = array( 
            'classname' => 'Widget_vh_recent_games',
            'description' => __('Display games with thumbnails from the VegasHero Plugin.', 'vegashero'),
            'title' => 'Latest Casino Games',
            'maxgames' => 6,
            'orderby' => 'date',
        );
        parent::__construct($widget_id, $widget_name, $widget_options);
    }

    public function form($instance) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Latest Games', 'text_domain' );
        $post_type = 'vegashero_games';
        $maxgames = ! empty( $instance['maxgames'] ) ? $instance['maxgames'] : __( '6', 'text_domain' );
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : __( 'date', 'text_domain' );
        include("LatestGamesFormTemplate.php");
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

    /**
     * @return string
     */
    static private function _getItem($instance, $key) {
        if(array_key_exists($key, $instance)) {
            $attribute = esc_attr($instance[$key]);
            return empty($attribute) ? "" : $attribute;
        }
        return "";
    }

    static private function _getOrderBy($orderby) {
        switch ($orderby) {
            case "datenewest":
            case "dateoldest":
                return "date";

            case "titleaz":
            case "titleza":
                return "title";

            default:
                return "rand";
        }
    }

    static private function _getSortOrder($orderby) {
        switch($orderby) {
            case "datenewest":
                return "DESC";
            case "dateoldest":
                return "ASC";
            case "titleaz":
                return "ASC";
            case "titleza":
                return "DESC";
            default:
                return "DESC";
        }
    }

    static private function _getEmptyMarkup($args) {
        extract( $args );
        $markup = "";
        $markup .= $before_widget;
        if ( $title) 
            $markup .= $before_title . $title . $after_title;
        $markup .= '<span class="nogames-mgs">No games to display...</span>';
        return $markup;
    }

    static private function _getGamesMarkup($items, $args, $image_url) {
        extract( $args );
        $current_post_id = get_the_ID();
        $output = "";
        $output .= $before_widget;
        if ( $title) 
            $output .= $before_title . $title . $after_title;
        $output .= "<ul>"; 
        foreach ($items as $post) {
            $post_title = $post->post_title;
            $post_id = $post->ID;
            $post_link = get_permalink($post_id);
            $cpi = '';
            if ($current_post_id == $post_id) {
                $cpi=' current_page_item';
            }
            $providers = wp_get_post_terms($post_id, 'game_provider', array("fields" => "all"));            
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id( $post_id ), 'vegashero-thumb');
            $mypostslug = get_post_meta( $post_id, 'game_title', true );
            if($thumbnail) {
                $thumbnail_new = $thumbnail[0];
            } else {
                if( ! $thumbnail_new = get_post_meta( $post_id, 'game_img', true )) {
                    $thumbnail_new = $image_url . '/' . $providers[0]->slug . '/' . sanitize_title($mypostslug) . '/cover.jpg';
                }
            }
            $output .= "\r\n<li class=\"vh-games-widget-item vh_recent_games_$post_id $cpi\"><a href=\"$post_link\" title=\"$post_title\" class=\"vh_recent_games_item_$post_id $cpi\" ><img alt=\"$post_title\" src=\"$thumbnail_new\"/><h3>$post_title</h3></a></li>";
        }

        $output .= "</ul>";
        $output .= $after_widget;
        return $output;
    }

    public function widget($args, $instance) { 
        $args['title'] = LatestGames::_getItem($instance, 'title');
        $args['orderby'] = LatestGames::_getItem($instance, 'orderby');
        $args['maxgames'] = LatestGames::_getItem($instance, 'maxgames');
        extract($args);

        $options = array(
            'orderby' => LatestGames::_getOrderBy($orderby),
            'order'    => LatestGames::_getSortOrder($orderby),
            'post_type' => $this->_config->customPostType,
            'post_status' => 'publish',
            'posts_per_page' => $maxgames
        );

        if ( ! $items = get_posts( $options )) {
            echo LatestGames::_getEmptyMarkup($args);
        }
        echo LatestGames::_getGamesMarkup($items, $args, $this->_config->gameImageUrl);
    }
}

add_action( 'widgets_init', function() { 
    register_widget( 'VegasHero\Widgets\LatestGames' ); 
});
