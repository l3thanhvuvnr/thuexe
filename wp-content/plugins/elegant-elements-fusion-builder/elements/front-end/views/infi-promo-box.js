/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Promo Box Element View.
		FusionPageBuilder.iee_promo_box = FusionPageBuilder.ElementView.extend( {

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

				// Check if dynamic field value is there.
				atts.params.background_image = ( 'undefined' !== typeof atts.values.background_image ) ? atts.values.background_image : atts.params.background_image;

				// Create attribute objects
				attributes.attr                   = this.buildAttr( atts.params );
				attributes.titleAttr              = this.buildTitleAttr( atts.params );
				attributes.descriptionAttr        = this.buildDescriptionAttr( atts.params );
				attributes.descriptionWrapperAttr = this.buildDescriptionWrapperAttr( atts.params );

				// Any extras that need passed on.
				attributes.content                = atts.params.element_content;
				attributes.image                  = ( 'undefined' !== typeof atts.values.image ) ? atts.values.image : atts.params.image;
				attributes.title                  = ( 'undefined' !== typeof atts.values.title ) ? atts.values.title : atts.params.title;
				attributes.heading_size           = atts.params.heading_size;
				attributes.description            = ( 'undefined' !== typeof atts.values.description ) ? atts.values.description : atts.params.description;
				attributes.typography_title       = atts.params.typography_title;
				attributes.typography_description = atts.params.typography_description;

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
				values.height                = _.fusionGetValueWithUnit( values.height );
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
						class: 'elegant-promo-box',
						style: ''
					} );

				attr['class'] += ' promo-image-align-' + values.image_align;

				if ( '' !== values.background_color ) {
					attr['style'] += 'background-color:' + values.background_color + ';';
				}

				if ( values.background_image ) {
					attr['style'] += 'background-image: url(' + values.background_image + ');';
					attr['style'] += 'background-position:' + values.background_position + ';';
					attr['style'] += 'background-repeat:' + values.background_repeat + ';';
					attr['style'] += 'background-blend-mode: overlay;';
				}

				if ( '' !== values.height ) {
					attr['style'] += 'height:' + values.height + ';';
				}

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildTitleAttr: function( values ) {
				var attr = {
						class: 'elegant-promo-box-title',
						style: ''
					},
					typography,
					title_typography;

				if ( '' !== values.typography_title ) {
					typography       = values.typography_title;
					title_typography = elegant_get_typography_css( typography );

					attr['style'] += title_typography;
				}

				if ( '' !== values.title_font_size ) {
					attr['style'] += 'font-size:' + values.title_font_size + ';';
				}

				if ( '' !== values.title_color ) {
					attr['style'] += 'color:' + values.title_color + ';';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'title',
						toolbar: false,
						'disable-return': true
					},
					attr
				);

				return attr;
			},

			/**
			 * Builds the attributes array for description.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildDescriptionWrapperAttr: function( values ) {
				var attr = {
						class: 'elegant-promo-box-description-wrapper'
					};

				attr['class'] += ' elegant-align-' + values.content_align;

				return attr;
			},

			/**
			 * Builds the attributes array for description.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildDescriptionAttr: function( values ) {
				var attr = {
						class: 'elegant-promo-box-description',
						style: ''
					},
					typography,
					description_typography;

				if ( '' !== values.typography_description ) {
					typography             = values.typography_description;
					description_typography = elegant_get_typography_css( typography );

					attr['style'] += description_typography;
				}

				if ( '' !== values.description_font_size ) {
					attr['style'] += 'font-size:' + values.description_font_size + ';';
				}

				if ( '' !== values.description_color ) {
					attr['style'] += 'color:' + values.description_color + ';';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'description',
						toolbar: false,
						'disable-return': true
					},
					attr
				);

				return attr;
			}
		} );
	} );
}( jQuery ) );
