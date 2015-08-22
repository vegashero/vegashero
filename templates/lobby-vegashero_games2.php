<?php
if( get_option('permalink_structure') ) {
    $format = "page/%#%/";
} else {
    $format = "?page=%#%";
}

// $images = plugins_url('vegasgod/images');
$config = new Vegashero_Config();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$page = (get_query_var('page')) ? get_query_var('page') : 1;

$categories = get_terms($config->gameCategoryTaxonomy);
$operators = get_terms($config->gameOperatorTaxonomy);
$providers = get_terms($config->gameProviderTaxonomy);
$posts_per_page = get_option('posts_per_page');

$post_args = array(
  'posts_per_page'   => $posts_per_page,
  $config->gameOperatorTaxonomy => @$_GET[$config->gameOperatorTaxonomy] ? $_GET[$config->gameOperatorTaxonomy] : '',
  $config->gameCategoryTaxonomy => @$_GET[$config->gameCategoryTaxonomy] ? $_GET[$config->gameCategoryTaxonomy] : '',
  $config->gameProviderTaxonomy => @$_GET[$config->gameProviderTaxonomy] ? $_GET[$config->gameProviderTaxonomy] : '',
  'offset' => ($page-1)*$posts_per_page,
  'orderby'          => 'post_date',
  'order'            => 'DESC',
  'post_type'        => $config->customPostType,
  'post_status'      => 'publish',
  'paged' => $paged,
  'page' => $page
);
// echo '<pre>';
// print_r($post_args);
// echo '</pre>';

$posts = get_posts( $post_args );
$total_posts = wp_count_posts($config->customPostType)->publish;
$max_pages = ceil($total_posts/get_option('posts_per_page'));
?>
<div class="vh-filter">
  <select data-taxonomy="<?=$config->gameOperatorTaxonomy?>">
    <option selected disabled>Filter by operator</option>
    <?php foreach($operators as $operator): ?>
    <option value="<?=$operator->slug?>"><?=$operator->name?></option>
    <?php endforeach; ?>
  </select>

  <select data-taxonomy="<?=$config->gameCategoryTaxonomy?>">
    <option selected disabled>Filter by category</option>
    <?php foreach($categories as $category): ?>
    <option value="<?=$category->slug?>"><?=$category->name?></option>
    <?php endforeach; ?>
  </select>

  <select data-taxonomy="<?=$config->gameProviderTaxonomy?>">
    <option selected disabled>Filter by provider</option>
    <?php foreach($providers as $provider): ?>
    <option value="<?=$provider->slug?>"><?=$provider->name?></option>
    <?php endforeach; ?>
</select>
</div>

<div id="vh-lobby-posts" class="vh-row-sm">
<?php if (count($posts) > 0) : ?>

    <?php foreach($posts as $post):
        $game_id = get_post_meta($post->ID, $config->postMetaGameId, true);
        $game_src = get_post_meta($post->ID, $config->postMetaGameSrc, true);
        $game_title = get_post_meta($post->ID, $config->postMetaGameTitle, true);
        $game_category = get_post_meta(get_the_category($post->ID));
        $operator = wp_get_post_terms($post->ID, $config->gameOperatorTaxonomy)[0];
        $provider = wp_get_post_terms($post->ID, $config->gameProviderTaxonomy)[0];
        $image_url = sprintf("%s/%s/%s/", $config->gameImageUrl, $provider->name, $game_title);
        $post_slug = sprintf(sanitize_title($post->post_title));
        $operator_slug = sprintf(sanitize_title($operator->name));
        $provider_slug = sprintf(sanitize_title($provider->name));
        ?>
      <div class="vh-item">
        <a href="<?=site_url();?>/<?=$post->post_name?>" class="vh-thumb-link">
          <img width="" height="" src="<?=$image_url?>cover.jpg" alt="<?=$post->post_title?>" title="<?=$post->post_title?>" />
        </a>
        <div class="vh-game-title">
          <a title="<?=$post->post_title?>" href="<?=site_url();?>/<?=$post->post_name?>"><?=$post->post_title?></a>
          <span class="vh-game-cat"><?=$game_category?></span>
        </div>
      </div>
    <?php endforeach; ?>
    <div class="vh-pagination">
<?php
        $current_url = sprintf("http://%s%s", $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
        $big = $paged+1;
        $pagination_options = array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => $format,
            'current' => is_front_page() ? $page : $paged,
            'total' => $max_pages,
            'show_all' => false,
            'mid_size' => 0,
            'end_size' => 0,
            'prev_text' => __('« Previous'),
            'next_text' => __('Next »'),
            'type' => 'array',
        );
        // echo '<pre>';
        // print_r($pagination_options);
        // echo '</pre>';
        $pagination = paginate_links($pagination_options);
        echo preg_match('/^<a class="prev.*$/', current($pagination)) ? current($pagination) : '';
        echo preg_match('/^<a class="next.*$/', end($pagination)) ? end($pagination) : '';
?>
  </div>
</div>
<?php endif ?>
