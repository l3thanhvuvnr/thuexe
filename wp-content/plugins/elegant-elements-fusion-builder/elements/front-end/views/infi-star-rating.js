var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Star Rating Element View.
		FusionPageBuilder.iee_star_rating = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 3.5.0
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
				attributes.attr     = this.buildAttr( atts.params );
				attributes.starAttr = this.buildStarAttr( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.element_content;
				attributes.args    = atts.params;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.5.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.icon_size    = _.fusionGetValueWithUnit( values.icon_size );
				values.icon_spacing = _.fusionGetValueWithUnit( values.icon_spacing );
				values.icon_size    = _.fusionGetValueWithUnit( values.icon_size );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.5.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-star-rating',
						style: ''
					} );

				attr['class'] += ' elegant-align-' + values.alignment;

				attr['title']    = values.rating_value + '/' + values.rating_scale;
				attr['itemtype'] = 'http://schema.org/Rating';
				attr['itemprop'] = 'reviewRating';

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
			 * @since 3.5.0
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildStarAttr: function( values ) {
				var attr = {
						class: 'elegant-star',
						style: ''
					};

				attr['style'] += 'margin-right:' + values.icon_spacing + ';';
				attr['style'] += 'width:' + values.icon_size + '; height:' + values.icon_size + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
