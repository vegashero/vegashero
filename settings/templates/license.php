<div class="wrap about-wrap">

    <form method="post" action="options.php">
    <?php settings_fields('vh-license-page'); //outputs boilerplate hidden fields ?> 
    <?php do_settings_sections( 'vh-license-page' );?>
    <?php submit_button(); ?>
    </form>

</div>
