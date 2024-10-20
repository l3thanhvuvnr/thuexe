<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_testimonials-shortcode">
<style type="text/css" scoped="true">
.elegant-testimonials-images-container.fusion-child-element.testimonial-child-element-{{{ cid }}} {
	display: grid;
	grid-gap: 0;
	grid-template-columns: 1fr 1fr 1fr 1fr;
}
{{{ styles }}}
</style>
<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
	<div class="elegant-testimonials-description-container elegant-testimonials-description-{{{ cid }}}"></div>
	<div class="elegant-testimonials-images-container fusion-child-element testimonial-child-element-{{{ cid }}}" style="background-color:{{{ images_background_color }}};"></div>
</div>
<#
var titleFont = typography_title,
	subTitleFont = typography_sub_title,
	contentFont = typography_content,
	selectedFont = ( ! Number.isInteger( parseInt( titleFont ) ) ) ? titleFont : '',
	webfonts = elegantGoogleFonts,
	googleFonts = webfonts['Google Fonts'],
	sep = '';

if ( '' !== subTitleFont ) {
	sep           = ( '' !== selectedFont ) ? '|' : '';
	selectedFont += ( ! Number.isInteger( parseInt( subTitleFont ) ) ) ? sep + subTitleFont : '';
}

if ( '' !== contentFont ) {
	sep           = ( '' !== selectedFont ) ? '|' : '';
	selectedFont += ( ! Number.isInteger( parseInt( contentFont ) ) ) ? sep + contentFont : '';
}

if ( '' !== selectedFont ) {
	#>
	<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
	<#
}
#>
</script>

<script type="text/html" id="tmpl-iee_testimonial-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
<#
if ( '' !== image_url ) {
	#>
	<img src="{{{ image_url }}}" />
	<#
} else {
	#>
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 560"><path fill="#EAECEF" d="M0 0h1024v560H0z"/><g fill-rule="evenodd" clip-rule="evenodd"><path fill="#BBC0C4" d="M378.9 432L630.2 97.4c9.4-12.5 28.3-12.6 37.7 0l221.8 294.2c12.5 16.6.7 40.4-20.1 40.4H378.9z"/><path fill="#CED3D6" d="M135 430.8l153.7-185.9c10-12.1 28.6-12.1 38.7 0L515.8 472H154.3c-21.2 0-32.9-24.8-19.3-41.2z"/><circle fill="#FFF" cx="429" cy="165.4" r="55.5"/></g></svg>
	<#
}
#>
</div>
</script>

<script type="text/html" id="tmpl-iee-testimonial-content-template">
<div class="elegant-testimonials-description elegant-testimonial-{{{ cid }}} {{{ active_class }}}">
	<div class="elegant-testimonials-title-container">
		<{{{heading_size }}} class="elegant-testimonials-title" style="color:{{{ title_color }}};">{{{ title }}}</{{{ heading_size }}}>
		<span class="elegant-testimonials-subtitle" style="color:{{{ sub_title_color }}};">{{{ sub_title }}}</span>
	</div>
	<div class="elegant-testimonials-content">
		{{{ FusionPageBuilderApp.renderContent( element_content, cid, false ) }}}
	</div>
</div>
</script>
