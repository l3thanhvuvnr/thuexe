<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 3.6.0
 */

?>
<script type="text/html" id="tmpl-iee_lottie_content_box-shortcode">
<#
// Content Box Link.
if ( 'content' === link_type ) {
	#>
	<a href="{{{ link_url }}}" target="{{{ link_target }}}">
	<#
}
#>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div {{{ _.fusionGetAttributes( icon_attr ) }}}>
		<lottie-player {{{ _.fusionGetAttributes( player_attr ) }}}></lottie-player>
	</div>
	<div {{{ _.fusionGetAttributes( content_attr ) }}}>
		<{{{ heading_size }}} {{{ _.fusionGetAttributes( heading_attr ) }}}>
			{{{ heading_text }}}
		</{{{ heading_size }}}>
		<p {{{ _.fusionGetAttributes( description_attr ) }}}>
			{{{ description_text }}}
		</p>

		<#
		// Content Box Button.
		if ( 'button' === link_type ) {
			#>
			{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}
			<#
		}

		// Content Box Text Link.
		if ( 'text' === link_type ) {
			#>
			<a {{{ _.fusionGetAttributes( link_text_attr ) }}}>{{{ link_text }}}</a>
			<#
		}
		#>
	</div>
</div>
<#
// Content Box Link.
if ( 'content' === link_type ) {
	#>
	</a>
	<#
}
#>
</script>
