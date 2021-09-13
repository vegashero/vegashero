<?php

/**
 * @covers Email
 */
final class ProviderImportTest extends WP_UnitTestCase
{

    protected static $config;
    protected static $importer;
    protected static $faker;
    protected static $provider_name;

    public function setUp() {
        parent::setUp();
        self::$config = \VegasHero\Config::getInstance();
        self::$importer = new VegasHero\Import\Provider();
        self::$faker = \Faker\Factory::create();
        self::$provider_name = strtolower(self::$faker->firstname);
    }

    /**
     * given a game with status 1
     * when the game is imported
     * then the game is imported
     * and meta data is added
     * and taxonomies created
     *
     * @group import
     * @group provider
     */
    public function testImportsAddNewGamesWhenStatusOne() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames(self::$faker, array("status" => 1, 'provider' => self::$provider_name));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), self::$importer, self::$config);
        $this->assertSame(count($games), count($posts));
        foreach($posts as $post) {
            $terms = get_the_terms($post, self::$config->gameProviderTaxonomy);
            $this->assertContains(self::$provider_name, array_column($terms, 'slug'));
            $this->assertObjectHasAttribute(self::$config->postMetaGameId, $post->meta);
            $this->assertObjectHasAttribute(self::$config->postMetaGameType, $post->meta);
            $this->assertObjectHasAttribute(self::$config->postMetaGameSrc, $post->meta);
            $this->assertObjectHasAttribute(self::$config->postMetaGameTitle, $post->meta);
            $this->assertObjectHasAttribute(self::$config->postMetaGameImg, $post->meta);
        }
    }


    /**
     * given game has status of 0
     * when the game is imported
     * then the game is not imported
     *
     * @group import
     * @group provider
     */
    public function testImportDontAddGamesWhenStatusZero() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames(self::$faker, array("status" => 0, 'provider' => self::$provider_name));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), self::$importer, self::$config);
        $this->assertSame(0, count($posts));
        foreach($posts as $post) {
            $terms = get_the_terms($post, self::$config->gameProviderTaxonomy);
            $this->assertNotContains(self::$provider_name, array_column($terms, 'slug'));
        }
    }

    /**
     * given game has already been imported
     * and updated game has status 0
     * when the game is updated 
     * then post status is left as is (do not change to draft)
     *
     * @group import
     * @group provider
     */
    public function testImportDontUpdatePostStatusOfExistingGamesWhenStatusZero() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames(self::$faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), self::$importer, self::$config);
        $updated_games = array_map(function($game) {
            $game->status = 0;
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), self::$importer, self::$config);
        $result = (array_search('draft', array_column($updated_posts, 'post_status')) !== FALSE);
        $this->assertSame($result, false);
        $this->assertEquals($posts, $updated_posts); // no new games imported
    }

    /**
     * given game has already been imported
     * and updated game has status 0
     * and the game meta changes
     * when the game is updated 
     * then the game src, game type, game title and game img meta data is updated
     * and the post title, post name and game id meta data is left untouched
     *
     * @group import
     * @group provider
     */
    public function testImportUpdateMetaOfExistingGame() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames(self::$faker, array("status" => 1));
        $posts = \VegasHero\Helpers\Test::importGames(json_encode($games), self::$importer, self::$config);
        $updated_games = array_map(function($game) {
            $game->src = self::$faker->url;
            $game->name = self::$faker->firstname;
            $game->type = $game->type ? 0 : 1; // swop zero for one and vice versa
            return $game;
        }, $games);
        $updated_posts = \VegasHero\Helpers\Test::importGames(json_encode($updated_games), self::$importer, self::$config);
        $this->assertNotEquals($posts, $updated_posts);
        for($i=0; $i<count($updated_posts); $i++) {
            $this->assertSame($posts[$i]->post_title, $updated_posts[$i]->post_title);
            $this->assertSame($posts[$i]->post_name, $updated_posts[$i]->post_name);
            $this->assertSame($posts[$i]->meta->game_id, $updated_posts[$i]->meta->game_id);
            $this->assertNotSame($posts[$i]->meta->game_src, $updated_posts[$i]->meta->game_src);
            $this->assertNotSame($posts[$i]->meta->game_title, $updated_posts[$i]->meta->game_title);
            $this->assertNotSame($posts[$i]->meta->game_img, $updated_posts[$i]->meta->game_img);
            $this->assertNotSame($posts[$i]->meta->game_type, $updated_posts[$i]->meta->game_type);
        }
    }

}
