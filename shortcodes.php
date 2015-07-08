<?php

class Vegashero_Shortcodes
{

    private $_config;

    public function __construct() {
        $this->_config = new Vegashero_Config();
        add_shortcode('vegashero-lobby', array($this, 'lobby'));
        // add_action( 'init', array($this, 'lobby_filters') );
        add_action('wp_enqueue_scripts', array($this, 'lobby_filters'));
        add_action( 'wp_ajax_lobby_search_filter', array($this, 'filter_lobby'));
        add_action( 'wp_ajax_nopriv_lobby_search_filter', array($this, 'filter_lobby'));
    }

    public function lobby() {
        $lobby_template_file = sprintf('%s/templates/lobby-%s.php', dirname(__FILE__), $this->_config->customPostType);
        // return file_get_contents($lobby_template_file);
        include_once $lobby_template_file;
    }

    public function lobby_filters() {
        $script_src = sprintf('%stemplates/js/lobby_search_filters.js', plugin_dir_url( __FILE__ ));
        wp_enqueue_script('vegashero_lobby_script', $script_src, array('jquery'), null, true);
        wp_localize_script( 'vegashero_lobby_script', 'ajax_object', 
            array(
                'ajax_url' => admin_url('admin-ajax.php'), 
                'site_url' => site_url(),
                'image_url' => $this->_config->gameImageUrl
            )
        );
    }

    public function filter_lobby() {
        $taxonomy = $_GET['taxonomy'];
        $filter = $_GET['filter'];
        $posts_per_page = get_option('posts_per_page');
        $paged = @$_GET['paged'] ? $_GET['paged'] : 1;
        $page = @$_GET['page'] ? $_GET['page'] : 1;
        $post_args = array(
            'posts_per_page'   => $posts_per_page,
            $taxonomy => $filter,
            'offset' => ($page-1)*$posts_per_page,
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'post_type'        => $this->_config->customPostType,
            'post_status'      => 'publish',
            'paged' => $paged,
            'page' => $page
        );

        $posts = get_posts( $post_args );
        foreach($posts as $post) {
            $provider = wp_get_post_terms($post->ID, $this->_config->gameProviderTaxonomy)[0];
            $post->provider = sanitize_title($provider->name);
        }
        echo json_encode(array(
            'page' => $page,
            'posts' => $posts
        ));
        wp_die();
    }

}
