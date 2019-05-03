<?php

namespace VegasHero\ShortCodes;

final class SingleGame
{

    private $wp_query;

    public function __construct() {
        $this->wp_query = new \WP_Query();
    }

    /**
     * Finds game by id and renders iframe markup
     * @param int $game_id Game id set on post meta
     * @param string $class Value for iframe class attribute 
     * @return string 
     */
    public function render($game_id, $class="singlegame-iframe")
    {
        $iframe_src = $this->_getIframeSrc($game_id);
        $template = $this->getTemplate();
        return sprintf($template, $class, $iframe_src);
    }

    public function getTemplate() {
        return <<<MARKUP
<div class="iframe_kh_wrapper">
    <div class="kh-no-close"></div>
    <iframe class="%s" frameborder="0" scrolling="no" allowfullscreen="" src="%s" sandbox="allow-same-origin allow-scripts allow-popups allow-forms"></iframe>
</div>
MARKUP;
    }

    /**
     * Finds iframe src post meta
     * @param int $game_id Game id set on post meta
     * @return string 
     */
    private function _getIframeSrc($game_id) 
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
