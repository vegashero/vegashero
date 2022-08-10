<?php $lobbywebp = get_option( $id, 'off' ); ?>

<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type='checkbox' 
						<?php
						if ( $lobbywebp == 'on' ) {
							echo ' checked="checked"';}
						?>
 />
	<p class='description' style='display:inline-block;'><?php echo wp_strip_all_tags( __( 'Display smaller and more optimized .webp format game thumbnails in the Lobby and Game grids instead of the default jpeg format.', 'vegashero' ) ); ?></p>
