<?php

final class OperatorImportTest extends WP_UnitTestCase
{

    public function setUp() {
        parent::setUp();
        $this->config = Vegashero_Config::getInstance();
        $this->operator_importer = new VegasHero\Import\Operator();
        $this->faker = \Faker\Factory::create();
        $this->operator = 'energycasino';
    }

    /**
     * status 1: (nothing changes for this case) 
     * game is imported in all cases and post meta is updated
     */
    public function testImportsAddNewGamesWhenStatusOne() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames(
            $this->faker, 
            array(
                "status" => 1, 
                "energycasino" => 1,
                "mrgreen" => 0,
                "slotsmillion" => 0,
                "europa" => 0,
                "slotslv" => 0,
                "winner" => 0,
                "bet365" => 0,
                "williamhill" => 0,
                "intercasino" => 0,
                "videoslots" => 0,
                "bellfruit" => 0
            )
        );
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->operator_importer, $this->config, $this->operator);
        $this->assertSame(count($games), count($posts));
    }

    /**
     * status 0 
     * if doesn't exist on customer's site: game is not imported 
     */
    public function testImportDontAddGamesWhenStatusZero() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 0));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->operator_importer, $this->config, $this->operator);
        $this->assertSame(0, count($posts));
    }

    /**
     * status 0 
     * if already exist on customer's site: status is left as is (do not change to draft)
     */
    public function testImportDontUpdatePostStatusOfExistingGamesWhenStatusZero() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->operator_importer, $this->config, $this->operator);
        $updated_games = array_map(function($game) {
            $game->status = 0;
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), $this->operator_importer, $this->config, $this->operator);
        $result = (array_search('draft', array_column($updated_posts, 'post_status')) !== FALSE);
        $this->assertSame($result, false);
        $this->assertSame(count($posts), count($updated_posts)); // no new games imported
    }

    /**
     * given game has already been imported
     * when game update has status 0
     * and the game meta changes
     * then the game src, game title and game img meta data is updated
     * and the post title, post name and game id meta data is left untouched
     */
    public function testImportUpdateMetaOfExistingGame() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->operator_importer, $this->config, $this->operator);
        $updated_games = array_map(function($game) {
            $game->src = $this->faker->url;
            $game->name =$this->faker->firstname;
            $game->modified = \VegasHero\Helpers\Test::getDateToday();
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), $this->operator_importer, $this->config, $this->operator);
        for($i=0; $i<count($updated_posts); $i++) {
            $this->assertSame($posts[$i]->post_title, $updated_posts[$i]->post_title);
            $this->assertSame($posts[$i]->post_name, $updated_posts[$i]->post_name);
            $this->assertSame($posts[$i]->meta->game_id, $updated_posts[$i]->meta->game_id);
            $this->assertFalse($posts[$i]->meta->game_src === $updated_posts[$i]->meta->game_src);
            $this->assertFalse($posts[$i]->meta->game_title === $updated_posts[$i]->meta->game_title);
            $this->assertFalse($posts[$i]->meta->game_img === $updated_posts[$i]->meta->game_img);
        }
    }

    /**
     * given game has already been imported
     * when a new operator is added to a game
     * and the game is updated
     * then the operator category is created and added to the game
     */
    public function testImportUpdateGameOperators() {}

}
