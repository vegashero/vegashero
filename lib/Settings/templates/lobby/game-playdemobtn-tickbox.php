<?php $gameplaynowbtn = get_option( $id, 'off' ); ?>

<input name="<?=esc_attr($id)?>" id="<?=esc_attr($id)?>" type='checkbox' <?php if ($gameplaynowbtn=="on") echo ' checked="checked"';  ?> />
    <p class='description' style='display:inline-block;'><?= wp_strip_all_tags(__("Disable the iframe loading upon page load and instead display a Play Demo button with some custom text over a game thumbnail image.", "vegashero")) ?></p>
