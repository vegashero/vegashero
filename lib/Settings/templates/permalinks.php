<div class="wrap vh-about-wrap">
	<?php if(get_option('vh_license_status') === 'valid'): ?>
	<div><!-- display this if valid license key entered --></div>
	<?php else: ?>
    <div class="updated" style="display:block!important;">
        <h3 style="margin-top:0.5em;"><?= wp_strip_all_tags(__('Get a license key and add 3000+ games to your website!', 'vegashero')) ?></h3>
        <p class="description"><?= wp_kses(sprintf(__('The free version of the plugin will let you import 2 games per software provider. To get full access to the game database: <strong><a target="_blank" href="%1$s">purchase a license key here.</a></strong>', 'vegashero'), esc_url('https://vegashero.co/downloads/vegas-hero-plugin/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page')), ["a" => ["target" => true, "href" => true], "strong" => []]) ?></p>
    </div>
	<?php endif ?>

    <form method="post" action="options.php">
    <?php settings_fields(\VegasHero\Settings\Permalinks::MENU_SLUG); //outputs boilerplate hidden fields ?> 
    <?php do_settings_sections(\VegasHero\Settings\Permalinks::PAGE_SLUG); //pass slug name of page ?>
    <?php submit_button(); ?>
    </form>

</div>
