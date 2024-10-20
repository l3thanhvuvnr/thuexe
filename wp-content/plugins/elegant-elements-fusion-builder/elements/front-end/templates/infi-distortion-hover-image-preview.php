<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.5
 */

?>
<script type="text/html" id="tmpl-iee_distortion_hover_image-shortcode">
<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
<div {{{ _.fusionGetAttributes( attr ) }}}></div>
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
</script>
<?php
// Enqueue the script.
wp_enqueue_script( 'infi-distortion-hover' );
?>
