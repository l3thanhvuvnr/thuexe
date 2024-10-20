<div style="display:none" id="fusion-builder-elegant-templates">
		<div class="fusion_builder_modal_settings" data-element-type="elegant-templates">
			<div id="elegant-elements-templates">
			<?php
				$registered_class = ( $is_registered ) ? 'active-license' : 'inactive-license';
			?>
			<div class="fusion-builder-main-settings fusion-builder-main-settings-full elegant-elements-templates-wrapper <?php echo esc_attr( $registered_class ); ?>">
				<?php
				if ( $is_registered ) :
					$templates = apply_filters( 'elegant_elements_templates', array() );
					?>

					<div class="fusion-builder-layouts-header">

						<div class="fusion-builder-modal-top-container elegant-elements-templates-header">
							<input type="text" class="elegant-template-search" placeholder="<?php esc_attr_e( 'Search Templates', 'elegant-elements' ); ?>" />
						</div>

						<div class="fusion-builder-layouts-header-info">
							<h2><?php esc_attr_e( 'Pre-Built Layout Templates', 'fusion-builder' ); ?></h2>
							<span class="fusion-builder-layout-info"><?php esc_attr_e( 'We have designed some awesome pre-defined layout templates for you to get started quickly. Preview the templates below and click on the "Load" button to insert them. You can choose between positions "Above" and "Below" if you want don\'t want to replace the entire page content, else choose "Replace All" to replace entire page content.', 'fusion-builder' ); ?></span>
							<span class="fusion-builder-layout-info"><?php esc_attr_e( 'Please set the page template to 100% width before importing / previewing the template to get the best results.', 'fusion-builder' ); ?></span>
						</div>

					</div>

					<ul id="elegant-elements-templates-container" class="elegant-elements-templates">
					<?php
					if ( is_array( $templates ) && ! empty( $templates ) ) {
						global $post;
						$post_id = $post->ID;

						// @codingStandardsIgnoreStart
						foreach ( $templates as $title => $template ) {
							echo '<li class="elegant-elements-template" data-key="' . $title . '">
										<img src="' . $template['preview_image'] . '" srcset="' . $template['preview_image'] . ' 1x, ' . $template['preview_image_retina'] . ' 2x" />
										<h4 class="elegant-elements-template-title hidden">' . $title . '</h4>
										<span class="elegant-elements-template-buttons">
											<a href="javascript:void(0)" class="elegant-elements-template-load-dialog">
												<span class="fa-download fas"></span>&nbsp;
												' . sprintf(
													esc_html__( 'Load %s', 'fusion-builder' ),
													'<div class="elegant-elements-load-template-dialog-container">
														<div class="elegant-elements-load-template-dialog">
														<span class="elegant-elements-save-element-title">' . esc_html__( 'How To Load Template?', 'elegant-elements' ) . '</span>
															<div class="elegant-elements-save-element-container">
																<span class="elegant-elements-template-button-load" data-load-type="replace" data-post-id="' . $post_id . '" data-key="' . $title . '" data-template-url="' . $template['template_url'] . '">' . esc_attr__( 'Replace all page content', 'elegant-elements' ) . '</span>
																<span class="elegant-elements-template-button-load" data-load-type="above" data-post-id="' . $post_id . '" data-key="' . $title . '" data-template-url="' . $template['template_url'] . '">' . esc_attr__( 'Insert above current content', 'elegant-elements' ) . '</span>
																<span class="elegant-elements-template-button-load" data-load-type="below" data-post-id="' . $post_id . '" data-key="' . $title . '" data-template-url="' . $template['template_url'] . '">' . esc_attr__( 'Insert below current content', 'elegant-elements' ) . '</span>
															</div>
														</div>
													</div>'
												) . '
											</a>
										</span></li>';
						}
					}
					// @codingStandardsIgnoreEnd
					?>
					</ul>
					<?php
				else :
					?>
				<span class="elegant-elements-registration-info">
					<div class="confused-man" style="margin-top: 30px;"><img width="159px" src="<?php echo ELEGANT_ELEMENTS_PLUGIN_URL; ?>/assets/admin/img/confused-man.jpg"></div>
					<h2><?php echo esc_attr__( 'Ouch! Forgot to Activate License?', 'elegant-elements' ); ?></h2>
					<?php printf( __( 'Your product must be registered to receive templates for Elegant Elements for Fusion Builder. <br/> Go to the %s admin menu to complete registration.', 'elegant-elements' ), '<a href="' . admin_url( 'admin.php?page=elegant-elements-options#elegant-elements-product-registration' ) . '" target="_blank">' . esc_attr__( 'Elegant Elements', 'elegant-elements' ) . '</a>' ); ?>
				</span>
					<?php
				endif;
				?>
			</div>
		</div>
	</div>
	<style type="text/css">
	ul.elegant-elements-templates {
		margin-top: 40px;
	}
	.fusion-builder-layouts-header .fusion-builder-layouts-header-info {
		margin: 30px 0;
	}
	.elegant-elements-load-template-dialog .elegant-elements-save-element-container span {
		box-sizing: content-box !important;
	}
	.elegant-elements-load-template-dialog-container {
		top: -153px;
	}
	.elegant-elements-templates .elegant-elements-template {
		border-bottom-width: 5px;
	}
	.elegant-elements-template-title.hidden {
		display: none;
	}
	.elegant-elements-templates-header .elegant-template-search {
		padding: 0 10px;
		background: #40464c;
		color: #fff;
		border: none;
		right: 60px;
		height: 30px;
		margin-top: 13px;
	}
	.inactive-license {
		background: #ffffff !important;
	}
	</style>
	<script>
	// Calls the template dialog in toolbar.
	function elegantToolbar() {
		var fusionTemplates;

		fusionTemplates        = new FusionPageBuilder.FusionTemplatesApp();
		window.fusionTemplates = fusionTemplates;

		if ( jQuery( '.fusion-builder-dialog' ).length && jQuery( '.fusion-builder-dialog' ).is( ':visible' ) ) {
			FusionApp.multipleDialogsNotice();
			return;
		}

		fusionTemplates.render();

		var macy = Macy({
			container: '#elegant-elements-templates-container',
			trueOrder: true,
			waitForImages: true,
			margin: 20,
			columns: 3,
			breakAt: {
				1200: 3,
				940: 3,
				520: 2,
				400: 1
			}
		});

		jQuery( '#fusion-builder-elegant-templates' ).scrollTop(0);
	}
	</script>
</div>
