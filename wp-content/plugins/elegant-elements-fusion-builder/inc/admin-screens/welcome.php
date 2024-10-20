<div class="wrap about-wrap elegant-elements-wrap">

	<?php Elegant_Elements_Admin::header(); ?>
	<div class="elegant-elements-important-notice">
		<p class="about-description">
			<?php esc_html_e( 'The Elegant Elements plugin is an add-on for the popular page builder plugin - Fusion Builder. It will extend the Fusion Builder\'s functionality and provide users with the tools to create even more dynamic and complex content and value added services .', 'elegant-elements' ); ?>
			<br/>
		</p>
	</div>

	<?php
	if ( isset( $_GET['code'] ) && '' !== $_GET['code'] ) { // @codingStandardsIgnoreLine
		$code = ( isset( $_GET['code'] ) ) ? $_GET['code'] : array(); // @codingStandardsIgnoreLine
		$code = base64_decode( $code ); // @codingStandardsIgnoreLine
		$code = json_decode( $code, true );

		if ( is_array( $code ) && ! empty( $code ) ) {
			update_option( 'elegant_elements_purchase_data', $code );

			// Delete transient caches to run the plugin update check.
			delete_transient( 'elegant_envato_access_token' );
			delete_transient( 'elegant_envato_buyer_plugins' );
		}

		// Remove the instagram code and other parameters from the url to avoid issues.
		?>
		<script type="text/javascript">
		jQuery( document ).ready( function() {
			window.history.replaceState( null, null, window.location.pathname + '?page=elegant-elements-options' );
		})
		</script>
		<?php
	}

	$callback_data   = array(
		'url'       => rawurlencode( admin_url( 'admin.php?page=elegant-elements-options' ) ),
		'item_name' => 'Elegant Elements for Fusion Builder and Avada',
		'item_id'   => 21113424,
	);
	$callback_json   = wp_json_encode( $callback_data );
	$callback_encode = base64_encode( $callback_json ); // @codingStandardsIgnoreLine

	// Retrieve purchase data.
	$purchase_data = get_option( 'elegant_elements_purchase_data', array() );

	$support_until = time() - 1;
	if ( isset( $purchase_data['supported_until'] ) ) {
		$support_until = strtotime( $purchase_data['supported_until'] );
		$support_valid = ( time() <= $support_until ) ? ' Valid Until' : ' Expired';
	} else {
		$support_valid = 'Not Found';
	}

	// If no plugin found in the user's purchases.
	if ( isset( $purchase_data['purchase_verified'] ) && ! isset( $purchase_data['supported_until'] ) ) {
		?>
		<p class="warning notice update-nag" style="display: block !important;margin-right: 0;margin-bottom: 30px;">
			<?php esc_attr_e( 'Looks like you have not purchased the plugin from this account. Please log in with a valid account and try again.', 'elegant-elements' ); ?>
		</p>
		<?php
	}

	// If user's support is expired.
	if ( 'Expired' === trim( $support_valid ) ) {
		?>
		<p class="warning notice update-nag" style="display: block !important;margin-right: 0;margin-bottom: 30px;">
			<?php echo sprintf( __( 'Looks like your support is expired. To receive support and help us to keep improving this product, please <a href="%s" target="_blank">renew your support</a>.', 'elegant-elements' ), 'https://codecanyon.net/checkout/from_item/21113424?size=source&support=renew_6month' ); ?>
		</p>
		<?php
	}

	// If refresh token is not valid, ask to re-validate.
	if ( isset( $purchase_data['invalid_token'] ) && $purchase_data['invalid_token'] ) {
		?>
		<p class="warning notice update-nag" style="display: block !important;margin-right: 0;margin-bottom: 30px;">
			<?php esc_attr_e( 'Looks like your Envato authenticate token is expired. Please click the "Refresh License" button to re-generate the access token so the update check will work. The refresh token expires only if you authenticate the license on another site. In that case, you need to re-authenticate this site.', 'elegant-elements' ); ?>
		</p>
		<?php
	}
	?>
	<div id="elegant-elements-product-registration" class="elegant-elements-registration-form">
		<?php
		$old_registration = get_option( 'elegant_element_registration', '' );
		if ( isset( $old_registration['elegantelementsforfusionbuilder']['token'] ) && '' !== $old_registration['elegantelementsforfusionbuilder']['token'] ) {
			infi_elegant_elements()->registration->the_form();
		} else {
			infi_elegant_elements()->registration->form_styles();
			?>
			<div class="elegant-elements-important-notice registration-form-container" style="padding: 0;display: flex;width: 100%;">
				<div class="elegant-license-graphics" style="width: 50%; background: #03A9F4; padding: 30px 30px 0;">
					<img src="<?php echo ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/img/license-graphic.svg'; ?>">
				</div>
				<div class="elegant-registration-wrapper" style="padding: 30px; width: 50%;">
					<h3 style="font-size: 21px;margin-top: 0;font-weight: 700;color: #464646; text-transform: uppercase;"><?php esc_attr_e( 'Register your product and enjoy the following benefits' ); ?></h3>
					<ul class="unlock-benefits" style="margin-bottom: 15px;">
						<li>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" fill="rgba(22,97,165,1)"/></svg>
							<span><?php esc_attr_e( 'Auto-updates from WordPress dashboard', 'elegant-elements' ); ?></span>
						</li>
						<li>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" fill="rgba(22,97,165,1)"/></svg>
							<span><?php esc_attr_e( 'Access to the template library', 'elegant-elements' ); ?></span>
						</li>
						<li>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" fill="rgba(22,97,165,1)"/></svg>
							<span><?php esc_attr_e( 'Access demos to get started', 'elegant-elements' ); ?></span>
						</li>
						<li>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" fill="rgba(22,97,165,1)"/></svg>
							<span><?php esc_attr_e( 'Create your own elements with Elementor Creator', 'elegant-elements' ); ?></span>
						</li>
						<li>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-.997-6l7.07-7.071-1.414-1.414-5.656 5.657-2.829-2.829-1.414 1.414L11.003 16z" fill="rgba(22,97,165,1)"/></svg>
							<span><?php esc_attr_e( 'Priority Support', 'elegant-elements' ); ?></span>
						</li>
					</ul>

					<?php
					if ( isset( $purchase_data['purchase_verified'] ) && $purchase_data['purchase_verified'] ) {
						?>
						<p style="font-size: 14px;font-style: italic;"><?php esc_attr_e( 'Your product is registered successfully. If you\'ve purchased support extension, click the button below to update the validity.', 'elegant-elements' ); ?></p>
						<a class="button button-primary button-large elegant-register-product-button" href="https://www.infiwebs.com/verify-envato-purchase?callback=<?php echo $callback_encode; ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M5.463 4.433A9.961 9.961 0 0 1 12 2c5.523 0 10 4.477 10 10 0 2.136-.67 4.116-1.81 5.74L17 12h3A8 8 0 0 0 6.46 6.228l-.997-1.795zm13.074 15.134A9.961 9.961 0 0 1 12 22C6.477 22 2 17.523 2 12c0-2.136.67-4.116 1.81-5.74L7 12H4a8 8 0 0 0 13.54 5.772l.997 1.795z" fill="rgba(255,255,255,1)"/></svg>
							<span><?php esc_attr_e( 'Refresh License', 'elegant-elements' ); ?></span>
						</a>
						<div class="support-validity support-<?php echo strtolower( str_replace( ' ', '-', $support_valid ) ); ?>">
							<span><?php echo esc_attr__( 'Support', 'elegant-elements' ) . $support_valid; ?></span>
							<span><?php echo $purchase_data['supported_until']; ?></span>
						</div>
						<?php
					} else {
						?>
						<p style="font-size: 14px;font-style: italic;"><?php esc_attr_e( 'Click the button to register your product in just a click', 'elegant-elements' ); ?></p>
						<a class="button button-primary button-large elegant-register-product-button" href="https://www.infiwebs.com/verify-envato-purchase?callback=<?php echo $callback_encode; ?>">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M18 8h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2V7a6 6 0 1 1 12 0v1zm-2 0V7a4 4 0 1 0-8 0v1h8zm-5 6v2h2v-2h-2zm-4 0v2h2v-2H7zm8 0v2h2v-2h-2z" fill="rgba(255,255,255,1)"/></svg>
							<span><?php esc_attr_e( 'Register Product', 'elegant-elements' ); ?></span>
						</a>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	<?php Elegant_Elements_Admin::footer(); ?>
</div>
