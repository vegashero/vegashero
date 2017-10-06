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
        $this->category = uniqid();
        $this->grid = new VegasHero\ShortCodes\GamesGrid($this->config);
        $this->games = $this->_createGames($this->category, 10);
    }

    /**
     * @param string|array $terms
     * @param int $count
     * @return array 
     */
    private function _createGames($terms, $count) {
        $games = array();
        for ( $i = 0; $i < $count; $i++ ) {
            $post = $this->factory->post->create_and_get(
                array(
                    'post_type'=> $this->config->customPostType
                )
            );
            $this->factory()->term->add_post_terms($post->ID, $terms, $this->config->gameCategoryTaxonomy);
            $img_path = sprintf("%s/%s/cover.jpg", $this->config->gameImageUrl, $post->slug, sanitize_title($post->slug));
            add_post_meta($post->ID, $this->config->postMetaGameImg, uniqid(), true); 
            //$term_details = get_term_by('name', $terms, $this->config->gameCategoryTaxonomy);
            $games[] = $post;
        }
        return $games;
    }

    /**
     * Create default image for each game
     */
    private function _addImageMeta($games) {
    }

    public function testGetThumbnailWithFeaturedImage() {
        $this->grid->getThumbnail($this->games[0]->ID);
    }

    public function testGetThumbnailWithDefaultImage() {
        $this->grid->getThumbnail($this->games[0]->ID);
    }

    public function testGetGamesReturnsGames() {
        $games = $this->grid->getGames(array(
            "category" => $this->category
        ));
        $this->assertEquals($games, $this->games);
        $this->assertCount(count($this->games), $games);
    }

    public function testGetGamesDoesNotReturnGames() {
        $games = $this->grid->getGames(array(
            "category" => uniqid()
        ));
        $this->assertCount(0, $games);
    }

    public function testGamesGridShortCodeReturnsCorrectMarkup() 
    {
        $template = $this->shortcode->getTemplate();
        $this->assertEquals(
            $this->shortcode->render($this->game_id, 'myclass'),
            sprintf($template, 'myclass', $this->iframe_src)
        );
    }

    /*
    public function testSingleGameShortCodeReturnsHelpfulException() {
        $this->expectException(InvalidArgumentException::class);
        $this->shortcode->render(rand());
    }
     */
}
