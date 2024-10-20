<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.3
 */

?>
<script type="text/html" id="tmpl-iee_business_hours-shortcode">
<style type="text/css">
.elegant-business-hours .elegant-business-hours-items p {
	margin: 0;
}
.elegant-business-hours .elegant-business-hours-items .elegant-business-hours-child:last-child .elegant-business-hours-sep {
	display: none;
}
</style>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="elegant-business-hours-items fusion-child-element"></div>
</div>
</script>

<script type="text/html" id="tmpl-iee_business_hours_item-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div {{{ _.fusionGetAttributes( dayAttr ) }}}>{{{ title }}}</div>
	<div {{{ _.fusionGetAttributes( hoursAttr ) }}}>{{{ hours_text }}}</div>
</div>
<div {{{ _.fusionGetAttributes( separatorAttr ) }}}></div>
</script>
