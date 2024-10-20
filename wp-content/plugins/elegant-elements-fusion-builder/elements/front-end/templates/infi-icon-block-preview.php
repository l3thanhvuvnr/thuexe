<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_icon_block-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
<#
if ( 'top' === icon_display ) {
	#>
	<div {{{ _.fusionGetAttributes( attrIconWrapper ) }}}>
		<span {{{ _.fusionGetAttributes( attrIcon ) }}}></span>
	</div>
	<#
}
#>

<div class="elegant-icon-block-title-wrapper">
	<h3 {{{ _.fusionGetAttributes( attrTitle ) }}}>{{{ title }}}</h3>
</div>
<div {{{ _.fusionGetAttributes( attrDescription ) }}}>
	{{{ description }}}
</div>

<#
if ( 'top' !== icon_display ) {
	#>
	<div {{{ _.fusionGetAttributes( attrIconWrapper ) }}}>
		<span {{{ _.fusionGetAttributes( attrIcon ) }}}></span>
	</div>
	<#
}
#>
<#
if ( '' !== link ) {
	#>
	<a class="icon-block-link" href="{{{ link }}}"></a>
	<#
}
#>
<#
var titleFont = typography_title,
	descriptionFont = typography_description,
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
