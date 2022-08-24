<?php
declare(strict_types=1);

namespace VegasHero\Helpers;

final class Test 
{

    /**
     * @param string $json_file
     * @param string $absolute_path No trailing slash
     * @return string A JSON array
     */
    static public function getFixture($json_file, $absolute_path = '') {
        if( ! $absolute_path) {
            $absolute_path = dirname(__FILE__);
        }
        return file_get_contents(sprintf("%s/%s", $absolute_path, $json_file));
    }

    /**
     * @param object $faker
     * @param integer $how_many
     * @param array $overwrite Game attributes to overwrite
     * @return array A list of games
     */
    static public function generateRandomGames($faker, $overwrite, $how_many=10) {
        $games = array();
        for($i=0; $i<$how_many; $i++) {
            array_push($games, self::generateRandomGame($faker, $overwrite));
        }
        return $games;
    }

    /**
     * Generates a random single game 
     * @param object $faker 
     * @param array $overwrite Game attributes to overwrite
     * @return array A single game 
     */
    static public function generateRandomGame($faker, $overwrite=array()) {
        return (object)array_merge(
            [
                "id" => (string)$faker->numberBetween(),
                "name" => $faker->firstname,
                "provider" => $faker->firstname,
                "category" => $faker->words(2, true),
                "src" => $faker->url,
                "status" => (string)$faker->numberBetween(0, 1),
                "type" => (string)$faker->numberBetween(0, 1),
                "energycasino" => (string)$faker->numberBetween(0, 1),
                "mrgreen" => (string)$faker->numberBetween(0, 1),
                "slotsmillion" => (string)$faker->numberBetween(0, 1),
                "europa" => (string)$faker->numberBetween(0, 1),
                "slotslv" => (string)$faker->numberBetween(0, 1),
                "winner" => (string)$faker->numberBetween(0, 1),
                "bet365" => (string)$faker->numberBetween(0, 1),
                "williamhill" => (string)$faker->numberBetween(0, 1),
                "intercasino" => (string)$faker->numberBetween(0, 1),
                "videoslots" => (string)$faker->numberBetween(0, 1),
                "bellfruit" => (string)$faker->numberBetween(0, 1),
                "created" => $faker->iso8601($max = 'now'),
                "modified" => $faker->iso8601($max = 'now')
            ],
            $overwrite
        );
    }

    /**
     * @param string $games JSON array of games 
     * @param importer 
     * @param $config
     * @param operator 
     * @return array Imported games
     */
    static public function importGames($games, $importer, $config, $params = []) {
        $args = array(
            'posts_per_page' => -1,
            'post_type' => $config->customPostType,
            'post_status' => 'any',
            'orderby' => 'ID'
        );
        $mock_request = \Mockery::mock('WP_REST_Request');
        foreach($params as $name => $value) {
            $mock_request->shouldReceive('get_param')->with($name)->andReturn($value);
        }
        $mock_request->shouldReceive('get_body')->andReturn($games);
        $importer->importGames($mock_request);
        $posts = get_posts($args);
        return array_map(function($post) {
            $post->meta = (object)get_post_meta($post->ID);
            return $post;
        }, $posts);
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
    static public function isPluginInstalled(string $plugin_name, string $path = '/var/www/html') {
        $command = sprintf("wp plugin is-installed %s --path=$path", $plugin_name);
        exec($command, $output, $exit_code);
        return $exit_code ? false : true;
    }

    /**
     * @return boolean Exit code of 0 means success. 
     */
    static public function isWordpressInstalled(string $path = '/var/www/html') {
        $command = "wp core is-installed --path=$path";
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

    static public function getDateYesterday() {
        $date = new \DateTime();
        $date->add(\DateInterval::createFromDateString('yesterday'));
        return $date->format('Y-m-d\TH:i:s.uP');
    }

    static public function getDateToday() {
        $date = new \DateTime();
        $date->add(\DateInterval::createFromDateString('today'));
        return $date->format('Y-m-d\TH:i:s.uP');
    }


}

