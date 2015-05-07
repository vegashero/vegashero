<?php

if ( ! defined( 'WPINC' ) ) {
    exit();
}

class Vegashero_Template
{

    private $_config;

    public function __construct() {

        $this->_config = new Vegashero_Config();
        $this->_images = plugins_url('vegashero/templates/img/');

        add_filter( 'single_template', array($this, 'getSingleTemplate'));
        add_filter( 'archive_template', array($this, 'getArchiveTemplate'));
        add_filter( 'page_template', array($this, 'getArchiveTemplate'));

    }

    private function _getArchivePageTemplateFile() {
        return sprintf("archive-%s.php", $this->_config->customPostType);
    }

    private function _getArchivePagePluginTemplatePath() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/%s", $plugin_dir, $this->_getArchivePageTemplateFile());
    }

    private function _getArchivePageTemplatePath() {
        $current_theme_dir = get_template_directory();
        return sprintf("%s/%s", $current_theme_dir, $this->_getArchivePageTemplateFile());
    }

    private function _getSinglePagePluginTemplatePath() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/%s", $plugin_dir, $this->_getSinglePageTemplateFile());
    }

    private function _getSinglePageTemplatePath() {
        $current_theme_dir = get_template_directory();
        return sprintf("%s/%s", $current_theme_dir, $this->_getSinglePageTemplateFile());
    }

    private function _getSinglePageTemplateFile() {
        return sprintf("single-%s.php", $this->_config->customPostType);
    }
    
    private function _getPagePluginTemplatePath() {
        $plugin_dir = plugin_dir_path(__FILE__);
        return sprintf("%s/templates/%s", $plugin_dir, $this->_getPageTemplateFile());
    }

    private function _getPageTemplatePath() {
        $current_theme_dir = get_template_directory();
        return sprintf("%s/%s", $current_theme_dir, $this->_getPageTemplateFile());
    }

    private function _getPageTemplateFile() {
        return sprintf("page-%s.php", $this->_config->customPostType);
    }

    public function getPageTemplate($page_template) {
        $post_id = get_the_ID();
        if ( get_post_type( $post_id ) == $this->_config->customPostType && ! file_exists($this->_getPageTemplatePath()) ) {
            $page_template = $this->_getPagePluginTemplatePath();
        }
        return $page_template;
    }

    public function getArchiveTemplate($archive_template) {
        $post_id = get_the_ID();
        if ( get_post_type( $post_id ) == $this->_config->customPostType && ! file_exists($this->_getArchivePageTemplatePath()) ) {
            $archive_template = $this->_getArchivePagePluginTemplatePath();
        }
        return $archive_template;
    }

    public function getSingleTemplate($single_template) {
        $post_id = get_the_ID();
        if ( get_post_type( $post_id ) == $this->_config->customPostType && ! file_exists($this->_getSinglePageTemplatePath())) {
            $single_template = $this->_getSinglePagePluginTemplatePath();
        }
        return $single_template;
    }

}
