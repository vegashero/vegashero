
      <div class="wrap about-wrap">
        <?php if(get_option('vh_license_status') === 'valid'): ?>
        <div><!-- display this if valid license key entered --></div>
        <?php else: ?>
        <div class="purchase-banner">
          <h3>Import 1000+ games</h3>
          <a target="_blank" href="http://vegashero.co?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=operators%20import%20page">Purchase a license</a>
        </div>
        <?php endif ?>

        <h1>Import by Casino Operator</h1>
        <br>
        <!-- <div class="vh-badge">Version 1.0</div> -->
        <hr>
        <h3>Import game selections from the following operators:</h3>
        <ul class="operator-cards">
        <?php
          foreach($this->_operators as $operator) {
              echo '<li>';
              echo '<div class="desc">';
              //echo '<form method="post" action="options.php">';
              //settings_fields($this->_getOptionGroup($operator));
              //$page = $this->_getPageName($operator);
              //do_settings_sections($page);
              echo '<h2 class="operatorname">' . $operator['operator'] . '</h2>';
              echo '<div class="provider-img"><img src="http://cdn.vegasgod.com/operators/' . $operator['operator'] . '.png" /></div>';
              echo '<div class="btn-area">';
              //echo "<input type='submit' name='submit' class='button button-primary' value='Apply Link'>";
              echo $this->_getUpdateBtn($operator['operator']);
              //echo '<div class="operator-img"><img src="http://cdn.vegasgod.com/operators/' . $operator . '.png" /></div>';
              echo '</div>';
              echo '<div class="btn-area">';
              //echo "<input type='submit' name='submit' class='button button-primary' value='Apply Link'>";
              echo $this->_getGameCount($operator['count']);
              //echo '<div class="operator-img"><img src="http://cdn.vegasgod.com/operators/' . $operator . '.png" /></div>';
              echo '</div>';
              echo '</div>';
              //echo '</form>';
              echo '</li>';
          }
        ?>
        </ul>

        <div class="clear"></div>
          <!-- <h3>Lobby Setup</h3>
          <ul class="instructions">
            <li>
              <ul>
                <li><b>1.</b> Add your affiliate code</li>
                <li><b>2.</b> Click "Apply Link"</li>
                <li><b>3.</b> Then click "Import games"</li>
                <li><b>4.</b> Create a new page</li>
                <li><b>5.</b> Add in this shortcode <span style="background:#f3f3f3; padding:3px 8px;">[vegashero-lobby]</span> </li>
              </ul>
              <div class="clear"></div>
            </li>

          </ul>
            <div class="clear"></div> -->
          </li>
        </ul>
      </div>
