<?php
namespace VegasHero\ShortCodes;

final class GamesGrid
{

    /**
     * @param array $attributes
     * @param Vegashero_Config $config
     * @return string
     */
    static public function render($attributes, $config) {
        $attributes = self::_getAttributes($attributes, self::_getPage());
        $query_params = self::_getQueryParams($attributes, $config);
        $query = new \WP_Query($query_params);
        $games = $query->query($query_params);
        $list_items = '';
        foreach($games as $game) {
            $thumbnail = self::_getThumbnail($game->ID, $config);
            $list_items .= self::_getGameMarkup($game, $thumbnail);
        }
        $template = self::_getTemplate($list_items);
        if($max_num_pages = self::_isPaginated($query, $attributes->pagination)) {
            $template .= self::_getPaginationMarkup($max_num_pages);
        }
        return $template;
    }

    static private function _isFirstPage() {
        return is_paged();
    }

    static private function _isLastPage($max_num_pages) {
      return ($max_num_pages > get_query_var('paged'));
    }

    static private function _getPreviousLink($text) {
        echo __METHOD__;
        if(self::_isPage()) {
            return self::_getPreviousPageLink($text);
        }
        return self::_getPreviousPostLink($text);
    }

    /**
     * On pages it works
     * http://localhost:8080/?page_id=2&paged=1
     * On posts it doesn't, but if we manually add ?paged to the url it starts to work
     */
    static private function _getPreviousPostLink($text) {
        $page = self::_getPage();
        if($page > 1) {
            return "<a href=''>$text</a>";
        }
    }

    static private function _getPreviousPreviousPageLink($text) {
        return get_previous_posts_link( $text );
    }

    static private function _getNextLink($text, $max_num_pages) {
        echo __METHOD__;
        if(self::_isPage()) {
            return self::_getNextPageLink($text, $max_num_pages);
        }
        return self::_getNextPostLink($text);
    }

    static private function _getNextPageLink($text, $max_num_pages) {
        return get_next_posts_link( $text, $max_num_pages );
    }

    /**
     * when it is a post we need to make our own pagination by appending ?paged=1 to the url
     * when it is a page it basically works
     * for both scenarios we need to build our own previous and next link hrefs
     */
    static private function _getPaginationMarkup($max_num_pages) {
        $markup = "<nav class='vh-pagination'>";
        if( self::_isFirstPage() ) {
            $previous = self::_getPreviousLink('<< Previous');
            //$previous = get_previous_post_link('<< Previous');
            $markup .= "<div class='prev page-numbers'>$previous</div>";
        }
        if( self::_isLastPage($max_num_pages)) {
             $next = self::_getNextLink( 'Next >>', $max_num_pages );
             $markup .= "<div class='next page-numbers'>$next</div>";
        }
        $markup .= "</nav>";
        return $markup;
    }

    static private function _getGameMarkup($post, $thumbnail) {
        $permalink = get_permalink($post);
        return <<<MARKUP
            <li class="vh-item" id="post-{$post->ID}">
                <a class="vh-thumb-link" href="{$permalink}">
                    <div class="vh-overlay">
                        <img src="{$thumbnail}" title="{$post->post_title}" alt="{$post->post_title}" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title">{$post->post_title}</div>
            </li>
MARKUP;
    }

    /**
     * @param WP_Query $query
     * @param string $pagination on | off
     * @return int
     */
    static private function _isPaginated($query, $pagination) {
        return (($pagination == "on") && ($query->max_num_pages > 1)) ? $query->max_num_pages : 0;
    }

    /**
     * @param array $attributes 
     * @returns object
     */
    static private function _getAttributes($attributes = array(), $paged=1) {
        return (object)shortcode_atts( 
            array(
                'order' => 'ASC',
                'orderby' => 'title',
                'gamesperpage' => -1,
                'pagination' => 'on',
                'provider' => '',
                'operator' => '',
                'category' => '',
                'tag' => '',
                'keyword' => '',
                'paged' => $paged 
            ), $attributes
        );
    }

    /**
     * @param array @attributes
     * @param Vegashero_Config $config
     * @return array
     */
    static private function _getQueryParams($attributes, $config) {
        return array(
            'post_type' => $config->customPostType,
            'order' => $attributes->order,
            'orderby' => $attributes->orderby,
            'posts_per_page' => $attributes->gamesperpage,
            'paged' => $attributes->paged,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => $config->gameProviderTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $attributes->provider,
                ),
                array(
                    'taxonomy' => $config->gameOperatorTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $attributes->operator,
                ),
                array(
                    'taxonomy' => $config->gameCategoryTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $attributes->category,
                ),
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $attributes->tag,
                ),
            ),
            's' => $attributes->keyword
        );
    }

    static private function _getThumbnail($post_id, $config) {
        $terms = wp_get_post_terms($post_id, $config->gameProviderTaxonomy, array('fields' => 'all'));
        $featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'vegashero-thumb');
        if($featured_image_src) {
            $thumbnail = $featured_image_src[0];
        } else {
            if( ! $thumbnail = get_post_meta($post_id, $config->postMetaGameImg, true )) {
                $mypostslug = get_post_meta($post_id, $config->postMetaGameTitle, true );
                $thumbnail = sprintf("%s/%s/cover.jpg", $config->gameImageUrl, $terms[0]->slug, sanitize_title($mypostslug));
            }
        }
        return $thumbnail;
    }

    static private function _isPage() {
        return !is_single();
    }

    static private function _getPage() {
        return ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    }

    /**
     * @param string $list_items Markup for <li> items
     * @return string
     */
    static private function _getTemplate($list_items) {
        return <<<MARKUP
            <!--vegashero games grid shortcode-->
            <ul id="vh-lobby-posts-grid" class="vh-row-sm">
              {$list_items}
            </ul>
            <!--/vegashero games grid shortcode-->
            <div class="clear"></div>
MARKUP;
    }

}
