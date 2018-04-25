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
        $this->faker = \Faker\Factory::create();
        $this->provider = $this->faker->firstname;
        $this->games = \VegasHero\Helpers\Test::generateRandomGames($this->faker, array("status" => 1, "provider" => $this->provider), 3);
        $this->posts = VegasHero\Helpers\Test::importGames(json_encode($this->games), new VegasHero\Import\Provider(), $this->config);
    }

    public function testSingleGameWithPaginationShouldShowSingleGameWithNextLink() {
        $template = $this->shortcode->render(
            array(
                "provider" => $this->provider,
                'gamesperpage' => 1,
                'pagination' => 'on',
            ), 
            $this->config
        );
        $pattern = "/<nav class='vh-pagination'><a class='next page-numbers' href='.*'>Next »<\/a><\/nav>$/";
        $this->assertEquals(preg_match($pattern, $template), 1);
    }

    public function testSingleGameWithPagintionShouldShowSingleGameWithPreviousLink() {
        $template = $this->shortcode->render(
            array(
                "provider" => $this->provider,
                'gamesperpage' => 1,
                'pagination' => 'on',
                "paged" => 3
            ), 
            $this->config
        );
        $pattern = "/<nav class='vh-pagination'><a class='prev page-numbers' href='[?|&]paged=\d'>« Previous<\/a>.*<\/nav>$/";
        $this->assertEquals(preg_match($pattern, $template), 1);
    }

    public function testSingleGameWithoutPagintionShouldShowSingleGameOnly() 
    {

        $template = $this->shortcode->render(
            array(
                "provider" => $this->provider,
                'gamesperpage' => 1,
                'pagination' => 'off',
                'orderby' => 'ID'
            ), 
            $this->config
        );

        $game = reset($this->games);
        $post = end($this->posts);
        $provider = strtolower($game->provider);
        $expected = <<<HEREDOC
<!--vegashero games grid shortcode-->
            <ul id="vh-lobby-posts-grid" class="vh-row-sm">
                          <li class="vh-item" id="post-$post->ID">
                <a class="vh-thumb-link" href="$post->guid">
                    <div class="vh-overlay">
                        <img src="//cdn.vegasgod.com/$provider/$post->post_name/cover.jpg" title="$post->post_title" alt="$post->post_title" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">$post->post_title</div>
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
