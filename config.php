<?php

class Vegashero_Config
{

    public function __construct() {
        $config = parse_ini_file('config.ini');
        foreach($config as $key => $value) {
            $this->$key = $value;
        }
    }

}

