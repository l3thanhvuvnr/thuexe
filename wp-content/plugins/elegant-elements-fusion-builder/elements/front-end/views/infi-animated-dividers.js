var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Animated Dividers Element View.
		FusionPageBuilder.iee_animated_dividers = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.4
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
				attributes.attr      = this.buildAttr( atts.params );
				attributes.blockAttr = this.buildBlockAttr( atts.params );

				// Any extras that need passed on.
				attributes.background_color     = atts.params.background_color;
				attributes.background_color_top = ( 'undefined' !== typeof atts.params.background_color_top ) ? atts.params.background_color_top : 'transparent';

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.4
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.margin_top    = _.fusionGetValueWithUnit( values.margin_top );
				values.height        = _.fusionGetValueWithUnit( values.height );
				values.mobile_height = _.fusionGetValueWithUnit( values.mobile_height );
				values.background_color_top = ( 'undefined' !== typeof values.background_color_top && ( '' !== values.background_color_top || 'rgba(255,255,255,0)' !== values.background_color_top ) ) ? values.background_color_top : 'transparent';
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.4
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-animated-divider',
						style: ''
					} );

				attr['class'] += ' elegant-animated-divider-' + this.model.get( 'cid' );
				attr['class'] += ' elegant-divider-' + values.type;

				attr['style'] += 'margin-top:' + values.margin_top + ';';

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
			 * @since 2.4
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildBlockAttr: function( values ) {
				var attr = {
						class: 'elegant-animated-divider-block'
					};

				attr['data-elegant-divider']               = values.type;
				attr['data-elegant-divider-zindex']        = values.z_index;
				attr['data-elegant-divider-height']        = values.height;
				attr['data-elegant-divider-mobile-height'] = values.mobile_height;

				return attr;
			}
		} );
	} );
}( jQuery ) );
