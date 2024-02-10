		<div class="wrap vh-about-wrap">
		<?php if ( get_option( 'vh_license_status' ) === 'valid' ) : ?>
		<div><!-- display this if valid license key entered --></div>
		<?php else : ?>
		<div class="updated" style="display:block!important;">
			<h3 style="margin-top:0.5em;"><?php echo wp_strip_all_tags( __( 'Get a license key and add 2000+ games to your website!', 'vegashero' ) ); ?></h3>
			<p class="description">
			<?php
			/* translators: %1$s will be replaced by a URL where a plugin license can be purchased */ echo wp_kses(
				sprintf( __( 'The free version of the plugin will let you import 2 games per software provider. To get full access to the game database: <strong><a target="_blank" href="%1$s">purchase a license key here.</a></strong>', 'vegashero' ), esc_url( 'https://vegashero.co/downloads/vegas-hero-plugin/?utm_source=VegasHeroPlugin&utm_medium=admin&utm_campaign=license%20settings%20page' ) ),
				array(
					'a'      => array(
						'target' => true,
						'href'   => true,
					),
					'strong' => array(),
				)
			);
			?>
									</p>
		</div>
		<?php endif ?>
		
		<h1><?php echo wp_strip_all_tags( __( 'Import by Game Provider', 'vegashero' ) ); ?></h1>

		<!-- sponsored add -->
		<iframe class="prov-admin-iframe-top" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/providers-admin-top.php"></iframe>
		<!-- /sponsored add -->

		<p class="description">
			<?php echo wp_strip_all_tags( __( 'Imported games will be grouped by game <u>provider</u> and <u>category</u>.', 'vegashero' ) ); ?>
			<br>
			<?php
			/* translators: %1$s will be replaced by a URL containing instructions to get your started */ echo wp_kses(
				sprintf( __( 'Please see our <a target="_blank" href="%1$s">quick start guide</a> for detailed instructions.', 'vegashero' ), esc_url( 'https://vegashero.co/quick-start-guide/' ) ),
				array(
					'a' => array(
						'target' => true,
						'href'   => true,
					),
				)
			);
			?>
			</p>

		<?php if ( isset( $this->_providers ) && count( $this->_providers ) ) : ?>
		<ul class="operator-cards">
			<?php foreach ( $this->_providers as $provider ) : ?>
				<li class="<?php echo esc_attr( sprintf( 'prov-%s', $provider['provider'] ) ); ?>">
					<div class="desc">
						<div class="provider-img"><img src="<?php echo esc_attr( sprintf( '%s/providers/%s.png', $this->_config->gameImageUrl, $provider['provider'] ) ); ?>" /></div>
						<form method="post" action="options.php">
							<?php
							echo settings_fields( $this->_getOptionGroup( $provider['provider'] ) );
							$page = $this->_getPageName( $provider['provider'] );
							do_settings_sections( $page );
							?>
							<h2 class="vh-provider-name"><?php echo $provider['provider']; ?></h2>
							<div class="btn-area">
								<?php echo $this->_getPostStatusDropdown(); ?>
								<?php echo $this->_getAjaxUpdateBtn( sanitize_title( $provider['provider'] ) ); ?>
							</div>
							<div class="footer-area">
								<?php if ( get_option( 'vh_license_status' ) === 'valid' ) : ?>
									<?php echo $this->_getGameTypeCheckboxes( sanitize_title( $provider['provider'] ), $provider['html5'], $provider['flash'] ); ?>
								<?php else : ?>
									<?php echo $this->_getGameCount( sanitize_title( $provider['provider'] ), $provider['total'] ); ?>
								<?php endif ?>
							</div>
						</form>
					</div>
				</li>
		<?php endforeach ?>
		</ul>
		<?php else : ?>
		<p style="color:red"><?php echo wp_strip_all_tags( __( 'Unable to fetch a list of providers. Please try again by refreshing your page.', 'vegashero' ) ); ?></p>
		<?php endif ?>
		<div class="clear"></div>
		<hr>

		<iframe class="prov-admin-iframe-bottom" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/providers-admin-bottom.php"></iframe>

		</div>
