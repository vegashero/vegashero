<?php

// VH Lobby shortcode
class Vegashero_Shortcodes
{

    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        add_shortcode('vegashero-lobby', array($this, 'lobby'));
    }

    public function lobby() {
        $lobby_template_file = sprintf('%s/templates/lobby-%s.php', dirname(__FILE__), $this->_config->customPostType);
        // return file_get_contents($lobby_template_file);
        include_once $lobby_template_file;
    }

}


// VH Operators Table shortcode

// [vh_table vh_tname="Table Title Here"]
//    [vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]
//    [vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]
// [/vh_table]

function vh_table_func($atts,$vhcontent = null){
    extract( shortcode_atts( array(
        'vh_tname' => '', //table title
    ), $atts ) );

    $vhoutput = "<table class=\"vh-casino-providers\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><thead><tr><th class=\"vh-casino\">";
    $vhoutput .= $vh_tname;
    $vhoutput .= "<th class=\"vh-bonus\">Bonus</th><th class=\"vh-devices\">Compatible Devices</th></th><th class=\"vh-cta-buttons\">&nbsp;</th></tr></thead><tbody>";
    $vhcontent = str_replace('<br />', '', $vhcontent);
    $vhoutput .= do_shortcode($vhcontent);
    $vhoutput .= "</tbody>";
    $vhoutput .= "</table>";
    return $vhoutput;
}

function vh_table_line_func($atts){
    extract( shortcode_atts( array(
        'vh_img' => '',         //thumb img URL path
        'vh_bonus' => '',       //bonus amount
        'vh_pc' => '',          //pc compatible
        'vh_tablet' => '',      //tablet compatible
        'vh_mobile' => '',      //mobile compatible
        'vh_link' => '',        //Affiliate link URL
        'vh_btnlabel' => ''     //CTA button title
    ), $atts ) );

    if ( $vh_pc == '1' ) { $vh_pc = '<div class="results-desktop">Desktop</div>'; }  
    if ( $vh_tablet == '1' ) { $vh_tablet = '<div class="results-tablet">Tablet</div>'; }  
    if ( $vh_mobile == '1' ) { $vh_mobile = '<div class="results-mobile">Mobile</div>'; }  

    $vhoutput = "<tr><td class=\"vh-casino\"><a href=\"";
    $vhoutput .= $vh_link;
    $vhoutput .= "\"><img src=\"";
    $vhoutput .= $vh_img;
    $vhoutput .= "\" width=\"180px\"></a></td>";
    $vhoutput .= "<td class=\"vh-bonus\">";
    $vhoutput .= $vh_bonus;
    $vhoutput .= "</td>";
    $vhoutput .= "<td class=\"vh-devices\">";
    $vhoutput .= $vh_pc . $vh_tablet . $vh_mobile;
    $vhoutput .= "</td>";
    $vhoutput .= "<td class=\"vh-cta-buttons\"><a href=\"";
    $vhoutput .= $vh_link;
    $vhoutput .= "\" class=\"vh-playnow\">";
    $vhoutput .= $vh_btnlabel;
    $vhoutput .= "</a></td></tr>";

    // ob_start();  
        
        
    // $output_string = ob_get_contents();  
    // ob_end_clean();  

    return $vhoutput;
}

add_shortcode( 'vh_table' , 'vh_table_func' );
add_shortcode( 'vh_table_line' , 'vh_table_line_func' );


/** Register sidebar widget area for single games page - widgets accepts shortcode, HTML banners codes etc */

