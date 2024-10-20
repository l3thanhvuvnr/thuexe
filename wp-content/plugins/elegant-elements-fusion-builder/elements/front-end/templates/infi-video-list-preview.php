<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.3
 */

?>
<script type="text/html" id="tmpl-iee_video_list-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="elegant-video-list-video-container"></div>
	<div class="elegant-video-list-items fusion-child-element"></div>
</div>
<style type="text/css">
	{{{ customStyle }}}
</style>
</script>

<script type="text/html" id="tmpl-iee_video_list_item-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<h3 {{{ _.fusionGetAttributes( titleAttr ) }}}>
		<i {{{ _.fusionGetAttributes( iconAttr ) }}}></i>
		{{{ title }}}
	</h3>
</div>
</script>
