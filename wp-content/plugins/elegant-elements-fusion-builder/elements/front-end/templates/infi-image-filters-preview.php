<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_image_filters-shortcode">
{{{ styles }}}
<div {{{ _.fusionGetAttributes( wrapperAttr ) }}}>
	<ul {{{ _.fusionGetAttributes( navAttr ) }}}></ul>
	<div {{{ _.fusionGetAttributes( contentAttr ) }}}></div>
	<#
	var titleFont = typography_image_title,
		descriptionFont = typography_navigation_title,
		selectedFont = ( ! Number.isInteger( parseInt( titleFont ) ) ) ? titleFont : '',
		webfonts = elegantGoogleFonts,
		googleFonts = webfonts['Google Fonts'],
		sep = '';

	if ( '' !== descriptionFont ) {
		sep           = ( '' !== selectedFont ) ? '|' : '';
		selectedFont += ( ! Number.isInteger( parseInt( descriptionFont ) ) ) ? sep + descriptionFont : '';
	}

	if ( '' !== selectedFont ) {
		#>
		<style type="text/css">@import url("https://fonts.googleapis.com/css?display=swap&family={{selectedFont}}");</style>
		<#
	}
	#>
</div>
</script>

<script type="text/html" id="tmpl-iee_filter_image-shortcode">
<#
var modal_data = ( '' !== modal_anchor ) ? 'data-toggle="modal" data-target=".fusion-modal.' + modal_anchor + '"' : '';
var filterImage = ( 'undefined' !== typeof image_url && '' !== image_url ) ? '<img ' + modal_data + ' src="' + image_url + '" />' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 560" style="width: 100%;"><path fill="#EAECEF" d="M0 0h1024v560H0z"/><g fill-rule="evenodd" clip-rule="evenodd"><path fill="#BBC0C4" d="M378.9 432L630.2 97.4c9.4-12.5 28.3-12.6 37.7 0l221.8 294.2c12.5 16.6.7 40.4-20.1 40.4H378.9z"/><path fill="#CED3D6" d="M135 430.8l153.7-185.9c10-12.1 28.6-12.1 38.7 0L515.8 472H154.3c-21.2 0-32.9-24.8-19.3-41.2z"/><circle fill="#FFF" cx="429" cy="165.4" r="55.5"/></g></svg>';
var filterContents = '';
var image_caption = ( 'undefined' !== typeof query_data && '' !== query_data.image.caption ) ? query_data.image.caption : 'Caption text for preview';
var image_title   = ( 'undefined' !== typeof query_data && '' !== query_data.image.title ) ? query_data.image.title : 'Image title for preview';
var image_metadata = '';

lightbox_image_meta = ( '' !== lightbox_image_meta ) ? lightbox_image_meta.split( ',' ) : '';

_.each ( lightbox_image_meta, function( meta ) {
	var metaData = ( 'undefined' !== typeof query_data && '' !== query_data.image[meta] ) ? query_data.image[meta] : 'Image ' +  meta + ' for preview';
	image_metadata += ' data-' + meta + '="' + metaData + '"';
} );

filterContents += '<div ' + _.fusionGetAttributes( attr ) + '>';
filterContents += '<div ' + _.fusionGetAttributes( filterAttr ) + '>';

if ( 'before_image' === image_title_position && 'undefined' !== typeof title ) {
	filterContents += '<div ' + _.fusionGetAttributes( titleAttr ) + '>';
	filterContents += title;
	filterContents += '</div>';
}

if ( 'url' === click_action ) {
	filterContents += '<a href="' + url + '" target="' + target + '">';
	filterContents += filterImage;
	filterContents += '</a>';
} else if ( 'lightbox' === click_action ) {
	var lightbox_image = lightbox_image_url;
	var data_rel       = 'iLightbox[gallery_image_' + cid + ']';

	filterContents += '<a href="' + lightbox_image + '" class="fusion-lightbox" data-rel="' + data_rel + '"' + image_metadata + '>';
	filterContents += filterImage;

	if ( ( 'on_image_hover' === image_title_position || 'after_image' === image_title_position ) && 'undefined' !== typeof title ) {
		filterContents += '<div ' + _.fusionGetAttributes( titleAttr ) + '>';

		if ( 'on_image_hover' === image_title_position ) {
			filterContents += '<div ' + _.fusionGetAttributes( titleOverlayAttr ) + '>';
			filterContents += title;
			filterContents += '</div>';
		} else {
			filterContents += title;
		}
		filterContents += '</div>';
	}
} else {
	filterContents += filterImage;
}

if ( 'lightbox' !== click_action ) {
	if ( ( 'after_image' === image_title_position || 'on_image_hover' === image_title_position ) && 'undefined' !== typeof title ) {
		filterContents += '<div ' + _.fusionGetAttributes( titleAttr ) + '>';

		if ( 'on_image_hover' === image_title_position ) {
			filterContents += '<div ' + _.fusionGetAttributes( titleOverlayAttr ) + '>';
			filterContents += title;
			filterContents += '</div>';
		} else {
			filterContents += title;
		}

		filterContents += '</div>';
	}
}

filterContents += '</div>';
filterContents += '</div>';
#>
{{{ filterContents }}}
</script>
