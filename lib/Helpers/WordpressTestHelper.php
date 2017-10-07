<?php
declare(strict_types=1);

namespace Vegashero\Helpers;

final class WordpressTestHelper
{

    static public function importGames() {
    }

    /**
     * Reset Wordpress database
     * @return boolean
     */
    static public function resetDatabase() {
        $command = "wp db reset --yes";
        exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

    /*
     * Enable a plugin
     * @param string $plugin_name
     * @return boolean
     */
    static public function enablePlugin(string $plugin_name) {
        $command = sprintf("wp plugin activate %s", $plugin_name);
        exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

    /**
     * Check if a plugin is installed
     * @param string $plugin_name
     * @return boolean
     */
    static public function isPluginInstalled(string $plugin_name) {
        $command = sprintf("wp plugin is-installed %s", $plugin_name);
        exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

    /**
     * @return boolean Exit code of 0 means success. 
     */
    static public function isWordpressInstalled() {
        $command = "wp core is-installed";
        exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

    /**
     * @param string $plugin_name
     * @return boolean
     */
    static public function removePlugin(string $plugin_name) {
        $command = sprintf("wp plugin uninstall %s --deactivate", $plugin_name);
        exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

    /**
     * @param string $plugin_name
     * @return boolean
     */
    static public function addPlugin(string $plugin_name) {
        $command = sprintf("wp plugin install %s --activate", $plugin_name);
        exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

    /**
     * wp import example.wordpress.2016-06-21.xml --authors=create
     */
    static public function importFixture(string $absolute_filepath) {
        self::addPlugin('wordpress-importer');
        $command = sprintf("wp import %s --authors=create", $absolute_filepath);
        exec($command, $output, $exit_code);
        self::removePlugin('wordpress-importer');
        return $exit_code ? false : true;
    }

    /**
     * wp config create --dbname=testing --dbuser=wp --dbpass=securepswd --locale=ro_RO
     */
    static public function createConfigFile() {
    }

    /**
     * Install Wordpress
     * @param string $url
     * @param string $title
     * @param string $admin_user
     * @param string $admin_password
     * @param string $admin_email
     * @return boolean
     */
    static public function installWordpress(string $url, string $title, string $admin_user, string $admin_password, string $admin_email) {
        $command = sprintf("wp core install --url=%s --title=%s --admin_user=%s --admin_password=%s --admin_email=%s --skip-email", $url, $title, $admin_user, $admin_password, $admin_email);
        $output = exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

}

