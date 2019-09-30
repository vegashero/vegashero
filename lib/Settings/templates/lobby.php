<div class="wrap vh-about-wrap">
    <form method="post" action="options.php">
    <?php settings_fields(\VegasHero\Settings\Lobby::MENU_SLUG); //outputs boilerplate hidden fields ?> 
    <?php do_settings_sections(\VegasHero\Settings\Lobby::PAGE_SLUG); //pass slug name of page ?>
    <?php submit_button(); ?>
    </form>
<div class="clear"></div>

<h3><?= __('How to display the lobby?', 'vegashero') ?></h3>
<ul class="instructions">
    <li>
      <ul>
          <li><?= __('<b>1.</b> Create a new page*', 'vegashero') ?></li>
          <li><?= sprintf(__('<b>2.</b> Add in this shortcode %1$s[vegashero-lobby]%2$s', 'vegashero'), '<span style="background:#f3f3f3; padding:3px 8px;">', '</span>') ?></li>
          <li><?= __('<b>3.</b> You are all set!', 'vegashero') ?></li>
          <li><?= __('*In order to display the games grid correctly we recommend the use of a full-width page template. If your theme doesn\'t support a full-width page template or you insist on showing a sidebar next to the lobby we suggest that a minimum of 800px wide content area is recommended where the lobby shortcode is added.') ?></li>
      </ul>
      <div class="clear"></div>
    </li>
</ul>
<div class="clear"></div>
</div>
