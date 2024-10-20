<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 3.3.5
 */

wp_enqueue_script( 'infi-qrcode' );
?>
<script type="text/html" id="tmpl-iee_qrcode-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div id="qrcode-{{{ cid }}}"></div>
</div>
</script>
