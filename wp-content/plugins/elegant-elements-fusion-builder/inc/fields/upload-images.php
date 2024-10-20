<?php
/**
 * Underscore.js template.
 *
 * @since 3.3.2
 * @package fusion-library
 */

?>
<div class="fusion-multiple-upload-images">
	<input
		type="hidden"
		name="{{ param.param_name }}"
		id="{{ param.param_name }}"
		class="fusion-multi-image-input"
		value="{{ option_value }}"
	/>
	<a href="#"
		type='button'
		class='button button-upload elegant-elements-upload-images'
		data-type="image"
		data-title="{{ fusionBuilderText.select_images }}"
		data-id="fusion-multiple-images"
		data-element="{{ param.element }}"
	>{{ fusionBuilderText.select_images }}</a>

	<div class="fusion-multiple-image-container">
		<?php
		if ( ! is_admin() ) {
			?>
			<#
			image_ids = option_value.split( ',' );
			if ( '' !== option_value && 'object' === typeof image_ids ) {
				jQuery.ajax( {
					type: 'POST',
					url: elegantElementsConfig.ajaxurl,
					data: {
						action: 'elegant_elements_get_image_url',
						elegant_load_nonce: elegantElementsConfig.elegant_load_nonce,
						elegant_image_ids: image_ids
					},
					success: function( data ) {
						var dataObj;
						dataObj = JSON.parse( data );
						_.each( dataObj.images, function( image ) {
							jQuery( '.fusion-multiple-image-container' ).append( image );
						} );
					}
				} );
			}
			#>
			<?php
		}
		?>
	</div>
</div>
