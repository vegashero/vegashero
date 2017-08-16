<?php

// TODO: autoload using psr4
require 'lib/ShortCodes/SingleGame.php';

// VH Lobby shortcode
class Vegashero_Shortcodes
{

    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        add_shortcode('vegashero-lobby', array($this, 'lobby'));
        add_shortcode( 'vh_table' , array($this, 'vh_table_func'));
        add_shortcode( 'vh_table_line' , array($this, 'vh_table_line_func'));
        add_shortcode( 'vh-grid', array($this, 'vh_grid_shortcode'));
        add_shortcode('vh-game', array($this, 'renderSingleGame'));
    }

    public function renderSingleGame($atts) {
        if(array_key_exists('id', $atts)) {
            $game_id = (int)$atts['id'];
            $game = new VegasHero\ShortCodes\SingleGame(new WP_Query());
            return $game->render($game_id);
        }
    }

    public function lobby() {
        ob_start();
        $lobby_template_file = sprintf('%s/templates/lobby-%s.php', dirname(__FILE__), $this->_config->customPostType);
        // return file_get_contents($lobby_template_file);
        include_once $lobby_template_file;
        $lobby_template_file = ob_get_clean();
        return $lobby_template_file;
    }

    // VH Operators Table shortcode

    // [vh_table vh_tname="Table Title Here" vh_bonushead="Bonus Title" vh_devicehead="Devices Title"]
    //    [vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]
    //    [vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]
    // [/vh_table]

    public function vh_table_func($atts, $vhcontent = null){
        extract( shortcode_atts( array(
            'vh_tname' => '', //table title
            'vh_bonushead' => '', //bonus column title
            'vh_devicehead' => '', //device compatibility column title
        ), $atts ) );

        if ( $vh_bonushead == '' ) { $vh_bonushead = 'Bonus'; }
        if ( $vh_devicehead == '' ) { $vh_devicehead = 'Compatible Devices'; }

        $vhoutput = "<table class=\"vh-casino-providers\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><thead><tr><th class=\"vh-casino\">";
        $vhoutput .= $vh_tname;
        $vhoutput .= "<th class=\"vh-bonus\">";
        $vhoutput .= $vh_bonushead;
        $vhoutput .= "</th><th class=\"vh-devices\">";
        $vhoutput .= $vh_devicehead;
        $vhoutput .= "</th></th><th class=\"vh-cta-buttons\">&nbsp;</th></tr></thead><tbody>";
        $vhcontent = str_replace('<br />', '', $vhcontent);
        $vhoutput .= do_shortcode($vhcontent);
        $vhoutput .= "</tbody>";
        $vhoutput .= "</table>";
        return $vhoutput;
    }


    public function vh_table_line_func($atts){
        extract( shortcode_atts( array(
            'vh_img' => '',         //thumb img URL path
            'vh_bonus' => '',       //bonus amount
            'vh_pc' => '',          //pc compatible
            'vh_tablet' => '',      //tablet compatible
            'vh_mobile' => '',      //mobile compatible
            'vh_link' => '',        //Affiliate link URL
            'vh_btnlabel' => '',     //CTA button title
            'vh_target' => ''     //open link in new window or not
        ), $atts ) );

        if ( $vh_pc == '1' ) { $vh_pc = '<div class="results-desktop">Desktop</div>'; }
        if ( $vh_tablet == '1' ) { $vh_tablet = '<div class="results-tablet">Tablet</div>'; }
        if ( $vh_mobile == '1' ) { $vh_mobile = '<div class="results-mobile">Mobile</div>'; }
        if ( $vh_target == 'new' ) { $vh_target = '_blank'; } else { $vh_target = '_self'; }

        $vhoutput = "<tr><td class=\"vh-casino\"><a target=\"";
        $vhoutput .= $vh_target;
        $vhoutput .= "\" href=\"";
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
        $vhoutput .= "<td class=\"vh-cta-buttons\"><a target=\"";
        $vhoutput .= $vh_target;
        $vhoutput .= "\" href=\"";
        $vhoutput .= $vh_link;
        $vhoutput .= "\" class=\"vh-playnow\">";
        $vhoutput .= $vh_btnlabel;
        $vhoutput .= "</a></td></tr>";

        // ob_start();  


        // $output_string = ob_get_contents();  
        // ob_end_clean();  

        return $vhoutput;
    }

    /** VegasHero Grid Shortcode with taxonomy filters with configurable game sorting and count and optional pagination */
    public function vh_grid_shortcode( $atts ) {
        ob_start();

        // define attributes and their defaults
        extract( shortcode_atts( array (
            'order' => 'ASC',
            'orderby' => 'title',
            'gamesperpage' => -1,
            'pagination' => 'off',
            'provider' => '',
            'operator' => '',
            'category' => '',
            'tag' => '',
            'keyword' => '',
        ), $atts ) );

        // define query parameters based on attributes
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $options = array(
            'post_type' => 'vegashero_games',
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $gamesperpage,
            'paged' => $paged,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'game_provider',
                    'field'    => 'slug',
                    'terms'    => $provider,
                ),
                array(
                    'taxonomy' => 'game_operator',
                    'field'    => 'slug',
                    'terms'    => $operator,
                ),
                array(
                    'taxonomy' => 'game_category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $tag,
                ),
            ),
            's' => $keyword,
            // 'game_provider' => $provider,
            // 'game_operator' => $operator,
            // 'game_category' => $category,
        );
        $the_query = new WP_Query( $options ); ?>
    <ul id="vh-lobby-posts-grid" class="vh-row-sm">
    <?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); // run the loop ?>

