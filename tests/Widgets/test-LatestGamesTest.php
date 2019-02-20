<?php

final class LatestGamesTest extends WP_UnitTestCase
{

    private $config;

    public function setUp() {
        parent::setUp();
        $this->config = \VegasHero\Config::getInstance();
        $this->args = array(
            'before_title'  => '<h2>',
            'after_title'   => "</h2>\n",
            'before_widget' => '<section>',
            'after_widget'  => "</section>\n",
        );
        $this->instance = array(
            'classname' => 'Widget_vh_recent_games',
            'description' => 'Display games with thumbnails from the VegasHero Plugin.',
            'title' => 'Latest Casino Games',
            'maxgames' => 5,
            'orderby' => 'date'
        );
    }

    public function testLatestGamesWithNoGames() 
    {
        $widget = new VegasHero\Widgets\LatestGames();
        ob_start();
        $widget->widget($this->args, $this->instance);
        $output = ob_get_clean();
        $pattern = "/No games to display/";
        $this->assertEquals(preg_match($pattern, $output), 1);
    }

    public function testLatestGamesWithGames() 
    {
        $game_count = 3;
        $this->faker = \Faker\Factory::create();
        $this->provider = $this->faker->firstname;
        $this->games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1, "provider" => $this->provider), $game_count);
        $this->posts = VegasHero\Helpers\Test::importGames(json_encode($this->games), new VegasHero\Import\Provider(), $this->config);
        $widget = new VegasHero\Widgets\LatestGames();
        ob_start();
        $widget->widget($this->args, $this->instance);
        $output = ob_get_clean();
        //echo $output;
        $pattern = '/<li class="vh-games-widget-item vh_recent_games_.*"><a href=".*" title=".*" class="vh_recent_games_item_.*" ><img alt=".*" src="\/\/cdn\.vegasgod\.com\/.*\/.*\/cover.jpg"\/><h3>.*<\/h3><\/a><\/li>/';
        preg_match_all($pattern, $output, $matches);
        $this->assertEquals(count($matches[0]), $game_count);
    }



}
