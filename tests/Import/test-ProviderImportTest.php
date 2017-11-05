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

    public function testImportsNewGames() 
    {
        $games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1));
        $mock_request = \Mockery::mock('WP_REST_Request');
        $mock_request->shouldReceive('get_body')->andReturn(json_encode($games));
        $result = $this->provider_importer->importGames($mock_request);
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => $this->config->customPostType
        ));
        $this->assertEquals(count($games), count($posts));
    }

}
