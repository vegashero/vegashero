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
        $this->provider = strtolower($this->faker->firstname);
    }

    /**
     * given a game with status 1
     * when the game is imported
     * then the game is imported
     * and meta data is added
     * and taxonomies created
     */
    public function testImportsAddNewGamesWhenStatusOne() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1, 'provider' => $this->provider));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $this->assertSame(count($games), count($posts));
        foreach($posts as $post) {
            $terms = get_the_terms($post, $this->config->gameProviderTaxonomy);
            $this->assertContains($this->provider, array_column($terms, 'slug'));
            $this->assertObjectHasAttribute($this->config->postMetaGameId, $post->meta);
            $this->assertObjectHasAttribute($this->config->postMetaGameSrc, $post->meta);
            $this->assertObjectHasAttribute($this->config->postMetaGameTitle, $post->meta);
            $this->assertObjectHasAttribute($this->config->postMetaGameImg, $post->meta);
        }
    }


    /**
     * given game has status of 0
     * when the game is imported
     * then the game is not imported
     */
    public function testImportDontAddGamesWhenStatusZero() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 0, 'provider' => $this->provider));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $this->assertSame(0, count($posts));
        foreach($posts as $post) {
            $terms = get_the_terms($post, $this->config->gameProviderTaxonomy);
            $this->assertNotContains($this->provider, array_column($terms, 'slug'));
        }
    }

    /**
     * given game has already been imported
     * and updated game has status 0
     * when the game is updated 
     * then post status is left as is (do not change to draft)
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
        $this->assertEquals($posts, $updated_posts); // no new games imported
    }

    /**
     * given game has already been imported
     * and updated game has status 0
     * and the game meta changes
     * when the game is updated 
     * then the game src, game title and game img meta data is updated
     * and the post title, post name and game id meta data is left untouched
     */
    public function testImportUpdateMetaOfExistingGame() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), $this->provider_importer, $this->config);
        $updated_games = array_map(function($game) {
            $game->src = $this->faker->url;
            $game->name =$this->faker->firstname;
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), $this->provider_importer, $this->config);
        $this->assertNotEquals($posts, $updated_posts);
        for($i=0; $i<count($updated_posts); $i++) {
            $this->assertSame($posts[$i]->post_title, $updated_posts[$i]->post_title);
            $this->assertSame($posts[$i]->post_name, $updated_posts[$i]->post_name);
            $this->assertSame($posts[$i]->meta->game_id, $updated_posts[$i]->meta->game_id);
            $this->assertNotSame($posts[$i]->meta->game_src, $updated_posts[$i]->meta->game_src);
            $this->assertNotSame($posts[$i]->meta->game_title, $updated_posts[$i]->meta->game_title);
            $this->assertNotSame($posts[$i]->meta->game_img, $updated_posts[$i]->meta->game_img);
        }
    }

}
