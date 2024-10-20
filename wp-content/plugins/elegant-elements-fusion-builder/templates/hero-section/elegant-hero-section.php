<?php
$button_1 = ( isset( $args['button_1'] ) && '' !== $args['button_1'] ) ? base64_decode( $args['button_1'] ) : ''; // @codingStandardsIgnoreLine
$button_2 = ( isset( $args['button_2'] ) && '' !== $args['button_2'] ) ? base64_decode( $args['button_2'] ) : ''; // @codingStandardsIgnoreLine
$lotti_image = ( isset( $this->args['lottie_image_shortcode'] ) && '' !== $this->args['lottie_image_shortcode'] ) ? base64_decode( $this->args['lottie_image_shortcode'] ) : ''; // @codingStandardsIgnoreLine

$html  = '<div ' . FusionBuilder::attributes( 'elegant-hero-section' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-hero-section-content' ) . '>';

// Heading text.
$html .= '<' . $this->args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-hero-section-heading' ) . '>';
$html .= html_entity_decode( $this->args['heading_text'] );
$html .= '</' . $this->args['heading_size'] . '>';

// Description text.
$html .= '<p ' . FusionBuilder::attributes( 'elegant-hero-section-description' ) . '>';
$html .= html_entity_decode( $this->args['description_text'] );
$html .= '</p>';

// Button 1.
$html .= '<div ' . FusionBuilder::attributes( 'elegant-hero-section-buttons' ) . '>';
if ( '' !== $button_1 ) {
	$html .= do_shortcode( $button_1 );
}
if ( '' !== $button_2 ) {
	$html .= do_shortcode( $button_2 );
}
$html .= '</div>';

$html .= '</div>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-hero-section-image' ) . '>';

if ( 'image' === $this->args['secondary_content'] ) {
	$html .= '<img ' . FusionBuilder::attributes( 'elegant-hero-section-image-src' ) . ' />';
}

if ( 'lottie' === $this->args['secondary_content'] ) {
	$html .= do_shortcode( $lotti_image );
}

if ( 'custom' === $this->args['secondary_content'] ) {
	$html .= do_shortcode( $content );
}

$html .= '</div>';

$html .= '</div>';
