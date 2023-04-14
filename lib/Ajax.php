<?php

namespace VegasHero;

use VegasHero\{ Config, Translations };

class Ajax
{

    protected static $_instance = null;
    private $_config;
    private $_posts_per_page;

    protected function __construct() {
        $this->_config = Config::getInstance();
        add_action( 'wp_ajax_lobby_search_filter', array($this, 'filter_lobby'));
        add_action( 'wp_ajax_nopriv_lobby_search_filter', array($this, 'filter_lobby'));
        $posts_per_page = (int)get_option('vh_lobby_games_per_page');
        $this->_posts_per_page = $posts_per_page ? $posts_per_page : 20;

    }

    public static function getInstance(): Ajax {
        if ( null === self::$_instance ) {
            self::$_instance = new Ajax();
        }
        return self::$_instance;
    }

    public function filter_lobby() {
        $sortingGames = get_option('vh_lobby_games_sort');
        //default sorting
        $orderby = 'post_date';
        $order = 'DESC';

        if ($sortingGames=="datenewest") {
            $orderby = 'post_date';
            $order = 'DESC';
        }
        if ($sortingGames=="dateoldest") {
            $orderby = 'post_date';
            $order = 'ASC';
        }
        if ($sortingGames=="modifiednewest") {
            $orderby = 'modified';
            $order = 'DESC';
        }
        if ($sortingGames=="modifiedoldest") {
            $orderby = 'modified';
            $order = 'ASC';
        }
        if ($sortingGames=="titleaz") {
            $orderby = 'title';
            $order = 'ASC';
        }
        if ($sortingGames=="titleza") {
            $orderby = 'title';
            $order = 'DESC';
        }
        if ($sortingGames=="random") {
            $orderby = 'rand';
            $order = 'DESC';
        }

        $paged = @$_GET['paged'] ? $_GET['paged'] : 1;
        $page = @$_GET['page'] ? $_GET['page'] : 1;
        $post_args = array(
            'lang' => Translations::getLanguage(),
            'posts_per_page'   => $this->_posts_per_page,
            'offset' => ($page-1)*$this->_posts_per_page,
            'orderby'          => $orderby,
            'order'            => $order,
            'post_type'        => $this->_config->customPostType,
            'post_status'      => 'publish',
            'paged' => $paged,
            'page' => $page
        );

        if(array_key_exists('filterBy', $_GET) && ! empty($_GET['filterBy'])) {
            $filterBy = $_GET['filterBy'];
            if(array_key_exists('taxonomy', $_GET) && ! empty($_GET['taxonomy'])) {
                // it's a filter
                $taxonomy = $_GET['taxonomy'];
                $post_args[$taxonomy] = $filterBy;
            } else {
                // it's a search
                $post_args['s'] = $filterBy;
                $post_args['sentence'] = true;
            }
        }

        $posts = get_posts( $post_args );

        // for image links
        foreach($posts as $post) {
            $operators = get_terms(['object_ids' => $post->ID, 'taxonomy' => $this->_config->gameOperatorTaxonomy, 'lang' => function_exists('pll_current_language') ? pll_current_language() : get_locale()]);
            if(count($operators)) {
                $operator = $operators[0];
                $post->operator = sanitize_title($operator->name);
            }
            $providers = get_terms(['object_ids' => $post->ID, 'taxonomy' => $this->_config->gameProviderTaxonomy, 'lang' => function_exists('pll_current_language') ? pll_current_language() : get_locale()]);
            if(count($providers)) {
                $provider = $providers[0];
                $post->provider = sanitize_title($provider->name);
            }
            $categories = get_terms(['object_ids' => $post->ID, 'taxonomy' => $this->_config->gameCategoryTaxonomy, 'lang' => function_exists('pll_current_language') ? pll_current_language() : get_locale()]);
            if(count($categories)) {
                $category = $categories[0];
                $post->category = sanitize_title($category->name);
            }
            //has featured image?
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'vegashero-thumb');
            if($thumbnail) {
                $post->thumbnail = $thumbnail[0];
            } else {
                if(get_option('vh_lobbywebp') === 'on') {
                    $imgpathtemp = get_post_meta( $post->ID, $this->_config->postMetaGameImg, true );
                    $webpimgpath = str_replace('cover.jpg', 'cover.webp', $imgpathtemp);
                    $post->imgpath = $webpimgpath;
                } else {
                    $post->imgpath = get_post_meta( $post->ID, $this->_config->postMetaGameImg, true );
                }
            }
            // remove post content
            unset($post->post_content);
        }

        echo json_encode(array(
            'args' => $post_args,
            'page' => $page,
            'posts' => $posts,
            'pagination' => $this->_getPaginationLinks($paged, count($posts))
        ));
        wp_die();
    }

    private function _getPaginationLinks($paged, $total) {
        $pagination = [];
        if($pagination_links = paginate_links($this->_getPaginationOptions($paged, $total))) {
            if($total >= $this->_posts_per_page) {
                $pagination['next'] = $this->_getNext( $pagination_links );
            }
            $pagination['prev'] = $this->_getPrevious($pagination_links);
        }
        return $pagination;
    }

    private function _getNext($pagination) : array {
        if( preg_match( '/^<a class="next.*" href="(.*)">(.*)<\/a>$/', end($pagination), $matches)) {
            return [
                'href' => urldecode(trim($matches[1])),
                'text' => trim($matches[2])
            ];
        }
        return [];
    }

    private function _getPrevious($pagination) : array {
        if( preg_match( '/^<a class="prev.*" href="(.*)">(.*)<\/a>$/', current($pagination), $matches) ) {
            return [
                'href' => urldecode(trim($matches[1])),
                'text' => trim($matches[2])
            ];
        }
        return [];
    }

    private function _getFormat() {
        if( get_option('permalink_structure') ) {
            return "page/%#%/";
        }
        return "?page=%#%";
    }

    private function _getPaginationOptions($paged) {
        $prev_btn = get_option('vh_pagination_prev');
        $next_btn = get_option('vh_pagination_next');
        $total_posts = wp_count_posts($this->_config->customPostType)->publish;
        $max_pages = ceil($total_posts/$this->_posts_per_page);
        $big = 999999999;
        return array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => $this->_getFormat(),
            'current' => is_front_page() ? $page : $paged,
            'total' => $max_pages,
            'show_all' => false,
            'mid_size' => 0,
            'end_size' => 0,
            'prev_text' => $prev_btn ? $prev_btn : wp_strip_all_tags(__('Previous', 'vegashero')),
            'next_text' => $next_btn ? $next_btn : wp_strip_all_tags(__('Next', 'vegashero')),
            'type' => 'array',
        );
    }

}
