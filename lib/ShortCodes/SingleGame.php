<?php
declare(strict_types=1);

namespace VegasHero\ShortCodes;

final class SingleGame
{

    private $wp_query;

    /**
     * @param WP_Query $wp_query
     */
    public function __construct(\WP_Query $wp_query) {
        $this->wp_query = $wp_query;
    }

    /**
     * @param int $game_id
     * @return string
     */
    public function render($game_id, $class="singlegame-iframe")
    {
        $iframe_src = $this->_getIframeSrc($game_id);
        $template = <<<MARKUP
<div class="iframe_kh_wrapper">
    <div class="kh-no-close"></div>
    <iframe class="%s" frameborder="0" scrolling="no" allowfullscreen="" src="%s"></iframe>
</div>
MARKUP;
        return sprintf($template, $class, $iframe_src);
    }

    /**
     * Fetches iframe src 
     * @param int $game_id
     * @return string
     */
    private function _getIframeSrc(int $game_id) 
    {
        $this->wp_query->query(
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
        $post = $this->wp_query->get_posts()[0];
        return \get_post_meta($post->ID, 'game_src', true);
    }

}
