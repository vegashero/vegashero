<?php

  add_action('admin_menu', 'addOperatorTableMenu');

  function addOperatorTableMenu() {
      add_submenu_page( 'vegashero-dashboard', 'Operators', 'Operator Table', 'manage_options', 'operator-table', 'operator_table_page' );
      //add_menu_page('op_vegashero', 'Operator Table', 'manage_options', 'vegashero-operator', 'operatorTablePage');
  }

  function custom_operator_html() {
    echo "<h1>asdasda</h1>";
    ?>
    <textarea name="custom_operator" value="<?php echo get_option('custom_operator'); ?>" placeholder="[vh_table vh_tname='Table Title Here'] Your Custom Table Rows Here... [/vh_table]"></textarea>
    <input type='submit' name='shortcodeSubmit' class='button button-primary' value=' <?php _e( 'Save Shortcode Settings', 'vegash' ); ?>'>
    <?php
  }

  // function custom_operator_init() {
  //
  //   add_settings_section('section', 'Operator Shortcode Settings', null, "theme-options");
  //   add_settings_field('custom_operator', 'Operator Shortcode', 'custom_operator_html', 'vegashero-dashboard', 'section');
  //   register_setting('vh_operator', 'custom_operator');
  //
  // }
  // add_action('admin_init', 'custom_operator_init');

  function operator_table_page() {

  ?>
    <div class="wrap vh-about-wrap">
      <h1>Add your own Casino Operators</h1>
      <div class="about-text">
      The Casino Operators will be added in a table below each game
      </div>
      <ul class="operator-cards">
        <li>

        </li>
      </ul>

    </div>
  <?php

    //<textarea class="'operator-shortcode-settings'" name="'operator_shortcode_override'" placeholder="[vh_table vh_tname='Table Title Here'] Your Custom Table Rows Here... [/vh_table]"></textarea>
    // <input type='submit' name='shortcodeSubmit' class='button button-primary' value='Save Shortcode Settings'>
  }
  add_action( 'admin_init', 'operator_table_page' );

?>
