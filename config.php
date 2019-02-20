<?php

namespace VegasHero;

class Config
{

    private static $instance;

	 public static function getInstance() {
        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            if (null === static::$instance) {
                static::$instance = new static();
            }
            return static::$instance;
        } else {
            if (null === self::$_instance) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
    }

    protected function __construct() {
        $config = $this->parseIniFileExtended('config.ini');
        $environment = getenv('VEGASHERO_ENV') ? getenv('VEGASHERO_ENV') : 'production';
        foreach($config[$environment] as $key => $value) {
            $this->$key = $value;
        }
    }   

    private function __clone() {
    }

    public function parseIniFileExtended($filename) {
        $p_ini = parse_ini_file($filename, true);
        $config = array();
        foreach($p_ini as $namespace => $properties){
            @list($name, $extends) = explode(':', $namespace);
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
            foreach($properties as $prop => $val) {
                $config[$name][$prop] = $val;
            }
        }
        return $config;
    }

}

