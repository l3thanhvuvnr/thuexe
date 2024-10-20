var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Big Caps Element View.
		FusionPageBuilder.iee_big_caps = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr   = this.buildAttr( atts.params );
				attributes.styles = this.buildStyles( atts.params );

				// Any extras that need passed on.
				attributes.content = atts.params.content;

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
				values.font_size              = _.fusionGetValueWithUnit( values.font_size );
				values.first_letter_font_size = _.fusionGetValueWithUnit( values.first_letter_font_size );
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
						class: 'elegant-big-caps',
						style: ''
					} );

				attr['class'] += ' elegant-big-caps-' + this.model.get( 'cid' );
				attr['class'] += ' elegant-align-' + values.alignment;

				attr['style'] += 'font-size:' + values.font_size + ';';

				if ( '' !== values.text_color ) {
					attr['style'] += 'color:' + values.text_color + ';';
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
			buildStyles: function( values ) {
				var styles = '';

				styles += '.elegant-big-caps.elegant-big-caps-' + this.model.get( 'cid' ) + ':first-letter{';

				styles += 'font-size:' + values.first_letter_font_size + ';';

				if ( '' !== values.caps_text_color ) {
					styles += 'color:' + values.caps_text_color + ';';
				}

				styles += '}';

				return styles;
			}
		} );
	} );
}( jQuery ) );
