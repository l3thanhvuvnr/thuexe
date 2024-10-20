/* global get_gradient_color, elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Gradient Heading Element View.
		FusionPageBuilder.iee_gradient_heading = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Validate values.
				this.validateValues( atts.params );

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr        = this.buildAttr( atts.params );
				attributes.headingAttr = this.buildHeadingAttr( atts.params );

				// Any extras that need passed on.
				attributes.content            = atts.params.element_content;
				attributes.heading            = atts.params.heading;
				attributes.heading_size       = atts.params.heading_size;
				attributes.typography_heading = atts.params.typography_heading;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.heading_font_size = _.fusionGetValueWithUnit( values.heading_font_size );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-gradient-heading-wrapper',
						style: ''
					} );

				attr['class'] += ' elegant-gradient-heading-align-' + values.alignment;

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Builds the attributes array for heading.
			 *
			 * @access public
			 * @since 2.0
 			 * @param {Object} values - The values.
 			 * @return {array} Attributes array for wrapper.
			 */
			buildHeadingAttr: function( values ) {
				var attr = {
						class: 'elegant-gradient-heading',
						style: ''
					},
					typography,
					heading_typography;

				if ( '' !== values.typography_heading ) {
					typography         = values.typography_heading;
					heading_typography = elegant_get_typography_css( typography );

					attr['style'] += heading_typography;
				}

				if ( '' !== values.background_color_1 ) {
					attr['style'] += 'background: ' + get_gradient_color( values ) + ';';
					attr['style'] += 'color:' + values.background_color_1 + ';';
				}

				if ( '' !== values.background_color_1 && '' !== values.background_color_2 ) {
					attr['style'] += '-webkit-background-clip: text;';
					attr['style'] += '-webkit-text-fill-color: transparent;';
				}

				if ( '' !== values.heading_font_size ) {
					attr['style'] += 'font-size:' + values.heading_font_size + ';';
					attr['style'] += 'line-height:1.2em;';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'heading'
					},
					attr
				);

				return attr;
			}
		} );
	} );
}( jQuery ) );
