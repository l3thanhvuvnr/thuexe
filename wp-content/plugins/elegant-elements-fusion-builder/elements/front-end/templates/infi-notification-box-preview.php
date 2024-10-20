<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_notification_box-shortcode">
<#
var notification_icon = '';

if ( '' !== icon ) {
	notification_icon = '<span class="elegant-notification-box-icon ' + icon + '"></span>';
}
#>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div {{{ _.fusionGetAttributes( titleAttr ) }}}>
	<#
	if ( 'classic' === type ) {
		#>
		{{{ notification_icon }}}
		<#
	}
	#>
	{{{ title }}}
	</div>
	<div {{{ _.fusionGetAttributes( contentAttr ) }}}>
	<#
	if ( 'modern' === type ) {
		#>
		{{{ notification_icon }}}
		<#
	}
	#>

	{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}

	</div>
	<#
	var titleFont = typography_notification_title,
		descriptionFont = typography_notification_content,
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
