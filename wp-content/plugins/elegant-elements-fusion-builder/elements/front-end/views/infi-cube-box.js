/* global FusionPageBuilderApp */

var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Cube Box Element View.
		FusionPageBuilder.iee_cube_box = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.3
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
				attributes.frontContentAttr = this.buildFrontContentAttr( atts.params );
				attributes.backContentAttr  = this.buildBackContentAttr( atts.params );

				// Any extras that need passed on.
				attributes.back_content  = atts.params.element_content;
				attributes.front_content = FusionPageBuilderApp.base64Decode( atts.params.front_content );

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.box_height                 = _.fusionGetValueWithUnit( values.box_height );
				values.border_size                = _.fusionGetValueWithUnit( values.border_size );
				values.border_radius_top_left     = values.border_radius_top_left ? _.fusionGetValueWithUnit( values.border_radius_top_left ) : '0px';
				values.border_radius_top_right    = values.border_radius_top_right ? _.fusionGetValueWithUnit( values.border_radius_top_right ) : '0px';
				values.border_radius_bottom_right = values.border_radius_bottom_right ? _.fusionGetValueWithUnit( values.border_radius_bottom_right ) : '0px';
				values.border_radius_bottom_left  = values.border_radius_bottom_left ? _.fusionGetValueWithUnit( values.border_radius_bottom_left ) : '0px';
				values.border_radius              = values.border_radius_top_left + ' ' + values.border_radius_top_right + ' ' + values.border_radius_bottom_right + ' ' + values.border_radius_bottom_left;
				values.border_radius              = ( '0px 0px 0px 0px' === values.border_radius ) ? '' : values.border_radius;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-cube-box',
						style: ''
					} );

				attr['class'] += ' cube-direction-' + values.cube_direction;

				attr['style'] += 'height:' + values.box_height + ';';

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
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildFrontContentAttr: function( values ) {
				var attr = {
						class: 'elegant-cube-box-front-content',
						style: ''
					};

				if ( '' !== values.front_background_color ) {
					attr['style'] += 'background:' + values.front_background_color + ';';
				}

				if ( values.border_size ) {
					attr['style'] += 'border: ' + values.border_size + ' ' + values.border_style + ' ' + values.border_color + ';';
				}

				if ( '' !== values.border_radius ) {
					attr['style'] += 'border-radius:' + values.border_radius + ';';
				}

				return attr;
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.3
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildBackContentAttr: function( values ) {
				var attr = {
						class: 'elegant-cube-box-back-content',
						style: ''
					};

				if ( '' !== values.back_background_color ) {
					attr['style'] += 'background:' + values.back_background_color + ';';
				}

				if ( 'yes' === values.back_border ) {
					if ( values.border_size ) {
						attr['style'] += 'border: ' + values.border_size + ' ' + values.border_style + ' ' + values.back_border_color + ';';
					}
				}

				if ( '' !== values.border_radius ) {
					attr['style'] += 'border-radius:' + values.border_radius + ';';
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
