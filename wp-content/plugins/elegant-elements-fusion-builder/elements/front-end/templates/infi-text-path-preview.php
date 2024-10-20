<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 3.5.0
 */

?>
<script type="text/html" id="tmpl-iee_text_path-shortcode">
<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
	<div {{{ _.fusionGetAttributes( attr ) }}}>
		{{{ svg }}}
	</div>
</div>
<#
var textFont = typography_path_text,
	selectedFont = ( ! Number.isInteger( parseInt( textFont ) ) ) ? textFont : '';

if ( '' !== selectedFont ) {
	#>
	<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
	<#
}
#>
</script>
