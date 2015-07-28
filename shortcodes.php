<?php

// VH Lobby shortcode
class Vegashero_Shortcodes
{

    private $_config;

    public function __construct() {
        $this->_config = new Vegashero_Config();
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

    $vhoutput = "<table class=\"vh-casino-providers\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><thead><tr><th width=\"40%\">";
    $vhoutput .= $vh_tname;
    $vhoutput .= "</th><th width=\"70%\">&nbsp;</th></tr></thead><tbody>";
    $vhcontent = str_replace('<br />', '', $vhcontent);
    $vhoutput .= do_shortcode($vhcontent);
    $vhoutput .= "</tbody>";
    $vhoutput .= "</table>";
    return $vhoutput;
}

function vh_table_line_func($atts){
    extract( shortcode_atts( array(
        'vh_img' => '',         //thumb img URL path
        'vh_link' => '',        //Affiliate link URL
        'vh_btnlabel' => ''     //CTA button title
    ), $atts ) );

    $vhoutput = "<tr><td class=\"vh-casino\"><a href=\"";
    $vhoutput .= $vh_link;
    $vhoutput .= "\"><img src=\"";
    $vhoutput .= $vh_img;
    $vhoutput .= "\" width=\"180px\"></a></td>";
    $vhoutput .= "<td><a href=\"";
    $vhoutput .= $vh_link;
    $vhoutput .= "\" class=\"vh-playnow\">";
    $vhoutput .= $vh_btnlabel;
    $vhoutput .= "</a></td></tr>";
    return $vhoutput;
}

add_shortcode( 'vh_table' , 'vh_table_func' );
add_shortcode( 'vh_table_line' , 'vh_table_line_func' );