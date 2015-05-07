<?php

class Vegashero_Shortcodes
{

    private $_config;

    public function __construct() {
        $this->_config = new Vegashero_Config();
        add_shortcode('vegashero-lobby', array($this, 'lobby'));
    }

    public function lobby() {
        $lobby_template_file = sprintf('%s/templates/archive-%s.php', dirname(__FILE__), $this->_config->customPostType);
        // return file_get_contents($lobby_template_file);
        include_once $lobby_template_file;
    }

}
