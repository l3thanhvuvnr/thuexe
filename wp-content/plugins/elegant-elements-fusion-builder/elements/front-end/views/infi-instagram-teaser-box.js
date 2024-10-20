var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Instagram Teaser Box Element View.
		FusionPageBuilder.iee_instagram_teaser_box = FusionPageBuilder.ElementView.extend( {

			/**
			 * Modify template attributes.
			 *
			 * @since 2.5
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Validate values.
				this.validateValues( atts.params );

				// Create attribute objects
				attributes.attr       = this.buildAttr( atts.params );
				attributes.buttonAttr = this.buildButtonAttr( atts.params );

				// Any extras that need passed on.
				attributes.content                       = atts.params.element_content;
				attributes.username                      = atts.params.username;
				attributes.max_height                    = atts.params.max_height;
				attributes.button_text_color             = atts.params.button_text_color;
				attributes.button_background_color       = atts.params.button_background_color;
				attributes.button_text_color_hover       = atts.params.button_text_color_hover;
				attributes.button_background_color_hover = atts.params.button_background_color_hover;
				attributes.url                           = atts.params.url;

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.max_height = _.fusionGetValueWithUnit( values.max_height );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-instagram-teaser-box',
						style: ''
					} );

				attr['class'] += ' fusion-clearfix';

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
			 * @since 2.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildButtonAttr: function( values ) {
				var attr = {
						class: 'button follow-button',
						style: ''
					};

				attr['style'] += '--color:' + values.button_text_color + ';';
				attr['style'] += '--color-hover:' + values.button_text_color_hover + ';';
				attr['style'] += '--background-color:' + values.button_background_color + ';';
				attr['style'] += '--background-color-hover:' + values.button_background_color_hover + ';';

				return attr;
			}
		} );
	} );
}( jQuery ) );
