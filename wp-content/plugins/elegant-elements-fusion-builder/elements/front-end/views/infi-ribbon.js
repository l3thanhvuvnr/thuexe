var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Ribbon Element View.
		FusionPageBuilder.iee_ribbon = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr        = this.buildAttr( atts.params );
				attributes.wrapperAttr = this.buildWrapperAttr( atts.params );

				// Any extras that need passed on.
				attributes.ribbon_text      = atts.params.ribbon_text;
				attributes.style            = atts.params.style;
				attributes.background_color = atts.params.background_color;

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
				values.font_size      = _.fusionGetValueWithUnit( values.font_size );
				values.letter_spacing = _.fusionGetValueWithUnit( values.letter_spacing );
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
						class: 'elegant-ribbon',
						style: ''
					} );

				attr['class'] += ' elegant-ribbon-' + this.model.get( 'cid' );
				attr['class'] += ' ribbon-position-' + values.position;
				attr['class'] += ' ribbon-style-' + values.style;

				if ( 'style05' === values.style || 'style06' === values.style ) {
					attr['class'] += ' ribbon-horizontal-' + values.horizontal_position;
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
			 * Builds the styles.
			 *
			 * @access public
			 * @since 2.4
			 * @param {Object} values - The values.
			 * @return {array} Styles.
			 */
			buildWrapperAttr: function( values ) {
				var attr = {
						class: 'elegant-ribbon-wrapper',
						style: ''
					};

				if ( '' !== values.text_color ) {
					attr['style'] += 'color:' + values.text_color + ';';
				}

				attr['style'] += 'font-size:' + values.font_size + ';';
				attr['style'] += 'border-color:' + values.background_color + ';';
				attr['style'] += 'background:' + values.background_color + ';';
				attr['style'] += '--background:' + values.background_color + ';';
				attr['style'] += 'text-transform:' + values.text_transform + ';';
				attr['style'] += 'letter-spacing:' + values.letter_spacing + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
