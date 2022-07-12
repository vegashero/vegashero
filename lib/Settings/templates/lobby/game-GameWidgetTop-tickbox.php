<?php $gamewidgettop = get_option( $id, 'off' ); ?>

<input name="<?=esc_attr($id)?>" id="<?=esc_attr($id)?>" type='checkbox' <?php if ($gamewidgettop=="on") echo ' checked="checked"';  ?> />
    <p class='description' style='display:inline-block;'><?= wp_strip_all_tags(__("Show the Single Game widget area directly under the game iframe, instead of the bottom of game post content.", "vegashero")) ?></p>
