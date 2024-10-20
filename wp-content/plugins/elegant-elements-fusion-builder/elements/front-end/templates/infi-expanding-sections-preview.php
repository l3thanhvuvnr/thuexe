<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_expanding_sections-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>

	<div {{{ _.fusionGetAttributes( headingAreaAttr ) }}}>
		<div class="elegant-expanding-section-heading-wrapper">
			<div {{{ _.fusionGetAttributes( titleAttr ) }}}>
				<h2>{{{ title }}}</h2>
			</div>

			<div {{{ _.fusionGetAttributes( descriptionAttr ) }}}>
				{{{ description }}}
			</div>
		</div>

		<div {{{ _.fusionGetAttributes( iconAttr ) }}}>
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 32px;" xml:space="preserve">
				<g transform="translate(1 1)">
					<g>
						<path d="M489.667,233.667H276.333V20.333C276.333,8.551,266.782-1,255-1s-21.333,9.551-21.333,21.333v213.333H20.333    C8.551,233.667-1,243.218-1,255s9.551,21.333,21.333,21.333h213.333v213.333c0,11.782,9.551,21.333,21.333,21.333    s21.333-9.551,21.333-21.333V276.333h213.333c11.782,0,21.333-9.551,21.333-21.333S501.449,233.667,489.667,233.667z"></path>
					</g>
				</g>
			</svg>
		</div>
	</div>

	<div {{{ _.fusionGetAttributes( contentAttr ) }}}>
		<div class="elegant-expanding-section-content">
			{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}
		</div>
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