function custom_sidebars() {

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
add_action( 'widgets_init', 'custom_sidebars' );


/** VegasHero Games Widget, configurable game sorting and count */
class Widget_vh_recent_games extends WP_Widget {


public function __construct() {
        $widget_ops = array( 
            'classname' => 'Widget_vh_recent_games',
            'description' => 'Display games with thumbnails from the VegasHero Plugin.',
            'title' => 'Latest Casino Games',
            'maxgames' => 5,
        );
        parent::__construct( 'Widget_vh_recent_games', 'VegasHero Games Widget', $widget_ops );
    }


public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Latest Games', 'text_domain' );
        $post_type = 'vegashero_games';
        $maxgames = ! empty( $instance['maxgames'] ) ? $instance['maxgames'] : __( '5', 'text_domain' );
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : __( 'post_date desc', 'text_domain' );

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

<!-- <fieldset><legend>Sort Order:</legend> 
    <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
        <option value="post_date desc"<?php if ($orderby=="post_date desc") echo ' selected="true"';  ?>>Date (Newest first)</option>
        <option value="post_date"<?php if ($orderby=="post_date") echo ' selected="true"';  ?>>Date (Oldest first)</option>
        <option value="upper(post_title)"<?php if ($orderby=="upper(post_title)") echo ' selected="true"';  ?>>Alphabetical Title</option>
        <option value="random"<?php if ($orderby=="random") echo ' selected="true"';  ?>>Random</option>
        <option value="post_modified desc, post_date desc"<?php if ($orderby=="post_modified desc, post_date desc") echo ' selected="true"';  ?>>Recently Modified</option>
    </select>   
</fieldset>
<br/> -->

<?php
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function widget($args, $instance) { 
        echo "\r\n<!-- Start of VegasHero Games Widget -->\r\n";
        // outputs the content of the widget
        extract( $args );
        $title = esc_attr($instance['title']);
        $post_type = 'vegashero_games';
        $orderby = esc_attr($instance['orderby']);
        $maxgames = esc_attr($instance['maxgames']);

        if (empty($title)) $title='';
        if (empty($post_type)) $post_type='post';
        if (empty($orderby)) $pfunc='post_date desc';
        if (empty($maxgames)) $maxgames=5;
        
        $sort=$orderby;
        if ($sort=='random') $sort='ID';
        $datelimit='';
        $items=vh_recent_games_get_results($sort,$post_type,$datelimit);
        if (empty($items)) {
            echo 'No games to display...';
            return;
        }
        if ($orderby=='random') {
            shuffle($items);
        }
        
        $max=$maxgames;
        $out='';
        global $wp_query;
        $thePostID = $wp_query->post->ID;

        foreach ($items as $post) {
            $max--;
            if ($max<0) break;
            $post_title=$post->post_title;
            $ID=$post->ID;
            $cpi='';
            if ($thePostID==$ID) {
               $cpi=' current_page_item';
            }
            $providers = wp_get_post_terms($post->ID, 'game_provider', array("fields" => "all"));            
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'thumbnail_size');
            if($thumbnail) {
                $thumbnail_new = $thumbnail[0];
            } else {
                $thumbnail_new = 'http://cdn.vegasgod.com/' . $providers[0]->slug . '/' . sanitize_title($post->post_title) . '/cover.jpg';
            }
            $post_link=get_permalink($ID);
            $out.= "\r\n<li class=\"vh-games-widget-item vh_recent_games_$cpi\"><a href=\"$post_link\" title=\"$post_title\" class=\"vh_recent_games_item_$cpi\" ><img alt=\"$post_title\" src=\"$thumbnail_new\"/><h3>$post_title</h3></a></li>";
        }

        if ( !empty( $out ) ) {
            echo $before_widget;
            if ( $title) {
                echo $before_title . $title . $after_title;
            }

            echo $out; 
            echo "</ul>";
            echo $after_widget;
        }
        echo "\r\n<!-- end of VegasHero Games Widget -->\r\n";

    }

}

function vh_recent_games_get_results($sort,$post_type,$datelimit) {
    global $wpdb;
    $dd='';
    if (!empty($datelimit)) {
        $dd=" AND POST_DATE>DATE('$datelimit')" ;
    }
    $sql="SELECT ID,post_title,post_author,comment_count, post_date, menu_order, post_modified FROM ".$wpdb->posts." WHERE post_status = 'publish' $dd and post_type='$post_type' ORDER by $sort";
    
    $results=$wpdb->get_results($sql);
    return $results;
}

add_action('widgets_init', create_function('', 'return register_widget("Widget_vh_recent_games");'));
