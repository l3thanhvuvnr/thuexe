<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 3.3.4
 */

?>
<script type="text/html" id="tmpl-iee_particles_banner-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
<div class="particles-js" id="particles-js-{{{ cid }}}"></div>
<#
if ( '' !== content ) {
	#>
	<div class="particles-js-content">{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}</div>
	<#
}
#>
</div>

</script>
