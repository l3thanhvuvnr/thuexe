<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 3.6.0
 */

?>
<script type="text/html" id="tmpl-iee_hero_section-shortcode">
<div {{{ _.fusionGetAttributes( attr) }}}>
	<div {{{ _.fusionGetAttributes( attrContent ) }}}>
		<# // Heading text. #>
		<{{{ heading_size }}} {{{ _.fusionGetAttributes( attrHeading ) }}}>
			{{{ heading_text }}}
		</{{{ heading_size }}}>

		<# // Description text. #>
		<p {{{ _.fusionGetAttributes( attrDescription ) }}}>
			{{{ description_text }}}
		</p>

		<# // Buttons. #>
		<div class="elegant-hero-section-buttons">
			<#
			if ( '' !== button_1 ) {
				#>
				{{{ FusionPageBuilderApp.renderContent( button_1, cid, false ) }}}
				<#
			}
			if ( '' !== button_2 ) {
				#>
				{{{ FusionPageBuilderApp.renderContent( button_2, cid, false ) }}}
				<#
			}
			#>
		</div>
	</div>
	<div class="elegant-hero-section-image">
		<#
		if ( 'image' === secondary_content ) {
			#>
			<img {{{ _.fusionGetAttributes( attrImage ) }}} />
			<#
		}

		if ( 'lottie' === secondary_content ) {
			#>
			{{{ FusionPageBuilderApp.renderContent( lottie_image_shortcode, cid, false ) }}}
			<#
		}

		if ( 'custom' === secondary_content ) {
			#>
			{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}
			<#
		}
		#>
	</div>
</div>
</script>
