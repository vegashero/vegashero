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
     * Finds game by id and renders iframe markup
     * @param int $game_id Game id set on post meta
     * @param string $class Value for iframe class attribute 
     * @return string 
     */
    public function render(int $game_id, string $class="singlegame-iframe")
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
     * Finds iframe src post meta
     * @param int $game_id Game id set on post meta
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
        $posts = (array)$this->wp_query->get_posts();
        if( ! count($posts)) {
            throw new \InvalidArgumentException(sprintf("Game with id %d not found", $game_id));
        }
        return \get_post_meta($posts[0]->ID, 'game_src', true);
    }

}
