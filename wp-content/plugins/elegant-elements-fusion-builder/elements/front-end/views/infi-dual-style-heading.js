/* global get_gradient_color, elegant_get_typography_css */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Dual Style Heading Element View.
		FusionPageBuilder.iee_dual_style_heading = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.1.0
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
				attributes.attr             = this.buildAttr( atts.params );
				attributes.headingAttrFirst = this.buildAttrHeadingFirst( atts.params );
				attributes.headingAttrLast  = this.buildAttrHeadingLast( atts.params );

				// Any extras that need passed on.
				attributes.heading_tag              = atts.params.heading_tag;
				attributes.heading_first            = atts.params.heading_first;
				attributes.heading_last             = atts.params.heading_last;
				attributes.typography_heading_first = atts.params.typography_heading_first;
				attributes.typography_heading_last  = atts.params.typography_heading_last;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.1.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.font_size      = _.fusionGetValueWithUnit( values.font_size );
				values.heading_gap    = _.fusionGetValueWithUnit( values.heading_gap );
				values.padding_top    = _.fusionGetValueWithUnit( values.padding_top );
				values.padding_right  = _.fusionGetValueWithUnit( values.padding_right );
				values.padding_bottom = _.fusionGetValueWithUnit( values.padding_bottom );
				values.padding_left   = _.fusionGetValueWithUnit( values.padding_left );

				values.heading_first_border_size        = _.fusionGetValueWithUnit( values.heading_first_border_size );
				values.first_border_radius_top_left     = _.fusionGetValueWithUnit( values.first_border_radius_top_left );
				values.first_border_radius_top_right    = _.fusionGetValueWithUnit( values.first_border_radius_top_right );
				values.first_border_radius_bottom_right = _.fusionGetValueWithUnit( values.first_border_radius_bottom_right );
				values.first_border_radius_bottom_left  = _.fusionGetValueWithUnit( values.first_border_radius_bottom_left );

				values.heading_last_border_size        = _.fusionGetValueWithUnit( values.heading_last_border_size );
				values.last_border_radius_top_left     = _.fusionGetValueWithUnit( values.last_border_radius_top_left );
				values.last_border_radius_top_right    = _.fusionGetValueWithUnit( values.last_border_radius_top_right );
				values.last_border_radius_bottom_right = _.fusionGetValueWithUnit( values.last_border_radius_bottom_right );
				values.last_border_radius_bottom_left  = _.fusionGetValueWithUnit( values.last_border_radius_bottom_left );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.1.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-dual-style-heading',
						style: ''
					} );

				attr['class'] += ' elegant-align-' + values.alignment;

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
			 * @since 2.1.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrHeadingFirst: function( values ) {
				var attr = {
						class: 'elegant-dual-style-heading-first',
						style: ''
					},
					gradientValues = {
						background_color_1: values.heading_first_background_color,
						background_color_2: values.heading_first_background_color_2,
						gradient_direction: ( ( 'vertical' == values.heading_first_gradient_type ) ? 'top' : values.heading_first_gradient_direction ),
						force_gradient: true
					},
					border_position,
					border_size,
					border_radius_top_left,
					border_radius_top_right,
					border_radius_bottom_right,
					border_radius_bottom_left,
					border_radius;

				if ( '' !== values.heading_first_text_color ) {
					attr['style'] += 'color: ' + values.heading_first_text_color + ';';
				}

				if ( '' !== values.heading_first_background_color ) {
					attr['style'] += 'background-color: ' + values.heading_first_background_color + ';';
				}

				if ( '' !== values.heading_first_background_color_2 ) {
					attr['style'] += get_gradient_color( gradientValues );
				}

				if ( '' !== values.padding ) {
					attr['style'] += 'padding: ' + values.padding + ';';
				}

				if ( '' !== values.font_size ) {
					attr['style'] += 'font-size: ' + values.font_size + ';';
				}

				if ( '' !== values.heading_gap ) {
					attr['style'] += 'margin-right: ' + values.heading_gap + ';';
				}

				// Border.
				if ( values.heading_first_border_color && values.heading_first_border_size && values.heading_first_border_style ) {
					border_position = ( 'all' !== values.heading_first_border_position ) ? '-' + values.heading_first_border_position : '';
					border_size     = values.heading_first_border_size;
					attr['style']  += 'border' + border_position + ':' + border_size + ' ' + values.heading_first_border_style + ' ' + values.heading_first_border_color + ';';
				}

				// Border radius.
				border_radius_top_left     = ( '' !== values.first_border_radius_top_left ) ? values.first_border_radius_top_left : '0px';
				border_radius_top_right    = ( '' !== values.first_border_radius_top_right ) ? values.first_border_radius_top_right : '0px';
				border_radius_bottom_right = ( '' !== values.first_border_radius_bottom_right ) ? values.first_border_radius_bottom_right : '0px';
				border_radius_bottom_left  = ( '' !== values.first_border_radius_bottom_left ) ? values.first_border_radius_bottom_left : '0px';
				border_radius              = border_radius_top_left + ' ' + border_radius_top_right + ' ' + border_radius_bottom_right + ' ' + border_radius_bottom_left;
				border_radius              = ( '0px 0px 0px 0px' === border_radius ) ? '' : border_radius;

				if ( '' !== border_radius ) {
					attr['style'] += 'border-radius: ' + border_radius + ';';
				}

				// Typography.
				if ( '' !== values.typography_heading_first ) {
					attr['style'] += elegant_get_typography_css( values.typography_heading_first );
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'heading_first',
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
			 * @since 2.1.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttrHeadingLast: function( values ) {
				var attr = {
						class: 'elegant-dual-style-heading-last',
						style: ''
					},
					gradientValues = {
						background_color_1: values.heading_last_background_color,
						background_color_2: values.heading_last_background_color_2,
						gradient_direction: ( ( 'vertical' == values.heading_last_gradient_type ) ? 'top' : values.heading_last_gradient_direction ),
						force_gradient: true
					},
					border_position,
					border_size,
					border_radius_top_left,
					border_radius_top_right,
					border_radius_bottom_right,
					border_radius_bottom_left,
					border_radius;

				if ( '' !== values.heading_last_text_color ) {
					attr['style'] += 'color: ' + values.heading_last_text_color + ';';
				}

				if ( '' !== values.heading_last_background_color ) {
					attr['style'] += 'background-color: ' + values.heading_last_background_color + ';';
				}

				if ( '' !== values.heading_last_background_color_2 ) {
					attr['style'] += get_gradient_color( gradientValues );
				}

				if ( '' !== values.padding ) {
					attr['style'] += 'padding: ' + values.padding + ';';
				}

				if ( '' !== values.font_size ) {
					attr['style'] += 'font-size: ' + values.font_size + ';';
				}

				// Border.
				if ( values.heading_last_border_color && values.heading_last_border_size && values.heading_last_border_style ) {
					border_position = ( 'all' !== values.heading_last_border_position ) ? '-' + values.heading_last_border_position : '';
					border_size     = values.heading_last_border_size;
					attr['style']  += 'border' + border_position + ':' + border_size + ' ' + values.heading_last_border_style + ' ' + values.heading_last_border_color + ';';
				}

				// Border radius.
				border_radius_top_left     = ( '' !== values.last_border_radius_top_left ) ? values.last_border_radius_top_left : '0px';
				border_radius_top_right    = ( '' !== values.last_border_radius_top_right ) ? values.last_border_radius_top_right : '0px';
				border_radius_bottom_right = ( '' !== values.last_border_radius_bottom_right ) ? values.last_border_radius_bottom_right : '0px';
				border_radius_bottom_left  = ( '' !== values.last_border_radius_bottom_left ) ? values.last_border_radius_bottom_left : '0px';
				border_radius              = border_radius_top_left + ' ' + border_radius_top_right + ' ' + border_radius_bottom_right + ' ' + border_radius_bottom_left;
				border_radius              = ( '0px 0px 0px 0px' === border_radius ) ? '' : border_radius;

				if ( '' !== border_radius ) {
					attr['style'] += 'border-radius: ' + border_radius + ';';
				}

				// Typography.
				if ( '' !== values.typography_heading_last ) {
					attr['style'] += elegant_get_typography_css( values.typography_heading_last );
				}

				attr = _.fusionInlineEditor(
					{
						cid: this.model.get( 'cid' ),
						param: 'heading_last',
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
