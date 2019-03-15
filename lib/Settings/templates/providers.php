      <div class="wrap vh-about-wrap">
        <?php if(get_option('vh_license_status') === 'valid'): ?>
        <div><!-- display this if valid license key entered --></div>
        <?php else: ?>
        <div class="updated" style="display:block!important;">
            <h3 style="margin-top:0.5em;">Get a license key and add 1800+ games to your website!</h3>
            <p class="description">The free version of the plugin will let you import 2 games per software provider. To get full access to the game database: <strong><a target="_blank" href="https://vegashero.co/downloads/vegas-hero-plugin/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page">purchase a license key here.</a></strong></p>
        </div>
        <?php endif ?>
        
        <h1>Import by Game Provider</h1>

        <p class="description">Imported games will be grouped by game <u>provider</u> and <u>category</u>.<br>
        Please see our <a target="_blank" href="https://vegashero.co/quick-start-guide/">quick start guide</a> for detailed instructions.</p>

        <!-- sponsored add -->
        <iframe class="prov-admin-iframe-top" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/providers-admin-top.php"></iframe>
        <!-- /sponsored add -->

        <?php if(isset($this->_providers) && count($this->_providers)): ?>
        <ul class="operator-cards">
        <?php foreach($this->_providers as $provider): ?>
              <li  class="prov-<?=$provider['provider']?>">
                  <div class="desc">
                      <div class="provider-img"><img src="<?=$this->_config->gameImageUrl?>/providers/<?=$provider['provider']?>.png" /></div>
                      <form method="post" action="options.php">
                          <?= settings_fields($this->_getOptionGroup($provider['provider'])); 
                          $page = $this->_getPageName($provider['provider']);
                          do_settings_sections($page); ?>
                          <h2><?=$provider['provider']?></h2>
                          <div class="btn-area">
                              <?= $this->_getAjaxUpdateBtn(sanitize_title($provider['provider'])); ?>
                              <?php if(get_option('vh_license_status') === 'valid'): ?>
                              <?= $this->_getGameTypeCheckboxes(sanitize_title($provider['provider']), $provider['html5'], $provider['flash']); ?>
                              <?php else: ?>
                              <?= $this->_getGameCount(sanitize_title($provider['provider']), $provider['total']); ?>
                              <?php endif ?>
                          </div>
                      </form>
                  </div>
              </li>
        <?php endforeach ?>
        </ul>
        <?php else: ?>
        <p style="color:red">Unable to fetch a list of providers. Please try again by refreshing your page.</p>
        <?php endif ?>
        <div class="clear"></div>
        <hr>

        <iframe class="prov-admin-iframe-bottom" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/providers-admin-bottom.php"></iframe>

      </div>
