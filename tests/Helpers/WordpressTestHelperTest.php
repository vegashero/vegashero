<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use VegasHero\Helpers\Test as WpHelper;

/**
 * @covers Email
 */
final class WordpressTestHelperTest extends TestCase {


	// these could be set via environment variables
	private $url            = 'http://localhost:4360';
	private $title          = 'VegasHero';
	private $admin_user     = 'vegashero';
	private $admin_password = 'secret';
	private $admin_email    = 'support@vegashero.co';
	private $import_file    = '/var/www/html/wp-content/plugins/vegashero/fixtures/vegashero.WordPress.2017-08-05.xml';

	protected function setUp(): void {
		WpHelper::resetDatabase();
		WpHelper::installWordpress( $this->url, $this->title, $this->admin_user, $this->admin_password, $this->admin_email );
		WpHelper::enablePlugin( 'vegashero' );
	}

	protected function tearDown(): void {
	}

	public function testIsWordpressInstalled() {
		$this->assertEquals(
			WpHelper::isWordpressInstalled(),
			true
		);
	}

	public function testEnableVegasHeroPlugin() {
		$this->assertEquals(
			WpHelper::isPluginInstalled( 'vegashero' ),
			true
		);
	}

	public function testImportGameFixtures() {

		$this->markTestSkipped();
		$this->assertEquals(
			WpHelper::importFixture( $this->import_file ),
			true
		);
	}


	/*
	public function testReturnsIframeSrcForGameId()
	{
		$game_id = rand();
		$iframe_src = uniqid();
		$query = Mockery::mock('WP_Query');

		// Other query properties
	)
	);
		$meta_query->shouldReceive('parse_query_vars')
			->with(array(
				"meta_key" => "game_id",
				"meta_value" => $game_id,
				"meta_type" => "UNSIGNED",
			))
			->andReturn($iframe_src);

		$shortcode = new VegasHero\ShortCodes\SingleGame($meta_query);

		$this->assertEquals(
			$shortcode->getIframeSrcForGameId($game_id),
			$iframe_src
		);
	}

	public function testReturnsSingleGameShortCodeMarkup()
	{
		$iframe_src = "https://my-iframe-source";
		$template = <<<MARKUP
	<div class="iframe_kh_wrapper">
	<div class="kh-no-close"></div>
	<iframe class="singlegame-iframe" frameborder="0" scrolling="no" allowfullscreen="" src="%s"></iframe>
	</div>
	MARKUP;
		$this->assertEquals(
			VegasHero\ShortCodes\SingleGame::byId(1),
			sprintf($template, $iframe_src)
		);
	}
	*/
}
