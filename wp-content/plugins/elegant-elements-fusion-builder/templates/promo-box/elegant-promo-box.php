<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-promo-box' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-promo-box-image-wrapper' ) . '>';
$html .= '<img src="' . $this->args['image'] . '" alt="' . basename( $this->args['image'] ) . '" />';
$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-promo-box-description-wrapper' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-promo-box-title' ) . '>' . $this->args['title'] . '</' . $this->args['heading_size'] . '>';
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . FusionBuilder::attributes( 'elegant-promo-box-description' ) . '>' . $this->args['description'] . '</p>';
}

$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
