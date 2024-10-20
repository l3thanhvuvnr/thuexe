<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_contact_form7-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<#
	var contact_form7_shortcode = '';

	if ( '' !== heading_text || '' !== caption_text ) {
		#>
		<div {{{ _.fusionGetAttributes( headingWrapperStyleAttr ) }}}>
		<#
		if ( heading_text ) {
			#>
			<{{{ heading_size }}} {{{ _.fusionGetAttributes( headingStyleAttr ) }}}>{{{ heading_text }}}</{{{ heading_size }}}>
			<#
		}

		if ( caption_text ) {
			#>
			<div {{{ _.fusionGetAttributes( captionStyleAttr ) }}}>{{{ caption_text }}}</div>
			<#
		}
		#>
		</div>
		<#
	}
	#>
	<div {{{ _.fusionGetAttributes( formAttr ) }}}>
		<#
		contact_form7_shortcode = '[contact-form-7 id="' + form + '"]';
		#>
		{{{ FusionPageBuilderApp.renderContent( contact_form7_shortcode, cid, false ) }}}
	</div>
	<#
	var titleFont = typography_heading,
		descriptionFont = typography_caption,
		selectedFont = ( ! Number.isInteger( parseInt( titleFont ) ) ) ? titleFont : '',
		webfonts = elegantGoogleFonts,
		googleFonts = webfonts['Google Fonts'],
		sep = '';

	if ( '' !== descriptionFont ) {
		sep           = ( '' !== selectedFont ) ? '|' : '';
		selectedFont += ( ! Number.isInteger( parseInt( descriptionFont ) ) ) ? sep + descriptionFont : '';
	}

	if ( '' !== selectedFont ) {
		#>
		<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
		<#
	}
	#>
</div>
</script>
