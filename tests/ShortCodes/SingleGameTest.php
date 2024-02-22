<?php

namespace VegasHero\Tests\ShortCodes;

use VegasHero\Helpers\Test as TestHelper;
use VegasHero\Config;
use VegasHero\ShortCodes\SingleGame;
use VegasHero\Import\Provider;

use WP_UnitTestCase, InvalidArgumentException, Faker;

/**
 * @covers Email
 */
final class SingleGameTest extends WP_UnitTestCase {


	private $config;
	private $shortcode;
	private $game_id;
	private $iframe_src;

	public function set_up() {
		parent::set_up();
		$this->config     = Config::getInstance();
		$this->shortcode  = SingleGame::getInstance();
		$post             = $this->factory->post->create_and_get(
			array(
				'post_type' => $this->config->customPostType,
			)
		);
		$this->iframe_src = uniqid();
		$this->game_id    = rand();
		add_post_meta( $post->ID, $this->config->postMetaGameSrc, $this->iframe_src, true );
		add_post_meta( $post->ID, $this->config->postMetaGameId, $this->game_id, true );
	}

	public function testSingleGameShortCodeReturnsMarkup() {
		$template = $this->shortcode->getTemplate();
		$this->assertEquals(
			$this->shortcode->render( $this->game_id, 'myclass' ),
			sprintf( $template, $this->iframe_src, '', 'Play Demo', '18+ Only. Play Responsibly.', 'myclass' )
		);
	}

	public function testSingleGameShortCodeReturnsHelpfulException() {
		$this->expectException( InvalidArgumentException::class );
		$this->shortcode->render( rand() );
	}
}
