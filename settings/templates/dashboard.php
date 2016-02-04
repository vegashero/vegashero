<div class="wrap about-wrap">
<h1>Welcome to Vegas Hero Games</h1>
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

<p>Don't have a license key? You can still import a sample of games or <a href="#">purchase your key now</a></p>
</div>
