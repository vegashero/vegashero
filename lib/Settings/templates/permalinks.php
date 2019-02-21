<div class="wrap vh-about-wrap">
	<?php if(get_option('vh_license_status') === 'valid'): ?>
	<div><!-- display this if valid license key entered --></div>
	<?php else: ?>
	<div class="purchase-banner">
	  <h3>Import 1800+ games</h3>
	  <a target="_blank" href="https://vegashero.co/downloads/vegas-hero-plugin/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=permalinks%20settings%20page">Purchase a license</a>
	</div>
	<?php endif ?>

    <form method="post" action="options.php">
    <?php settings_fields(\VegasHero\Settings\Permalinks::MENU_SLUG); //outputs boilerplate hidden fields ?> 
    <?php do_settings_sections(\VegasHero\Settings\Permalinks::PAGE_SLUG); //pass slug name of page ?>
    <?php submit_button(); ?>
    </form>

</div>
