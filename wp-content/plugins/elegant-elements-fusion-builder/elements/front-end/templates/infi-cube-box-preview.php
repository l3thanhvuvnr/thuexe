<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.3
 */

?>
<script type="text/html" id="tmpl-iee_cube_box-shortcode">
<!-- Start cube box. -->
<div {{{ _.fusionGetAttributes( attr ) }}}>

<!-- Front side content. -->
<div class="elegant-cube-box-front">
	<div {{{ _.fusionGetAttributes( frontContentAttr ) }}}>
		<div class="elegant-cube-box-content">
			{{{ FusionPageBuilderApp.renderContent( front_content, cid, false ) }}}
		</div>
	</div>
</div>

<!-- Back side content. -->
<div class="elegant-cube-box-back">
	<div {{{ _.fusionGetAttributes( backContentAttr ) }}}>
		<div class="elegant-cube-box-content">
			{{{ FusionPageBuilderApp.renderContent( back_content, cid, false ) }}}
		</div>
	</div>
</div>

<!-- Close cube box. -->
</div>
</script>
