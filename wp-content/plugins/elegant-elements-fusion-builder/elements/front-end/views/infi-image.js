var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Elegant Image Element View.
		FusionPageBuilder.iee_image = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 3.3.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Validate values.
				this.validateValues( atts.params );

				// Create attribute objects
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content              = atts.params.element_content;
				attributes.image_url            = atts.params.image_url;
				attributes.alignment            = atts.params.alignment;
				attributes.click_action         = atts.params.click_action;
				attributes.modal_anchor         = atts.params.modal_anchor;
				attributes.url                  = atts.params.url;
				attributes.target               = atts.params.target;
				attributes.lightbox_image       = atts.params.lightbox_image;
				attributes.lightbox_image_meta  = ( 'undefined' !== typeof atts.params.lightbox_image_meta ) ? atts.params.lightbox_image_meta : '';
				attributes.image_width      = ( 'undefined' !== typeof atts.params.image_width ) ? atts.params.image_width : '400px';
				attributes.image_shape          = atts.params.image_shape;
				attributes.blob_shape           = atts.params.blob_shape;
				attributes.images_border_radius = atts.params.images_border_radius;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.3.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.image_width      = _.fusionGetValueWithUnit( values.image_width );
				values.images_border_radius = _.fusionGetValueWithUnit( values.images_border_radius );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.3.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = {
						class: 'elegant-image',
						style: ''
					};

				attr['class'] += ' elegant-image-' + this.model.get( 'cid' );
				attr['class'] += ' elegant-align-' + values.alignment;

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
