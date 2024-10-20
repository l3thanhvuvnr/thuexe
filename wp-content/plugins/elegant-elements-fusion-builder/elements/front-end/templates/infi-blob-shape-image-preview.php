<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.5
 */

?>
<script type="text/html" id="tmpl-iee_blob_shape_image-shortcode">
<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
	<div {{{ _.fusionGetAttributes( attr ) }}}>
		<div {{{ _.fusionGetAttributes( backgroundAttr ) }}}></div>
		<#
		if ( '' !== content ) {
			#>
			<div {{{ _.fusionGetAttributes( contentAttr ) }}}>
				{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}
			</div>
			<#
		}
		#>
	</div>
</div>
</script>
