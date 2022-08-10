<?php $lobbylink = get_option( $id, 'off' ); ?>

<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type='checkbox' 
						<?php
						if ( $lobbylink == 'on' ) {
							echo ' checked="checked"';}
						?>
 />
	<p class='description' style='display:inline-block;'><?php echo wp_strip_all_tags( __( 'Support our work by displaying a subtle backlink at the bottom of the lobby. We are greatful if you show your appreciation by ticking this box.', 'vegashero' ) ); ?></p>
