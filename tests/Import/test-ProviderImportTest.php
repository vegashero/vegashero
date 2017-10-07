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
    }

    public function testImportsNewGames() 
    {
        $fixture = sprintf("%s/../Fixtures/elk.json", dirname(__FILE__));
        $json_string = file_get_contents($fixture);
        $games = json_decode($json_string, true);
        $mock_request = \Mockery::mock('WP_REST_Request');
        $mock_request->shouldReceive('get_body')->andReturn($json_string);
        $result = $this->provider_importer->importGames($mock_request);
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => $this->config->customPostType
        ));
        $this->assertEquals(count($games), count($posts));
    }

}
