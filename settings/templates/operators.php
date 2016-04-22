
      <div class="wrap about-wrap">
        <?php if(get_option('vh_license_status') === 'valid'): ?>
        <div><!-- display this if valid license key entered --></div>
        <?php else: ?>
        <div class="purchase-banner">
          <h3>Import 1000+ games</h3>
          <a target="_blank" href="http://vegashero.co?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=operators%20import%20page">Purchase a license</a>
        </div>
        <?php endif ?>

        <h1 class="op-pagetitle">Import by Casino Operator</h1>

        <iframe class="op-admin-iframe-top" frameborder="0" scrolling="no" src="http://vegasgod.com/iframes/operators-admin-top.php"></iframe>

        <h3>Import game selections from the following operators:</h3>
        <p>Some operators may share the same game selection. Importing games from multiple operators won't duplicate games. It will result multiple operators assigned to a game that is featured those operators.</p>
        <?php if(isset($this->_operators) && count($this->_operators)): ?>
            <ul class="operator-cards">
            <?php foreach($this->_operators as $operator): ?>
                  <li class="op-<?=$operator['operator']?>">
                  <span class="op-ribbon">
                    <span class="op-ribbon-content">Featured</span>
                  </span>
                  <div class="desc">
                  <h2 class="operatorname"><?=$operator['operator']?></h2>
                  <div class="provider-img"><img src="http://cdn.vegasgod.com/operators/<?=$operator['operator']?>.png" /></div>
                  <div class="btn-area">
                  <?= $this->_getUpdateBtn($operator['operator']);?>
                  </div>
                  <div class="btn-area">
                  <?= $this->_getGameCount($operator['count']); ?>
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

        <iframe class="op-admin-iframe-bottom" frameborder="0" scrolling="no" src="http://vegasgod.com/iframes/operators-admin-bottom.php"></iframe>
        
      </div>
