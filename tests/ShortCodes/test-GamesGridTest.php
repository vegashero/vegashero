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
        $json_fixture = VegasHero\Helpers\Test::getFixture("elk.json");
        $this->games = VegasHero\Helpers\Test::importGames($json_fixture, new VegasHero\Import\Provider(), $this->config);
    }

    public function testGamesGridShortCodeReturnsCorrectMarkup() 
    {
        $template = $this->shortcode->render(
            array(
                "provider" => "elk",
            ), 
            $this->config
        );
        $expected = <<<HEREDOC
        <!--vegashero games grid shortcode-->
            <ul id="vh-lobby-posts-grid" class="vh-row-sm">
               <li class="vh-item" id="post-12">
                <a class="vh-thumb-link" href="http://example.org/?game=bloopers">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/bloopers/cover.jpg" title="Bloopers" alt="Bloopers" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Bloopers</div>
            </li>            <li class="vh-item" id="post-3">
                <a class="vh-thumb-link" href="http://example.org/?game=champions-goal">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/champions-goal/cover.jpg" title="Champions goal" alt="Champions goal" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Champions goal</div>
            </li>            <li class="vh-item" id="post-10">
                <a class="vh-thumb-link" href="http://example.org/?game=dj-wild">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/dj-wild/cover.jpg" title="Dj wild" alt="Dj wild" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Dj wild</div>
            </li>            <li class="vh-item" id="post-4">
                <a class="vh-thumb-link" href="http://example.org/?game=electric-sam">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/electric-sam/cover.jpg" title="Electric sam" alt="Electric sam" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Electric sam</div>
            </li>            <li class="vh-item" id="post-14">
                <a class="vh-thumb-link" href="http://example.org/?game=hong-kong-tower">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/hong-kong-tower/cover.jpg" title="Hong kong tower" alt="Hong kong tower" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Hong kong tower</div>
            </li>            <li class="vh-item" id="post-15">
                <a class="vh-thumb-link" href="http://example.org/?game=ivanhoe">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/ivanhoe/cover.jpg" title="Ivanhoe" alt="Ivanhoe" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Ivanhoe</div>
            </li>            <li class="vh-item" id="post-13">
                <a class="vh-thumb-link" href="http://example.org/?game=poltava">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/poltava/cover.jpg" title="Poltava" alt="Poltava" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Poltava</div>
            </li>            <li class="vh-item" id="post-7">
                <a class="vh-thumb-link" href="http://example.org/?game=sam-on-the-beach">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/sam-on-the-beach/cover.jpg" title="Sam on the beach" alt="Sam on the beach" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Sam on the beach</div>
            </li>            <li class="vh-item" id="post-11">
                <a class="vh-thumb-link" href="http://example.org/?game=taco-brothers">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/taco-brothers/cover.jpg" title="Taco brothers" alt="Taco brothers" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Taco brothers</div>
            </li>            <li class="vh-item" id="post-8">
                <a class="vh-thumb-link" href="http://example.org/?game=taco-brothers-saving-christmas">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/taco-brothers-saving-christmas/cover.jpg" title="Taco brothers saving christmas" alt="Taco brothers saving christmas" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Taco brothers saving christmas</div>
            </li>            <li class="vh-item" id="post-6">
                <a class="vh-thumb-link" href="http://example.org/?game=the-lab">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/the-lab/cover.jpg" title="The lab" alt="The lab" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">The lab</div>
            </li>            <li class="vh-item" id="post-9">
                <a class="vh-thumb-link" href="http://example.org/?game=wild-toro">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/wild-toro/cover.jpg" title="Wild toro" alt="Wild toro" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Wild toro</div>
            </li>            <li class="vh-item" id="post-5">
                <a class="vh-thumb-link" href="http://example.org/?game=winners-scratch">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/elk/winners-scratch/cover.jpg" title="Winners scratch" alt="Winners scratch" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">Winners scratch</div>
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


    /*
    public function testSingleGameShortCodeReturnsHelpfulException() {
        $this->expectException(InvalidArgumentException::class);
        $this->shortcode->render(rand());
    }
     */
}
