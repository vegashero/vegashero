<?php

/**
 * @covers Email
 */
final class SingleGameTest extends WP_UnitTestCase
{

    private $config;
    private $shortcode;
    private $game_id;
    private $iframe_src;

    public function setUp() {
        parent::setUp();
        $this->config = Vegashero_Config::getInstance();
        $this->shortcode = new VegasHero\ShortCodes\SingleGame();
        $post = $this->factory->post->create_and_get(
            array(
                'post_type'      => $this->config->customPostType
            )
        );
        $this->iframe_src = uniqid(); 
        $this->game_id = rand();
        add_post_meta($post->ID, $this->config->postMetaGameSrc, $this->iframe_src, true); 
        add_post_meta($post->ID, $this->config->postMetaGameId, $this->game_id, true); 
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
