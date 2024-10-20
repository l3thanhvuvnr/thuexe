<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 3.3.0
 */

?>
<script type="text/html" id="tmpl-iee_image-shortcode">
<#
var modal_data = ( '' !== modal_anchor ) ? 'data-toggle="modal" data-target=".fusion-modal.' + modal_anchor + '"' : '';
var elegantImage = ( 'undefined' !== typeof image_url && '' !== image_url ) ? '<img ' + modal_data + ' src="' + image_url + '" style="width:' + image_width + ';" />' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 560" style="width: 100%;"><path fill="#EAECEF" d="M0 0h1024v560H0z"/><g fill-rule="evenodd" clip-rule="evenodd"><path fill="#BBC0C4" d="M378.9 432L630.2 97.4c9.4-12.5 28.3-12.6 37.7 0l221.8 294.2c12.5 16.6.7 40.4-20.1 40.4H378.9z"/><path fill="#CED3D6" d="M135 430.8l153.7-185.9c10-12.1 28.6-12.1 38.7 0L515.8 472H154.3c-21.2 0-32.9-24.8-19.3-41.2z"/><circle fill="#FFF" cx="429" cy="165.4" r="55.5"/></g></svg>';
var imageBack = ( 'undefined' !== typeof image_url && '' !== image_url ) ? '<img src="' + image_url + '" style="width:' + image_width + ';" />' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 560" style="width: 100%;"><path fill="#EAECEF" d="M0 0h1024v560H0z"/><g fill-rule="evenodd" clip-rule="evenodd"><path fill="#BBC0C4" d="M378.9 432L630.2 97.4c9.4-12.5 28.3-12.6 37.7 0l221.8 294.2c12.5 16.6.7 40.4-20.1 40.4H378.9z"/><path fill="#CED3D6" d="M135 430.8l153.7-185.9c10-12.1 28.6-12.1 38.7 0L515.8 472H154.3c-21.2 0-32.9-24.8-19.3-41.2z"/><circle fill="#FFF" cx="429" cy="165.4" r="55.5"/></g></svg>';
var imageHTML = '';
var image_caption = 'Caption text for preview';
var image_title   = 'Image title for preview';
var image_metadata = '';

lightbox_image_meta = ( '' !== lightbox_image_meta && 'undefined' !== typeof lightbox_image_meta ) ? lightbox_image_meta.split( ',' ) : '';

_.each ( lightbox_image_meta, function( meta ) {
	var metaData = 'Image ' +  meta + ' for preview';
	image_metadata += ' data-' + meta + '="' + metaData + '"';
} );

if ( 'url' === click_action ) {
	imageHTML += '<a href="' + url + '" target="' + target + '">';
	imageHTML += elegantImage;
	imageHTML += '</a>';
} else if ( 'lightbox' === click_action ) {
	var lightbox_image = lightbox_image;
	var data_rel       = 'iLightbox';

	imageHTML += '<a href="' + lightbox_image + '" class="fusion-lightbox" data-rel="' + data_rel + '"' + image_metadata + '>';
	imageHTML += elegantImage;
	imageHTML += '</a>';
} else {
	imageHTML += elegantImage;
}
#>
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<div class="elegant-image-wrapper">
		{{{ imageHTML }}}
	</div>
	<div class="elegant-image-blur-shadow">
		{{{ imageBack }}}
	</div>
</div>
<style type="text/css">
<#
	if ( 'custom' === image_shape ) {
		#>
		.elegant-image-{{{ cid }}} img {
			border-radius: {{{ images_border_radius }}} !important;
		}
		<#
	} else if ( 'blob' === image_shape ) {
		#>
		.elegant-image-{{{ cid }}} img {
			border-radius: {{{ blob_shape }}} !important;
		}
		<#
	}
#>
</style>
</script>
