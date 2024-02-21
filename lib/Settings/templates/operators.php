		<div class="wrap vh-about-wrap">
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

		<h1><?php echo wp_strip_all_tags( __( 'Import by Casino Operator', 'vegashero' ) ); ?></h1>

		<!-- sponsored add -->
		<iframe class="op-admin-iframe-top" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/operators-admin-top.php"></iframe>
		<!-- /sponsored add -->

		<p class="description">
			<?php echo wp_kses( __( 'Imported games will be grouped by casino <u>operator</u> and game <u>category</u>.', 'vegashero' ), [ 'u' => [] ] ); ?>
			<br>
			<?php
			echo wp_kses(
				__( 'Casino <i>operators</i> may share some of the same games, but rest assured, games will <u>not</u> be duplicated and instead can be associated with multiple casino <u>operators</u>.', 'vegashero' ),
				[
					'i' => [],
					'u' => [],
				]
			)
			?>
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

		<?php if ( isset( $this->_operators ) && count( $this->_operators ) ) : ?>
			<ul class="operator-cards">
			<?php foreach ( $this->_operators as $operator ) : ?>
					<li class="<?php echo esc_attr( sprintf( 'op-%s', $operator['operator'] ) ); ?>">
						<div class="desc">
							<div class="provider-img"><img src="<?php echo esc_attr( sprintf( '%s/operators/%s.png', $this->_config->gameImageUrl, $operator['operator'] ) ); ?>" /></div>
							<h2 class="vh-operator-name"><?php echo $operator['operator']; ?></h2>
							<div class="btn-area">
								<?php echo $this->_getPostStatusDropdown(); ?>
								<?php echo $this->_getAjaxUpdateBtn( sanitize_title( $operator['operator'] ) ); ?>
							</div>
							<div class="footer-area">
								<?php if ( get_option( 'vh_license_status' ) === 'valid' ) : ?>
									<?php echo $this->_getGameTypeCheckboxes( sanitize_title( $operator['operator'] ), $operator['html5'], $operator['flash'] ); ?>
								<?php else : ?>
									<?php echo $this->_getGameCount( sanitize_title( $operator['operator'] ), $operator['total'] ); ?>
								<?php endif ?>
							</div>
						</div>
					</li>
			<?php endforeach ?>
			</ul>
		<?php else : ?>
			<p style="color:red"><?php echo wp_strip_all_tags( __( 'Unable to fetch a list of operators. Please try again by refreshing your page.', 'vegashero' ) ); ?></p>
		<?php endif ?>
		<div class="clear"></div>
		<hr>

		<iframe class="op-admin-iframe-bottom" frameborder="0" scrolling="no" src="https://vegasgod.com/iframes/operators-admin-bottom.php"></iframe>
		
		</div>
