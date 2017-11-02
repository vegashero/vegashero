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
        $this->shortcode = new VegasHero\ShortCodes\GamesGrid($this->config);
        $json_fixture = VegasHero\Helpers\Test::getFixture("elk.json", sprintf("%s/../Fixtures", dirname(__FILE__)));
        $this->games = VegasHero\Helpers\Test::importGames($json_fixture, new VegasHero\Import\Provider(), $this->config);
    }

    public function testSingleGameWithPaginationShouldShowSingleGameWithNextLink() {
        $template = $this->shortcode->render(
            array(
                "provider" => "elk",
                'gamesperpage' => 1,
                'pagination' => 'on',
            ), 
            $this->config
        );
        $pattern = "/<nav class='vh-pagination'><div class='next page-numbers'><a href=.*>Next >><\/a><\/div><\/nav>$/";
        $this->assertEquals(preg_match($pattern, $template), 1);
    }

    public function testSingleGameWithPagintionShouldShowSingleGameWithPreviousLink() {
        $template = $this->shortcode->render(
            array(
                "provider" => "elk",
                'gamesperpage' => 1,
                'pagination' => 'on',
                "paged" => 3
            ), 
            $this->config
        );
        $pattern = "/<nav class='vh-pagination'><div class='prev page-numbers'><a href=.*paged=.*><< Previous<\/a><\/div>.*<\/nav>/";
        $this->assertEquals(preg_match($pattern, $template), 1);
    }

    public function testSingleGameWithoutPagintionShouldShowSingleGameOnly() 
    {
        $template = $this->shortcode->render(
            array(
                "provider" => "elk",
                'gamesperpage' => 1,
                'pagination' => 'off',
            ), 
            $this->config
        );
        $expected = <<<HEREDOC
<!--vegashero games grid shortcode-->
            <ul id="vh-lobby-posts-grid" class="vh-row-sm">
                          <li class="vh-item" id="post-51">
                <a class="vh-thumb-link" href="http://example.org/?game=bloopers">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/bloopers/cover.jpg" title="Bloopers" alt="Bloopers" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Bloopers</div>
            </li>
            </ul>
            <!--/vegashero games grid shortcode-->
            <div class="clear"></div>
HEREDOC;
        $this->assertEquals(self::_trim($template), self::_trim($expected));
    }

    /**
     * @param string $str
     * @return string
     */
    static private function _trim($str) {
        return preg_replace('/^\s+|\s+$/m', '', $str);
    }

}
