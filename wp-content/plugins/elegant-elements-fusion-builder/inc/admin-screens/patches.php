<div class="wrap about-wrap elegant-elements-wrap">

	<?php Elegant_Elements_Admin::header(); ?>

	<div class="elegant-elements-important-notice">
		<p class="about-description">
			<?php esc_attr_e( 'Elegant Elements for Avada Builder introduces the patcher tool, which allows our development team to deploy fixes and improvements without the need for our team to prepare and release a full plugin update. All you need to do is, just click the Apply Patch button for each patch available.', 'elegant-elements' ); ?>
			<br/>
		</p>
		<p class="about-description"><?php esc_attr_e( 'If, for some reason or server config, the patcher tool is not appling the patches, you can  download the fix manually and upload it on your server using FTP. Click the download patch button to download each patch file, and upload it manually.', 'elegant-elements' ); ?></p>
		<br/><br/> <a class="button button-primary button-hero" href="https://infiwebs.freshdesk.com/" target="_blank">Contact Support</a>
	</div>

	<?php
	$current_version = get_option( 'elegant_elements_version' );
	if ( ELEGANT_ELEMENTS_VERSION !== $current_version ) {
		// Update current version number to database.
		update_option( 'elegant_elements_version', ELEGANT_ELEMENTS_VERSION );

		// Reset Patches.
		delete_transient( 'wppatcher_patches_' . sanitize_title_with_dashes( 'elegant-elements-fusion-builder' ) );
	}

	if ( infi_elegant_elements()->registration->is_registered() ) {
		echo infi_elegant_elements()->patcher->get_patches();
	} else {
		?>
		<p class="error notice update-nag" style="display: block !important;margin-right: 0;margin-bottom: 30px;">
			<?php echo sprintf( __( 'Looks like you have not registered the plugin yet. Please go to <a href="%s">Welcome</a> tab and register your product with just one click.', 'elegant-elements' ), admin_url( '/admin.php?page=elegant-elements-options' ) ); ?>
		</p>
		<?php
	}
	?>

	<?php Elegant_Elements_Admin::footer(); ?>
</div>
