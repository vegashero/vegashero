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
        $this->grid = new VegasHero\ShortCodes\GamesGrid($this->config);
        $this->games = $this->_importGames($this->category, 10);
    }

    private function _

    private function _addGameMeta($post) {
        $title = uniqid("game_title_");
        $img_path = sprintf("%s/%s/cover.jpg", $this->config->gameImageUrl, $post->slug, sanitize_title($post->slug));
        add_post_meta($post->ID, $this->config->postMetaGameImg, uniqid(), true); 
        //$term_details = get_term_by('name', $terms, $this->config->gameCategoryTaxonomy);
    }

    private function _addGameTerms($post) {
        $this->_addGameCategory($post);
    }

    private function _addGameCategory($post) {
        $this->factory()->term->add_post_terms($post->ID, $terms, $this->config->gameCategoryTaxonomy);
    }

    /**
     * @param string|array $terms
     * @param int $count
     * @return array 
     */
    private function _importGames($terms, $count) {
        $games = array();
        for ( $i = 0; $i < $count; $i++ ) {
            $post = $this->factory->post->create_and_get(
                array(
                    'post_type'=> $this->config->customPostType
                )
            );
            $this->_addGameTerms($post);
            $this->_addGameMeta($post);
            $games[] = $post;
        }
        return $games;
    }

    private function _setGameMeta() {
        $post_meta_game_id = add_post_meta($post_id, $this->_config->postMetaGameId, $game->id, true); // add post meta data
        $post_meta_game_src_id = add_post_meta($post_id, $this->_config->postMetaGameSrc, $game->src, true); // add post meta data
        $post_meta_game_title = add_post_meta($post_id, $this->_config->postMetaGameTitle, $game_title, true); // add post meta data
        $post_meta_game_img = add_post_meta($post_id, $this->_config->postMetaGameImg, sprintf("%s/%s/%s/cover.jpg", $this->_config->gameImageUrl, sanitize_title(strtolower(trim($game->provider))), $game_title), true); // add post meta data
    }

    /**
     * Create default image for each game
     */
    private function _addImageMeta($games) {
        $terms = wp_get_post_terms($post_id, $this->config->gameProviderTaxonomy, array('fields' => 'all'));
        $featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'vegashero-thumb');
        if($featured_image_src) {
            $thumbnail = $featured_image_src[0];
        } else {
            if( ! $thumbnail = get_post_meta($post_id, $this->config->postMetaGameImg, true )) {
                $mypostslug = get_post_meta($post_id, $this->config->postMetaGameTitle, true );
                $thumbnail = sprintf("%s/%s/cover.jpg", $this->config->gameImageUrl, $terms[0]->slug, sanitize_title($mypostslug));
            }
        }
        return $thumbnail;
    }

    public function testGetThumbnailWithFeaturedImage() {
        $this->grid->getThumbnail($this->games[0]->ID);
    }

    public function testGetThumbnailWithDefaultImage() {
        $this->grid->getThumbnail($this->games[0]->ID);
    }

    public function testGetGamesReturnsGames() {
        $games = $this->grid->getGames(array(
            "category" => $this->game_category
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
