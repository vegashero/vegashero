<?php $lobbylink = get_option( $id, 'off' ); ?>

<input name="<?=$id?>" id="<?=$id?>" type='checkbox' <?php if ($lobbylink=="on") echo ' checked="checked"';  ?> />
<p class='description' style='display:inline-block;'>Support our work by displaying a subtle backlink at the bottom of the lobby. We put many hours of work creating this plugin and we are greatful if you show your appreciation by ticking this box.</p>
