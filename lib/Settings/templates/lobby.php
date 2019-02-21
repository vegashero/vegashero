<div class="wrap vh-about-wrap">

    <form method="post" action="options.php">
    <?php settings_fields(\VegasHero\Settings\Lobby::MENU_SLUG); //outputs boilerplate hidden fields ?> 
    <?php do_settings_sections(\VegasHero\Settings\Lobby::PAGE_SLUG); //pass slug name of page ?>

    <?php submit_button(); ?>
    </form>

<div class="clear"></div>

<h3>How to display the lobby?</h3>
<ul class="instructions">
<li>
  <ul>
    <li><b>1.</b> Create a new page*</li>
    <li><b>2.</b> Add in this shortcode <span style="background:#f3f3f3; padding:3px 8px;">[vegashero-lobby]</span> </li>
    <li><b>3.</b> You are all set!</li>
    <li>*In order to display the games grid correctly we recommend the use of a full-width page template. If your theme doesn't support a full-width page template or you insist on showing a sidebar next to the lobby we suggest that a minimum of 800px wide content area is recommended where the lobby shortcode is added.</li>
  </ul>
  <div class="clear"></div>
</li>

</ul>
<div class="clear"></div>

</div>
