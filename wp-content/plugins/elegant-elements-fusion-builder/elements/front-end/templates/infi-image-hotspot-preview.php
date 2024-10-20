<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_image_hotspot-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<#
		window[ cid + 'hotspot_counter' ] = 0;

		if ( '' !== hotspot_image ) {
			#>
			<img src="{{{ hotspot_image }}}">
			<#
		} else {
			#>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 560" style="width: 100%"><path fill="#EAECEF" d="M0 0h1024v560H0z"/><g fill-rule="evenodd" clip-rule="evenodd"><path fill="#BBC0C4" d="M378.9 432L630.2 97.4c9.4-12.5 28.3-12.6 37.7 0l221.8 294.2c12.5 16.6.7 40.4-20.1 40.4H378.9z"/><path fill="#CED3D6" d="M135 430.8l153.7-185.9c10-12.1 28.6-12.1 38.7 0L515.8 472H154.3c-21.2 0-32.9-24.8-19.3-41.2z"/><circle fill="#FFF" cx="429" cy="165.4" r="55.5"/></g></svg>
			<#
		}
	#>
	<div class="elegant-image-hotspot-items fusion-child-element"></div>
</div>
</script>

<script type="text/html" id="tmpl-iee_image_hotspot_item-shortcode">
<style type="text/css">
.elegant-image-hotspot-childitem[data-cid="{{{ cid }}}"] {
	position: absolute;
	top: {{{ position_top }}}%;
	left: {{{ position_left }}}%;
}
</style>
<#
window[ pcid + 'hotspot_counter' ] = ( 'undefined' !== typeof window[ pcid + 'hotspot_counter' ] ) ? window[ pcid + 'hotspot_counter' ] + 1 : 1;
window[ cid + 'hotspot_counter' ] = ( 'undefined' !== typeof window[ cid + 'hotspot_counter' ] ) ? window[ cid + 'hotspot_counter' ] : window[ pcid + 'hotspot_counter' ];

var hotspot_counter = window[ cid + 'hotspot_counter' ];

var pointer  = ( 'icon' === pointer_type ) ? '<i class="' + _.fusionFontAwesome( pointer_icon ) + '"></i>' : hotspot_counter;
var position = ( 'undefined' !== typeof tooltip_position && '' !== tooltip_position ) ? tooltip_position : 'top';

if ( 'text' === pointer_type ) {
	pointer = ( '' !== pointer_custom_text ) ? pointer_custom_text : '';
}
#>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<span class="elegant-image-hotspot-pointer elegant-pointer-shape-{{{ pointer_shape }}}">{{{ pointer }}}</span>
	<#
	if ( 'undefined' !== typeof custom_pointer_title && '' !== custom_pointer_title ) {
		#>
		<span class="elegant-image-hotspot-pointer-title position-{{{ pointer_title_position }}}">{{{ custom_pointer_title }}}</span>
		<#
	}

	if ( 'yes' !== disable_tooltip ) {
		#>
		<span class="elegant-image-hotspot-tooltip tooltip-position-{{{ position }}} title-position-{{{ pointer_title_position }}}" aria-label="{{{ title }}}" role="tooltip">{{{ title }}}</span>
		<#
	}
	#>
	<span class="elegant_{{{ pointer_effect }}}"></span>
</div>
</script>
