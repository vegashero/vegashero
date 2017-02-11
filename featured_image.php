<?php

namespace Vegashero;

add_filter('post_thumbnail_html', array('Vegashero\FeaturedImage', 'post_thumbnail_html'), $priority=10, $accepted_args=5);

class FeaturedImage {


    public static function post_thumbnail_html($html, $post_id, $post_image_id, $size, $attr) {
        echo "<pre>";
        print_r($html);
        echo "</pre>";

        echo "<pre>";
        print_r($post_id);
        echo "</pre>";

        echo "<pre>";
        print_r($post_image_id);
        echo "</pre>";

        echo "<pre>";
        print_r($size);
        echo "</pre>";

        echo "<pre>";
        print_r($attr);
        echo "</pre>";
        #if ($this->has_external_thumbnail($post_id)) {
        #    $html = $this->get_external_thumbnail($post_id, $size, $attr);
        #}

        return $html;
    }

    public static function get_external_thumbnail($post_id = null, $size = false, $attr = array()) {
        global $_wp_additional_image_sizes;

        if (!$this->has_external_thumbnail($post_id)) {
            return false;
        }

        if (is_array($size)) {
            $width = $size[0];
            $height = $size[1];
        } else if (isset($_wp_additional_image_sizes[$size])) {
            $width = $_wp_additional_image_sizes[$size]['width'];
            $height = $_wp_additional_image_sizes[$size]['height'];
            $additional_classes = 'attachment-' . $size . ' ';
        }

        $width = ($width && $width > 0) ? "width:${width}px;" : '';
        $height = ($height && $height > 0) ? "height:${height}px;" : '';

        if (isset($attr['class'])) {
            $additional_classes .= $attr['class'];
        }

        $thumbnail = $this->get_external_thumbnail_metadata($post_id);
        $style = isset($attr['style']) ? 'style="' . $attr['style'] . '" ' : null;

        if (is_feed()) {
            $html = sprintf('<img src="%s" %s' . 'class="%s wp-post-image" '. 'alt="%s" />', $thumbnail['url'], $style, $additional_classes, $thumbnail['alt']);
        } else {
            $html = sprintf('<img src="%s" %s' . 'class="%s wp-post-image" '. 'alt="%s" />', $thumbnail['url'], $style, $additional_classes, $thumbnail['alt']);
        }

        return $html;
    }

    public static function has_external_thumbnail($post_id = null) {
        $thumbnail = $this->get_external_thumbnail_metadata($post_id);

        if (empty($thumbnail['url'])) {
            return false;
        } else {
            return true;
        }
    }

}


