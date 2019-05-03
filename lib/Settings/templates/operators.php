      <div class="wrap vh-about-wrap">
        <?php if(get_option('vh_license_status') === 'valid'): ?>
        <div><!-- display this if valid license key entered --></div>
        <?php else: ?>
        <div class="updated" style="display:block!important;">
            <h3 style="margin-top:0.5em;">Get a license key and add 1800+ games to your website!</h3>
            <p class="description">The free version of the plugin will let you import 2 games per software provider. To get full access to the game database: <strong><a target="_blank" href="https://vegashero.co/downloads/vegas-hero-plugin/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page">purchase a license key here.</a></strong></p>
        </div>
        <?php endif ?>

        <h1>Import by Casino Operator</h1>


        <p class="description">
            Imported games will be grouped by casino <u>operator</u> and game <u>category</u>.<br>
            Casino <i>operators</i> may share some of the same games, but rest assured, games will <u>not</u> be duplicated and instead can be associated with multiple casino <u>operators</u>.<br>
            Please see our <a target="_blank" href="https://vegashero.co/quick-start-guide/">quick start guide</a> for detailed instructions.</p>
        </p>
        <!-- sponsored add -->
        <iframe class="op-admin-iframe-top" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/operators-admin-top.php"></iframe>
        <!-- /sponsored add -->

        <?php if(isset($this->_operators) && count($this->_operators)): ?>
            <ul class="operator-cards">
            <?php foreach($this->_operators as $operator): ?>
                  <li class="op-<?=$operator['operator']?>">
                      <span class="op-ribbon">
                        <span class="op-ribbon-content">Featured</span>
                      </span>
                      <div class="desc">
                          <h2 class="operatorname"><?=$operator['operator']?></h2>
                          <div class="provider-img"><img src="<?=$this->_config->gameImageUrl?>/operators/<?=$operator['operator']?>.png" /></div>
                          <div class="btn-area">
                              <?= $this->_getAjaxUpdateBtn($operator['operator']); ?>
                          </div>
                          <div class="footer-area">
                              <?php if(get_option('vh_license_status') === 'valid'): ?>
                              <?= $this->_getGameTypeCheckboxes(sanitize_title($operator['operator']), $operator['html5'], $operator['flash']); ?>
                              <?php else: ?>
                              <?= $this->_getGameCount(sanitize_title($operator['operator']), $operator['total']); ?>
                              <?php endif ?>
                          </div>
                      </div>
                  </li>
            <?php endforeach ?>
            </ul>
        <?php else: ?>
            <p style="color:red">Unable to fetch a list of operators. Please try again by refreshing your page.</p>
        <?php endif ?>
        <div class="clear"></div>
        <hr>

        <iframe class="op-admin-iframe-bottom" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/operators-admin-bottom.php"></iframe>
        
      </div>
