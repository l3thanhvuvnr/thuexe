var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// WhatsApp Chat Button Element View.
		FusionPageBuilder.iee_whatsapp_chat_button = FusionPageBuilder.ElementView.extend( {

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
				attributes.attr     = this.buildAttr( atts.params );
				attributes.linkAttr = this.buildLinkAttr( atts.params );
				attributes.styles   = this.buildStyles( atts.params );

				// Any extras that need passed on.
				attributes.content         = atts.params.element_content;
				attributes.title           = atts.params.title;
				attributes.secondary_title = atts.params.secondary_title;
				attributes.avatar_image    = atts.params.avatar_image;

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
				values.font_size = _.fusionGetValueWithUnit( values.font_size );
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
						class: 'elegant-whatsapp-chat-button',
						style: ''
					} );

				attr['class'] += ' whatsapp-chat-button-' + this.model.get( 'cid' );
				attr['class'] += ' elegant-align-' + values.alignment;

				attr['style'] += 'font-size:' + values.font_size + ';';

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
			buildLinkAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-whatsapp-chat-button-link',
						style: ''
					} ),
					linkAttr = {},
					url = '';

				if ( '' !== values.text_color ) {
					attr['style'] += 'color:' + values.text_color + ';';
				}

				if ( '' !== values.button_bg_color ) {
					attr['style'] += 'background:' + values.button_bg_color + ';';
				}

				linkAttr     = {
					'phone': values.phone_number,
					'text': values.message
				};

				url = jQuery.param( linkAttr );

				attr['href']   = 'https://web.whatsapp.com/send?' + url;
				attr['target'] = '_blank';

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

				styles += '.elegant-whatsapp-chat-button.whatsapp-chat-button-' + this.model.get( 'cid' ) + ' .elegant-whatsapp-chat-button-link:hover{';

				if ( '' !== values.button_hover_bg_color ) {
					styles += 'background:' + values.button_hover_bg_color + ' !important;';
				}

				styles += '}';

				return styles;
			}
		} );
	} );
}( jQuery ) );
