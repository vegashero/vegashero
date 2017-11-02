<?php
namespace VegasHero\ShortCodes;

final class GamesGrid
{

    /**
     * The output of the gamed grid shortcode
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
            $thumbnail = self::_getThumbnail($game, $config);
            $list_items .= self::_getGameMarkup($game, $thumbnail);
        }
        $template = self::_getTemplate($list_items);
        if($max_num_pages = self::_isPaginated($query, $attributes->pagination)) {
            $template .= self::_getPaginationMarkup($attributes->paged, $max_num_pages);
        }
        return $template;
    }

    /**
     * Previous pagination link
     * When it is a post we need to make our own pagination links by appending ?paged=1 to the url
     * When it is a page we can use built in wp methods to build the urls
     * @param string $text Text for next link
     * @param int $current_page 
     * @return string
     */
    static private function _getPreviousLink($text, $current_page) {
        if( ! self::_isPage()) {
            return self::_getPreviousPostLink($text, $current_page);
        }
        return self::_getPreviousPageLink($text);
    }

    /**
     * Previous pagination link when grid embedded in a Wordpress post
     * @param string $text Text for next link
     * @param int $current_page
     * @return string
     */
    static private function _getPreviousPostLink($text, $current_page) {
        $url = add_query_arg(
            array(
                "paged" => (int)$current_page - 1
            )
        );
        return "<a href='$url'>$text</a>";
    }

    /**
     * Previous pagination link when grid embedded in a Wordpress page
     * @param string $text Text for next link
     * @return string
     */
    static private function _getPreviousPageLink($text) {
        return get_previous_posts_link( $text );
    }

    /**
     * Next pagination link
     * When it is a post we need to make our own pagination links by appending ?paged=1 to the url
     * When it is a page we can use built in wp methods to build the urls
     * @param string $text Text for next link
     * @param int $current_page 
     * @param int $max_num_pages Highest page in pagination
     * @return string
     */
    static private function _getNextLink($text, $current_page, $max_num_pages) {
        if(self::_isPage()) {
            return self::_getNextPageLink($text, $max_num_pages);
        }
        return self::_getNextPostLink($text, $current_page);
    }

    /**
     * Next pagination link when grid embedded in a Wordpress post
     * @param string $text Text for next link
     * @param int $current_page 
     * @return string
     */
    static private function _getNextPostLink($text, $current_page) {
        $url = add_query_arg(
            array(
                "paged" => (int)$current_page + 1
            )
        );
        return "<a href='$url'>$text</a>";
    }

    /**
     * Next pagination link when grid embedded in a Wordpress page
     * @param string $text Text for next link
     * @param int $max_num_pages Highest page in pagination
     * @return string
     */
    static private function _getNextPageLink($text, $max_num_pages) {
        return get_next_posts_link( $text, $max_num_pages );
    }

    /**
     * @param int $current_page
     * @param int $max_num_pages
     * @return string
     */
    static private function _getPaginationMarkup($current_page, $max_num_pages) {
        $markup = "<nav class='vh-pagination'>";
        if( ! self::_isFirstPage($current_page) ) {
            $previous = self::_getPreviousLink('<< Previous', $current_page);
            $markup .= "<div class='prev page-numbers'>$previous</div>";
        }
        if( ! self::_isLastPage($max_num_pages)) {
            $next = self::_getNextLink( 'Next >>', $current_page, $max_num_pages );
            $markup .= "<div class='next page-numbers'>$next</div>";
        }
        $markup .= "</nav>";
        return $markup;
    }

    /**
     * @param int $current_page
     * @return bool
     */
    static private function _isFirstPage($current_page) {
        return ($current_page == 1);
        return ! is_paged();
    }

    /**
     * @return bool
     */
    static private function _isLastPage($max_num_pages) {
        return !($max_num_pages > get_query_var('paged'));
    }

    /**
     * @param WP_Post $post
     * @param string $thumbnail_url
     * @return string
     */
    static private function _getGameMarkup($post, $thumbnail_url) {
        $permalink = get_permalink($post);
        return <<<MARKUP
            <li class="vh-item" id="post-{$post->ID}">
                <a class="vh-thumb-link" href="{$permalink}">
                    <div class="vh-overlay">
                        <img src="{$thumbnail_url}" title="{$post->post_title}" alt="{$post->post_title}" />
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
     * @return bool
     */
    static private function _isPaginated($query, $pagination) {
        return (($pagination == "on") && ($query->max_num_pages > 1)) ? $query->max_num_pages : 0;
    }

    /**
     * @param array $attributes Shortcode attributes
     * @param int $paged The current page number
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

    /**
     * Calculates the thumbnail url for img src attribute
     * @param WP_Post $post
     * @param Vegashero_Config $config
     * @return string 
     */
    static private function _getThumbnail($post, $config) {
        $terms = wp_get_post_terms($post->ID, $config->gameProviderTaxonomy, array('fields' => 'all'));
        $featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'vegashero-thumb');
        if($featured_image_src) {
            $thumbnail = $featured_image_src[0];
        } else {
            if( ! $thumbnail = get_post_meta($post->ID, $config->postMetaGameImg, true )) {
                if( ! $mypostslug = get_post_meta($post->ID, $config->postMetaGameTitle, true )) {
                    $mypostslug = $post->post_name;
                }
                $thumbnail = sprintf("%s/%s/%s/cover.jpg", $config->gameImageUrl, $terms[0]->slug, sanitize_title($mypostslug));
            }
        }
        return $thumbnail;
    }

    /**
     * Is current url a page or a post?
     * @return bool
     */
    static private function _isPage() {
        return is_page();
    }

    static private function _isPost() {
        return is_single();
    }

    /**
     * The current page for pagination
     * @return int
     */
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
