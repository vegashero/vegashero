<?php if(get_option('vh_license_status') === 'valid'): ?>
	<div><!-- display this if valid license key entered --></div>
<?php else: ?>
<div class="updated" style="display:block!important;">
    <h3 style="margin-top:0.5em;"><?= __("Get a license key and add 1800+ games to your website!", "vegashero") ?></h3>
    <p class="description"><?= __("The free version of the plugin will let you import 2 games per software provider.", "vegashero") ?><?= __("To get full access to the game database", "vegashero")?>: <strong><a target="_blank" href="https://vegashero.co/downloads/vegas-hero-plugin/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page"><?= __("purchase a license key here.", "vegashero")?></a></strong></p>
</div>
<?php endif ?>

<p class="description"><?= __("Enter your license code to activate full import features and game updates.", "vegashero")?><br>
<?= __("Please see our", "vegashero") ?> <a target="_blank" href="https://vegashero.co/quick-start-guide/"><?= __("quick start guide", "vegashero") ?></a> <?= __("for detailed instructions.", "vegashero") ?></p>
