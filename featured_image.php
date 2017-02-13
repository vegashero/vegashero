<?php

namespace Vegashero;


class FeaturedImage {

    public static function post_thumbnail_html($html, $post_id, $post_image_id, $size, $attr) {
        if (self::has_external_thumbnail($post_id)) {
            $html = self::get_external_thumbnail($post_id, $size, $attr);
        }
        return $html;
    }

    public static function get_external_thumbnail_metadata($post_id = null) {
        $post_id = (null === $post_id) ? get_the_ID() : $post_id;
        $config = \Vegashero_Config::getInstance();

        $post_meta = get_post_meta($post_id, '_' . $config->postMetaGameImg, true);

        if (empty($post_meta) || strlen($post_meta) == 0) {
            return false;
        } else {
            return $post_meta;
        }
    }

    public static function get_external_thumbnail($post_id = null, $size = false, $attr = array()) {
        global $_wp_additional_image_sizes;

        if (!$this->has_external_thumbnail($post_id)) {
            return false;
        }

        $config = \Vegashero_Config::getInstance();
        $thumbnail = self::get_external_thumbnail_metadata($post_id);
        $html = sprintf('<img src="%s" class="wp-post-image" '. 'alt="%s" />', $thumbnail, 'alt text goes here');

        return the_post_thumbnail();
        //return $html;
    }

    public static function has_external_thumbnail($post_id = null) {
        $config = \Vegashero_Config::getInstance();
        $game_img = get_post_meta($post_id, $config->postMetaGameImg, true);
		echo "game img: ";
		echo $game_img;
		echo "<br>";
		$attachment = array(
			'guid'           => $game_img,
			'post_mime_type' => 'image/jpg',
			'post_title'     => 'howdy',
			'post_content'   => '',
			'post_status'    => 'inherit'
		);


		$attachment_id = wp_insert_attachment( $attachment, 'howdy.jpg', $post_id );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attachment_id, $game_img );
        echo "meta";
        echo "<pre>";
        print_r($attach_data);
        echo "</pre>";
		wp_update_attachment_metadata( $attachment_id, $attach_data );

        if (empty($game_img)) {
            return false;
        } else {
            return true;
        }
    }

	public static function the_post($post) {

		$post_id = is_array($post) ? $post['ID'] : $post->ID;
        $config = \Vegashero_Config::getInstance();

		$external_thumbnail = self::has_external_thumbnail($post_id);
		$wordpress_thumbnail = get_post_meta($post_id, '_thumbnail_id', true);

		if ($external_thumbnail && !$wordpress_thumbnail) {
			//update_post_meta($post_id, '_thumbnail_id', sprintf("_%s_%d", $config->customPostType, $post_id));
		}
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );
        echo sprintf("post thumbnail id: %s<br>", $post_thumbnail_id);
        echo sprintf("attachment url: %s<br>", wp_get_attachment_url($post_thumbnail_id)); 

        $attachment = get_post( $post_id );
        echo "<pre>";
        print_r($attachment);
        echo "</pre>";
        $attachments = get_children( 
            array(
                'post_parent' => get_the_ID(), 
                'post_type' => 'attachment', 
                'post_mime_type' => 'image'
            )
        );
        echo "<pre>";
        print_r($attachments);
        echo "</pre>";

        #wp_update_attachment_metadata($post_id, $post_meta);
#        $test = get_post_meta( $attachment->ID, '_thumbnail_id', true );
#        $info = array(
#            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_url', true ),
#            'caption' => $attachment->post_excerpt,
#            'description' => $attachment->post_content,
#            'href' => get_permalink( $attachment->ID ),
#            'src' => $attachment->guid,
#            'title' => $attachment->post_title
#        );

		/*if (!$external_thumbnail && $wordpress_thumbnail == '_' . $config->customPostType) {
			delete_post_meta($post_id, '_thumbnail_id');
        }*/
	}

}


