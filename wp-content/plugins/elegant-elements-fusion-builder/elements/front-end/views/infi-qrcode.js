/* global QRCode */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// QRCode Element View.
		FusionPageBuilder.iee_qrcode = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 3.3.5
			 * @return {void}
			 */
			afterPatch: function() {
				this.generateQRCode( this.model.attributes );
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 3.3.5
			 * @param {Object} atts - The attributes.
			 * @return {Object} atts - The attributes.
			 */
			filterTemplateAtts: function( atts ) {
				var attributes = {};

				// Unique ID for this particular element instance, can be useful.
				attributes.cid = this.model.get( 'cid' );

				// Create attribute objects
				attributes.attr = this.buildAttr( atts.params );

				// Any extras that need passed on.
				attributes.content    = atts.params.element_content;
				attributes.type       = atts.params.type;
				attributes.link       = atts.params.qr_url;
				attributes.name       = atts.params.qr_full_name;
				attributes.phone      = atts.params.qr_phone;
				attributes.email      = atts.params.qr_email;
				attributes.sms_number = atts.params.qr_sms_number;
				attributes.sms_text   = atts.params.qr_sms_text;
				attributes.text       = atts.params.qr_text;
				attributes.skype_id   = atts.params.qr_skype_id;
				attributes.qr_color   = atts.params.qr_color;
				attributes.width      = atts.params.width;
				attributes.height     = atts.params.height;

				attributes.whatsapp_link = '';
				if ( 'whatsapp' === attributes.type ) {
					attributes.whatsapp_link = 'https://api.whatsapp.com/send?phone=' + attributes.phone + '&text=' + encodeURI( attributes.sms_text );
				}

				// Validate values.
				this.generateQRCode( attributes );

				return attributes;
			},

			/**
			 * Modifies values.
			 *
			 * @since 3.3.5
			 * @param {Object} atts - The attributes.
			 * @return {void}
			 */
			generateQRCode: function( atts ) {
				var qrType = atts.type,
					qrText = '';

				switch ( qrType ) {
					case 'link':
						qrText = atts.link;
						break;

					case 'vcard':
						// Contact Example.
						qrText = 'BEGIN:VCARD \\n';
						qrText += 'FN: ' + atts.name + ' \n';
						qrText += 'TEL;WORK;VOICE: ' + atts.phone + ' \n';
						qrText += 'EMAIL: ' + atts.email + ' \n';
						qrText += 'END:VCARD';
						break;

					case 'sms':
						// SMS Example - Opens SMS app with default text if set.
						qrText = 'sms:' + atts.sms_number;
						qrText += '?&body=' + encodeURI( atts.sms_text );
						break;

					case 'phone':
						// Opens calling app.
						qrText = 'tel:' + atts.phone;
						break;

					case 'email':
						// Opens email app.
						qrText = 'mailto:' + atts.email;
						break;

					case 'skype':
						// Opens skype app.
						qrText = 'skype:' + atts.skype_id;
						break;

					case 'whatsapp':
						// Open WhatsApp.
						qrText = atts.whatsapp_link;
						break;

					case 'text':
					default:
						// Shows the text.
						qrText = atts.text;
						break;

				}

				jQuery( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#qrcode-' + atts.cid ) ).html( '' );
				setTimeout( function() {
					if ( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#qrcode-' + atts.cid ) ) {
						new QRCode( jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( '#qrcode-' + atts.cid )[0], {
							text: qrText,
							width: atts.width,
							height: atts.height,
							colorDark: atts.qr_color,
							colorLight: '#ffffff',
							correctLevel: QRCode.CorrectLevel.H
						} );
					}
				}, 300 );
			},

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 3.3.5
			 * @param {Object} values - The values.
			 * @return {array} Attributes array for wrapper.
			 */
			buildAttr: function( values ) {
				var attr = _.fusionVisibilityAtts( values.hide_on_mobile, {
						class: 'elegant-qrcode',
						style: ''
					} );

				attr['style'] += 'height:' + values.height + ';';
				attr['style'] += 'width:' + values.width + ';';

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
