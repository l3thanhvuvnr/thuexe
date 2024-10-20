<?php
$link       = $this->args['qr_url'];
$name       = $this->args['qr_full_name'];
$phone      = $this->args['qr_phone'];
$email      = $this->args['qr_email'];
$sms_number = $this->args['qr_sms_number'];
$sms_text   = $this->args['qr_sms_text'];
$text       = $this->args['qr_text'];
$skype_id   = $this->args['qr_skype_id'];
$random_id  = $this->args['random_id'];

$html  = '<div ' . FusionBuilder::attributes( 'elegant-qrcode' ) . '>';
$html .= '<div id="qrcode-' . $random_id . '"></div>';

$whatsapp_link = '';

if ( 'whatsapp' === $this->args['type'] ) {
	$link_attr = array(
		'phone' => $phone,
		'text'  => rawurlencode( $sms_text ),
	);

	$url           = build_query( $link_attr );
	$whatsapp_link = 'https://api.whatsapp.com/send?' . $url;
}

wp_add_inline_script(
	'infi-qrcode',
	" var qrType = '" . $this->args['type'] . "',
		qrText = '';

	switch( qrType ) {
		case 'link':
			qrText = '" . $link . "';
			break;

		case 'vcard':
			// Contact Example.
			qrText = 'BEGIN:VCARD \\n';
			qrText += 'FN: " . $name . " \\n';
			qrText += 'TEL;WORK;VOICE: " . $phone . " \\n';
			qrText += 'EMAIL: " . $email . " \\n';
			qrText += 'END:VCARD';
			break;

		case 'sms':
			// SMS Example - Opens SMS app with default text if set.
			qrText = 'sms:" . $sms_number . "';
			qrText += '?&body=" . rawurlencode( $sms_text ) . "';
			break;

		case 'phone':
			// Opens calling app.
			qrText = 'tel:" . $phone . "';
			break;

		case 'email':
			// Opens email app.
			qrText = 'mailto:" . $email . "';
			break;

		case 'skype':
			// Opens skype app.
			qrText = 'skype:" . $skype_id . "';
			break;

		case 'whatsapp':
			// Open WhatsApp.
			qrText = '" . $whatsapp_link . "';
			break;

		case 'text':
		default:
			// Shows the text.
			qrText = '" . $text . "';
			break;

	}

	if ( document.getElementById( 'qrcode-" . $random_id . "' ) ) {
		new QRCode( document.getElementById( 'qrcode-" . $random_id . "' ), {
			text: qrText,
			width: '" . $this->args['width'] . "',
			height: '" . $this->args['height'] . "',
			colorDark: '" . $this->args['qr_color'] . "',
			colorLight: '#ffffff',
			correctLevel: QRCode.CorrectLevel.H
		} );
	}
	"
);

$html .= '</div>';
