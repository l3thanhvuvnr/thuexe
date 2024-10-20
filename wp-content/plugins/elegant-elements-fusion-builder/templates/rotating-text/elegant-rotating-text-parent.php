<?php

do_shortcode( $content );

$html = '<div ' . FusionBuilder::attributes( 'elegant-rotating-text-container' ) . '>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-rotating-text' ) . '>';

if ( isset( $args['prefix'] ) && '' !== $args['prefix'] ) {
	$html .= '<p ' . FusionBuilder::attributes( 'elegant-rotating-text-prefix' ) . '>';
	$html .= $args['prefix'] . '&nbsp;';
	$html .= '</p>';
}

$html .= '<p ' . FusionBuilder::attributes( 'elegant-rotating-text-child' ) . '>';
foreach ( $this->rotating_text[ $this->rotating_text_counter ] as $key => $rotating_text ) {
	$html .= '<span ' . FusionBuilder::attributes( 'elegant-rotating-text-wrap', $rotating_text ) . '>' . $rotating_text['title'] . '</span>';
}
$html .= '</p>';

$html .= '</div>';
$html .= '</div>';
