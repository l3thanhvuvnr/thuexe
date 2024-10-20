var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	'use strict';

	jQuery( document ).ready( function() {

		// Retina Image Element View.
		FusionPageBuilder.iee_retina_image = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.3
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Check for dynamic value.
				atts.params.image        = ( 'undefined' !== typeof atts.values.image ) ? atts.values.image : atts.params.image;
				atts.params.image_retina = ( 'undefined' !== typeof atts.values.image_retina ) ? atts.values.image_retina : atts.params.image_retina;

				// Create attribute objects
				attributes.attr      = this.buildAttr( atts.params );
				attributes.imageAttr = this.buildAttrImage( atts.params );

				// Any extras that need passed on.
				attributes.content  = atts.params.element_content;
				attributes.image    = ( 'undefined' !== typeof atts.values.image ) ? atts.values.image : atts.params.image;
				attributes.link_url = ( 'undefined' !== typeof atts.values.link_url ) ? atts.values.link_url : atts.params.link_url;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.width = _.fusionGetValueWithUnit( values.width );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-retina-image',
						style: ''
					} );

				attr['class'] += ' fusion-align' + values.alignment;

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrImage: function( values ) {
				var attr = {
					class: 'retina-image',
					style: '',
					src: '',
					srcset: ''
				};

				attr['src'] = values.image;

				if ( 'undefined' !== typeof values.image_retina ) {
					attr['srcset']  = values.image + ' 1x, ';
					attr['srcset'] += values.image_retina + ' 2x ';
				}

				attr['style'] = 'max-width:' + values.width + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
