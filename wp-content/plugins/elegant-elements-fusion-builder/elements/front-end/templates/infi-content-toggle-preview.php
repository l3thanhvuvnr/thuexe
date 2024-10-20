<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_content_toggle-shortcode">
<style type="text/css">
	{{{ styles }}}
</style>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="content-toggle-switch">
		<div class="content-toggle-switch-first">
			<span class="content-toggle-title">{{{ title_first }}}</span>
		</div>
		<div class="content-toggle-switch-button">
			<label class="content-toggle-switch-label"></label>
		</div>
		<div class="content-toggle-switch-last">
			<span class="content-toggle-title">{{{ title_last }}}</span>
		</div>
	</div>

	<div class="content-toggle-first active-content id-{{{ template_content_first }}}">
		{{{ FusionPageBuilderApp.renderContent( '[elegant_libray_element id="' + template_content_first + '"]', cid, false ) }}}
	</div>

	<div class="content-toggle-last">
		{{{ FusionPageBuilderApp.renderContent( '[elegant_libray_element id="' + template_content_last + '"]', cid, false ) }}}
	</div>
</div>
<#
var titleFont = typography_title,
	selectedFont = ( ! Number.isInteger( parseInt( titleFont ) ) ) ? titleFont : '',
	webfonts = elegantGoogleFonts,
	googleFonts = webfonts['Google Fonts'],
	sep = '';

if ( '' !== selectedFont ) {
	#>
	<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
	<#
}
#>
</script>
