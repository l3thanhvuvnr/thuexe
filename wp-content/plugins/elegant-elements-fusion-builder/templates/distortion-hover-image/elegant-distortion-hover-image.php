<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-distortion-hover-image-wrapper' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-distortion-hover-image' ) . '></div>';

if ( '' !== $content ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-distortion-hover-content' ) . '>';
	$html .= wpautop( do_shortcode( $content ) );
	$html .= '</div>';
}

$html .= '</div>';

// Enqueue the script.
wp_enqueue_script( 'infi-distortion-hover' );
