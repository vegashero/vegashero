<?php

class Vegashero_Settings_Lobby
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

    public function registerSettings() {
        // lobby settings
        add_settings_section('vegashero_lobbySettings_section', __( 'Lobby Settings', 'vhero' ), '', 'lobbySettings');
        add_settings_field('vegashero_lobbyGamesPerPage', __( 'Number of games to show', 'vhero' ), array($this, 'vegashero_lobbyGamesPerPage_render'), 'lobbySettings', 'vegashero_lobbySettings_section');
        register_setting( 'lobbySettings', 'vegashero_settings' );
    }

    public function vegashero_lobbyGamesPerPage_render() { 
      $options = get_option( 'vegashero_settings' , 20 );   //default game count 20
      echo "<input type='text' name='vegashero_settings[vegashero_lobbyGamesPerPage]' value='".$options['vegashero_lobbyGamesPerPage']."'>";
      echo "<p class='description'>If your lobby is showing in 4 columns set the number of games to show to be multiples of 4. For example to display a 4x4 grid of games set the number to 16. Default value is 20.</p>";
    }
}
