<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_dual_button-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="elegant-dual-button-first">
	{{{ FusionPageBuilderApp.renderContent( button_1, cid, false ) }}}
	<#
	if ( '' !== separator_content ) {
		#>
		<div {{{ _.fusionGetAttributes( separatorAttr ) }}}>
		<span class="elegant-dual-button-separator-text">{{{ separator_content }}}</span>
		</div>
		<#
	}
	#>
	</div>
	<div class="elegant-dual-button-last">
	{{{ FusionPageBuilderApp.renderContent( button_2, cid, false ) }}}
	</div>
	<#
	var separatorFont = typography_separator,
		selectedFont = ( ! Number.isInteger( parseInt( separatorFont ) ) ) ? separatorFont : '',
		webfonts = elegantGoogleFonts,
		googleFonts = webfonts['Google Fonts'];

	if ( '' !== selectedFont ) {
		#>
		<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
		<#
	}
	#>
</div>
</script>
