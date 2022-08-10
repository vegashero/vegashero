<?php $lobbysearch = get_option( $id, 'off' ); ?>

<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type='checkbox' 
						<?php
						if ( $lobbysearch == 'on' ) {
							echo ' checked="checked"';}
						?>
 />
	<p class='description' style='display:inline-block;'><?php echo wp_strip_all_tags( __( 'You can display a search field in the lobby for dynamic games search. Visitors can filter games by entering any keyword.', 'vegashero' ) ); ?></p>
