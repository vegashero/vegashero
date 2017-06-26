      <div class="wrap about-wrap">
        <?php if(get_option('vh_license_status') === 'valid'): ?>
        <div><!-- display this if valid license key entered --></div>
        <?php else: ?>
        <div class="purchase-banner">
          <h3>Import 1000+ games</h3>
          <a target="_blank" href="https://vegashero.co?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=providers%20import%20page">Purchase a license</a>
        </div>
        <?php endif ?>
        
        <h1 class="prov-pagetitle">Import by Game Provider</h1>

        <iframe class="prov-admin-iframe-top" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/providers-admin-top.php"></iframe>

        <h3>Import 1 at a time</h3>
        <?php if(isset($this->_providers) && count($this->_providers)): ?>
        <ul class="operator-cards">
        <?php foreach($this->_providers as $provider): ?>
              <li>
              <div class="desc">
              <div class="provider-img"><img src="<?=$this->_config->gameImageUrl?>/providers/<?=$provider['provider']?>.png" /></div>
              <form method="post" action="options.php">
              <?= settings_fields($this->_getOptionGroup($provider['provider'])); 
              $page = $this->_getPageName($provider['provider']);
              do_settings_sections($page); ?>
              <h2><?=$provider['provider']?></h2>
              <div class="btn-area">
              <?= $this->_getAjaxUpdateBtn($provider['provider']); ?>
              </div>
              <div class="btn-area">
              <?= $this->_getGameCount($provider['count']); ?>
              </div>
              </div>
              </form>
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
