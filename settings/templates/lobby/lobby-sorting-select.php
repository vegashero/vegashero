<!-- <input name="<?=$id?>" id="<?=$id?>" type='select' min='1' step='1' value='<?=get_option($id)?get_option($id):20?>' /> -->

<?php

$orderGamesBy = get_option( $id, 'DESC' );

?>

<select name="<?=$id?>" id="<?=$id?>">
    <option value="datenewest"<?php if ($orderGamesBy=="datenewest") echo ' selected="true"';  ?>>Publish Date (Newest first)</option>
    <option value="dateoldest"<?php if ($orderGamesBy=="dateoldest") echo ' selected="true"';  ?>>Publish Date (Oldest first)</option>
    <option value="datenewest"<?php if ($orderGamesBy=="modifiednewest") echo ' selected="true"';  ?>>Last Modified Date (Newest first)</option>
    <option value="dateoldest"<?php if ($orderGamesBy=="modifiedoldest") echo ' selected="true"';  ?>>Last Modified Date (Oldest first)</option>
    <option value="titleaz"<?php if ($orderGamesBy=="titleaz") echo ' selected="true"';  ?>>Alphabetical Title (A-Z)</option>
    <option value="titleza"<?php if ($orderGamesBy=="titleza") echo ' selected="true"';  ?>>Alphabetical Title (Z-A)</option>
    <option value="random"<?php if ($orderGamesBy=="random") echo ' selected="true"';  ?>>Random</option>
</select>

<p class='description'>Set the default sorting method for games displayed in the Lobby.</p>
