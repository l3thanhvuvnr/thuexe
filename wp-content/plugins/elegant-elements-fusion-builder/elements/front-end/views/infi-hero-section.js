/* global FusionPageBuilderApp */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Hero Section Element View.
		FusionPageBuilder.iee_hero_section = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 3.6.0
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
				attributes.attrContent     = this.buildAttrContent( atts.params );
				attributes.attrHeading     = this.buildAttrHeading( atts.params );
				attributes.attrDescription = this.buildAttrDescription( atts.params );
				attributes.attrImage       = this.buildAttrImage( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;

				attributes.heading_text             = atts.params.heading_text;
				attributes.heading_size             = atts.params.heading_size;
				attributes.description_text         = atts.params.description_text;
				attributes.hero_image               = atts.params.hero_image;
				attributes.image_retina             = atts.params.image_retina;
				attributes.secondary_content        = atts.params.secondary_content;
				attributes.lottie_image_shortcode   = atts.params.lottie_image_shortcode;
				attributes.heading_font_size        = atts.params.heading_font_size;
				attributes.heading_font_size_medium = atts.params.heading_font_size_medium;
				attributes.heading_font_size_small  = atts.params.heading_font_size_small;
				attributes.description_font_size    = atts.params.description_font_size;
				attributes.heading_text_color       = atts.params.heading_text_color;
				attributes.description_text_color   = atts.params.description_text_color;
				attributes.pace_after_heading       = atts.params.pace_after_heading;
				attributes.pace_after_description   = atts.params.pace_after_description;
				attributes.button_1                 = atts.params.button_1;
				attributes.button_2                 = atts.params.button_2;
				attributes.image_position           = atts.params.image_position;
				attributes.content_padding          = atts.params.content_padding;
				attributes.ection_padding           = atts.params.ection_padding;
				attributes.content_padding_top      = atts.params.content_padding_top;
				attributes.content_padding_right    = atts.params.content_padding_right;
				attributes.content_padding_bottom   = atts.params.content_padding_bottom;
				attributes.content_padding_left     = atts.params.content_padding_left;
				attributes.ection_padding_top       = atts.params.ection_padding_top;
				attributes.ection_padding_right     = atts.params.ection_padding_right;
				attributes.ection_padding_bottom    = atts.params.ection_padding_bottom;
				attributes.ection_padding_left      = atts.params.ection_padding_left;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				var paddingValues;

				if ( ! values.content_padding_top ) {
					values.content_padding_top = '';
				} else {
					values.content_padding_top = _.fusionGetValueWithUnit( values.content_padding_top );
				}

				if ( ! values.content_padding_right ) {
					values.content_padding_right = '';
				} else {
					values.content_padding_right = _.fusionGetValueWithUnit( values.content_padding_right );
				}

				if ( ! values.content_padding_bottom ) {
					values.content_padding_bottom = '';
				} else {
					values.content_padding_bottom = _.fusionGetValueWithUnit( values.content_padding_bottom );
				}

				if ( ! values.content_padding_left ) {
					values.content_padding_left = '';
				} else {
					values.content_padding_left = _.fusionGetValueWithUnit( values.content_padding_left );
				}

				paddingValues  = 'padding-top:' + values.content_padding_top + ';';
				paddingValues += 'padding-right:' + values.content_padding_right + ';';
				paddingValues += 'padding-bottom:' + values.content_padding_bottom + ';';
				paddingValues += 'padding-left:' + values.content_padding_left + ';';

				values.content_padding = paddingValues;

				if ( ! values.section_padding_top ) {
					values.section_padding_top = '';
				} else {
					values.section_padding_top = _.fusionGetValueWithUnit( values.section_padding_top );
				}

				if ( ! values.section_padding_right ) {
					values.section_padding_right = '';
				} else {
					values.section_padding_right = _.fusionGetValueWithUnit( values.section_padding_right );
				}

				if ( ! values.section_padding_bottom ) {
					values.section_padding_bottom = '';
				} else {
					values.section_padding_bottom = _.fusionGetValueWithUnit( values.section_padding_bottom );
				}

				if ( ! values.section_padding_left ) {
					values.section_padding_left = '';
				} else {
					values.section_padding_left = _.fusionGetValueWithUnit( values.section_padding_left );
				}

				paddingValues  = 'padding-top:' + values.section_padding_top + ';';
				paddingValues += 'padding-right:' + values.section_padding_right + ';';
				paddingValues += 'padding-bottom:' + values.section_padding_bottom + ';';
				paddingValues += 'padding-left:' + values.section_padding_left + ';';

				values.section_padding = paddingValues;

				// Decode the Lottie Image shortcode.
				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.lottie_image_shortcode ) ) === values.lottie_image_shortcode ) {
						values.lottie_image_shortcode = FusionPageBuilderApp.base64Decode( values.lottie_image_shortcode );
					}
				} catch ( error ) {
					// Print error here.
				}

				// Decode the button 1 shortcode.
				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.button_1 ) ) === values.button_1 ) {
						values.button_1 = FusionPageBuilderApp.base64Decode( values.button_1 );
					}
				} catch ( error ) {
					// Print error here.
				}

				// Decode the button 2 shortcode.
				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.button_2 ) ) === values.button_2 ) {
						values.button_2 = FusionPageBuilderApp.base64Decode( values.button_2 );
					}
				} catch ( error ) {
					// Print error here.
				}

				values.height = _.fusionGetValueWithUnit( values.height );
				values.width  = _.fusionGetValueWithUnit( values.width );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-hero-section',
						style: ''
					} );

				attr['class'] += ' image-position-' + values.image_position;

				if ( '' !== values.section_padding ) {
					attr['style'] += values.section_padding;
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
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrContent: function( values ) {
				var attr = {
						class: 'elegant-hero-section-content',
						style: ''
					};

				if ( '' !== values.content_padding ) {
					attr['style'] += values.content_padding;
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrHeading: function( values ) {
				var attr = {
						class: 'elegant-hero-section-heading',
						style: ''
					},
					$font_size,
					$spacing;

				$font_size     = _.fusionGetValueWithUnit( values.heading_font_size );
				attr['style'] += 'font-size:' + $font_size + ';';

				if ( values.heading_font_size ) {
					$font_size     = _.fusionGetValueWithUnit( values.heading_font_size_medium );
					attr['style'] += '--medium-font-size:' + $font_size + ';';
				}

				if ( values.heading_font_size ) {
					$font_size     = _.fusionGetValueWithUnit( values.heading_font_size_small );
					attr['style'] += '--small-font-size:' + $font_size + ';';
				}

				if ( values.heading_text_color ) {
					attr['style'] += 'color:' + values.heading_text_color + ';';
				}

				if ( values.space_after_heading ) {
					$spacing       = _.fusionGetValueWithUnit( values.space_after_heading );
					attr['style'] += 'margin-bottom:' + $spacing + ';';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'heading_text',
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
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrDescription: function( values ) {
				var attr = {
						class: 'elegant-hero-section-description',
						style: ''
					},
					$font_size,
					$spacing;

				$font_size     = _.fusionGetValueWithUnit( values.description_font_size );
				attr['style'] += 'font-size:' + $font_size + ';';

				if ( values.description_text_color ) {
					attr['style'] += 'color:' + values.description_text_color + ';';
				}

				if ( values.space_after_description ) {
					$spacing       = _.fusionGetValueWithUnit( values.space_after_description );
					attr['style'] += 'margin-bottom:' + $spacing + ';';
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'description_text',
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
			 * @since 3.6.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrImage: function( values ) {
				var attr = {
						class: '',
						style: ''
					};

				attr['src'] = values.hero_image;
				attr['alt'] = '';

				if ( 'undefined' !== typeof values.image_retina ) {
					attr['srcset']  = values.hero_image + ' 1x, ';
					attr['srcset'] += values.image_retina + ' 2x ';
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
