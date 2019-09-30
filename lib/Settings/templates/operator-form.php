<form id="addtag" method="post" action="edit-tags.php" class="validate">
    <input type="hidden" name="action" value="add-tag">
    <input type="hidden" name="screen" value="edit-category">
    <input type="hidden" name="taxonomy" value="category">
    <input type="hidden" name="post_type" value="post">
    <input type="hidden" id="_wpnonce_add-tag" name="_wpnonce_add-tag" value="c737e36386"><input type="hidden" name="_wp_http_referer" value="/wp-admin/edit-tags.php?taxonomy=category">
    <div class="form-field form-required operator-logo-wrap">
        <label for="operator-logo"><?= __('Logo', 'vegashero') ?></label>
        <button id="operator-logo" class="button"><?= __('Add operator logo', 'vegashero') ?></button>
        <p><?= __('The name is how it appears on your site.', 'vegashero') ?></p>
    </div>
    <div class="form-field form-required operator-name-wrap">
        <label for="operator-name"><?= __('Name', 'vegashero') ?></label>
        <input name="operator-name" id="tag-name" type="text" value="" size="40" aria-required="true">
        <p><?= __('The name is how it appears on your site.', 'vegashero') ?></p>
    </div>
    <div class="form-field form-required operator-link-wrap">
        <label for="operator-link"><?= __('Affiliate link', 'vegashero') ?></label>
        <input name="operator-link" id="operator-link" type="text" value="" size="40" aria-required="true">
        <p><?= __('The name is how it appears on your site.', 'vegashero') ?></p>
    </div>
    <div class="form-field form-required operator-providers">
        <label for="operator-providers"><?= __('Providers associated with this operator', 'vegashero') ?></label>
        <select id="operator-providers" name="operator-providers" multiple="multiple">
        <option><?= __('Provider', 'vegashero') ?> <?= __('1', 'vegashero') ?></option>
        <option><?= __('Provider', 'vegashero') ?> <?= __('2', 'vegashero') ?></option>
        <option><?= __('Provider', 'vegashero') ?> <?= __('3', 'vegashero') ?></option>
        <option><?= __('Provider', 'vegashero') ?> <?= __('4', 'vegashero') ?></option>
        <option><?= __('Provider', 'vegashero') ?> <?= __('5', 'vegashero') ?></option>
        <option><?= __('Provider', 'vegashero') ?> <?= __('6', 'vegashero') ?></option>
        </select>
        <p><?= __('The name is how it appears on your site.', 'vegashero') ?></p>
    </div>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Add New Operator', 'vegashero') ?>"></p>
</form>
