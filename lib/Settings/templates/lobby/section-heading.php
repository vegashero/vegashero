<p class="description">
	<?php /* translators: %1$s will be replaced by a website URL */ echo wp_kses(
		sprintf( __( 'Use these settings to configure the game lobby displayed by the <a target="_blank" href="%1$s">vh-lobby</a> shortcode.', 'vegashero' ), esc_url( 'https://vegashero.co/lobby-permalink-customization-settings/' ) ),
		array(
			'a' => array(
				'target' => true,
				'href'   => true,
			),
		)
	); ?>
</p>
