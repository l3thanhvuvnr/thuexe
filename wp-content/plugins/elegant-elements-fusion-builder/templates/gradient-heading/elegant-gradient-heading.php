<?php
$html .= '<div ' . FusionBuilder::attributes( 'elegant-gradient-heading-wrapper' ) . '>';

if ( isset( $args['heading'] ) && '' !== $args['heading'] ) {
	$heading_text = preg_replace( '~<p[^>]*>~', '', $args['heading'] );

	$html .= '<' . $args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-gradient-heading' ) . '>' . $heading_text . '</' . $args['heading_size'] . '>';
}

$html .= '</div>';
