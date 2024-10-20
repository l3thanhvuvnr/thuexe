<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_special_heading-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
		<#
		if ( 'above_heading' === separator_position ) {
			#>
			{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}
			<#
		}

		if ( '' !== title ) {
			#>
			<{{{ heading_size }}} {{{ _.fusionGetAttributes( titleAttr ) }}}>{{{ title }}}</{{{ heading_size }}}>
			<#
		}

		if ( 'after_heading' === separator_position ) {
			#>
			{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}
			<#
		}

		if ( '' !== description ) {
			#>
			<p {{{ _.fusionGetAttributes( descriptionAttr ) }}}>{{{ description }}}</p>
			<#
		}

		if ( 'after_decription' === separator_position ) {
			#>
			{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}
			<#
		}
		#>
	</div>
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
