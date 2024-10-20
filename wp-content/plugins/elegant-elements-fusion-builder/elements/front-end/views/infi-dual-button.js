/* global FusionPageBuilderApp, elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Dual Button Element View.
		FusionPageBuilder.iee_dual_button = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr              = this.buildAttr( atts.params );
				attributes.button_1          = this.buildAttrButton1( atts.params );
				attributes.button_2          = this.buildAttrButton2( atts.params );
				attributes.separator_content = this.buildAttrSeparatorContent( atts.params );
				attributes.separatorAttr     = this.buildAttrSeparatorAttr( atts.params );

				// Any extras that need passed on.
				attributes.content              = atts.params.element_content;
				attributes.typography_separator = atts.params.typography_separator;

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
				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.button_1 ) ) === values.button_1 ) {
						values.button_1 = FusionPageBuilderApp.base64Decode( values.button_1 );
					}
				} catch ( error ) {
					// Print error here.
				}

				try {
					if ( FusionPageBuilderApp.base64Encode( FusionPageBuilderApp.base64Decode( values.button_2 ) ) === values.button_2 ) {
						values.button_2 = FusionPageBuilderApp.base64Decode( values.button_2 );
					}
				} catch ( error ) {
					// Print error here.
				}
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
					class: 'elegant-dual-button',
					style: ''
				} );

				if ( values.alignment && '' !== values.alignment ) {
					attr['class'] += ' elegant-align-' + values.alignment;
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
			 * Builds the attributes array for button 1.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrButton1: function( values ) {
				var button_1 = values.button_1;

				return button_1;
			},

			/**
			 * Builds the attributes array for button 2.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrButton2: function( values ) {
				var button_2 = values.button_2;

				return button_2;
			},

			/**
			 * Builds the attributes array for separator content.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrSeparatorContent: function( values ) {
				var separator_content = '';

				if ( values.separator_type && 'string' === values.separator_type ) {
					separator_content = ( values.sep_text && '' !== values.sep_text ) ? values.sep_text : '';
				} else if ( values.separator_type && 'icon' === values.separator_type ) {
					separator_content = ( values.sep_icon && '' !== values.sep_icon ) ? values.sep_icon : '';
					if ( '' !== separator_content ) {
						separator_content = '<span class="' + _.fusionFontAwesome( separator_content ) + '"></span>';
					}
				}

				return separator_content;
			},

			/**
			 * Builds the attributes array for separator wrapper.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrSeparatorAttr: function( values ) {
				var attr = {
						class: 'elegant-dual-button-separator',
						style: ''
					},
					typography,
					separator_typography;

				attr['class'] += ' elegant-separator-type-' + values.separator_type;

				if ( values.sep_background_color && '' !== values.sep_background_color ) {
					attr['style'] += 'background-color:' + values.sep_background_color + ';';
				}

				if ( values.sep_color && '' !== values.sep_color ) {
					attr['style'] += 'color:' + values.sep_color + ';';
				}

				if ( values.typography_separator && '' !== values.typography_separator ) {
					typography           = values.typography_separator;
					separator_typography = elegant_get_typography_css( typography );

					attr['style'] += separator_typography;
				}

				return attr;
			}
		} );
	} );

	_.extend( FusionPageBuilder.Callback.prototype, {
		// Dual button shortcode filter. Build dual button shortcode.
		elegantDualButtonShortcodeFilter: function( attributes, view ) {

			var button = view.$el,
				button_1 = button.find( '#button_1' ).val(),
				button_2 = button.find( '#button_2' ).val();

			button_1 = FusionPageBuilderApp.base64Encode( button_1 );
			button_2 = FusionPageBuilderApp.base64Encode( button_2 );

			attributes.params.button_1 = button_1;
			attributes.params.button_2 = button_2;

			return attributes;

		}
	} );
}( jQuery ) );
