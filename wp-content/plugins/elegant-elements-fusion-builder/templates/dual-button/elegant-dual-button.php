<?php
$button_1 = ( isset( $args['button_1'] ) && '' !== $args['button_1'] ) ? base64_decode( $args['button_1'] ) : ''; // @codingStandardsIgnoreLine
$button_2 = ( isset( $args['button_2'] ) && '' !== $args['button_2'] ) ? base64_decode( $args['button_2'] ) : ''; // @codingStandardsIgnoreLine

$separator_content = '';

if ( isset( $args['separator_type'] ) && 'string' === $args['separator_type'] ) {
	$separator_content = ( isset( $args['sep_text'] ) && '' !== $args['sep_text'] ) ? $args['sep_text'] : '';
} elseif ( isset( $args['separator_type'] ) && 'icon' === $args['separator_type'] ) {
	$separator_content = ( isset( $args['sep_icon'] ) && '' !== $args['sep_icon'] ) ? $args['sep_icon'] : '';
	if ( '' !== $separator_content ) {
		$icon_class        = ( function_exists( 'fusion_font_awesome_name_handler' ) ) ? fusion_font_awesome_name_handler( $separator_content ) : FusionBuilder::font_awesome_name_handler( $separator_content );
		$separator_content = '<span class="' . $icon_class . '"></span>';
	}
}

$html  = '<div ' . FusionBuilder::attributes( 'elegant-dual-button' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-dual-button-first' ) . '>';
$html .= do_shortcode( $button_1 );

if ( '' !== $separator_content ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-dual-button-separator' ) . '>';
	$html .= '<span class="elegant-dual-button-separator-text">' . $separator_content . '</span>';
	$html .= '</div>';
}

$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-dual-button-last' ) . '>';
$html .= do_shortcode( $button_2 );
$html .= '</div>';
$html .= '</div>';
