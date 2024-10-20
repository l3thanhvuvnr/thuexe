<?php
/**
 * Underscore.js template
 *
 * @package elegan-elements-fusion-builder
 * @since 2.0
 */

?>
<script type="text/html" id="tmpl-iee_cards-shortcode">
<div {{{ _.fusionGetAttributes( attr ) }}}>
	<#
	var html = '';
	if ( 'card' === link_type && '' !== link_url ) {
		html += '<a href="' + link_url + '">';
	}

	if ( image ) {
		html += '<div ' + _.fusionGetAttributes( elegant_cards_image_wrapper ) + '>';

		if ( 'image' === link_type && '' !== link_url ) {
			html     += '<a href="' + link_url + '">';
			html     += '<img src="' + image + '" />';
			html     += '</a>';
		} else {
			html += '<img src="' + image + '" />';
		}

		html += '</div>';
	} else {
		html += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 560" style="width: 100%;"><path fill="#EAECEF" d="M0 0h1024v560H0z"/><g fill-rule="evenodd" clip-rule="evenodd"><path fill="#BBC0C4" d="M378.9 432L630.2 97.4c9.4-12.5 28.3-12.6 37.7 0l221.8 294.2c12.5 16.6.7 40.4-20.1 40.4H378.9z"/><path fill="#CED3D6" d="M135 430.8l153.7-185.9c10-12.1 28.6-12.1 38.7 0L515.8 472H154.3c-21.2 0-32.9-24.8-19.3-41.2z"/><circle fill="#FFF" cx="429" cy="165.4" r="55.5"/></g></svg>';
	}

	html += '<div ' + _.fusionGetAttributes( elegant_cards_description_wrapper ) + '>';

	if ( '' !== title ) {
		html += '<' + heading_size + ' ' + _.fusionGetAttributes( elegant_cards_title ) + '>' + title + '</' + heading_size + '>';
	}

	if ( '' !== description ) {
		html += '<p ' + _.fusionGetAttributes( elegant_cards_description ) + '>' + description + '</p>';
	}

	if ( 'button' === link_type ) {
		html += FusionPageBuilderApp.renderContent( content, cid, false );
	}

	html += '</div>';

	if ( 'card' === link_type && '' !== link_url ) {
		html += '</a>';
	}

	html += '</div>';
	#>
	{{{ html }}}
	<#
	var titleFont = typography_title,
		descriptionFont = typography_description,
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
