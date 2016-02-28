<div class="wrap about-wrap">

    <form method="post" action="options.php">
    <?php settings_fields('vh-lobby-page'); //outputs boilerplate hidden fields ?> 
    <?php do_settings_sections( 'vh-lobby-page' ); //pass slug name of page ?>
    <?php submit_button(); ?>
    </form>

</div>
