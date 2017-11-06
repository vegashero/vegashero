<?php

final class OperatorImportTest extends WP_UnitTestCase
{

    public function setUp() {
        parent::setUp();
        $this->config = Vegashero_Config::getInstance();
        $this->operator_importer = new VegasHero\Import\Operator();
        $this->faker = \Faker\Factory::create();
    }

    public function testImportsNewGames() 
    {
        $operator = 'energycasino';
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
        $mock_request = \Mockery::mock('WP_REST_Request');
        $mock_request->shouldReceive('get_url_params')->andReturn(array("operator" => $operator));
        $mock_request->shouldReceive('get_body')->andReturn(json_encode($games));
        $result = $this->operator_importer->importGames($mock_request);
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => $this->config->customPostType
        ));
        $this->assertEquals(count($games), count($posts));
    }

}
