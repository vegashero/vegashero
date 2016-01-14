<div class="wrap about-wrap">
<h1>Welcome to Vegas Hero Games</h1>
<div class="about-text">
  Install a whole ton of games in an instant, add your affiliate codes from multiple operators.
</div>
<p>To get access to all games paste your license key below</p>
<label for="vegashero-license">License key</label>

 <form method="post" action="options.php">
<?php 
settings_fields($this->_getOptionGroup());
$page = $this->_getPageName();
do_settings_sections($page); 
?>
<input type='submit' name='submit' class='button button-primary' value='Save License'>
</form>

<p>Don't have a license key? You can still import a sample of games or <a href="#">purchase your key now</a></p>
</div>
