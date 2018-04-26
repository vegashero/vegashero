<?php

// TODO: autoload using psr4
require 'lib/ShortCodes/SingleGame.php';
require 'lib/ShortCodes/GamesGrid.php';

// VH Lobby shortcode
class Vegashero_Shortcodes
{

    private $_config;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        add_shortcode('vegashero-lobby', array($this, 'lobby'));
        add_shortcode( 'vh_table' , array($this, 'vh_table_func'));
        add_shortcode( 'vh_table_line' , array($this, 'vh_table_line_func'));
        add_shortcode( 'vh-grid', array($this, 'renderGamesGrid'));
        add_shortcode( 'vh_grid', array($this, 'renderGamesGrid'));
        add_shortcode('vh-game', array($this, 'renderSingleGame')); // tested
        add_shortcode('vh_game', array($this, 'renderSingleGame')); // tested
    }

    /**
     * Method called by vh-game shortcode
     * @param array $atts
     * @return string
     */
    public function renderSingleGame($atts) {
        if(array_key_exists('id', $atts)) {
            $game_id = (int)$atts['id'];
            $game = new VegasHero\ShortCodes\SingleGame();
            return $game->render($game_id);
        }
    }

    /**
     * Method called by vh-grid showcode
     * @param array $atts
     * @return string
     */
    public function renderGamesGrid($atts) {
        return VegasHero\ShortCodes\GamesGrid::render($atts, $this->_config);
    }

	public function lobby() {
		$playnow_btn_value = get_option('vh_playnow_btn');
		if ($playnow_btn_value == '') {
			$playnow_btn_value = 'Play Now';
		} else {
			$playnow_btn_value = get_option('vh_playnow_btn');
		}
		$script_src = sprintf('%stemplates/js/lobby_search_filters.js', plugin_dir_url( __FILE__ ));
		wp_register_script('jquery_debounce', sprintf("%stemplates/js/jquery.ba-throttle-debounce.min.js", plugin_dir_url(__FILE__)), null, true);
		wp_enqueue_script('jquery_debounce', '', array('jquery'), null, true);
		wp_enqueue_script('vegashero_lobby_script', $script_src, array('jquery_debounce'), null, true);
		wp_localize_script( 'vegashero_lobby_script', 'ajax_object',
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'site_url' => site_url(),
				'image_url' => $this->_config->gameImageUrl,
				'playnow_btn_value' => $playnow_btn_value,
				'vh_custom_post_type_url_slug' => get_option('vh_custom_post_type_url_slug')
			)
		);
		$lobby_template_file = sprintf('%s/templates/lobby.php', dirname(__FILE__));
		include_once $lobby_template_file;
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
            'vh_btnlabel' => '',    //CTA button title
            'vh_target' => '',      //open link in new window or not
            'vh_rel' => '',         //make aff link nofollow or noindex
            'vh_tnctext' => '',     //text for tnc link
            'vh_tnclink' => '',     //tnc external link url
            'vh_tncinfo' => '',     //tnc more info for toggle
            'vh_reviewtext' => '',  //text for full review link
            'vh_reviewlink' => '',  //full review link url
            'vh_rating' => ''       //rating stars shortcode
        ), $atts ) );

        if ( $vh_pc == '1' ) { $vh_pc = '<div class="results-desktop">Desktop</div>'; }
        if ( $vh_tablet == '1' ) { $vh_tablet = '<div class="results-tablet">Tablet</div>'; }
        if ( $vh_mobile == '1' ) { $vh_mobile = '<div class="results-mobile">Mobile</div>'; }
        if ( $vh_target == 'new' ) { $vh_target = '_blank'; } else { $vh_target = '_self'; }
        $vh_imgalt = basename($vh_img);
        $vh_rating_output = do_shortcode("[wp-review-total id=$vh_rating]");

        $vhoutput = "<tr><td class=\"vh-casino\"><a target=\"";
        $vhoutput .= $vh_target;
        $vhoutput .= "\" ";

        if ($vh_rel != '') {
        $vhoutput .= "rel=\"";
        $vhoutput .= $vh_rel;
        $vhoutput .= "\" ";
        }

        $vhoutput .= "href=\"";
        $vhoutput .= $vh_link;
        $vhoutput .= "\"><img src=\"";
        $vhoutput .= $vh_img;
        $vhoutput .= "\" alt=\"";
        $vhoutput .= $vh_imgalt;
        $vhoutput .= "\" width=\"180px\"></a></td>";
        $vhoutput .= "<td class=\"vh-bonus\">";
        $vhoutput .= $vh_bonus;
        
        if ($vh_tnctext != '') {
        $vhoutput .= "<span><a href=\"";
        $vhoutput .= $vh_tnclink;
        $vhoutput .= "\" target=\"_blank\">";
        $vhoutput .= $vh_tnctext;
        $vhoutput .= "</a><i class=\"terms-info\" title=\"";
        $vhoutput .= $vh_tncinfo;
        $vhoutput .= "\"></i></span>";
        }
        
        $vhoutput .= "</td>";
        $vhoutput .= "<td class=\"vh-devices\"><span class=\"device-icons\">";
        $vhoutput .= $vh_pc . $vh_tablet . $vh_mobile;
        $vhoutput .= "</span>";

        if (($vh_rating != '') && ($vh_pc == '') && ($vh_tablet == '') && ($vh_mobile == '')) {
        $vhoutput .= $vh_rating_output;
        }

        $vhoutput .= "</td>";
        $vhoutput .= "<td class=\"vh-cta-buttons\"><a target=\"";
        $vhoutput .= $vh_target;
        $vhoutput .= "\" ";

        if ($vh_rel != '') {
        $vhoutput .= "rel=\"";
        $vhoutput .= $vh_rel;
        $vhoutput .= "\" ";
        }

        $vhoutput .= "href=\"";
        $vhoutput .= $vh_link;
        $vhoutput .= "\" class=\"vh-playnow\">";
        $vhoutput .= $vh_btnlabel;
        $vhoutput .= "</a>";

        if ($vh_reviewtext != '') {
        $vhoutput .= "<span><a class=\"reviewlink\" href=\"";
        $vhoutput .= $vh_reviewlink;
        $vhoutput .= "\">";
        $vhoutput .= $vh_reviewtext;
        $vhoutput .= "</a></span>";
        }

        $vhoutput .= "</td></tr>";

        // ob_start();  


        // $output_string = ob_get_contents();  
        // ob_end_clean();  

        return $vhoutput;
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


//setting next pagination link class
add_filter("next_posts_link_attributes", "next_posts_link_class");
function next_posts_link_class() {
    return "class='next page-numbers'";
}
//setting prev pagination link class
add_filter("previous_posts_link_attributes", "prev_posts_link_class");
function prev_posts_link_class() {
    return "class='prev page-numbers'";
}

function add_terms_toggle() {
    wp_register_script('vegashero_termstoggle', sprintf("%stemplates/js/terms_toggle.js", plugin_dir_url(__FILE__)), null, true);
    wp_enqueue_script('vegashero_termstoggle', '', array('jquery'), null, true);
};
add_action( 'wp_enqueue_scripts', 'add_terms_toggle' );  
