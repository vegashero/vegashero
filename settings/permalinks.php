<?php

class Vegashero_Settings_Permalinks
{

    private static $_config;
    private static $_instance;

    public static function getInstance() {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    private function __clone() {
    }

    protected function __construct() {
        static::$_config = Vegashero_Config::getInstance();
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function settingsFieldMarkup() {
        $args = func_get_args();
        $id = $args[0]['id'];
        include_once( dirname( __FILE__ ) . '/templates/permalinks-field.php' );
    }

    public function settingsSectionHeaderDescriptionMarkup() {
        $args = func_get_args();
        $id = $args[0]['id'];
        $title = $args[0]['title'];
        include_once( dirname( __FILE__ ) . '/templates/permalinks-section.php' );
    }

    public function registerSettings() {

        add_settings_section(
            $id = 'vegashero-permalink-section',
            $title = 'Permalink section',
            $callback = array($this, 'settingsSectionHeaderDescriptionMarkup'),
            $page = 'vegashero-dashboard'
        );

        add_settings_field( 
            $id = 'vegashero-permalink-field', 
            $title = 'Permalink field', 
            $callback = array($this, 'settingsFieldMarkup'), 
            $page = 'vegashero-dashboard', 
            $section = 'vegashero-permalink-section',
            $args = array(
                'id' => 'vegashero-permalink-field'
            )
        );

        register_setting(
            $option_group = 'permalinkSettings', 
            $option_name = 'vegashero_settings'
        );

        // lobby settings
        //add_settings_section('vegashero_permalink_section', 'Permalink Settings', '', 'permalinkSettings');
        //add_settings_field('vegashero_lobbyGamesPerPage', 'Number of games to show', array($this, 'vegashero_lobbyGamesPerPage_render'), 'permalinkSettings', 'vegashero_lobbySettings_section');
    }

    public function vegashero_lobbyGamesPerPage_render() { 
      $options = get_option( 'vegashero_settings' , 20 );   //default game count 20
      echo "<input type='text' name='vegashero_settings[vegashero_lobbyGamesPerPage]' value='".$options['vegashero_lobbyGamesPerPage']."'>";
      echo "<p class='description'>If your lobby is showing in 4 columns set the number of games to show to be multiples of 4. For example to display a 4x4 grid of games set the number to 16. Default value is 20.</p>";
    }


}

