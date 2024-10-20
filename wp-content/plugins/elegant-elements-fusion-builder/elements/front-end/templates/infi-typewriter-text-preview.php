<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_typewriter_text-shortcode">
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
			<p class="elegant-typewriter-text-prefix">
			{{{ prefix }}} &nbsp;
			<div id="elegant-typewriter-{{{ cid }}}" class="typewriter-text"></div>
			</p>
			<#
		}
		#>

		<div class="fusion-child-element" style="display: inline-block;"></div>

		<#
		if ( '' !== suffix ) {
			#>
			<p class="elegant-typewriter-text-suffix">
			{{{ suffix }}}
			</p>
			<#
		}
		#>
	</div>
</div>
</script>

<script type="text/html" id="tmpl-iee_typewriter_text_child-shortcode">
<span {{{ _.fusionGetAttributes( attr ) }}}>{{{ title }}}</span>
</script>
