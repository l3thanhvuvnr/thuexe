<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_modal_dialog-shortcode">
<div class="fusion-builder-placeholder-preview">
	<i class="{{ icon }}"></i> {{ label }} ({{ name }})
</div>
<#
var style = '';
if ( '' !== borderColor ) {
	style = '<style type="text/css">.elegant-modal.modal-' + cid + ' .modal-header, .elegant-modal.modal-' + cid + ' .modal-footer{border-color:' + borderColor + ';}</style>';
}
#>
<div {{{ _.fusionGetAttributes( attrModal ) }}}>
	{{{ style }}}
	<div {{{ _.fusionGetAttributes( attrDialog ) }}}>
		<div {{{ _.fusionGetAttributes( attrContent ) }}}>
			<div {{{ _.fusionGetAttributes( attrHeader ) }}}>
				<button {{{ _.fusionGetAttributes( attrButton ) }}}>&times;</button>
				<h3 {{{ _.fusionGetAttributes( attrHeading ) }}}>{{{ title }}}</h3>
			</div>
			<div {{{ _.fusionGetAttributes( attrBody ) }}}>
				{{{ FusionPageBuilderApp.renderContent( elementContent, cid, false ) }}}
			</div>
			<# if ( 'yes' === showFooter ) { #>
				<div {{{ _.fusionGetAttributes( attrFooter ) }}}>
					<div {{{ _.fusionGetAttributes( attrFooterButton ) }}}>
						{{{ FusionPageBuilderApp.renderContent( closeButton, cid, false ) }}}
					</div>
				</div>
			<# } #>
		</div>
	</div>
</div>
<div {{{ _.fusionGetAttributes( triggerAttr ) }}}>
	{{{ FusionPageBuilderApp.renderContent( triggerContent, cid, false ) }}}
</div>
<#
var titleFont = typography_title,
	descriptionFont = typography_content,
	footerButtonFont = typography_footer_button,
	selectedFont = ( ! Number.isInteger( parseInt( titleFont ) ) ) ? titleFont : '',
	webfonts = elegantGoogleFonts,
	googleFonts = webfonts['Google Fonts'],
	sep = '';

if ( '' !== descriptionFont ) {
	sep           = ( '' !== selectedFont ) ? '|' : '';
	selectedFont += ( ! Number.isInteger( parseInt( descriptionFont ) ) ) ? sep + descriptionFont : '';
}

if ( '' !== footerButtonFont ) {
	sep           = ( '' !== selectedFont ) ? '|' : '';
	selectedFont += ( ! Number.isInteger( parseInt( footerButtonFont ) ) ) ? sep + footerButtonFont : '';
}

if ( '' !== selectedFont ) {
	#>
	<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
	<#
}
#>
</script>
