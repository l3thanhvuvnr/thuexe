<?php
$separator = do_shortcode( $content );

$html  = '<div ' . FusionBuilder::attributes( 'elegant-special-heading' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-special-heading-wrapper' ) . '>';

if ( 'above_heading' === $args['separator_position'] ) {
	$html .= $separator;
}

if ( isset( $args['title'] ) && '' !== $args['title'] ) {
	$html .= '<' . $args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-special-heading-title' ) . '>' . $args['title'] . '</' . $args['heading_size'] . '>';
}

if ( 'after_heading' === $args['separator_position'] ) {
	$html .= $separator;
}

if ( isset( $args['description'] ) && '' !== $args['description'] ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-special-heading-description' ) . '>' . wpautop( $args['description'] ) . '</div>';
}

if ( 'after_decription' === $args['separator_position'] ) {
	$html .= $separator;
}

$html .= '</div>';
$html .= '</div>';
