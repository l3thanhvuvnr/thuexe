<?php
$uid = wp_rand();

$html  = '<div ' . FusionBuilder::attributes( 'elegant-particles-banner' ) . '>';
$html .= '<div class="particles-js" id="particles-js-' . $uid . '"></div>';

if ( '' !== $content ) {
	$html .= '<div class="particles-js-content">' . do_shortcode( $content ) . '</div>';
}

$html .= '</div>';
