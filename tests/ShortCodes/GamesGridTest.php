<?php

namespace VegasHero\Tests\ShortCodes;

use VegasHero\Helpers\Test as TestHelper;
use VegasHero\Config;
use VegasHero\ShortCodes\GamesGrid;
use VegasHero\Import\Provider;

use WP_UnitTestCase, Faker;

/**
 * [vh-grid provider="netent" orderby="title" order="ASC" gamesperpage="3" pagination="on"]
 */
final class GamesGridTest extends WP_UnitTestCase {


	private $config;
	private $shortcode;
	private $game_id;
	private $iframe_src;

	public function set_up() {
		parent::set_up();
		$this->config   = Config::getInstance();
		$this->faker    = Faker\Factory::create();
		$this->provider = $this->faker->firstname;
		$this->games    = TestHelper::generateRandomGames(
			$this->faker,
			array(
				'status'   => 1,
				'provider' => $this->provider,
			),
			3
		);
		$this->posts    = TestHelper::importGames(
			json_encode( $this->games ),
			Provider::getInstance(),
			$this->config,
			array(
				'post_status' => 'publish',
			)
		);
	}

	/**
	 * @group gamesgrid
	 */
	public function testSingleGameWithPaginationShouldShowSingleGameWithNextLink() {
		$template = GamesGrid::render(
			array(
				'provider'     => $this->provider,
				'gamesperpage' => 1,
				'pagination'   => 'on',
			),
			$this->config
		);
		$pattern  = "/.*<nav class='vh-pagination'><a class='next page-numbers' rel='next nofollow' href='.*'>Next<\/a><\/nav>.*$/";
		$this->assertEquals( preg_match( $pattern, $template ), 1 );
	}

	/**
	 * @group gamesgrid
	 */
	public function testSingleGameWithPagintionShouldShowSingleGameWithPreviousLink() {
		$template = GamesGrid::render(
			array(
				'provider'     => $this->provider,
				'gamesperpage' => 1,
				'pagination'   => 'on',
				'paged'        => 3,
			),
			$this->config
		);
		$pattern  = "/.*<nav class='vh-pagination'><a class='prev page-numbers' rel='prev nofollow' href='[?|&]paged=\d'>Previous<\/a>.*<\/nav>.*$/";
		$this->assertEquals( preg_match( $pattern, $template ), 1 );
	}

	/**
	 * @group gamesgrid
	 */
	public function testSingleGameWithoutPagintionShouldShowSingleGameOnly() {
		$template = GamesGrid::render(
			array(
				'provider'     => $this->provider,
				'gamesperpage' => 1,
				'pagination'   => 'off',
				'orderby'      => 'ID',
			),
			$this->config
		);

		$game     = reset( $this->games );
		$post     = end( $this->posts );
		$provider = strtolower( $game->provider );
		$expected = <<<HEREDOC
<div class='vh-posts-grid-wrap'>            <!--vegashero games grid shortcode-->
            <ul id="vh-lobby-posts-grid" class="vh-row-sm">
                          <li class="vh-item" id="post-$post->ID">
                <a class="vh-thumb-link" href="$post->guid">
                    <div class="vh-overlay">
                        <img width="376" height="250" src="//cdn.vegasgod.com/$provider/$post->post_name/cover.jpg" title="$post->post_title" alt="$post->post_title" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">$post->post_title</div>
            </li>
            </ul>
            <!--/vegashero games grid shortcode-->
            <div class="clear"></div></div>
HEREDOC;
		$this->assertEquals( self::_trim( $template ), self::_trim( $expected ) );
	}

	/**
	 * @group gamesgrid
	 *
	 * @param string $str
	 * @return string
	 */
	private static function _trim( string $str ): string {
		return preg_replace( '/^\s+|\s+$/m', '', $str );
	}
}
