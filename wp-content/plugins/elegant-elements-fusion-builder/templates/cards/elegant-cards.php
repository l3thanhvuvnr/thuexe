<?php
$html = '<div ' . FusionBuilder::attributes( 'elegant-cards' ) . '>';

$target = '';
if ( '' !== $this->args['target'] ) {
	$target = ( '' !== $this->args['target'] ) ? ' target="' . $this->args['target'] . '"' : '';
}

if ( 'card' === $this->args['link_type'] && '' !== $this->args['link_url'] ) {
	$url   = esc_url( $this->args['link_url'] );
	$html .= '<a href="' . $url . '"' . $target . '>';
}

if ( isset( $this->args['image'] ) && '' !== $this->args['image'] ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-cards-image-wrapper' ) . '>';

	if ( 'image' === $this->args['link_type'] && '' !== $this->args['link_url'] ) {
		$url       = esc_url( $this->args['link_url'] );
		$image_url = esc_url( $this->args['image'] );
		$html     .= '<a href="' . $url . '"' . $target . '>';
		$html     .= '<img src="' . $image_url . '" alt="' . basename( $image_url ) . '" />';
		$html     .= '</a>';
	} else {
		$html .= '<img src="' . $this->args['image'] . '" alt="' . basename( $this->args['image'] ) . '" />';
	}

	$html .= '</div>';
}

$html .= '<div ' . FusionBuilder::attributes( 'elegant-cards-description-wrapper' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<' . $this->args['heading_size'] . ' ' . FusionBuilder::attributes( 'elegant-cards-title' ) . '>' . $this->args['title'] . '</' . $this->args['heading_size'] . '>';
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . FusionBuilder::attributes( 'elegant-cards-description' ) . '>' . $this->args['description'] . '</p>';
}

$html .= do_shortcode( $content );
$html .= '</div>';

if ( 'card' === $this->args['link_type'] && '' !== $this->args['link_url'] ) {
	$html .= '</a>';
}

$html .= '</div>';