<?php 
        $providerz = wp_get_post_terms(get_the_ID(), 'game_provider', array('fields' => 'all'));
        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'vegashero-thumb');
        $mypostslug = get_post_meta( get_the_ID(), 'game_title', true );
        if($thumbnail) {
            $thumbnail_new = $thumbnail[0];
        } else {
            if( ! $thumbnail_new = get_post_meta( get_the_ID(), 'game_img', true )) {
                $thumbnail_new = $this->_config->gameImageUrl . '/' . $providerz[0]->slug . '/' . sanitize_title($mypostslug) . '/cover.jpg';
            }
        }
?>            

            <li class="vh-item" id="post-<?php the_ID(); ?>">
                <a class="vh-thumb-link" href="<?php the_permalink(); ?>">
                    <div class="vh-overlay">
                        <img src="<?php echo $thumbnail_new; ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title"><?php the_title(); ?></div>
            </li>
            <?php endwhile; ?>

            <!-- grid pagination -->
            <?php if ($pagination == 'on') { ?>
            <?php if ($the_query->max_num_pages > 1) { ?>
              <nav class="vh-pagination">                
                <?php if( is_paged() ) { //check if first page ?>
                <div class="prev page-numbers">
                  <?php echo get_previous_posts_link( '<< Previous' ); ?>
                </div>
                <?php } ?>
                <?php if ( $the_query->max_num_pages > get_query_var('paged') ) { //check if last page ?>
                <div class="next page-numbers">
                  <?php echo get_next_posts_link( 'Next >>', $the_query->max_num_pages ); ?>
                </div>
                <?php } ?>
              </nav>
            <?php } ?>
            <?php } ?>

            <?php else: ?>
              <p><?php _e('Sorry, no games matched your criteria.'); ?></p>
<?php endif; 
wp_reset_postdata(); ?>
        </ul>
        <div class="clear"></div>

<?php $myvariable = ob_get_clean();
return $myvariable;
    }   


}

// adds unique CSS class to lobby page for easy custom styling
function vhLobby_body_class( $c ) {
    global $post;
    if( isset($post->post_content) && has_shortcode( $post->post_content, 'vegashero-lobby' ) ) {
        $c[] = 'vh-lobby-page';
    }
    return $c;
}
add_filter( 'body_class', 'vhLobby_body_class' );




