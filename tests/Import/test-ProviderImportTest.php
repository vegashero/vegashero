<?php

/**
 * @covers Email
 */
final class ProviderImportTest extends WP_UnitTestCase
{

    public function setUp() {
        parent::setUp();
        $this->config = Vegashero_Config::getInstance();
        $this->provider_importer = new VegasHero\Import\Provider();
        $this->faker = \Faker\Factory::create();
    }

    /**
     * status 1: (nothing changes for this case) 
     * game is imported in all cases and post meta is updated
     */
    public function testImportsAddNewGamesWhenStatusOne() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $this->assertSame(count($games), count($posts));
    }


    /**
     * status 0 
     * if doesn't exist on customer's site: game is not imported 
     */
    public function testImportDontAddGamesWhenStatusZero() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 0));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $this->assertSame(0, count($posts));
    }

    /**
     * status 0 
     * if already exist on customer's site: status is left as is (do not change to draft)
     */
    public function testImportDontUpdatePostStatusOfExistingGamesWhenStatusZero() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $updated_games = array_map(function($game) {
            $game->status = 0;
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), $this->provider_importer, $this->config);
        $result = (array_search('draft', array_column($updated_posts, 'post_status')) !== FALSE);
        $this->assertSame($result, false);
        $this->assertSame(count($posts), count($updated_posts)); // no new games imported
    }

    /**
     * status 0 
     * if already exist on customer's site: post meta is updated 
     * post meta is postMetaGameSrc, postMetaGameTitle and postMetaGameImg
     */
    public function testImportUpdateMetaOfExistingGame() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $updated_games = array_map(function($game) {
            $game->src = $this->faker->url;
            $game->name =$this->faker->firstname;
            $game->modified = \VegasHero\Helpers\Test::getDateToday();
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), $this->provider_importer, $this->config);
        for($i=0; $i<count($updated_posts); $i++) {
            $this->assertSame($posts[$i]->post_title, $updated_posts[$i]->post_title);
            $this->assertSame($posts[$i]->post_name, $updated_posts[$i]->post_name);
            $this->assertSame($posts[$i]->meta->game_id, $updated_posts[$i]->meta->game_id);
            $this->assertFalse($posts[$i]->meta->game_src === $updated_posts[$i]->meta->game_src);
            $this->assertFalse($posts[$i]->meta->game_title === $updated_posts[$i]->meta->game_title);
            $this->assertFalse($posts[$i]->meta->game_img === $updated_posts[$i]->meta->game_img);
        }
    }

}
