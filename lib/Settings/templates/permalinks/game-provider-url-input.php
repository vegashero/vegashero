<input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" class="regular-text" placeholder="<?php echo esc_attr( __( 'permalink base path for vegashero games', 'vegashero' ) ); ?>" value="<?php echo esc_attr( $option ); ?>"/>
<p class="description">
<?php
/* translators: %1$s will be replaced by the current website URL */ echo wp_kses(
	sprintf( __( 'Sets the URL path prefix for listing games per provider. The default prefix is "provider".<br>For example, to replace %1$s/game/<u>provider</u>/ with %1$s/game/<u>studio</u>/ set this so "studio".', 'vegashero' ), site_url() ),
	array(
		'br' => array(),
		'u'  => array(),
	)
);
?>
</p>
