<?php

class Vegashero_Settings 
{

    public function __construct() {

        add_action('admin_init', array($this, 'registerSettings'));
        add_action('admin_menu', array($this, 'addOptionsPage'));

    }

    public function registerSettings() {
        $option_group = 'vegashero_settings_group';
        $option_name = 'vegashero_settings_name';
        $sanititze_callback = '';
        register_setting($option_group, $option_name, $sanitize_callback);

        $vegasgod = $this->_getVegasgod();
        $sites = $vegasgod->getSites();
        foreach($sites as $site) {
            add_settings_section('vegashero_settings_'.$site, $site.' Settings', array($this, 'getDescriptionForSiteSettings'), 'vegashero');
            $args = array($site);
            add_settings_field('vegashero_options_'.$site, 'Affiliate code', array($this, 'getOptionsInputBox'), 'vegashero', 'vegashero_settings_'.$site, $args);
        }
    }

    public function getDescriptionForSiteSettings() {
        echo "<p>Site specific settings description goes here</p>";
    }

    public function getOptionsInputBox($args) {
        $site = $args[0];
        $options = get_option('vegashero_options');
        echo "<input id='$args[0]' name='vegashero_options[]' size='40' type='text' value='' />";
    }

    // public function getOptionsSectionText() {
    //     echo "<p>Description for this section</p>";
    // }

    public function addOptionsPage() {
        add_options_page('Vegas Hero Options', 'Vegas Hero Options', 'manage_options', 'vegashero', array($this, 'getOptionsPage'));
    }

    public function getOptionsPage() {
        echo '<div class="wrap">';
        echo '<h2>Vegas Hero Settings</h2>';
        echo '<form method="post" action="options.php">';
        settings_fields('vegashero_settings_group');
        do_settings_sections('vegashero');
        submit_button('Save');
        echo '</form>';
        echo '</div>';

    }
}

