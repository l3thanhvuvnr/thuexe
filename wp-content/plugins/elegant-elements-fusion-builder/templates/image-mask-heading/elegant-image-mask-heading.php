<?php
$html = '<div ' . FusionBuilder::attributes( 'elegant-image-mask-heading-wrapper' ) . '>';

if ( isset( $args['heading'] ) && '' !== $args['heading'] ) {
	$html .= '<' . $args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-image-mask-heading' ) . '><span>' . $args['heading'] . '</span></' . $args['heading_size'] . '>';
}

$html .= '</div>';
