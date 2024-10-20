<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 3.2.0
 */

?>
<script type="text/html" id="tmpl-iee_off_canvas_content-shortcode">
<#
var $trigger_content = '';
if ( 'text' === args['trigger_source'] ) {
	$trigger_content = args['trigger_text'];
} else if ( 'image' === args['trigger_source'] ) {
	$trigger_content = '<img src="' + args['trigger_image'] + '"/>';
} else if ( 'icon' === args['trigger_source'] ) {
	$trigger_content = '<span ' + _.fusionGetAttributes( attrTriggerIcon ) + '></span>';
}
#>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="elegant-off-canvas-content-trigger">
		<a href="#" class="elegant-off-canvas-trigger">
			<span data-target="{{{ args['canvas_id'] }}}">
				{{{ $trigger_content }}}
			</span>
		</a>
	</div>
</div>
</script>
<script type="text/html" id="tmpl-iee_off_canvas_content-template">
<#
var $template_content = '';
if ( 'saved_template' === args['content_source'] ) {
	$template_content = '[elegant_libray_element id="' + args['content_template'] + '"]';
} else if ( 'custom' === args['content_source'] ) {
	$template_content = args['element_content'];
} else if ( 'sidebar' === args['content_source'] && '' !== args['sidebar'] ) {
	$template_content = '[iee_display_sidebar sidebar="' + args['sidebar'] + '"]';
}
#>
<div {{{ _.fusionGetAttributes( attrContentWrapper ) }}}>
	<div class="elegant-off-content-header">
		<a href="#" class="elegant-off-content-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg></a>
	</div>
	<div {{{ _.fusionGetAttributes( attrContentBody ) }}}>
		{{{ FusionPageBuilderApp.renderContent( $template_content, cid, false ) }}}
	</div>
</div>
</script>
