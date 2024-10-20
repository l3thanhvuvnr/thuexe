<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_fancy_button-shortcode">
<#
var icon = '';

if ( '' !== button_icon ) {
	icon = '<i class="' + button_icon + '"></i>';
}

if ( '' !== button_title ) {
	#>
	<div class="elegant-fancy-button-wrapper elegant-align-{{{ alignment }}}">
		<style type="text/css">
			{{{ customStyle }}}
		</style>
		<div {{{ _.fusionGetAttributes( attr ) }}}>
			<#
			if ( '' !== icon && 'left' === icon_position ) {
				button_title = icon + button_title;
			} else if ( '' !== icon && 'right' === icon_position ) {
				button_title = button_title + icon;
			}

			if ( 'corners' === style || 'alternate' === style || 'zoning-in' === style || 'vertical-overlap' === style || 'horizontal-overlap' === style ) {
				button_title = '<span>' + button_title + '</span>';
			}

			if ( 'position-aware' == style ) {
				button_title = '<span></span>' + button_title;
			}
			#>

			<a {{{ _.fusionGetAttributes( linkAttr ) }}}>{{{ button_title }}}</a>

		</div>
	</div>
	<#
	var titleFont = typography_button_title,
		selectedFont = ( ! Number.isInteger( parseInt( titleFont ) ) ) ? titleFont : '',
		webfonts = elegantGoogleFonts,
		googleFonts = webfonts['Google Fonts'];

	if ( '' !== selectedFont ) {
		#>
		<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
		<#
	}
}
#>
</script>
