<?php $gamesarchives = get_option( $id, 'off' ); ?>

<input name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" type='checkbox' 
						<?php
						if ( $gamesarchives == 'on' ) {
							echo ' checked="checked"';}
						?>
/>
	<p class="description" style="display:inline;">
	<?php
	/* translators: %1$s will be replaced by the current website URL */ echo wp_kses(
		sprintf( __( 'You can disable the games archives page and give priority to posts/pages with same URL slug. <br>For example, you want to use a lobby page URL like %1$s/<u>game</u>/ and have your game base url as %1$s/<u>game</u>/game-title/', 'vegashero' ), site_url() ),
		[
			'br' => [],
			'u'  => [],
		]
	);
	?>
	</p>
