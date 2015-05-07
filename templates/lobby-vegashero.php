<?php
// $images = plugins_url('vegasgod/images');
$images = "http://cdn.vegasgod.com";
$config = new Vegashero_Config();
$current_page = get_query_var('page');
$args = array(
  'posts_per_page'   => $config->postsPerPage,
  'offset'           => 0,
  'category'         => '',
  'category_name'    => '',
  'orderby'          => 'post_date',
  'order'            => 'DESC',
  'post_type'        => $config->customPostType,
  'post_status'      => 'publish',
  'suppress_filters' => true 
);
$posts = get_posts( $args ); ?>

<h2>Vegas Hero Lobby</h2>
<p>Current page is <?=$current_page?></p>

<?php if (count($posts) > 0) : ?>

    <?php foreach($posts as $post):
        $post_meta = get_post_meta($post->ID, $config->metaKey, true);
        $categories = wp_get_post_terms($post->ID, $config->gameCategoryTaxonomy);
        $operators = wp_get_post_terms($post->ID, $config->gameOperatorTaxonomy);
        $provider = wp_get_post_terms($post->ID, $config->gameProviderTaxonomy)[0];
        $image_url = sprintf("%s/%s/%s/", $images, $provider->name, sanitize_title($post->post_title));
        $post_slug = sprintf(sanitize_title($post->post_title));
        $category_slug = sprintf(sanitize_title($categories[0]->name));
        ?>
        <img src="<?=$image_url?>/cover.jpg" alt="<?php the_title(); ?>" style="max-width:150px" title="<?=$post->post_title?>">
        <p>Category: <a href="/game-categories/<?=$category_slug?>"><?=$categories[0]->name?></a></p>
    <?php endforeach; ?>

<?php else: ?>

    Please add your affiliate code in settings > vegashero and import the games

<?php endif; ?>
