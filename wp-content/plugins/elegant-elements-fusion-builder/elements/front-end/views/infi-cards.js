/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Elegant Cards Element View.
		FusionPageBuilder.iee_cards = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr                              = this.buildAttr( atts.params );
				attributes.elegant_cards_image_wrapper       = this.image_attr();
				attributes.elegant_cards_description_wrapper = this.description_wrapper_attr( atts.params );
				attributes.elegant_cards_title               = this.title_attr( atts.params );
				attributes.elegant_cards_description         = this.description_attr( atts.params );

				// Any extras that need passed on.
				attributes.content                = atts.params.element_content;
				attributes.alignment              = atts.params.alignment;
				attributes.element_typography     = atts.params.element_typography;
				attributes.heading_size           = atts.params.heading_size;
				attributes.title                  = ( 'undefined' !== typeof atts.values.title ) ? atts.values.title : atts.params.title;
				attributes.typography_title       = atts.params.typography_title;
				attributes.title_font_size        = atts.params.title_font_size;
				attributes.title_color            = atts.params.title_color;
				attributes.description            = ( 'undefined' !== typeof atts.values.description ) ? atts.values.description : atts.params.description;
				attributes.typography_description = atts.params.typography_description;
				attributes.description_font_size  = atts.params.description_font_size;
				attributes.description_color      = atts.params.description_color;
				attributes.hide_on_mobile         = atts.params.hide_on_mobile;
				attributes.class                  = atts.params.class;
				attributes.id                     = atts.params.id;
				attributes.background_color       = atts.params.background_color;
				attributes.border_color           = atts.params.border_color;
				attributes.link_type              = atts.params.link_type;
				attributes.link_url               = ( 'undefined' !== typeof atts.values.link_url ) ? atts.values.link_url : atts.params.link_url;
				attributes.image                  = ( 'undefined' !== typeof atts.values.image ) ? atts.values.image : atts.params.image;


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

				// Make sure the title text is not wrapped with an unattributed p tag.
				if ( 'undefined' !== typeof values.element_content ) {
					values.element_content = values.element_content.trim();
					values.element_content = values.element_content.replace( /(<p[^>]+?>|<p>|<\/p>)/img, '' );
				}

				values.border_radius_top_left     = ( 'undefined' !== typeof values.border_radius_top_left ) ? _.fusionGetValueWithUnit( values.border_radius_top_left ) : '0px';
				values.border_radius_top_right    = ( 'undefined' !== typeof values.border_radius_top_right ) ? _.fusionGetValueWithUnit( values.border_radius_top_right ) : '0px';
				values.border_radius_bottom_right = ( 'undefined' !== typeof values.border_radius_bottom_right ) ? _.fusionGetValueWithUnit( values.border_radius_bottom_right ) : '0px';
				values.border_radius_bottom_left  = ( 'undefined' !== typeof values.border_radius_bottom_left ) ? _.fusionGetValueWithUnit( values.border_radius_bottom_left ) : '0px';
				values.border_radius              = values.border_radius_top_left + ' ' + values.border_radius_top_right + ' ' + values.border_radius_bottom_right + ' ' + values.border_radius_bottom_left;
				values.border_radius              = ( '0px 0px 0px 0px' === values.border_radius ) ? '0px' : values.border_radius;
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
					class: 'elegant-cards',
					style: ''
				} );

				if ( 'undefined' !== typeof values.disable_hover_effect && 'yes' === values.disable_hover_effect ) {
					attr['class'] += ' no-hover-effect';
				}

				if ( 'undefined' !== typeof values.enable_box_shadow && 'yes' === values.enable_box_shadow ) {
					attr['class'] += ' elegant-card-shadow';
				}

				attr['style'] += 'border-radius:' + values.border_radius + ';';

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
			 * @return {array} Attributes array.
			 */
			image_attr: function() {
				var attr = {
					class: 'elegant-cards-image-wrapper',
					style: ''
				};

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array.
			 */
			description_wrapper_attr: function( values ) {
				var attr = {
					class: 'elegant-cards-description-wrapper elegant-align-' + values.alignment,
					style: ''
				};

				if ( '' !== values.background_color ) {
					attr['style'] += 'background-color:' + values.background_color + ';';
				}

				if ( '' !== values.border_color ) {
					attr['style'] += 'border-color:' + values.border_color + ';';
				}

				if ( '' == values.image ) {
					attr['style'] += 'border-top-style: solid;';
					attr['style'] += 'border-top-width: 1px;';
				}

				if ( '' == values.element_content ) {
					attr['style'] += 'padding-bottom: 5px;';
				}

				return attr;
			},

			/**
			 * Builds the attributes array for title.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array.
			 */
			title_attr: function( values ) {
				var attr = {
						class: 'elegant-cards-title',
						style: ''
					},
					typography,
					title_typography;

				if ( '' !== values.typography_title ) {
					typography = values.typography_title;
					title_typography = elegant_get_typography_css( typography );

					attr['style'] += title_typography;
				}

				if ( '' !== values.title_font_size ) {
					attr['style'] += 'font-size:' + _.fusionGetValueWithUnit( values.title_font_size ) + ';';
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
			 * @return {array} Attributes array.
			 */
			description_attr: function( values ) {
				var attr = {
						class: 'elegant-cards-description',
						style: ''
					},
					typography,
					description_typography;

				if ( '' !== values.typography_description ) {
					typography = values.typography_description;
					description_typography = elegant_get_typography_css( typography );

					attr['style'] += description_typography;
				}

				if ( '' !== values.description_font_size ) {
					attr['style'] += 'font-size:' + _.fusionGetValueWithUnit( values.description_font_size ) + ';';
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
