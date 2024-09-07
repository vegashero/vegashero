<?php

namespace VegasHero;

class Config {

	private static $instance;
	private array $config = [];

	public static function getInstance(): self {
		if (null === static::$instance) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	protected function __construct() {
		$configData = $this->parseIniFileExtended('config.ini');
		$environment = getenv('VEGASHERO_ENV') ?: 'production';

		if (isset($configData[$environment])) {
			$this->config = $configData[$environment];
		}
	}

	private function __clone() {
		// Prevent cloning
	}

	public function __get(string $name) {
		return $this->config[$name] ?? null;
	}

	public function __set(string $name, $value): void {
		$this->config[$name] = $value;
	}

	public function parseIniFileExtended(string $filename): array {
		$p_ini = parse_ini_string(
			file_get_contents(
				sprintf('%s%s', plugin_dir_path(__FILE__), $filename)
			),
			true
		);
		$config = [];

		foreach ($p_ini as $namespace => $properties) {
			$name = $namespace;
			$extends = '';

			if (strpos($namespace, ':') !== false) {
				list($name, $extends) = explode(':', $namespace);
				$name = trim($name);
				$extends = trim($extends);
			}

			// Create namespace if necessary
			if (!isset($config[$name])) {
				$config[$name] = [];
			}

			// Inherit base namespace
			if ($extends && isset($p_ini[$extends])) {
				foreach ($p_ini[$extends] as $prop => $val) {
					$config[$name][$prop] = $val;
				}
			}

			// Overwrite / set current namespace values
			foreach ($properties as $prop => $val) {
				$config[$name][$prop] = $val;
			}
		}

		return $config;
	}
}
