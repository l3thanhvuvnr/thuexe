<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.4
 */

?>
<script type="text/html" id="tmpl-iee_ribbon-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
		<span class="elegant-ribbon-text">
			{{{ ribbon_text }}}
		</span>
		<#
		if ( 'style04' === style ) {
			#>
			<span class="elegant-ribbon-arrow">
				<svg class="ribbon-triangle" xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="15" viewBox="0 0 100 100" preserveAspectRatio="none" style="fill:{{{ background_color }}};padding: 0;display: block;"><path d="M-1 -1 L50 99 L101 -1 Z"></path></svg>
			</span>
			<#
		}
		#>
	</div>
</div>
</script>
