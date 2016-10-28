<?php

require_once( dirname( __FILE__ ) . '/../config.php' );

$config = Vegashero_Config::getInstance();
$categories = get_terms($config->gameCategoryTaxonomy);
$operators = get_terms($config->gameOperatorTaxonomy);
$providers = get_terms($config->gameProviderTaxonomy);

// TODO refactor into a method
$provider_query_var = get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_provider_url_slug')) : get_option('vh_game_provider_url_slug');
$operator_query_var = get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_operator_url_slug')) : get_option('vh_game_operator_url_slug');
$category_query_var = get_option('vh_custom_post_type_url_slug') ? sprintf('%s-%s', get_option('vh_custom_post_type_url_slug'), get_option('vh_game_category_url_slug')) : get_option('vh_game_category_url_slug');
?>
<div class="vh-filter">

<?php if(count($operators)): ?>
  <select data-taxonomy="<?=$operator_query_var?>">
    <option selected disabled><?php if (get_option('vh_lobby_filterstext_op')=='') echo 'Filter by operator'; else { echo get_option('vh_lobby_filterstext_op'); } ?></option>
    <?php foreach($operators as $operator): ?>
    <option value="<?=$operator->slug?>"><?=$operator->name?> (<?=$operator->count?>)</option>
    <?php endforeach; ?>
  </select>
<?php endif ?>

<?php if(count($categories)): ?>
  <select data-taxonomy="<?=$category_query_var?>">
    <option selected disabled><?php if (get_option('vh_lobby_filterstext_cat')=='') echo 'Filter by category'; else { echo get_option('vh_lobby_filterstext_cat'); } ?></option>
    <?php foreach($categories as $category): ?>
    <option value="<?=$category->slug?>"><?=$category->name?> (<?=$category->count?>)</option>
    <?php endforeach; ?>
  </select>
<?php endif ?>

<?php if(count($providers)): ?>
  <select data-taxonomy="<?=$provider_query_var?>">
    <option selected disabled><?php if (get_option('vh_lobby_filterstext_prov')=='') echo 'Filter by provider'; else { echo get_option('vh_lobby_filterstext_prov'); } ?></option>
    <?php foreach($providers as $provider): ?>
    <option value="<?=$provider->slug?>"><?=$provider->name?> (<?=$provider->count?>)</option>
    <?php endforeach; ?>
  </select>
<?php endif ?>

  <!-- <input type="text" id="vh-search" class="vh-search" placeholder="Search" /> -->
</div>

<ul id="vh-lobby-posts" class="vh-row-sm"><span class="loading-icon">loading games...</span></ul>
<?php if(get_option('vh_lobbylink') === 'on'): ?>
  <div class="vh-linklove">- <a target="_blank" href="https://vegashero.co">VegasHero Casino Affiliate Plugin</a> -</div>
<?php else: ?>
<?php endif ?>
