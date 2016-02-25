<div class="wrap about-wrap">

	<h1>Welcome to Vegas Hero Games</h1>

    <div class="about-text">
    To get access to all games paste your license key below
    </div>

    <form method="post" action="options.php">
    <?php settings_fields('vegashero-dashboard'); //outputs boilerplate hidden fields ?> 
    <?php settings_fields($this->_getOptionGroup()); ?>
    <?php $page = $this->_getPageName();?>
    <?php do_settings_sections($page);?>

	<hr class="dash-divider" />

    <?php do_settings_sections( 'lobbySettings' );?>

	<hr class="dash-divider" />
    <?php do_settings_sections( 'vegashero-dashboard' ); //pass slug name of page ?>
    <?php submit_button(); ?>
    </form>

</div>
