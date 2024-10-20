<?php
$html .= '<div ' . FusionBuilder::attributes( 'elegant-slicebox-image-slider' ) . '>';
$html .= '<ul ' . FusionBuilder::attributes( 'elegant-slicebox-image-slider-items' ) . '>';
$html .= do_shortcode( $content );
$html .= '</ul>';

if ( 'yes' === $this->args['navigation_arrows'] ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-slicebox-navigation-arrows' ) . '>';
	$html .= '<span class="elegant-slicebox-navigation-arrow navigation-arrow-prev"><i class="' . $this->args['prev_slide_icon'] . '"></i></span>';
	$html .= '<span class="elegant-slicebox-navigation-arrow navigation-arrow-next"><i class="' . $this->args['next_slide_icon'] . '"></i></span>';
	$html .= '</div>';
}

$html .= '</div>';
