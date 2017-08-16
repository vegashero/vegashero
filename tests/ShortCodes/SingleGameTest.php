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

    public function setUp() {
        self::$functions = Mockery::mock();
    }

    public function tearDown() {
        Mockery::close();
    }

    public function testSingleGameShortCodeReturnsMarkup() 
    {
        $game_id = rand();
        $iframe_src = uniqid();
        $wp_query = Mockery::mock('WP_Query');
        $wp_query->shouldReceive('query')
            ->with(
                array(
                    'post_type' => 'vegashero_games',
                    'meta_query' => array(
                        array(
                            'key' => 'game_id',
                            'value' => $game_id,
                        )
                    )
                )
            );
        $wp_query->shouldReceive('get_posts');
        self::$functions->shouldReceive('get_post_meta')->andReturn($iframe_src);

        $single_game = new VegasHero\ShortCodes\SingleGame($wp_query);
        $template = <<<MARKUP
<div class="iframe_kh_wrapper">
    <div class="kh-no-close"></div>
    <iframe class="singlegame-iframe" frameborder="0" scrolling="no" allowfullscreen="" src="%s"></iframe>
</div>
MARKUP;

        $this->assertEquals(
            $single_game->render($game_id),
            sprintf($template, $iframe_src)
        );
    }
}
