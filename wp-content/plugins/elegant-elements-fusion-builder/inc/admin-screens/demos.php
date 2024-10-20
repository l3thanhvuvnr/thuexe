<div class="wrap about-wrap elegant-elements-wrap">

	<?php Elegant_Elements_Admin::header(); ?>

	<div class="elegant-elements-important-notice">
		<p class="about-description">
			<?php esc_attr_e( 'Elegant Elements for Fusion Builder provides carefully crafted demo websites that can be imported from this page after you register your purchase. Images and other assets used in the demos are licensed so you\'ll need to replace them with your own licensed images.', 'elegant-elements' ); ?>
			<br/>
		</p>
	</div>

	<div class="elegant-elements-demos-wrapper theme-browser" style="float: left; width: 100%;">
		<?php
		if ( isset( $_GET['elegant-reset-demo-cache'] ) ) { // @codingStandardsIgnoreLine
			delete_transient( 'elegant_elements_demos' );
		}

		if ( infi_elegant_elements()->registration->is_registered() ) :
			?>
			<div id="import_notes"></div>
			<div class="themes">
				<?php
				$demos_transient = get_transient( 'elegant_elements_demos' );
				$demos           = json_decode( $demos_transient, true );

				if ( false === $demos_transient && ! is_array( $demos ) ) {
					$demos_json = wp_remote_retrieve_body( wp_remote_get( 'https://library.fusionelegantelements.com/demos/' ) );
					$demos      = json_decode( $demos_json, true );

					set_transient( 'elegant_elements_demos', $demos_json, 60 * 60 * 24 );
				}

				foreach ( $demos as $demo_title => $info ) {
					$demo = strtolower( str_replace( array( ' ', '_' ), '-', $demo_title ) );
					?>
					<div class="theme" tabindex="0" aria-describedby="<?php echo esc_attr( $demo ); ?>-action <?php echo esc_attr( $demo ); ?>-name" data-slug="<?php echo esc_attr( $demo ); ?>">
						<div class="theme-screenshot" style="height: 188px;">
							<img src="<?php echo esc_attr( $info['preview_image'] ); ?>" alt="">
						</div>
						<div class="theme-id-container">
							<h2 class="theme-name" id="<?php echo esc_attr( $demo ); ?>-name" style="text-align: left;"><?php echo esc_attr( $demo_title ); ?></h2>
							<div class="theme-actions">
								<button class="button import-demo" onclick="jQuery('.demo-modal.demo-<?php echo esc_attr( $demo ); ?>').show();" aria-label="import <?php echo esc_attr( $demo ); ?>" data-template="<?php echo esc_attr( $demo ); ?>"><?php esc_attr_e( 'Import', 'elegant-elements' ); ?></button>
								<a class="button button-primary" href="<?php echo $info['live_preview']; ?>" target="_blank"><?php esc_attr_e( 'Preview', 'elegant-elements' ); ?></a>
							</div>
						</div>
						<div class="demo-modal demo-<?php echo esc_attr( $demo ); ?>" style="display:none;">
							<div class="demo-modal-inner">
								<div class="demo-modal-header">
									<div class="demo-modal-title">
										<?php echo __( 'Import Demo:', 'elegant-elements' ) . ' ' . esc_attr( $demo_title ); ?>
									</div>
									<div class="demo-modal-close">
										<div style="cursor: pointer;" onclick="jQuery('.demo-modal.demo-<?php echo esc_attr( $demo ); ?>').hide(); return false;" class="demo-modal-corner-close">
											<span class="dashicons dashicons-no-alt"></span>
										</div>
									</div>
								</div>
								<div class="demo-modal-content">
									<div class="demo-modal-content-required-parts">
										<h3><?php esc_attr_e( 'Choose Content to be Imported:', 'elegant-elements' ); ?></h3>
										<div class="content-parts-list">
											<form method="POST" class="elegant-elements-demo-import">
												<input type="hidden" value="<?php echo $info['zip_file']; ?>" name="demo_file" class="demo-file">
												<input type="hidden" value="<?php echo esc_attr( $demo ); ?>" name="template" class="template">
												<p>
													<input class="module" type="checkbox" checked="true" value="contents" id="import-content" name="modules[]">
													<label for="import-content">Contents <br/><small style="margin-left: 22px;font-style: italic;color: #828282;">( All posts, pages and images will be imported )</small></label>
												</p>
												<p>
													<input class="module" type="checkbox" checked="true" value="widgets" id="import-widgets" name="modules[]">
													<label for="import-widgets">Widgets <br/><small style="margin-left: 22px;font-style: italic;color: #828282;">( Import widgets in corresponding widget areas )</small></label>
												</p>
												<p>
													<input class="module" type="checkbox" checked="true" value="options" id="import-options" name="modules[]">
													<label for="import-options">Theme Options <br/><small style="margin-left: 22px;font-style: italic;color: #828282;">( Existing Theme Options will be replaced )</small></label>
												</p>
												<p>
													<input class="module" type="checkbox" checked="true" value="fusion_slider" id="import-fusion-slider" name="modules[]">
													<label for="import-fusion-slider">Fusion Slider <br/><small style="margin-left: 22px;font-style: italic;color: #828282;">( Fusion Core plugin needs to be active. Only imported if demo has Slider used. )</small></label>
												</p>
												<div class="submit-button">
													<a href="#" data-template="<?php echo esc_attr( $demo ); ?>" class="button button-primary elegant-elements-import" style="width: 100%;height: 40px;margin-top: 10px;text-align: center;line-height: 40px;font-size: 18px;letter-spacing: 1px;"><?php esc_attr_e( 'Import', 'elegant-elements' ); ?></a>
												</div>
											</form>
										</div>
									</div>
								</div>
								<p class="import_notes import_notes-<?php echo esc_attr( $demo ); ?>"></p>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		else :
			?>
			<span class="elegant-elements-registration-info">
				<?php esc_attr_e( 'Your product must be registered to import demos for Elegant Elements for Fusion Builder. Go to the welcome tab to complete registration.', 'elegant-elements' ); ?>
			</span>
			<?php
		endif;
		?>
	</div>
	<style scoped="true">
		.demo-modal {
			background: rgba(0,0,0,0.6);
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			z-index: 99999;
			padding: 50px;
			height: 100%;
			cursor: auto;
		}
		.demo-modal-inner {
			max-width: 600px;
			margin: auto;
			max-height: 90%;
			background: #fff;
			z-index: 999999;
			box-shadow: 0 1px 1px -1px rgba(0,0,0,.1);
			border-radius: 3px;
			overflow-y: auto;
		}
		.demo-modal-inner .demo-modal-content {
			padding: 20px;
		}
		.demo-modal-header {
			background: #03A9F4;
			color: #fff;
			float: left;
			width: 100%;
			padding: 10px;
			box-sizing: border-box;
			border-top-left-radius: 3px;
			border-top-right-radius: 3px;
			margin-bottom: 15px;
		}
		.demo-modal-header .demo-modal-title {
			display: inline-block;
			float: left;
			line-height: 26px;
			font-weight: bold;
		}
		.demo-modal-header .demo-modal-close {
			display: inline-block;
			float: right;
		}
		.demo-modal-corner-close {
			color: #333;
			background: #fff;
			border-radius: 50px;
			padding: 3px;
		}
		.import_notes {
			margin: 0;
			padding: 0;
		}
		.import_notes.importing {
			padding: 1px 20px;
			background: #03A9F4;
			color: #fff;
			line-height: 1em !important;
		}
		.import_notes.importing a {
			color: #fff;
		}
		.lds-ellipsis {
			display: inline-block;
			position: relative;
			width: 64px;
			height: 18px;
		}
		.lds-ellipsis div {
			position: absolute;
			top: 8px;
			width: 11px;
			height: 11px;
			border-radius: 50%;
			background: #fff;
			animation-timing-function: cubic-bezier(0, 1, 1, 0);
		}
		.lds-ellipsis div:nth-child(1) {
			left: 6px;
			animation: lds-ellipsis1 0.6s infinite;
		}
		.lds-ellipsis div:nth-child(2) {
			left: 6px;
			animation: lds-ellipsis2 0.6s infinite;
		}
		.lds-ellipsis div:nth-child(3) {
			left: 26px;
			animation: lds-ellipsis2 0.6s infinite;
		}
		.lds-ellipsis div:nth-child(4) {
			left: 45px;
			animation: lds-ellipsis3 0.6s infinite;
		}
		#wpwrap .theme-browser .theme {
			width: 31%;
			margin: 0 3.5% 3.5% 0;
		}
		#wpwrap .theme-browser .theme:nth-child(3n) {
			margin-right: 0 !important;
		}
		#wpwrap .theme-browser .theme:nth-child(4n),
		#wpwrap .theme-browser .theme:nth-child(5n) {
			margin-right: 3.5% !important;
		}
		@keyframes lds-ellipsis1 {
			0% {
				transform: scale(0);
			}
			100% {
				transform: scale(1);
			}
		}
		@keyframes lds-ellipsis3 {
			0% {
				transform: scale(1);
			}
			100% {
				transform: scale(0);
			}
		}
		@keyframes lds-ellipsis2 {
			0% {
				transform: translate(0, 0);
			}
			100% {
				transform: translate(19px, 0);
			}
		}
	</style>
	<?php Elegant_Elements_Admin::footer(); ?>
</div>
