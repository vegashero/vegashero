
      <div class="wrap about-wrap">
        <h1>Import games by operator</h1>
        <div class="about-text">
			  Install a whole ton of games in an instant, add your affiliate codes from multiple operators.
        </div>
        <!-- <div class="vh-badge">Version 1.0</div> -->
        <hr>
        <h3>Operators available to install</h3>
        <ul class="operator-cards">
        <?php
          foreach($this->_operators as $operator) {
              echo '<li>';
              echo '<div class="desc">';
              echo '<form method="post" action="options.php">';
              settings_fields($this->_getOptionGroup($operator));
              $page = $this->_getPageName($operator);
              do_settings_sections($page);
              echo '<div class="btn-area">';
              echo "<input type='submit' name='submit' class='button button-primary' value='Apply Link'>";
              echo $this->_getUpdateBtn($operator);
              echo '<div class="operator-img"><img src="http://cdn.vegasgod.com/operators/' . $operator . '.png" /></div>';
              echo '</div></div>';
              echo '</form>';
              echo '</li>';
          }
        ?>
        </ul>

        <div class="clear"></div>
        <h3>Lobby Setup</h3>
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
            <div class="clear"></div>
          </li>
        </ul>
      </div>
