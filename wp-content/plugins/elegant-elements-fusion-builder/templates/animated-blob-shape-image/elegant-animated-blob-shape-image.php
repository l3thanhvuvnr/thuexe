<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-animated-blob-shape-image-wrapper' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-animated-blob-shape-image' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-animated-blob-shape-image-background' ) . '></div>';

if ( '' !== $content ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-animated-blob-shape-image-content' ) . '>';
	$html .= wpautop( do_shortcode( $content ) );
	$html .= '</div>';
}

if ( '' !== $this->args['link_url'] ) {
	$url    = $this->args['link_url'];
	$target = ( '' !== $this->args['target'] ) ? ' target="' . $this->args['target'] . '"' : '';
	$html  .= '<a class="elegant-blob-link" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;" href="' . $url . '"' . $target . '></a>';
}

$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-animated-blob-shape-image-inner' ) . '></div>';
$html .= '</div>';
