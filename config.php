<?php

// TODO: make this class a singleton
class Vegashero_Config
{

    public function __construct() {
        $config = $this->parseIniFileExtended('config.ini');
        if(empty(getenv('VEGASHERO_ENV'))) {
            putenv('VEGASHERO_ENV=production');
        } 
        foreach($config[getenv('VEGASHERO_ENV')] as $key => $value) {
            $this->$key = $value;
        }
    }

    public function parseIniFileExtended($filename) {
        $p_ini = parse_ini_file($filename, true);
        $config = array();
        foreach($p_ini as $namespace => $properties){
            list($name, $extends) = explode(':', $namespace);
            $name = trim($name);
            $extends = trim($extends);
            // create namespace if necessary
            if(!isset($config[$name])) $config[$name] = array();
            // inherit base namespace
            if(isset($p_ini[$extends])){
                foreach($p_ini[$extends] as $prop => $val)
                    $config[$name][$prop] = $val;
            }
            // overwrite / set current namespace values
            foreach($properties as $prop => $val)
            $config[$name][$prop] = $val;
        }
        return $config;
    }

}

