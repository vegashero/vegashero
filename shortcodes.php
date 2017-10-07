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
        if(array_key_exists('provider', $atts)) {
            return VegasHero\ShortCodes\GamesGrid::render($atts, $this->_config);
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




