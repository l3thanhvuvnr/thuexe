<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-fancy-banner' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-fancy-banner-background-wrapper' ) . '></div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-fancy-banner-description-wrapper' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-fancy-banner-title' ) . '>' . $this->args['title'] . '</' . $this->args['heading_size'] . '>';
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . FusionBuilder::attributes( 'elegant-fancy-banner-description' ) . '>' . $this->args['description'] . '</p>';
}

$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
