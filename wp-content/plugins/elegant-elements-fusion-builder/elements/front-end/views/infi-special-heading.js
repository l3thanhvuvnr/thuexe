/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Special Heading Element View.
		FusionPageBuilder.iee_special_heading = FusionPageBuilder.ElementView.extend( {

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
				attributes.wrapperAttr     = this.buildWrapperAttr( atts.params );
				attributes.titleAttr       = this.buildTitleAttr( atts.params );
				attributes.descriptionAttr = this.buildDescriptionAttr( atts.params );

				// Any extras that need passed on.
				attributes.content                = atts.params.element_content;
				attributes.title                  = atts.params.title;
				attributes.heading_size           = atts.params.heading_size;
				attributes.description            = atts.params.description;
				attributes.separator_position     = atts.params.separator_position;
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
				var paddingValues = '',
					mobilePaddingValues = '';

				paddingValues += 'padding-top:' + ( ( 'undefined' !== typeof values.padding_top ) ? _.fusionGetValueWithUnit( values.padding_top ) + ';' : '0px;' );
				paddingValues += 'padding-right:' + ( ( 'undefined' !== typeof values.padding_right ) ? _.fusionGetValueWithUnit( values.padding_right ) + ';' : '0px;' );
				paddingValues += 'padding-bottom:' + ( ( 'undefined' !== typeof values.padding_bottom ) ? _.fusionGetValueWithUnit( values.padding_bottom ) + ';' : '0px;' );
				paddingValues += 'padding-left:' + ( ( 'undefined' !== typeof values.padding_left ) ? _.fusionGetValueWithUnit( values.padding_left ) + ';' : '0px;' );

				values.container_padding = paddingValues;

				mobilePaddingValues += ' ' + ( ( 'undefined' !== typeof values.mobile_padding_top ) ? _.fusionGetValueWithUnit( values.mobile_padding_top ) : '0px' );
				mobilePaddingValues += ' ' + ( ( 'undefined' !== typeof values.mobile_padding_right ) ? _.fusionGetValueWithUnit( values.mobile_padding_right ) : '0px' );
				mobilePaddingValues += ' ' + ( ( 'undefined' !== typeof values.mobile_padding_bottom ) ? _.fusionGetValueWithUnit( values.mobile_padding_bottom ) : '0px' );
				mobilePaddingValues += ' ' + ( ( 'undefined' !== typeof values.mobile_padding_left ) ? _.fusionGetValueWithUnit( values.mobile_padding_left ) : '0px' );

				values.container_padding_mobile = mobilePaddingValues;

				values.height                   = _.fusionGetValueWithUnit( values.height );
				values.title_font_size          = _.fusionGetValueWithUnit( values.title_font_size );
				values.description_font_size    = _.fusionGetValueWithUnit( values.description_font_size );
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
						class: 'elegant-special-heading',
						style: ''
					} );

				attr['class'] += ' special-heading-align-' + values.alignment;

				if ( '' !== values.container_padding_mobile ) {
					attr['style'] += '--padding:' + values.container_padding_mobile + ';';
				}

				if ( '' !== values.height ) {
					attr['style'] += 'height:' + values.height + ';';
				}

				if ( '' !== values.background_color ) {
					attr['style'] += 'background-color:' + values.background_color + ';';
				}

				if ( values.background_image ) {
					attr['style'] += 'background-image: url(' + values.background_image + ');';
					attr['style'] += 'background-position:' + values.background_position + ';';
					attr['style'] += 'background-repeat:' + values.background_repeat + ';';
					attr['style'] += 'background-blend-mode: overlay;';
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
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.2
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildWrapperAttr: function( values ) {
				var attr = {
						class: 'elegant-special-heading-wrapper',
						style: ''
					};

				if ( '' !== values.container_padding ) {
					attr['style'] += values.container_padding;
				}

				if ( '' !== values.container_padding_mobile ) {
					attr['style'] += '--padding:' + values.container_padding_mobile + ';';
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
						class: 'elegant-special-heading-title',
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
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildDescriptionAttr: function( values ) {
				var attr = {
						class: 'elegant-special-heading-description',
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
