<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_list_box-shortcode">
<style type="text/css">
	.elegant-list-item.fusion-li-item.fusion-builder-live-child-element {
		display: block !important;
		width: 100%;
	}
	.elegant-list-item.fusion-li-item.fusion-builder-live-child-element .fusion-builder-child-element-content {
		display: grid;
		grid-template-columns: min-content auto;
		text-align: left;
		grid-gap: 15px;
	}
</style>
<div class="elegant-list-box-container">
	<div {{{ _.fusionGetAttributes( titleAttr ) }}}>
		<span {{{ _.fusionGetAttributes( titleSpanAttr ) }}}>{{{ title }}}</span>
	</div>
	<div {{{ _.fusionGetAttributes( itemsAttr ) }}}>
		<ul {{{ _.fusionGetAttributes( listNodeAttr ) }}}></ul>
	</div>
</div>
</script>

<script type="text/html" id="tmpl-iee_list_box_item-shortcode">
<span {{{ _.fusionGetAttributes( spanAttr ) }}}>
	<i {{{ _.fusionGetAttributes( iconAttr ) }}}></i>
</span>
<div {{{ _.fusionGetAttributes( itemContentAttr ) }}}>{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}</div>
</script>
