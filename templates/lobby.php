<?php

require_once( dirname( __FILE__ ) . '/../config.php' );

$config = \VegasHero\Config::getInstance();
$categories = get_terms(['taxonomy' => $config->gameCategoryTaxonomy, 'lang' => \VegasHero\Translations\get_language()]);
$operators = get_terms(['taxonomy' => $config->gameOperatorTaxonomy, 'lang' => '']);
$providers = get_terms(['taxonomy' => $config->gameProviderTaxonomy, 'lang' => '']);

// TODO refactor into a method
$provider_query_var = get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_provider_url_slug')) : get_option('vh_game_provider_url_slug');
$operator_query_var = get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_operator_url_slug')) : get_option('vh_game_operator_url_slug');
$category_query_var = get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_category_url_slug')) : get_option('vh_game_category_url_slug');

?>

<div class="lobby-wrap">

<div class="vh-filter">
<?php if(count($operators)): ?>
  <select data-taxonomy="<?=$operator_query_var?>">
    <option selected disabled><?= ! get_option('vh_lobby_filterstext_op') ? wp_strip_all_tags(__('Filter by operator', 'vegashero')) : get_option('vh_lobby_filterstext_op'); ?></option>
    <?php foreach($operators as $operator): ?>
    <option value="<?=$operator->slug?>"><?=$operator->name?> (<?=$operator->count?>)</option>
    <?php endforeach; ?>
  </select>
<?php endif ?>

<?php if(count($categories)): ?>
  <select data-taxonomy="<?=$category_query_var?>">
    <option selected disabled><?= ! get_option('vh_lobby_filterstext_cat') ? wp_strip_all_tags(__('Filter by category', 'vegashero')) : get_option('vh_lobby_filterstext_cat'); ?></option>
    <?php foreach($categories as $category): ?>
    <option value="<?=$category->slug?>"><?=$category->name?> (<?=$category->count?>)</option>
    <?php endforeach; ?>
  </select>
<?php endif ?>

<?php if(count($providers)): ?>
  <select data-taxonomy="<?=$provider_query_var?>">
    <option selected disabled><?= ! get_option('vh_lobby_filterstext_prov') ? wp_strip_all_tags(__('Filter by provider', 'vegashero')) : get_option('vh_lobby_filterstext_prov'); ?></option>
    <?php foreach($providers as $provider): ?>
    <option value="<?=$provider->slug?>"><?=$provider->name?> (<?=$provider->count?>)</option>
    <?php endforeach; ?>
  </select>
<?php endif ?>

<?php if(get_option('vh_lobbysearch') === 'on'): ?>
<input type="text" id="vh-search" class="vh-search" placeholder="<?= ! get_option('vh_lobbysearchtext') ? wp_strip_all_tags(__('search', 'vegashero')) : get_option('vh_lobbysearchtext'); ?>" />
<?php endif ?>

</div>

<ul id="vh-lobby-posts" class="vh-row-sm"><span class="loading-icon"><?= wp_strip_all_tags(__('loading games...', 'vegashero')) ?></span></ul>
<?php if(get_option('vh_lobbylink') === 'on'): ?>
<div class="vh-linklove">- <a target="_blank" href="https://vegashero.co"><?= wp_strip_all_tags(__('VegasHero Casino Affiliate Plugin', 'vegashero')) ?></a> -</div>
<?php else: ?>
<?php endif ?>

</div>