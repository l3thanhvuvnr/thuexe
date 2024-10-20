<div class="wrap about-wrap elegant-elements-wrap">

	<?php Elegant_Elements_Creator::header(); ?>
	<div class="elegant-elements-important-notice elegant-elements-element-creator-form">
		<div class="intro-text">
			<h3 style="margin-top: 0;"><?php esc_attr_e( 'Elegant Element Creator', 'elegant-elements' ); ?></h3>
			<p>
				<?php echo sprintf( __( 'Enter the new element name and hit create button. You\'ll be redirected to the element creator screen where you can create the element attributes, add your element HTML, CSS and JavaScript code. You can also import the custom element and customize it the way you want. For more information please see the <a href="%s" target="_blank" rel="noopener noreferrer">Element Creator Intro Video</a>.', 'elegant-elements' ), 'https://www.youtube.com/watch?v=_zozn-1_1zc&t=10s' ); ?>
			</p>
		</div>
		<?php
		if ( class_exists( 'ACF' ) ) {
			?>
			<form id="element-creator-form" action="<?php echo admin_url( 'post-new.php' ); ?>">
				<input type="hidden" name="post_type" value="element_creator">
				<input type="text" placeholder="Enter your element name..." required="" id="elegant-element-name" name="post_title">
				<input type="submit" value="Create New Element" class="button button-large button-primary elegant-large-button">
				<div style="line-height: 2em;padding: 3px 0;">OR</div>
				<a href="javascript:void(0);" class="button button-large button-primary element-creator-import-button elegant-large-button"><?php esc_attr_e( 'Import From Library', 'elegant-elements' ); ?></a>
			</form>
			<?php
		} else {
			?>
			<div class="element-creator-acf-required intro-text">
				<h3 style="margin-top: 0;"><?php esc_attr_e( 'ACF PRO Not Active!', 'elegant-elements' ); ?></h3>
				<p>
					<?php
					$installed_plugins = get_plugins();
					$plugins           = Avada_TGM_Plugin_Activation::$instance->plugins;
					$acf_pro           = $plugins['advanced-custom-fields-pro'];
					$url               = '';
					$action            = '';

					if ( ! isset( $installed_plugins[ $acf_pro['file_path'] ] ) ) {
						$action = __( 'Install & Activate ACF PRO', 'elegant-elements' );
						$url    = esc_url(
							wp_nonce_url(
								add_query_arg(
									array(
										'page'          => rawurlencode( Avada_TGM_Plugin_Activation::$instance->menu ),
										'plugin'        => rawurlencode( $acf_pro['slug'] ),
										'plugin_name'   => rawurlencode( $acf_pro['name'] ),
										'tgmpa-install' => 'install-plugin',
										'return_url'    => 'fusion_plugins',
									),
									Avada_TGM_Plugin_Activation::$instance->get_tgmpa_url()
								),
								'tgmpa-install',
								'tgmpa-nonce'
							)
						);
					} elseif ( is_plugin_inactive( $acf_pro['file_path'] ) ) {
						$action = __( 'Activate ACF PRO', 'elegant-elements' );
						$url    = esc_url(
							add_query_arg(
								array(
									'plugin'               => rawurlencode( $acf_pro['slug'] ),
									'plugin_name'          => rawurlencode( $acf_pro['name'] ),
									'avada-activate'       => 'activate-plugin',
									'avada-activate-nonce' => wp_create_nonce( 'avada-activate' ),
								),
								admin_url( 'admin.php?page=avada-plugins' )
							)
						);
					}

					echo sprintf( __( 'Element Creator requires the ACF PRO plugin to be installed and active. Click on the button below to install and activate the plugin.</p><p> <a href="%1$s" class="button button-primary elegant-large-button">%2$s</a>', 'elegant-elements' ), $url, $action );
					?>
				</p>
			</div>
			<?php
		}
		?>
	</div>

	<div class="elegant-element-creator-elements">
		<?php
		$elements = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'		 => 'element_creator',
			)
		);

		if ( class_exists( 'ACF' ) ) :
			if ( $elements ) {
				echo '<ul class="element-creator-elements-list">';
				foreach ( $elements as $key => $element ) {
					$element_icon  = get_field( 'element_icon_class', $element->ID );
					$element_icon  = ( $element_icon ) ? $element_icon : 'fa-cog fas';
					$element_post  = '<li>';
					$element_post .= '<div class="elegant-element-creator-item-wrapper">';
					$element_post .= '<div class="elegant-element-creator-item">';
					$element_post .= '<i class="' . $element_icon . '"></i>';
					$element_post .= '<span class="element-creator-item-title">' . $element->post_title . '</span>';
					$element_post .= '</div>';
					$element_post .= '<div class="elegant-element-creator-item-actions">';
					$element_post .= '<a class="element-creator-edit" href="' . get_edit_post_link( $element ) . '"><i class="dashicons dashicons-edit"></i></a>';
					$element_post .= '<a class="element-creator-delete" href="' . get_delete_post_link( $element, '', true ) . '"><i class="dashicons dashicons-trash"></i></a>';
					$element_post .= '</div>';
					$element_post .= '</div>';
					$element_post .= '</li>';

					echo $element_post;
				}
				echo '</ul>';
			}
			?>
			<?php
		endif;
		?>
	</div>
	<div class="elegant-element-creator-library">
		<div class="elegant-element-creator-library-popup theme-browser">
			<div class="element-creator-library-heading">
				<h3><?php esc_attr_e( 'Element Creator Library', 'elegant-elements' ); ?></h3>
				<a href="javascript:void(0);" class="element-creator-library-close"><span class="dashicons dashicons-no-alt"></span></a>
			</div>
			<div class="element-creator-library-content themes">
				<?php
				if ( infi_elegant_elements()->registration->is_registered() ) {
					$elements_transient = get_transient( 'element_creator_elements' );

					if ( false === $elements_transient ) {
						$api_url       = 'https://templates.fusionelegantelements.com/wp-json/wp/v2/element_creator';
						$elements_json = wp_remote_retrieve_body( wp_remote_get( $api_url ) );
						$elements      = json_decode( $elements_json, true );

						set_transient( 'element_creator_elements', $elements_json, 60 * 60 * 24 );
					} else {
						$elements = json_decode( $elements_transient, true );
					}

					if ( is_array( $elements ) ) {
						foreach ( $elements as $key => $element ) {
							$name      = $element['title']['rendered'];
							$fields    = $element['acf'];
							$thumbnail = ( $element['featured_media']['large'] ) ? $element['featured_media']['large'] : ELEGANT_ELEMENTS_PLUGIN_URL . 'assets/admin/img/element-creator@2x.jpg';
							?>
							<div class="element-creator-library-item theme">
								<div class="theme-screenshot" style="height: 200px;background: url('<?php echo esc_attr( $thumbnail ); ?>') center center no-repeat;background-size: contain;background-color: #ffffff;">
									<img src="<?php echo esc_attr( $thumbnail ); ?>" style="opacity: 0;" />
								</div>
								<div class="theme-id-container">
									<div class="element-creator-library-item-title theme-name">
										<?php echo esc_attr( $name ); ?>
									</div>
									<div class="element-creator-library-item-actions theme-actions" style="height: 47px;box-sizing: border-box;box-shadow: none;">
										<div class="element-creator-library-element-data" style="display:none;"><?php echo base64_encode( wp_json_encode( $fields ) ); // @codingStandardsIgnoreLine ?></div>
										<a data-element-name="<?php echo esc_attr( $name ); ?>" href="javascript:void(0);" class="element-creator-import-element" style="display: inline-flex;align-items: center;box-shadow: none;" title="<?php esc_attr_e( 'Import this element', 'elegant-elements' ); ?>">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 10h5l-6 6-6-6h5V3h2v7zm-9 9h16v-7h2v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-8h2v7z"/></svg>
										</a>
									</div>
								</div>
							</div>
							<?php
						}
					}
				} else {
					?>
					<div class="elegant-elements-registration-info">
						<span class="elegant-elements-registration-info-text">
							<?php echo sprintf( __( 'Your product must be registered to import elements for Elements Creator. <a href="%s">Go to the welcome tab</a> to complete registration.', 'elegant-elements' ), admin_url( 'admin.php?page=elegant-elements-options' ) ); ?>
						</span>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery( document ).ready( function() {
			// Open element creator library.
			jQuery( '.element-creator-import-button' ).click( function() {
				jQuery( '.elegant-element-creator-library' ).addClass( 'import-active' );
			} );

			// Close element creator library.
			jQuery( '.element-creator-library-close' ).click( function() {
				jQuery( '.elegant-element-creator-library' ).removeClass( 'import-active' );
			} );

			// Process import.
			jQuery( '.element-creator-import-element' ).click( function() {
				var $this = jQuery( this ),
					elementName = $this.attr( 'data-element-name' ),
					elementData = $this.prev( '.element-creator-library-element-data' ).html();

				if ( $this.hasClass( 'element-creator-edit-element' ) ) {
					window.location = $this.attr( 'href' );
					return false;
				}

				$this.css( 'pointer-events', 'none' );
				$this.html( '<span class="spinner" style="visibility: visible;margin: 4px 2px 0;"></span>' );
				$this.closest( '.element-creator-library-item' ).addClass( 'focus' );

				jQuery.ajax( {
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'element_creator_import_element',
						element_name: elementName,
						acf: elementData
					},
					success: function( response ) {
						// Parse JSON to object.
						response = JSON.parse( response );

						// If import is success, replace the import link with edit element link.
						if ( '#' !== response.element_edit_url ) {
							$this.attr( 'href', response.element_edit_url.replace( '&amp;', '&' ) );
							$this.attr( 'class', 'element-creator-edit-element' );
							$this.html( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M15.728 9.686l-1.414-1.414L5 17.586V19h1.414l9.314-9.314zm1.414-1.414l1.414-1.414-1.414-1.414-1.414 1.414 1.414 1.414zM7.242 21H3v-4.243L16.435 3.322a1 1 0 0 1 1.414 0l2.829 2.829a1 1 0 0 1 0 1.414L7.243 21z"/></svg>' );
							$this.css( 'pointer-events', 'all' );
							$this.closest( '.element-creator-library-item' ).removeClass( 'focus' );
						} else {
							// If import is failed, restore the link so users can retry.
							$this.html( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13 10h5l-6 6-6-6h5V3h2v7zm-9 9h16v-7h2v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-8h2v7z"/></svg>' );
							$this.css( 'pointer-events', 'all' );
							$this.closest( '.element-creator-library-item' ).removeClass( 'focus' );
						}
					}
				} );
			} );
		} );
	</script>

	<?php Elegant_Elements_Creator::footer(); ?>
	<?php Elegant_Elements_Creator::styling(); ?>
</div>
