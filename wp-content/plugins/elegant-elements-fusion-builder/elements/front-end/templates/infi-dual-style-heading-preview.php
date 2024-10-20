<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_dual_style_heading-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
<{{{ heading_tag }}} {{{ _.fusionGetAttributes( headingAttrFirst ) }}}>
		{{{ heading_first }}}
</{{{ heading_tag }}}><{{{ heading_tag }}} {{{ _.fusionGetAttributes( headingAttrLast ) }}}>
		{{{ heading_last }}}
</{{{ heading_tag }}}>
<#
var titleFont = typography_heading_first,
	descriptionFont = typography_heading_last,
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
