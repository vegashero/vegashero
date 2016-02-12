
      <div class="wrap about-wrap">
        <div class="purchase-banner">
          <h3>Import 1000+ games</h3>
          <a href="http://vegashero.co">Purchase a license</a>
        </div>
        <h1>Import by Game Provider</h1>
        <br>
        <!-- <div class="vh-badge">Version 1.0</div> -->
        <hr>
        <h3>Import 1 at a time</h3>
        <ul class="operator-cards">
        <?php
          foreach($this->_providers as $provider) {
              echo '<li>';
              echo '<div class="desc">';
              echo '<div class="provider-img"><img src="http://cdn.vegasgod.com/providers/' . $provider . '.png" /></div>';
              echo '<form method="post" action="options.php">';
              settings_fields($this->_getOptionGroup($provider));
              $page = $this->_getPageName($provider);
              do_settings_sections($page);
              echo '<div class="btn-area">';
              echo $this->_getUpdateBtn($provider);
              echo '</div></div>';
              echo '</form>';
              echo '</li>';
          }
        ?>
        </ul>

      </div>
