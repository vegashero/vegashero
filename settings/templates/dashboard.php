<div class="wrap about-wrap">

	<h1>Welcome to Vegas Hero Games</h1>

	<div class="license-settings">
		<div class="about-text">
		To get access to all games paste your license key below
		</div>

		<form method="post" action="options.php">
		<?php
		settings_fields($this->_getOptionGroup());
		$page = $this->_getPageName();
		do_settings_sections($page);
		?>
		<input type='submit' name='submit' class='button button-primary' value='Save License'>
		</form>

		<p>Don't have a license key? You can still import a sample of games or <a href="http://vegashero.co">purchase your key now</a></p>
		<div class="purchase-banner">
		  <h3>Import 1000+ games</h3>
		  <a href="http://vegashero.co">Purchase a license</a>
		</div>
	</div>

	<hr class="dash-divider" />

	<div class="lobby-settings">
		<form action='options.php' method='post'>
		<?php
		settings_fields( 'lobbySettings' );
		do_settings_sections( 'lobbySettings' );
		submit_button();
		?>  
		</form>
	</div>

	<hr class="dash-divider" />

    <div class="permalink-settings">
        Permalink settings go here
    </div>

</div>
