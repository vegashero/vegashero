<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * @covers Email
 */
final class SingleGameTest extends TestCase
{

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
}
