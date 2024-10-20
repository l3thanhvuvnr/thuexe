/* global elegant_get_typography_css, get_gradient_color */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Icon Block Element View.
		FusionPageBuilder.iee_icon_block = FusionPageBuilder.ElementView.extend( {

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
				attributes.attrTitle       = this.buildAttrTitle( atts.params );
				attributes.attrDescription = this.buildAttrDescription( atts.params );
				attributes.attrIconWrapper = this.buildAttrIconWrapper( atts.params );
				attributes.attrIcon        = this.buildAttrIcon( atts.params );

				// Any extras that need passed on.
				attributes.content                = atts.params.element_content;
				attributes.icon_display           = atts.params.icon_display;
				attributes.title                  = atts.params.title;
				attributes.description            = atts.params.description;
				attributes.typography_title       = atts.params.typography_title;
				attributes.typography_description = atts.params.typography_description;
				attributes.link                   = ( 'undefined' !== typeof atts.params.link ) ? atts.params.link : '';

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
				values.title_font_size       = _.fusionGetValueWithUnit( values.title_font_size );
				values.description_font_size = _.fusionGetValueWithUnit( values.description_font_size );
				values.icon_size             = _.fusionGetValueWithUnit( values.icon_size );
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
						class: 'elegant-icon-block',
						style: ''
					} );

				attr['class'] += ' elegant-align-' + values.content_align;

				if ( '' !== values.background_color_1 && '' === values.background_color_2 ) {
					attr['style'] += 'background:' + values.background_color_1 + ';';
				}

				if ( '' !== values.background_color_1 && '' !== values.background_color_2 ) {
					attr['style'] += 'background: ' + get_gradient_color( values ) + ';';
				}

				attr['style'] += 'border-color:' + values.background_color_1 + ';';

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
			 * @return {array} Attributes array.
			 */
			buildAttrTitle: function( values ) {
				var attr = {
						class: 'elegant-icon-block-title',
						style: ''
					};

				if ( '' !== values.typography_title ) {
					attr['style'] += elegant_get_typography_css( values.typography_title );
				}

				attr['style'] += 'color:' + values.title_color + ';';
				attr['style'] += 'font-size:' + values.title_font_size + ';';

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'title',
						toolbar: false,
						'disable-return': true,
						'disable-extra-spaces': true
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
			 * @return {array} Attributes array.
			 */
			buildAttrDescription: function( values ) {
				var attr = {
						class: 'elegant-icon-block-description',
						style: ''
					};

				if ( '' !== values.typography_description ) {
					attr['style'] += elegant_get_typography_css( values.typography_description );
				}

				attr['style'] += 'color:' + values.description_color + ';';
				attr['style'] += 'font-size:' + values.description_font_size + ';';

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'description',
						toolbar: false,
						'disable-return': true,
						'disable-extra-spaces': true
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
			 * @return {array} Attributes array.
			 */
			buildAttrIconWrapper: function( values ) {
				var attr = {
						class: 'elegant-icon-block-icon-wrapper'
					};

				attr['class'] += ' elegant-icon-block-icon-position-' + values.icon_display;

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array.
			 */
			buildAttrIcon: function( values ) {
				var attr = {
						class: 'elegant-icon-block-icon',
						style: ''
					};

				attr['class'] += ' ' + values.icon;

				attr['style'] += 'color:' + values.icon_color + ';';
				attr['style'] += 'font-size:' + values.icon_size + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
