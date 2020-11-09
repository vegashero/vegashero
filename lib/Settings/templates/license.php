<div class="wrap vh-about-wrap">

    <form method="post" action="options.php">
    <?php settings_fields(\VegasHero\Settings\License::MENU_SLUG); //outputs boilerplate hidden fields ?> 
    <?php do_settings_sections(\VegasHero\Settings\License::PAGE_SLUG);?>
    <?php submit_button(); ?>
    </form>

    <div class="helpinfo">
        <h3 style="margin-top:0;"><?= wp_strip_all_tags(__('Check out our WordPress Themes', 'vegashero')) ?></h3>
        <p>
            <?php /* translators: %1$s will be replaced by URL where Casino Theme can be downloaded. %2$s will be replaced by URL where Sportsbook Theme can be downloaded. */ echo wp_kses(sprintf(__('We also offer stylish and functional WP themes that integrate well with our games plugin. Choose from a <a target="_blank" href="%1$s">Casino Theme</a> or a <a target="_blank" href="%2$s">Sportsbook Theme</a>.', 'vegashero'), esc_url('https://vegashero.co/downloads/vegashero-theme/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page'), esc_url('https://vegashero.co/downloads/sports-betting-theme/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page')), ["a" => ["target" => true, "href" => true]]) ?>
            <br/>
            <?php /* translators: %1$s will be replaced by URL where plugin and theme combo can be purchased */ echo wp_kses(sprintf(__('You can also get the <a target="_blank" href="%1$s">plugin + theme in a bundle license</a> and save money.', 'vegashero'), esc_url('https://vegashero.co/checkout?edd_action=add_to_cart&download_id=132994&edd_options[price_id]=1')), ["a" => ["target" => true, "href" => true]]) ?>
        </p>
    </div>

    <div class="helpinfo">
        <h3 style="margin-top:0;"><?= wp_strip_all_tags(__('Follow us for latest game release & feature updates!', 'vegashero')) ?></h3>
        <p>
            <?php /* translators: %1$s will be replaced by URL of the Vegas Hero Facebook page. %2$s will be replaced by URL of the Vegas Hero Twitter feed. */ echo wp_kses(sprintf(__('Check us out on <a target="_blank" href="%1$s">Facebook</a> and follow our <a target="_blank" href="%2$s">Twitter</a> feed. We post about latest version updates, industry news and events and game release info.', 'vegashero'), esc_url('https://www.facebook.com/VegasHeroPlugin/'), esc_url('https://twitter.com/Vegas_Hero">Twitter')), ["a" => ["href" => true, "target" => true]]) ?>
            <br/>
            <?= wp_strip_all_tags(__('Connect with us and share your journey of being/becoming an igaming affiliate. We woud love to hear your story and check out your website.')) ?>
        </p>

        <h3><?= wp_strip_all_tags(__('Need support?', 'vegashero')) ?></h3>
        <p>
            <?php /* translators: %1$s will be replaced by an email link. %2$s will be replaced by the Vegas Hero support email address. */ echo wp_kses(sprintf(__('Email us: <a href="%1$s">%2$s</a>', 'vegashero'), antispambot('mailto:support@vegashero.co'), antispambot('support@vegashero.co')), ["a" => ["href" => true]]) ?>
        </p>

        <p>
            <?php /* translators: %1$s will be replaced by URL of frequently asked questions about the Vegas Hero WordPress plugin */ echo wp_kses(sprintf(__('See the <a target="_blank" href="%1$s">FAQs</a>.', 'vegashero'), esc_url('https://vegashero.co/faq/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page')), ["a" => ["target" => true, "href" => true]]) ?>
        </p>

        <p>
            <?php /* translators: %1$s will be replaced by URL of Vegas Hero help and support page. */ echo wp_kses(sprintf(__('Browse the <a target="_blank" href="%1$s">support section</a> of our website.', 'vegashero'), esc_url('https://vegashero.co/category/support/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page')), ["a" => ["target" => true, "href" => true]]) ?> 
        </p>

        <p>
            <?= wp_kses(sprintf(__('Check the <a target="_blank" href="">latest game release</a> posts.', 'vegashero'), esc_url('https://vegashero.co/category/game-releases/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page')), ["a" => ["target" => true, "href" => true]]) ?></p>

        <div style="font-size:11px; margin-top:25px;">
        <strong><?= wp_strip_all_tags(__('Notice about game assets', 'vegashero')) ?></strong>
        <p style="font-size:11px;"><?= wp_strip_all_tags(__('The VegasHero Plugin sources games and game images from external sources. Although regular quality checks are carried out to safeguard the reliability of the games and image assets used by the plugin neither VegasHero.co nor its contracting partners can give any explicit or implicit assurance or warranty (including to third parties) in respect of the accuracy, reliability or completeness of the games and their assets. Casino games operated by third parties are provided "as is" without warranty of any kind. VegasHero accepts no responsibility and gives no guarantee to the effect that the functions of VegasHero Plugin will not be interrupted.', 'vegashero')) ?></p>
        </div>

    </div>

<div class="clear"></div>

<iframe class="licensing-admin-iframe-bottom" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/licensing-admin-bottom.php"></iframe>

</div>
