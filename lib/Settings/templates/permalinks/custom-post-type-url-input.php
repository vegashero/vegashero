<input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="regular-text" placeholder="<?php echo esc_attr( __( 'url base path for vegashero games', 'vegashero' ) ); ?>"  value="<?php echo esc_attr( $option ); ?>"/>
<p class="description">
<?php
/* translators: %1$s will be replaced by the current website URL */ echo wp_kses(
	sprintf( __( 'Sets the URL path prefix for an individual game. The default prefix is "game".<br>For example, to replace %1$s/<u>game</u>/ with %1$s/<u>slots</u>/ set this to "slots".', 'vegashero' ), site_url() ),
	[
		'br' => [],
		'u'  => [],
	]
);
?>
</p>
