<?php

if( !defined( 'ABSPATH') ) exit();

global $EssentialAsTheme;

$dir = plugin_dir_path(__FILE__).'../../../';

$validated = get_option('tp_eg_valid', 'false');
$username = get_option('tp_eg_username', '');
$api_key = get_option('tp_eg_api-key', '');
$code = get_option('tp_eg_code', '');
$latest_version = get_option('tp_eg_latest-version', Essential_Grid::VERSION);
if(version_compare($latest_version, Essential_Grid::VERSION, '>')){
	//new version exists
}else{
	//up to date
}
?>

<!--
THE INFO ABOUT EMBEDING OF THE SLIDER
-->
<div class="title_line" style="margin-top:45px"><div class="view_title"><?php _e("How To Use the Lobby Grid Builder", EG_TEXTDOMAIN)?></div></div>

<div style="border:1px solid #e5e5e5; padding:15px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background:#FFFFFF">
	<div class="revred" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-arrows-ccw"></i></div>
	<p><?php _e('From the <b>page and/or post editor</b> insert the shortcode from the sliders table', EG_TEXTDOMAIN)?></p>
	<p><?php _e('From the <b>widgets panel</b> drag the "Essential Grid" widget to the desired sidebar', EG_TEXTDOMAIN); ?></p>
</div>

<?php
if(!$EssentialAsTheme){
	?>

	<?php
}else{
	?>
	<div style="width:100%;height:50px"></div>
	<!-- INFORMATIONS -->
	<div class="title_line"><div class="view_title"><?php _e("Information", EG_TEXTDOMAIN)?></div></div>

	<div style="border:1px solid #e5e5e5; padding:15px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background:#FFFFFF">
		<div class="revgray" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-info-circled"></i></div>
		<p style="margin-top:5px; margin-bottom:5px;">
			<?php _e("Please note that this plugin came bundled with a theme. The use of the Essential Grid is limited to this theme only.<br>If you need support from the plugin author ThemePunch or you want to use Essential Grid with an other theme you will need an extra single license available at CodeCanyon.", EG_TEXTDOMAIN); ?>
		</p>
	</div>
	<?php
}
?>



<script type="text/javascript">
jQuery(document).ready(function(){

	jQuery('#tp-validation-box').click(function() {
		jQuery(this).css({cursor:"default"});
		if (jQuery('#rs-validation-wrapper').css('display')=="none") {
			jQuery('#tp-before-validation').hide();
			jQuery('#rs-validation-wrapper').slideDown(200);
		}
	})

	AdminEssentials.initUpdateRoutine();
	AdminEssentials.initNewsletterRoutine();

});
</script>
