<?php
$style = '';

if ( isset( $args['border_color'] ) && '' !== $args['border_color'] ) {
	$style = '<style type="text/css">.elegant-modal.modal-' . $this->modal_counter . ' .modal-header, .elegant-modal.modal-' . $this->modal_counter . ' .modal-footer{border-color:' . $args['border_color'] . ';}</style>';
}

$html  = '<div ' . FusionBuilder::attributes( 'infi-modal-shortcode' ) . '>';
$html .= $style;
$html .= '<div ' . FusionBuilder::attributes( 'infi-modal-shortcode-dialog' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'infi-modal-shortcode-content' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'infi-modal-shortcode-header' ) . '>';
$html .= '<button ' . FusionBuilder::attributes( 'infi-modal-shortcode-button' ) . '>&times;</button>';
$html .= '<h3 ' . FusionBuilder::attributes( 'infi-modal-shortcode-heading' ) . '>' . $args['title'] . '</h3>';
$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'infi-modal-shortcode-body' ) . '>' . do_shortcode( $content ) . '</div>';

if ( isset( $args['show_footer'] ) && 'yes' === $args['show_footer'] ) {
	$button_title = ( isset( $args['button_title'] ) && '' !== $args['button_title'] ) ? $args['button_title'] : esc_attr__( 'Close', 'elegant-elements' );
	$button_color = ( isset( $args['button_color'] ) && '' !== $args['button_color'] ) ? $args['button_color'] : 'default';

	if ( 'custom' === $button_color ) {
		$button_attr      = array();
		$button_attr[]    = 'link="#"';
		$button_attr[]    = 'title="' . $button_title . '"';
		$button_attr[]    = 'color="' . $button_color . '"';
		$button_attr[]    = 'button_gradient_top_color="' . $args['button_gradient_top_color'] . '"';
		$button_attr[]    = 'button_gradient_bottom_color="' . $args['button_gradient_bottom_color'] . '"';
		$button_attr[]    = 'button_gradient_top_color_hover="' . $args['button_gradient_top_color_hover'] . '"';
		$button_attr[]    = 'button_gradient_bottom_color_hover="' . $args['button_gradient_bottom_color_hover'] . '"';
		$button_attr[]    = 'size="' . $args['footer_button_size'] . '"';
		$button_attr[]    = 'stretch="' . $args['footer_button_stretch'] . '"';
		$button_attr[]    = 'accent_color="' . $args['accent_color'] . '"';
		$button_attr[]    = 'accent_hover_color="' . $args['accent_hover_color'] . '"';
		$button_shortcode = '[fusion_button ' . implode( ' ', $button_attr ) . ']' . $button_title . '[/fusion_button]';
	} else {
		$button_shortcode = '[fusion_button size="' . $args['footer_button_size'] . '" stretch="' . $args['footer_button_stretch'] . '" color="' . $button_color . '"]' . $button_title . '[/fusion_button]';
	}

	$html .= '<div ' . FusionBuilder::attributes( 'infi-modal-shortcode-footer' ) . '>';
	$html .= '<div ' . FusionBuilder::attributes( 'infi-modal-shortcode-button-footer' ) . '>';
	$html .= do_shortcode( $button_shortcode );
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '</div></div></div>';

if ( isset( $args['modal_trigger'] ) && 'none' !== $args['modal_trigger'] ) {
	$trigger = '';
	switch ( $args['modal_trigger'] ) {
		case 'button':
			$trigger = ( isset( $args['button_shortcode'] ) && '' !== $args['button_shortcode'] ) ? do_shortcode( base64_decode( $args['button_shortcode'] ) ) : '';  // @codingStandardsIgnoreLine
			break;
		case 'image':
			$trigger = ( isset( $args['image_url'] ) && '' !== $args['image_url'] ) ? do_shortcode( '<img src="' . $args['image_url'] . '">' ) : '';
			break;
		case 'icon':
			$trigger = ( isset( $args['icon_shortcode'] ) && '' !== $args['icon_shortcode'] ) ? do_shortcode( base64_decode( $args['icon_shortcode'] ) ) : '';  // @codingStandardsIgnoreLine
			break;
		case 'text':
			$trigger = ( isset( $args['custom_text'] ) && '' !== $args['custom_text'] ) ? do_shortcode( '<span>' . $args['custom_text'] . '</span>' ) : '';
			break;
	}

	$html .= '<div ' . FusionBuilder::attributes( 'infi-modal-trigger' ) . '>';
	$html .= $trigger;
	$html .= '</div>';
}
