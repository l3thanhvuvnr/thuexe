<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_rotating_text-shortcode">
<style type="text/css" scoped="true">
.elegant-rotating-text-child.fusion-builder-live-child-element {
	position: absolute;
	top: 0;
}
</style>
<#
var titleFont = typography_parent,
	descriptionFont = typography_child,
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
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div {{{ _.fusionGetAttributes( titleAttr ) }}}>
		<#
		if ( '' !== prefix ) {
			#>
			<p class="elegant-rotating-text-prefix">
			{{{ prefix }}} &nbsp;
			</p>
			<#
		}
		#>
		<div class="fusion-child-element" style="display: inline-block;"></div>
	</div>
</div>
</script>

<script type="text/html" id="tmpl-iee_rotating_text_child-shortcode">
<span {{{ _.fusionGetAttributes( attr ) }}}>{{{ title }}}</span>
<span style="opacity:0;padding-right: 15px;">{{{ title }}}</span>
</script>
