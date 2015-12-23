
      <div class="wrap about-wrap">
        <h1>Import games by provider</h1>
        <div class="about-text">
			  Install a whole ton of games in an instant, add your affiliate codes from multiple operators.
        </div>
        <!-- <div class="vh-badge">Version 1.0</div> -->
        <hr>
        <h3>Providers available to install</h3>
        <ul class="operator-cards">
        <?php
          foreach($this->_providers as $provider) {
              echo '<li>';
              echo '<div class="desc">';
              echo '<form method="post" action="options.php">';
              settings_fields($this->_getOptionGroup($provider));
              $page = $this->_getPageName($provider);
              do_settings_sections($page);
              echo '<div class="btn-area">';
              echo $this->_getUpdateBtn($provider);
              echo '<div class="provider-img"><img src="http://cdn.vegasgod.com/providers/' . $provider . '.png" /></div>';
              echo '</div></div>';
              echo '</form>';
              echo '</li>';
          }
        ?>
        </ul>

      </div>
