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
     * if doesn't exists on customer's site: game is not imported 
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
        $result = (array_search('publish', array_column($updated_posts, 'post_status')) !== FALSE);
        $this->assertSame($result, false);
        $this->assertSame(count($posts), count($updated_posts));
    }

    /**
     * status 0 
     * if already exist on customer's site: post meta is updated if modify date is newer 
     * post meta is postMetaGameSrc, postMetaGameTitle and postMetaGameImg
     */
    public function testImportOnlyUpdateMetaOfExistingGameWhenStatusZero() 
    {
        $yesterday = \VegasHero\Helpers\Test::getDateYesterday();
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1, "modified" => $yesterday));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $updated_games = array_map(function($game) {
            $game->modified = \VegasHero\Helpers\Test::getDateToday();
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), $this->provider_importer, $this->config);
        
    }

}
