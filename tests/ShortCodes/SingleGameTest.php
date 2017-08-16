<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

function get_post_meta() {
    return SingleGameTest::$functions->get_post_meta();
}


/**
 * @covers Email
 */
final class SingleGameTest extends TestCase
{

    public static $functions;
    public static $wp_query;
    public static $game_id;
    public static $iframe_src;
    public static $shortcode;

    public function setUp() {
        self::$functions = Mockery::mock();
        self::$wp_query = Mockery::mock('WP_Query');
        self::$game_id = rand();
        self::$iframe_src = uniqid();
        self::$shortcode = new VegasHero\ShortCodes\SingleGame(self::$wp_query);
    }

    public function tearDown() {
        Mockery::close();
    }

    public function testSingleGameShortCodeReturnsMarkup() 
    {
        self::$wp_query->shouldReceive('query')
            ->with(
                array(
                    'post_type' => 'vegashero_games',
                    'meta_query' => array(
                        array(
                            'key' => 'game_id',
                            'value' => self::$game_id,
                        )
                    )
                )
            );
        self::$wp_query->shouldReceive('get_posts')->andReturn(array(new stdClass()));
        self::$functions->shouldReceive('get_post_meta')->andReturn(self::$iframe_src);

        $template = <<<MARKUP
<div class="iframe_kh_wrapper">
    <div class="kh-no-close"></div>
    <iframe class="singlegame-iframe" frameborder="0" scrolling="no" allowfullscreen="" src="%s"></iframe>
</div>
MARKUP;

        $this->assertEquals(
            self::$shortcode->render(self::$game_id),
            sprintf($template, self::$iframe_src)
        );
    }

    public function testSingleGameShortCodeReturnsHelpfulException() {
        self::$wp_query->shouldReceive('query');
        self::$wp_query->shouldReceive('get_posts')->andReturn(null);
        $this->expectException(InvalidArgumentException::class);
        self::$shortcode->render(rand());
    }
}
