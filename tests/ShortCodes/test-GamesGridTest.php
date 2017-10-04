<?php

/**
 * [vh-grid provider="netent" orderby="title" order="ASC" gamesperpage="3" pagination="on"]
 */
final class GamesGridTest extends WP_UnitTestCase
{

    private $config;
    private $shortcode;
    private $game_id;
    private $iframe_src;

    public function setUp() {
        parent::setUp();
        $this->config = Vegashero_Config::getInstance();
        $this->taxonomy = uniqid();
        $this->_createGames($this->taxonomy, 10);
        $this->iframe_src = uniqid(); 
        $this->game_id = rand();
    }

    private function _createGames($taxonomy, $count) {
        $games = array();
        for ( $i = 0; $i < $count; $i++ ) {
            $post = $this->factory->post->create_and_get(
                array(
                    'post_type'      => $this->config->customPostType
                )
            );

            $this->factory($taxonomy)->term->add_post_terms($post->ID, $taxonomy, );
            $games[] = $post;
        }
        return $games;
    }

    public function testGetGamesReturnsGames() {
        $grid = new VegasHero\ShortCodes\GamesGrid();
        $grid->getGames();
    }

    public function testSingleGameShortCodeReturnsMarkup() 
    {
        $template = $this->shortcode->getTemplate();
        $this->assertEquals(
            $this->shortcode->render($this->game_id, 'myclass'),
            sprintf($template, 'myclass', $this->iframe_src)
        );
    }

    public function testSingleGameShortCodeReturnsHelpfulException() {
        $this->expectException(InvalidArgumentException::class);
        $this->shortcode->render(rand());
    }
}
