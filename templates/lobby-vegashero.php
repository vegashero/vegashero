<?php
if( get_option('permalink_structure') ) {
    $format = "page/%#%/";
} else {
    $format = "?page=%#%";
}

// $images = plugins_url('vegasgod/images');
$images = "http://cdn.vegasgod.com";
$config = new Vegashero_Config();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
  'posts_per_page'   => $config->postsPerPage,
  'offset'           => 0,
  'category'         => '',
  'category_name'    => '',
  'orderby'          => 'post_date',
  'order'            => 'DESC',
  'post_type'        => $config->customPostType,
  'post_status'      => 'publish',
  'suppress_filters' => true,
  'paged' => $paged
);
$posts = get_posts( $args ); 
$total_posts = wp_count_posts($config->customPostType)->publish;
$max_pages = ceil($total_posts/$config->postsPerPage);
?>

<h2>Vegas Hero Lobby</h2>

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
        <h4><?=$post->post_title?></h4>
        <img src="<?=$image_url?>/cover.jpg" alt="<?=$post->post_title?>" style="max-width:150px" title="<?=$post->post_title?>">
        <p>Category: <a href="/game-categories/<?=$category_slug?>"><?=$categories[0]->name?></a></p>
    <?php endforeach; ?>

<?php
        $current_url = sprintf("http://%s%s", $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
        $big = 9999999999;
        $pagination = paginate_links(
            array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => $format,
                'current' => max(1, $paged),
                'total' => $max_pages,
                'prev_text' => __('« Previous'),
                'next_text' => __('Next »'),
                'type' => 'array',
            ) 
        );
        echo '<pre>';
        print_r($pagination);
        echo '</pre>';
?>


<?php else: ?>

    Please add your affiliate code in settings > vegashero and import the games

<?php endif; ?>
