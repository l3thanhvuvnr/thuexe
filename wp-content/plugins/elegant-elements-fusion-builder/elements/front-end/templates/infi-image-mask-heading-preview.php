<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_image_mask_heading-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<#
	if ( '' !== heading ) {
		#>
		<{{{ heading_size }}} {{{ _.fusionGetAttributes( headingAttr ) }}}>{{{ heading }}}</{{{ heading_size }}}>
		<#
	}
	#>
</div>
<#
var titleFont = typography_heading,
	selectedFont = ( ! Number.isInteger( parseInt( titleFont ) ) ) ? titleFont : '',
	webfonts = elegantGoogleFonts,
	googleFonts = webfonts['Google Fonts'];

if ( '' !== selectedFont ) {
	#>
	<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
	<#
}
#>
</script>
