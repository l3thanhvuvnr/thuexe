<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_skew_heading-shortcode">
<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
	<div {{{ _.fusionGetAttributes( attr ) }}}>
		<{{{ heading_tag }}} {{{ _.fusionGetAttributes( headingAttr ) }}}>{{{ heading_text }}}</{{{ heading_tag }}}>
	</div>
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
