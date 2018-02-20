<?php $lobbysearch = get_option( $id, 'off' ); ?>

<input name="<?=$id?>" id="<?=$id?>" type='checkbox' <?php if ($lobbysearch=="on") echo ' checked="checked"';  ?> />
<p class='description' style='display:inline-block;'>You can display a search field in the lobby for dynamic games search. Visitors can filter games by entering any keyword.</p>
