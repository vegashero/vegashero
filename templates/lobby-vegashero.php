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
  'posts_per_page'   => get_option('posts_per_page'),
  $config->gameOperatorTaxonomy => (get_query_var($config->gameOperatorTaxonomy)) ? get_query_var($config->gameOperatorTaxonomy) : '',
  $config->gameCategoryTaxonomy => (get_query_var($config->gameCategoryTaxonomy)) ? get_query_var($config->gameCategoryTaxonomy) : '',
  $config->gameProviderTaxonomy => (get_query_var($config->gameProviderTaxonomy)) ? get_query_var($config->gameProviderTaxonomy) : '',
  'orderby'          => 'post_date',
  'order'            => 'DESC',
  'post_type'        => $config->customPostType,
  'post_status'      => 'publish',
  'paged' => $paged
);
$posts = get_posts( $args ); 
$total_posts = wp_count_posts($config->customPostType)->publish;
$max_pages = ceil($total_posts/get_option('posts_per_page'));
?>

<h2>Vegas Hero Lobby</h2>

<?php if (count($posts) > 0) : ?>

    <?php foreach($posts as $post):
        $post_meta = get_post_meta($post->ID, $config->metaKey, true);
        $operator = wp_get_post_terms($post->ID, $config->gameOperatorTaxonomy)[0];
        $provider = wp_get_post_terms($post->ID, $config->gameProviderTaxonomy)[0];
        $image_url = sprintf("%s/%s/%s/", $images, $provider->name, sanitize_title($post->post_title));
        $post_slug = sprintf(sanitize_title($post->post_title));
        $operator_slug = sprintf(sanitize_title($operator->name));
        $provider_slug = sprintf(sanitize_title($provider->name));
        ?>
        <h4><a href="/games/<?=$post->post_name?>"><?=$post->post_title?></a></h4>
        <img src="<?=$image_url?>/cover.jpg" alt="<?=$post->post_title?>" style="max-width:150px" title="<?=$post->post_title?>">
        <?php 
        $category = wp_get_post_terms($post->ID, $config->gameCategoryTaxonomy)[0];
        if ($category):
            $category_slug = sprintf(sanitize_title($category->name));?>
        <p>Filter by game category: <a href="?<?=$config->gameCategoryTaxonomy?>=<?=$category_slug?>"><?=$category->name?></a></p>
        <?php endif ?>
        <p>Filter by game operator: <a href="?<?=$config->gameOperatorTaxonomy?>=<?=$operator_slug?>"><?=$operator->name?></a></p>
        <p>Filter by game provider: <a href="?<?=$config->gameProviderTaxonomy?>=<?=$provider_slug?>"><?=$provider->name?></a></p>
    <?php endforeach; ?>

<?php
        $current_url = sprintf("http://%s%s", $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
        $big = $paged+1;
        $pagination = paginate_links(
            array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => $format,
                'current' => max(1, $paged),
                'total' => $max_pages,
                'show_all' => false,
                'mid_size' => 0,
                'end_size' => 0,
                'prev_text' => __('« Previous'),
                'next_text' => __('Next »'),
                'type' => 'array',
            ) 
        );
        echo preg_match('/^<a class="prev.*$/', current($pagination)) ? current($pagination) : '';
        echo preg_match('/^<a class="next.*$/', end($pagination)) ? end($pagination) : '';

?>

<?php else: ?>

    Please add your affiliate code in settings > vegashero and import the games

<?php endif; ?>
