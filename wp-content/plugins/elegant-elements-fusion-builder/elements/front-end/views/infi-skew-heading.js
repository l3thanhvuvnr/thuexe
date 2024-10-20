/* global get_gradient_color, elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Skew Heading Element View.
		FusionPageBuilder.iee_skew_heading = FusionPageBuilder.ElementView.extend( {

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
				attributes.wrapperAttr = this.buildWrapperAttr( atts.params );
				attributes.headingAttr = this.buildHeadingAttr( atts.params );

				// Any extras that need passed on.
				attributes.content            = atts.params.element_content;
				attributes.heading_tag        = atts.params.heading_tag;
				attributes.heading_text       = atts.params.heading_text;
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
				var attr = {
						class: 'elegant-skew-heading',
						style: ''
					};

				attr['class'] += ' elegant-skew-direction-' + values.skew_direction;

				if ( '' !== values.background_color_1 && '' === values.background_color_2 ) {
					attr['style'] += 'background:' + values.background_color_1 + ';';
				}

				if ( '' !== values.background_color_1 && '' !== values.background_color_2 ) {
					attr['style'] += 'background: ' + get_gradient_color( values ) + ';';
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildWrapperAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-skew-heading-wrapper',
						style: ''
					} );

				attr['style'] += '--background_color_1:' + values.background_color_1 + ';';
				attr['style'] += '--background_color_2:' + ( ( '' !== values.background_color_2 ) ? values.background_color_2 : values.background_color_1 ) + ';';

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
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildHeadingAttr: function( values ) {
				var attr = {
						class: 'elegant-skew-heading-text',
						style: ''
					};

				if ( '' !== values.typography_heading ) {
					attr['style'] += elegant_get_typography_css( values.typography_heading );
				}

				if ( '' !== values.heading_color ) {
					attr['style'] += 'color:' + values.heading_color + ';';
				}

				if ( '' !== values.heading_font_size ) {
					attr['style'] += 'font-size:' + values.heading_font_size + ';';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'heading_text',
						toolbar: false,
						'disable-return': true,
						'disable-extra-spaces': true
					},
					attr
				);

				return attr;
			}
		} );
	} );
}( jQuery ) );
