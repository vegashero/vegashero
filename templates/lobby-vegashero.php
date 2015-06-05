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

$categories = get_terms($config->gameCategoryTaxonomy);
$operators = get_terms($config->gameOperatorTaxonomy);
$providers = get_terms($config->gameProviderTaxonomy);

$post_args = array(
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
$posts = get_posts( $post_args );
$total_posts = wp_count_posts($config->customPostType)->publish;
$max_pages = ceil($total_posts/get_option('posts_per_page'));
?>
<div class="vh-filter">
  <!-- <select onchange="window.location = '?<?=$config->gameOperatorTaxonomy?>=' + this.options[this.selectedIndex].value">
    <option selected disabled>Filter by operator</option>
    <?php foreach($operators as $operator): ?>
    <option value="<?=$operator->slug?>"><?=$operator->name?></option>
    <?php endforeach; ?>
</select> -->

  <select onchange="window.location = '?<?=$config->gameCategoryTaxonomy?>=' + this.options[this.selectedIndex].value">
    <option selected disabled>Filter by category</option>
    <?php foreach($categories as $category): ?>
    <option value="<?=$category->slug?>"><?=$category->name?></option>
    <?php endforeach; ?>
  </select>

  <select onchange="window.location = '?<?=$config->gameProviderTaxonomy?>=' + this.options[this.selectedIndex].value">
    <option selected disabled>Filter by provider</option>
    <?php foreach($providers as $provider): ?>
    <option value="<?=$provider->slug?>"><?=$provider->name?></option>
    <?php endforeach; ?>
</select>
</div>
<div class="vh-row-sm">
<?php if (count($posts) > 0) : ?>

    <?php foreach($posts as $post):
        $game_id = get_post_meta($post->ID, $config->postMetaGameId, true);
        $game_src = get_post_meta($post->ID, $config->postMetaGameSrc, true);
        $game_title = get_post_meta($post->ID, $config->postMetaGameTitle, true);
        $operator = wp_get_post_terms($post->ID, $config->gameOperatorTaxonomy)[0];
        $provider = wp_get_post_terms($post->ID, $config->gameProviderTaxonomy)[0];
        $image_url = sprintf("%s/%s/%s/", $images, $provider->name, $game_title);
        $post_slug = sprintf(sanitize_title($post->post_title));
        $operator_slug = sprintf(sanitize_title($operator->name));
        $provider_slug = sprintf(sanitize_title($provider->name));
        ?>
      <div class="vh-item">

        <a href="<?=site_url();?>/<?=$post->post_name?>">
          <img width="" height="" src="<?=$image_url?>cover.jpg" alt="<?=$post->post_title?>" title="<?=$post->post_title?>" />
          </a>
          <div class="vh-game-title">
            <a href="<?=site_url();?>/<?=$post->post_name?>"><?=$post->post_title?></a>
          </div>
      </div>
    <?php endforeach; ?>
    <div class="vh-pagination">
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
  </div>
</div>
<?php else:

  $current_cat = $_GET["vegashero_providers"];

  if ($current_cat == NULL) {

  } else {
    wp_redirect( get_permalink() . '?vegashero_providers=' . $_GET["vegashero_providers"] );
  }
 endif; ?>
