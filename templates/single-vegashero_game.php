<?php get_header();
$images = plugins_url('vegashero/templates/img/');
$config = new Vegashero_Config();
?>
<?php if(have_posts()): ?>
<?php while(have_posts()) : ?>
<?php 
the_post();
$game_id = get_post_meta(get_the_ID(), 'game_id', true);
$iframe_src = get_post_meta(get_the_ID(), 'game_src', true);
$categories = wp_get_post_terms(get_the_ID(), $config->gameCategoryTaxonomy);
$operators = wp_get_post_terms(get_the_ID(), $config->gameOperatorTaxonomy);
$provider = wp_get_post_terms(get_the_ID(), $config->gameProviderTaxonomy)[0];
?>

    <h1><?= the_title(); ?></h1>

    <!-- iframe and images -->
    <?php require_once "iframe-vegashero_game.php" ?>

    <!-- content -->
    <?php the_content(); ?>

    <!-- buttons for each operator -->
    <?php require_once "table-vegashero_game.php" ?>

<?php endwhile ?>
<?php endif ?>
