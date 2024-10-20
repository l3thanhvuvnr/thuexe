var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Empty Space Element View.
		FusionPageBuilder.iee_empty_space = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;

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
				values.height = _.fusionGetValueWithUnit( values.height );
				values.width  = _.fusionGetValueWithUnit( values.width );
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
						class: 'elegant-empty-space',
						style: ''
					} ),
					height,
					width;

				attr['class'] += ' space-' + values.type;

				if ( 'vertical' == values.type ) {
					attr['class'] += ' fusion-clearfix';
					height         = values.height;
					attr['style']  = 'height:' + height + ';';
				} else {
					width         = values.width;
					attr['style'] = 'width:' + width + ';';
				}

				if ( values.class ) {
					attr['class'] += ' ' + values.class;
				}

				if ( values.id ) {
					attr['id'] = values.id;
				}

				return attr;
			}
		} );
	} );
}( jQuery ) );
