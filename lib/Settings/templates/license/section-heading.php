<?php if ( get_option( 'vh_license_status' ) === 'valid' ) : ?>
	<div><!-- display this if valid license key entered --></div>
<?php else : ?>
<div class="updated" style="display:block!important;">
	<h3 style="margin-top:0.5em;"><?php echo wp_strip_all_tags( __( 'Get a license key and add 2000+ games to your website!', 'vegashero' ) ); ?></h3>
	<p class="description">
		<?php
		echo wp_kses(
			sprintf( __( 'The free version of the plugin will let you import 2 games per software provider. To get full access to the game database: <strong><a target="_blank" href="%1$s">purchase a license key here.</a></strong>', 'vegashero' ), esc_url( 'https://vegashero.co/downloads/vegas-hero-plugin/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page' ) ),
			[
				'strong' => [],
				'a'      => [
					'target' => true,
					'href'   => true,
				],
			]
		)
		?>
	</p>
</div>
<?php endif ?>

<p class="description">
	<?php echo wp_strip_all_tags( __( 'Enter your license code to activate full import features and game updates.', 'vegashero' ) ); ?>
	<br>
	<?php
	echo wp_kses(
		sprintf( __( 'Please see our <a target="_blank" href="%1$s">quick start guide</a> for detailed instructions.', 'vegashero' ), esc_url( 'https://vegashero.co/quick-start-guide/' ) ),
		[
			'a' => [
				'target' => true,
				'href'   => true,
			],
		]
	)
	?>
</p>
	
