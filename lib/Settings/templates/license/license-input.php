<input id='<?php echo $id; ?>' name='<?php echo $id; ?>' size='30' type='text' class='regular-text' value='<?php echo get_option( $id ); ?>' placeholder='<?php echo esc_attr( __( 'enter your license key here', 'vegashero' ) ); ?>' />
<?php if ( get_option( 'vh_license_status' ) === 'valid' ) : ?>
<span class="dashicons dashicons-yes" style="font-size:x-large;color:#53a93f;"></span>
<?php else : ?>
<span class="dashicons dashicons-no" style="font-size:x-large;color:#d73d32;margin-top:3px;"></span>
<?php endif ?>
