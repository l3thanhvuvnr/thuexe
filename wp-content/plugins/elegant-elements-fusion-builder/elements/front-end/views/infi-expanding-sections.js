/* global elegant_get_typography_css, get_gradient_color */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Expanding Sections Element View.
		FusionPageBuilder.iee_expanding_sections = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr            = this.buildAttr( atts.params );
				attributes.headingAreaAttr = this.buildHeadingAreaAttr( atts.params );
				attributes.titleAttr       = this.buildTitleAttr( atts.params );
				attributes.descriptionAttr = this.buildDescriptionAttr( atts.params );
				attributes.iconAttr        = this.buildIconAttr( atts.params );
				attributes.contentAttr     = this.buildContentAttr( atts.params );

				// Any extras that need passed on.
				attributes.content                = atts.params.element_content;
				attributes.title                  = atts.params.title;
				attributes.description            = atts.params.description;
				attributes.typography_title       = atts.params.typography_title;
				attributes.typography_description = atts.params.typography_description;

				return attributes;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildIconAttr: function( values ) {
				var attr = {
						class: 'elegant-expanding-section-icon',
						style: ''
					};

				if ( values.heading_color && '' !== values.heading_color ) {
					attr['style'] += 'fill:' + values.heading_color + ';';
				}

				return attr;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.title_font_size       = _.fusionGetValueWithUnit( values.title_font_size );
				values.description_font_size = _.fusionGetValueWithUnit( values.description_font_size );
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
					class: 'elegant-expanding-sections',
					style: ''
				} );

				attr['class'] += ' elegant-expanding-section-' + values.expanding_section_counter;

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Builds the attributes array for heading area.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildHeadingAreaAttr: function( values ) {
				var attr = {
						class: 'elegant-expanding-section-heading-area',
						style: ''
					};

				if ( ( values.background_color_1 && '' !== values.background_color_1 ) && ( values.background_color_2 && '' !== values.background_color_2 ) ) {
					attr['style'] += 'background: ' + get_gradient_color( values ) + ';';
				} else if ( values.background_color_1 && '' !== values.background_color_1 ) {
					attr['style'] += 'background: ' + values.background_color_1 + ';';
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
			buildTitleAttr: function( values ) {
				var attr = {
					class: 'elegant-expanding-section-title',
					style: ''
				},
				typography,
				title_typography;

				if ( values.typography_title && '' !== values.typography_title ) {
					typography       = values.typography_title;
					title_typography = elegant_get_typography_css( typography );

					attr['style'] += title_typography;
				}

				if ( values.title_font_size && '' !== values.title_font_size ) {
					attr['style'] += 'font-size:' + values.title_font_size + ';';
				}

				if ( values.heading_color && '' !== values.heading_color ) {
					attr['style'] += 'color:' + values.heading_color + ';';
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
			buildDescriptionAttr: function( values ) {
				var attr = {
						class: 'elegant-expanding-section-description',
						style: ''
					},
					typography,
					description_typography;

				if ( values.typography_description && '' !== values.typography_description ) {
					typography             = values.typography_description;
					description_typography = elegant_get_typography_css( typography );

					attr['style'] += description_typography;
				}

				if ( values.description_font_size && '' !== values.description_font_size ) {
					attr['style'] += 'font-size:' + values.description_font_size + ';';
				}

				if ( values.heading_color && '' !== values.heading_color ) {
					attr['style'] += 'color:' + values.heading_color + ';';
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
			buildContentAttr: function( values ) {
				var attr = {
						class: 'elegant-expanding-section-content-area',
						style: ''
					};

				attr['style'] += 'display: none;';

				if ( values.background_color_content && '' !== values.background_color_content ) {
					attr['style'] += 'background: ' + values.background_color_content + ';';
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
