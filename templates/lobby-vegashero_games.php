<?php
$config = new Vegashero_Config();
$categories = get_terms($config->gameCategoryTaxonomy);
$operators = get_terms($config->gameOperatorTaxonomy);
$providers = get_terms($config->gameProviderTaxonomy);
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

  <input type="text" id="vh-search" class="vh-search" placeholder="Search" />
</div>

<ul id="vh-lobby-posts" class="vh-row-sm"><span class="loading-icon">loading games...</span></ul>
