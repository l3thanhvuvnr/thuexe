<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.1.0
 */

?>
<script type="text/html" id="tmpl-iee_faq_rich_snippets-shortcode">
<div class="elegant-faq-rich-snippets output-type-{{{ output_type }}}">
	<#
	var accordion = '';

	if ( 'undefined' !== typeof title ) {
		#>
		<h2 class="faq-rich-snippets-title">{{{ title }}}</h2>
		<#
	}
	#>
	<div class="fusion-child-element"></div>
</div>
</script>

<script type="text/html" id="tmpl-iee_faq_rich_snippet_item-shortcode">
<#
if ( 'descriptive' === parentValues.output_type ) {
	#>
	<div class="elegant-faq-rich-snippet-item">
		<h3 class="faq-rich-snippet-item-question">{{{ question }}}</h3>
		<div class="faq-rich-snippet-item-answer">{{{ FusionPageBuilderApp.renderContent( content, cid, false ) }}}</div>
	</div>
	<#
} else {
	var accordion = '[fusion_toggle title="' + question + '" open="no"]' + content + '[/fusion_toggle]';
	#>
	{{{ FusionPageBuilderApp.renderContent( accordion, cid, false ) }}}
	<#
}
#>
</script>
