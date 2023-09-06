<input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="regular-text" placeholder="<?php echo esc_attr__( 'url base path for game categories', 'vegashero' ); ?>" value="<?php echo esc_attr( $option ); ?>" />
<p class="description">
<?php
/* translators: %1$s will be replaced with the current website URL */ echo wp_kses(
	sprintf( __( 'Sets the URL path prefix for listing games per category. The default prefix is "category".<br>For example, to replace %1$s/game/<u>category</u>/ with %1$s/game/<u>type</u>/ set this to "type".', 'vegashero' ), site_url() ),
	[
		'br' => [],
		'u'  => [],
	]
);
?>
</p>
