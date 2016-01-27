<?php
wp_enqueue_script('post');
//$_wp_editor_expand = $_content_editor_dfw = false;
$post_type = 'vegashero_games';
if ( post_type_supports( $post_type, 'editor' ) && ! wp_is_mobile() &&
	 apply_filters( 'wp_editor_expand', true, $post_type )) {

	wp_enqueue_script('editor-expand');
	$_content_editor_dfw = true;
	$_wp_editor_expand = ( get_user_setting( 'editor_expand', 'on' ) === 'on' );
}

if ( wp_is_mobile() )
	wp_enqueue_script( 'jquery-touch-punch' );

?>
      <div class="wrap about-wrap">
        <h1>Custom affiliate links</h1>
        <div class="about-text">
            Combine different game providers for your affiliate urls
        </div>
        <!-- <div class="vh-badge">Version 1.0</div> -->
        <hr>
        <h3>Providers available to install</h3>
        <div id="tagsdiv-game_provider" class="postbox">
<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Game Providers</span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle"><span>Game Providers</span></h2>
<div class="inside">
<div class="tagsdiv" id="game_provider">
    <div class="jaxtag">
    <div class="nojs-tags hide-if-js">
        <label for="tax-input-game_provider">Add or remove tags</label>
        <p><textarea name="tax_input[game_provider]" rows="3" cols="20" class="the-tags" id="tax-input-game_provider" aria-describedby="new-tag-game_provider-desc"></textarea></p>
    </div>
        <div class="ajaxtag hide-if-no-js">
        <label class="screen-reader-text" for="new-tag-game_provider">Add New Game Provider</label>
        <p><input type="text" id="new-tag-game_provider" name="newtag[game_provider]" class="newtag form-input-tip" size="16" autocomplete="off" aria-describedby="new-tag-game_provider-desc" value="">
        <input type="button" class="button tagadd" value="Add"></p>
    </div>
    <p class="howto" id="new-tag-game_provider-desc">Separate tags with commas</p>
        </div>
    <div class="tagchecklist"></div>
</div>
<p class="hide-if-no-js"><a href="#titlediv" class="tagcloud-link" id="link-game_provider">Choose from the most used tags</a></p>
</div>
</div>

      </div>
