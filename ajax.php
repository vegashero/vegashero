<?php

class Vegashero_Ajax
{

    private $_config;
    private $_posts_per_page;

    public function __construct() {
        $this->_config = Vegashero_Config::getInstance();
        add_action( 'wp_ajax_lobby_search_filter', array($this, 'filter_lobby'));
        add_action( 'wp_ajax_nopriv_lobby_search_filter', array($this, 'filter_lobby'));
        $posts_per_page = get_option('vh_lobby_games_per_page');
        $this->_posts_per_page = $posts_per_page ? $posts_per_page : 20;
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
            $operators = wp_get_post_terms($post->ID, $this->_config->gameOperatorTaxonomy);
            if(count($operators)) {
                $operator = $operators[0];
                $post->operator = sanitize_title($operator->name);
            }
            $providers = wp_get_post_terms($post->ID, $this->_config->gameProviderTaxonomy);
            if(count($providers)) {
                $provider = $providers[0];
                $post->provider = sanitize_title($provider->name);
            }
            $categories = wp_get_post_terms($post->ID, $this->_config->gameCategoryTaxonomy);
            if(count($categories)) {
                $category = $categories[0];
                $post->category = sanitize_title($category->name);
            }
            //has featured image?
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'vegashero-thumb');
            if($thumbnail) {
                $post->thumbnail = $thumbnail[0];
            } else {
                $post->imgpath = get_post_meta( $post->ID, $this->_config->postMetaGameImg, true );
            }
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
        $pagination = array();
        if($pagination_links = paginate_links($this->_getPaginationOptions($paged, $total))) {
            if($total >= get_option('vh_lobby_games_per_page')) {
                if($next = $this->_getNext($pagination_links)) {
                    $pagination['next'] = $next;
                }
            }
            if($prev = $this->_getPrevious($pagination_links)) {
                $pagination['prev'] = $prev;
            }
        }
        return $pagination;
    }

    private function _getNext($pagination) {
        return preg_match('/^<a class="next.*$/', end($pagination)) ? end($pagination) : '';
    }

    private function _getPrevious($pagination) {
        return preg_match('/^<a class="prev.*$/', current($pagination)) ? current($pagination) : '';
    }

    private function _getFormat() {
        if( get_option('permalink_structure') ) {
            return "page/%#%/";
        }
        return "?page=%#%";
    }

    private function _getPaginationOptions($paged) {
        $prev_btn = get_option('vh_pagination_prev', '« Previous');
        $next_btn = get_option('vh_pagination_next', 'Next »');
        $total_posts = wp_count_posts($this->_config->customPostType)->publish;
        $max_pages = ceil($total_posts/get_option('vh_lobby_games_per_page'));
        $big = $paged+1;
        return array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => $this->_getFormat(),
            'current' => is_front_page() ? $page : $paged,
            'total' => $max_pages,
            'show_all' => false,
            'mid_size' => 0,
            'end_size' => 0,
            'prev_text' => $prev_btn,
            'next_text' => $next_btn,
            'type' => 'array',
        );
    }

}
