<?php
$image = '<img ' . FusionBuilder::attributes( 'elegant-retina-image-src' ) . ' />';
$html  = '<div ' . FusionBuilder::attributes( 'elegant-retina-image' ) . '>';

if ( '' !== $this->args['link_url'] ) {
	$url    = esc_url( $this->args['link_url'] );
	$target = ( isset( $this->args['target'] ) && '' !== $this->args['target'] ) ? ' target="' . $this->args['target'] . '"' : '';
	$html  .= '<a href="' . $url . '"' . $target . '>';
	$html  .= $image;
	$html  .= '</a>';
} else {
	$html .= $image;
}

$html .= '</div>';
$html .= '<div class="fusion-clearfix"></div>';
