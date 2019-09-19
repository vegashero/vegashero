<?php
$orderGamesBy = get_option( $id, 'DESC' );
?>

<select name="<?=$id?>" id="<?=$id?>">
<option value="datenewest"<?php if ($orderGamesBy=="datenewest") echo ' selected="true"';  ?>><?= __("Publish Date (Newest first)", "vegashero") ?></option>
<option value="dateoldest"<?php if ($orderGamesBy=="dateoldest") echo ' selected="true"';  ?>><?= __("Publish Date (Oldest first)", "vegashero") ?></option>
<option value="modifiednewest"<?php if ($orderGamesBy=="modifiednewest") echo ' selected="true"';  ?>><?= __("Last Modified Date (Newest first)", "vegashero") ?></option>
<option value="modifiedoldest"<?php if ($orderGamesBy=="modifiedoldest") echo ' selected="true"';  ?>><?= __("Last Modified Date (Oldest first)", "vegashero") ?></option>
<option value="titleaz"<?php if ($orderGamesBy=="titleaz") echo ' selected="true"';  ?>><?= __("Alphabetical Title (A-Z)", "vegashero") ?></option>
<option value="titleza"<?php if ($orderGamesBy=="titleza") echo ' selected="true"';  ?>><?= __("Alphabetical Title (Z-A)", "vegashero") ?></option>
<option value="random"<?php if ($orderGamesBy=="random") echo ' selected="true"';  ?>><?= __("Random", "vegashero") ?></option>
</select>

<p class='description'><?= __("Set the default sorting method for games displayed in the Lobby.", "vegashero") ?></p>
