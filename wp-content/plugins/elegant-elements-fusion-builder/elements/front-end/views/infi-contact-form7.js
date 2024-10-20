/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Contact Form 7 Element View.
		FusionPageBuilder.iee_contact_form7 = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr                    = this.buildAttr( atts.params );
				attributes.formAttr                = this.buildFormAttr( atts.params );
				attributes.headingWrapperStyleAttr = this.buildHeadingWrapperStyleAttr( atts.params );
				attributes.headingStyleAttr        = this.buildHeadingStyleAttr( atts.params );
				attributes.captionStyleAttr        = this.buildCaptionStyleAttr( atts.params );

				// Any extras that need passed on.
				attributes.content            = atts.params.element_content;
				attributes.form               = atts.params.form;
				attributes.heading_text       = atts.params.heading_text;
				attributes.heading_size       = atts.params.heading_size;
				attributes.caption_text       = atts.params.caption_text;
				attributes.typography_heading = atts.params.typography_heading;
				attributes.typography_caption = atts.params.typography_caption;

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
				values.form_padding       = _.fusionGetValueWithUnit( values.form_padding );
				values.form_border_size   = _.fusionGetValueWithUnit( values.form_border_size );
				values.form_border_radius = _.fusionGetValueWithUnit( values.form_border_radius );
				values.heading_padding    = _.fusionGetValueWithUnit( values.heading_padding );
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
						class: 'elegant-contact-form7',
						style: ''
					} );

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			},

			/**
			 * Builds the attributes array for form wrapper.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildFormAttr: function( values ) {
				var attr = {
						class: 'elegant-contact-form7-form-wrapper',
						style: ''
					},
					border_radius;

				// Set form styles.
				if ( values.form_padding ) {
					attr['style'] += 'padding:' + values.form_padding + ';';
				}

				if ( values.form_background_color ) {
					attr['style'] += 'background-color:' + values.form_background_color + ';';
				}

				if ( values.form_background_image ) {
					attr['style'] += 'background-image: url(' + values.form_background_image + ');';
					attr['style'] += 'background-position: ' + values.form_background_position + ';';
					attr['style'] += 'background-repeat: ' + values.form_background_repeat + ';';
					attr['style'] += 'background-blend-mode: overlay;';
				}

				if ( values.form_border_size ) {
					attr['style'] += 'border-width:' + values.form_border_size + ';';
					attr['style'] += 'border-color:' + values.form_border_color + ';';
					attr['style'] += 'border-style:' + values.form_border_style + ';';

					if ( '' !== values.heading_text || '' !== values.caption_text ) {
						attr['style'] += 'border-top: none;';
					}
				}

				if ( '' !== values.form_border_radius ) {
					border_radius = values.form_border_radius;

					if ( '' !== values.heading_text || '' !== values.caption_text ) {
						attr['style'] += 'border-radius:0 0 ' + border_radius + ' ' + border_radius + ';';
					} else {
						attr['style'] += 'border-radius:' + border_radius + ';';
					}
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
			buildHeadingWrapperStyleAttr: function( values ) {
				var attr = {
						class: 'elegant-contact-form7-heading-wrapper',
						style: ''
					};

				attr['class'] += ' form-align-' + values.heading_align;

				if ( '' !== values.heading_padding ) {
					attr['style'] += 'padding:' + values.heading_padding + ';';
				}

				attr['style'] += ( '' !== values.heading_background_color ) ? 'background-color:' + values.heading_background_color + ';' : '';

				if ( '' !== values.heading_background_image ) {
					attr['style'] += 'background-image: url(' + values.heading_background_image + ');';
					attr['style'] += 'background-position: ' + values.heading_background_position + ';';
					attr['style'] += 'background-repeat: ' + values.heading_background_repeat + ';';
					attr['style'] += 'background-blend-mode: overlay;';
				}

				if ( '' !== values.form_border_size ) {
					attr['style'] += 'border-width:' + values.form_border_size + ';';
					attr['style'] += 'border-color:' + values.form_border_color + ';';
					attr['style'] += 'border-style:' + values.form_border_style + ';';
					attr['style'] += 'border-bottom: none;';
				}

				if ( '' !== values.form_border_radius ) {
					attr['style'] += 'border-radius:' + values.form_border_radius + ' ' + values.form_border_radius + ' 0 0;';
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
			buildHeadingStyleAttr: function( values ) {
				var attr = {
						class: 'elegant-contact-form7-heading',
						style: ''
					},
					typography,
					heading_typography;

				if ( values.heading_color ) {
					attr['style'] += 'color:' + values.heading_color + ';';
				}

				if ( values.heading_font_size ) {
					attr['style'] += 'font-size:' + values.heading_font_size + 'px;';
				}

				if ( '' !== values.typography_heading ) {
					typography         = values.typography_heading;
					heading_typography = elegant_get_typography_css( typography );

					attr['style'] += heading_typography;
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
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildCaptionStyleAttr: function( values ) {
				var attr = {
						class: 'elegant-contact-form7-caption',
						style: ''
					},
					typography,
					caption_typography;

				if ( values.caption_color ) {
					attr['style'] += 'color:' + values.caption_color + ';';
				}

				if ( values.caption_font_size ) {
					attr['style'] += 'font-size:' + values.caption_font_size + 'px;';
				}

				if ( '' !== values.typography_caption ) {
					typography         = values.typography_caption;
					caption_typography = elegant_get_typography_css( typography );

					attr['style'] += caption_typography;
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'caption_text',
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
