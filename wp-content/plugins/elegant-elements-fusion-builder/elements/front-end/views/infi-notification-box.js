/* global elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Notification Box Element View.
		FusionPageBuilder.iee_notification_box = FusionPageBuilder.ElementView.extend( {

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
				attributes.titleAttr   = this.buildTitleAttr( atts.params );
				attributes.contentAttr = this.buildContentAttr( atts.params );

				// Any extras that need passed on.
				attributes.content                         = atts.params.element_content;
				attributes.type                            = atts.params.type;
				attributes.title                           = atts.params.title;
				attributes.icon                            = atts.params.icon;
				attributes.typography_notification_title   = atts.params.typography_notification_title;
				attributes.typography_notification_content = atts.params.typography_notification_content;

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
				values.title_font_size   = _.fusionGetValueWithUnit( values.title_font_size );
				values.content_font_size = _.fusionGetValueWithUnit( values.content_font_size );
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
						class: 'elegant-notification-box',
						style: ''
					} ),
					colorObj,
					color_css;

				attr['class'] += ' elegant-notification-type-' + values.type;
				attr['class'] += ' elegant-notification-color-type-' + values.color_type;

				if ( 'custom' === values.color_type && '' !== values.custom_color_background ) {
					colorObj       = jQuery.Color( values.custom_color_background );
					color_css      = colorObj.alpha( 0.20 ).toRgbaString();
					attr['style'] += 'border-color:' + color_css + ';';

					attr['style'] += 'background:' + values.custom_color_background + ';';
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
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildTitleAttr: function( values ) {
				var attr = {
						class: 'elegant-notification-title',
						style: ''
					},
					colorObj,
					color_css,
					typography,
					notification_title_typography;

				if ( 'custom' === values.color_type && '' !== values.custom_color_title ) {
					attr['style'] += 'color:' + values.custom_color_title + ';';
				}

				if ( 'custom' === values.color_type && '' !== values.custom_color_background ) {
					colorObj       = jQuery.Color( values.custom_color_background );
					color_css      = colorObj.alpha( 0.20 ).toRgbaString();
					attr['style'] += 'background:' + color_css + ';';

					if ( 'modern' === values.type ) {
						attr['style'] += 'border-color:' + color_css + ';';
					}
				}

				if ( values.typography_notification_title ) {
					typography                    = values.typography_notification_title;
					notification_title_typography = elegant_get_typography_css( typography );

					attr['style'] += notification_title_typography;
				}

				if ( '' !== values.title_font_size ) {
					attr['style'] += 'font-size:' + values.title_font_size + ';';
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
						class: 'elegant-notification-content',
						style: ''
					},
					colorObj,
					color_css,
					typography,
					notification_content_typography;

				if ( '' !== values.custom_color_background && 'classic' === values.type && 'custom' === values.color_type ) {
					colorObj       = jQuery.Color( values.custom_color_background );
					color_css      = colorObj.alpha( 0.20 ).toRgbaString();
					attr['style'] += 'border-color:' + color_css + ';';
				}

				if ( 'custom' === values.color_type && '' !== values.custom_color_content ) {
					attr['style'] += 'color:' + values.custom_color_content + ';';
				}

				if ( values.typography_notification_content ) {
					typography                      = values.typography_notification_content;
					notification_content_typography = elegant_get_typography_css( typography );

					attr['style'] += notification_content_typography;
				}

				if ( '' !== values.content_font_size ) {
					attr['style'] += 'font-size:' + values.content_font_size + ';';
					attr['style'] += 'line-height: 1em;';
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
