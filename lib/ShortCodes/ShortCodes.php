<?php

namespace VegasHero\ShortCodes;

use VegasHero\ShortCodes\{ SingleGame, GamesGrid };

use VegasHero\Config;

// VH Lobby shortcode
class ShortCodes
{

    public static function addShortCodes() {
        add_shortcode('vegashero-lobby', array(self::class, 'lobby'));
        add_shortcode('vh-lobby', array(self::class, 'lobby'));
        add_shortcode('vh_lobby', array(self::class, 'lobby'));
        add_shortcode( 'vh_table' , array(self::class, 'vh_table_func'));
        add_shortcode( 'vh_table_line' , array(self::class, 'vh_table_line_func'));
        add_shortcode( 'vh-grid', array(self::class, 'renderGamesGrid'));
        add_shortcode( 'vh-casino-grid', array(self::class, 'renderGamesGrid'));
        add_shortcode( 'vh_grid', array(self::class, 'renderGamesGrid'));
        add_shortcode('vh-game', array(self::class, 'renderSingleGame')); // tested
        add_shortcode('vh_game', array(self::class, 'renderSingleGame')); // tested
    }

    public static function addFilters() {
        // adds unique CSS class to lobby page for easy custom styling
        add_filter( 'body_class', array( self::class, 'addBodyClass' ));

        //setting next pagination link class
        add_filter("next_posts_link_attributes", array( self::class, 'next_posts_link_class' ));
        //setting prev pagination link class
        add_filter("previous_posts_link_attributes", array(self::class, 'prev_posts_link_class' ));
    }

    public static function addBodyClass( array $classes ): array {
        global $post;
        if( isset($post->post_content) && has_shortcode( $post->post_content, 'vegashero-lobby' ) ) {
            $classes[] = 'vh-lobby-page';
        }
        return $classes;
    }

    public static function next_posts_link_class(): string {
        return "class='next page-numbers' rel='next' ";
    }

    public static function prev_posts_link_class(): string {
        return "class='prev page-numbers' rel='prev' ";
    }


    /**
     * Method called by vh-game shortcode
     * @param array $atts
     * @return string
     */
    public static function renderSingleGame($atts) {
        if(array_key_exists('id', $atts)) {
            $game_id = (int)$atts['id'];
            $game = SingleGame::get_instance();
            return $game->render($game_id);
        }
    }

    /**
     * Method called by vh-grid showcode
     * @param array $atts
     * @return string
     */
    public static function renderGamesGrid($atts) {
        return GamesGrid::render($atts, Config::getInstance());
    }

	public static function lobby() {
        $config = Config::getInstance();
		$playnow_btn_value = get_option('vh_playnow_btn');
		if ($playnow_btn_value == '') {
			$playnow_btn_value = wp_strip_all_tags(__('Play Now', 'vegashero'));
		} else {
			$playnow_btn_value = get_option('vh_playnow_btn');
		}

        $lobby_img_format = 'cover.jpg';
        if(get_option('vh_lobbywebp') === 'on') {
            $lobby_img_format = 'cover.webp';
        } else {
            $lobby_img_format = 'cover.jpg';
        }
		
        wp_enqueue_script(array('jquery'));
		wp_register_script('jquery_debounce', plugins_url("vegashero/templates/js/jquery.ba-throttle-debounce.min.js"), null, true);
		wp_enqueue_script('jquery_debounce', '', array('jquery'), null, true);
		wp_enqueue_script('vegashero_lobby_script', plugins_url('vegashero/templates/js/lobby_search_filters.js'), array('jquery_debounce', 'wp-i18n'), null, true);
		wp_localize_script( 'vegashero_lobby_script', 'ajax_object',
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'site_url' => site_url(),
				'image_url' => $config->gameImageUrl,
				'playnow_btn_value' => $playnow_btn_value,
                'lobby_img_format' => $lobby_img_format,
				'vh_custom_post_type_url_slug' => get_option('vh_custom_post_type_url_slug')
			)
		);
        ob_start();
		$lobby_template_file = sprintf('%s/vegashero/templates/lobby.php', WP_PLUGIN_DIR);
		include_once $lobby_template_file;
        $lobby_template_file = ob_get_clean();
        return $lobby_template_file;
	}

    // VH Operators Table shortcode

    // [vh_table vh_tname="Table Title Here" vh_bonushead="Bonus Title" vh_devicehead="Devices Title"]
    //    [vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]
    //    [vh_table_line vh_img="http://url" vh_link="http://myafflinkhere.to/" vh_btnlabel="Play Now"]
    // [/vh_table]

    public static function vh_table_func($atts, $vhcontent = null){
        wp_register_script('vegashero_termstoggle', plugins_url("vegashero/templates/js/terms_toggle.js"), null, true);
        wp_enqueue_script('vegashero_termstoggle', '', array('jquery'), null, true);
        
        extract( shortcode_atts( array(
            'vh_tname' => '', //table title
            'vh_bonushead' => '', //bonus column title
            'vh_devicehead' => '', //device compatibility column title
        ), $atts ) );

        if ( $vh_bonushead == '' ) { $vh_bonushead = wp_strip_all_tags(__('Bonus', 'vegashero')); }
        if ( $vh_devicehead == '' ) { $vh_devicehead = wp_strip_all_tags(__('Compatible Devices', 'vegashero')); }

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

    public static function vh_table_line_func($atts){
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
        $vhoutput .= "\" width=\"180px\" height=\"90px\"></a></td>";
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

