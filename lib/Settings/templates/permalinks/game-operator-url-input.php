<input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="regular-text" placeholder="<?php echo esc_attr( __( 'permalink base path for vegashero games', 'vegashero' ) ); ?>" value="<?php echo esc_attr( $option ); ?>"/>
<p class="description">
<?php
/* translators: %1$s will be replaced with the current website URL */ echo wp_kses(
	sprintf( __( 'Sets the URL path prefix for listing games per operator. The default prefix is "operator".<br>For example, to replace %1$s/game/<u>operator</u>/ with %1$s/game/<u>casino</u>/ set this to "casino".', 'vegashero' ), site_url() ),
	[
		'br' => [],
		'u'  => [],
	]
);
?>
</p>
