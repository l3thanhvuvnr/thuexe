<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-lottie-content-box' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-lottie-content-box-icon' ) . '>';
$html .= '<lottie-player ' . FusionBuilder::attributes( 'elegant-lottie-icon-player' ) . '></lottie-player>';
$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-lottie-content-box-content' ) . '>';
$html .= '<' . $this->args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-lottie-content-box-heading' ) . '>';
$html .= $this->args['heading_text'];
$html .= '</' . $this->args['heading_size'] . '>';
$html .= '<p ' . FusionBuilder::attributes( 'elegant-lottie-content-box-description' ) . '>';
$html .= $this->args['description_text'];
$html .= '</p>';

// Content Box Button.
if ( 'button' === $this->args['link_type'] ) {
	$html .= do_shortcode( $content );
}

// Content Box Text Link.
if ( 'text' === $this->args['link_type'] ) {
	$html .= '<a ' . FusionBuilder::attributes( 'elegant-lottie-content-box-link-text' ) . '>' . $this->args['link_text'] . '</a>';
}

$html .= '</div>';
$html .= '</div>';

// Content Box Link.
if ( 'content' === $this->args['link_type'] ) {
	$html = '<a href="' . $this->args['link_url'] . '" target="' . $this->args['link_target'] . '">' . $html . '</a>';
}
