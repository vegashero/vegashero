<?php

if ( ! defined( 'WPINC' ) ) {
    exit();
}

class Vegashero_Template
{

    private $_config = array();

    public function __construct() {

        $this->_config = new Vegashero_Config();
        $this->_images = plugins_url('vegashero/templates/img/');

        // add_filter( 'single_template', array($this, 'getSingleTemplate'));
        add_filter( 'archive_template', array($this, 'getArchiveTemplate'));
        add_filter( 'the_content', array($this, 'wrapSingleCustomPostContent'));

        // add custom template
        $this->templates = array();

        // Add a filter to the attributes metabox to inject template into the cache.
        add_filter('page_attributes_dropdown_pages_args', array( $this, 'registerProjectTemplates' ));

        // Add a filter to the save post to inject out template into the page cache
        add_filter('wp_insert_post_data', array( $this, 'registerProjectTemplates'));

        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter('template_include', array( $this, 'viewProjectTemplate'));

        // Add your templates to this array.

        $this->templates = array(
            'templates/custom-page-template.php'     => 'Vegas Hero Games Lobby',
        );
        //end

    }

    public function registerProjectTemplates( $atts ) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;

    }

    /**
     * Checks if the template is assigned to the page
     */
    public function viewProjectTemplate( $template ) {

        global $post;

        if (!isset($this->templates[get_post_meta(
            $post->ID, '_wp_page_template', true
        )] ) ) {

            return $template;

        }

        $file = plugin_dir_path(__FILE__). get_post_meta(
            $post->ID, '_wp_page_template', true
        );

        // Just to be safe, we check if the file exist first
        if( file_exists( $file ) ) {
            return $file;
        } else { echo $file; }

        return $template;

    }

    public function getArchiveTemplate($archive_template){

        $plugin_dir = plugin_dir_path(__FILE__);

        if ( is_post_type_archive ( $this->_config->customPostType ) ) {
            $archive_template = sprintf("%s/templates/archive-%s.php", $plugin_dir, $this->_config->customPostType);
        }
        return $archive_template;

    }

    public function getSingleTemplate($single_template) {

        $post_id = get_the_ID();

        if ( get_post_type( $post_id ) == $this->_config->customPostType ) {

            $single_template = $this->_getSinglePageTemplatePath();

        }
        return $single_template;
    }

    private function _getSinglePageTemplatePath() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/single-%s.php", $plugin_dir, $this->_config->customPostType);
    }

    public function appendTable($content) {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/single-%s.php", $plugin_dir, $this->_config->customPostType);
    }

    private function _getGalleryTemplate() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/gallery-%s.php", $plugin_dir, $this->_config->customPostType);
    }

    private function _getIframeTemplate() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/iframe-%s.php", $plugin_dir, $this->_config->customPostType);
    }

    private function _getTableBody() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/tablebody-%s.php", $plugin_dir, $this->_config->customPostType);
    }

    private function _getTableTemplate() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/table-%s.php", $plugin_dir, $this->_config->customPostType);
    }

    public function wrapSingleCustomPostContent($content) {

        $post_id = get_the_ID();

        if ( get_post_type( $post_id ) == $this->_config->customPostType ) {
            $images = plugins_url('vegashero/templates/img/');
            $this->_gameId = get_post_meta($post_id, 'game_id', true);
            $iframe_src = get_post_meta($post_id, 'game_src', true);
            $categories = wp_get_post_terms($post_id, $this->_config->gameCategoryTaxonomy);
            $operators = wp_get_post_terms($post_id, $this->_config->gameOperatorTaxonomy);
            $provider = wp_get_post_terms($post_id, $this->_config->gameProviderTaxonomy)[0];
            $gallery_string = file_get_contents($this->_getGalleryTemplate());
            $iframe_string = file_get_contents($this->_getIframeTemplate());
            $table_string = file_get_contents($this->_getTableTemplate());
            $gallery_template = sprintf($gallery_string, $images);
            $iframe_template = sprintf($iframe_string, $iframe_src);
            $tablebody_string = file_get_contents($this->_getTableBody());

            $tablebody_template = '';

            foreach($operators as $operator) {
                $tablebody_template .= sprintf($tablebody_string, $images, $operator->slug, $operator->name);
            }

            $table_template = sprintf($table_string, $tablebody_template);
            $content = sprintf("%s $content %s", $gallery_template, $iframe_template, $table_template);
        }
        return $content;
    }

}
