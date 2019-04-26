<form id="addtag" method="post" action="edit-tags.php" class="validate">
    <input type="hidden" name="action" value="add-tag">
    <input type="hidden" name="screen" value="edit-category">
    <input type="hidden" name="taxonomy" value="category">
    <input type="hidden" name="post_type" value="post">
    <input type="hidden" id="_wpnonce_add-tag" name="_wpnonce_add-tag" value="c737e36386"><input type="hidden" name="_wp_http_referer" value="/wp-admin/edit-tags.php?taxonomy=category">
    <div class="form-field form-required operator-logo-wrap">
        <label for="operator-logo">Logo</label>
        <button id="operator-logo" class="button">Add operator logo</button>
        <p>The name is how it appears on your site.</p>
    </div>
    <div class="form-field form-required operator-name-wrap">
        <label for="operator-name">Name</label>
        <input name="operator-name" id="tag-name" type="text" value="" size="40" aria-required="true">
        <p>The name is how it appears on your site.</p>
    </div>
    <div class="form-field form-required operator-link-wrap">
        <label for="operator-link">Affiliate link</label>
        <input name="operator-link" id="operator-link" type="text" value="" size="40" aria-required="true">
        <p>The name is how it appears on your site.</p>
    </div>
    <div class="form-field form-required operator-providers">
        <label for="operator-providers">Providers associated with this operator</label>
        <select id="operator-providers" name="operator-providers" multiple="multiple">
          <option>Provider 1</option>
          <option>Provider 2</option>
          <option>Provider 3</option>
          <option>Provider 4</option>
          <option>Provider 5</option>
          <option>Provider 6</option>
        </select>
        <p>The name is how it appears on your site.</p>
    </div>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Add New Operator"></p>
</form>
