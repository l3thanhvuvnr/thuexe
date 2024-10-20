<?php

do_shortcode( $content );

$main_class = '.elegant-partner-logos-container.elegant-partner-logo-container-' . $this->partner_logos_counter;

$style  = '<style type="text/css">';
$style .= $main_class . ' .elegant-partner-logo {';

if ( isset( $args['border'] ) && $args['border'] ) {
	$style .= 'border-width: ' . FusionBuilder::validate_shortcode_attr_value( $args['border'], 'px' ) . ';';
	$style .= 'border-color: ' . $args['border_color'] . ';';
	$style .= 'border-style: ' . $args['border_style'] . ';';
}

if ( isset( $args['padding'] ) && '' !== $args['padding'] ) {
	$style .= 'padding:' . FusionBuilder::validate_shortcode_attr_value( $args['padding'], 'px' ) . ';';
}

if ( isset( $args['margin'] ) && '' !== $args['margin'] ) {
	$style .= 'margin:' . FusionBuilder::validate_shortcode_attr_value( $args['margin'], 'px' ) . ';';
}

if ( isset( $args['width'] ) && '' !== $args['width'] ) {
	$style .= 'max-width: ' . FusionBuilder::validate_shortcode_attr_value( $args['width'], 'px' ) . ';';
}

if ( isset( $args['height'] ) && '' !== $args['height'] ) {
	$style .= 'max-height: ' . FusionBuilder::validate_shortcode_attr_value( $args['height'], 'px' ) . ';';
}

$style .= '}';

$style .= '</style>';

$html = '<div ' . FusionBuilder::attributes( 'elegant-partner-logos-container elegant-partner-logo-container-' . $this->partner_logos_counter ) . '>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-partner-logos' ) . '>';

foreach ( $this->partner_logos[ $this->partner_logos_counter ] as $key => $partner ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-partner-logo elegant-partner-logo-' . $key ) . '>';

	$modal_data = ( isset( $partner['modal_anchor'] ) && '' !== $partner['modal_anchor'] ) ? 'data-toggle="modal" data-target=".fusion-modal.' . $partner['modal_anchor'] . '"' : '';

	$image = '<img ' . $modal_data . 'src="' . $partner['image_url'] . '" alt="' . basename( $partner['image_url'] ) . '"/>';

	if ( 'url' === $partner['click_action'] ) {
		$url   = ( strpos( $partner['url'], '://' ) === false ) ? 'http://' . $partner['url'] : $partner['url'];
		$html .= '<a href="' . $url . '" target="' . $partner['target'] . '">';
		$html .= $image;
		$html .= '</a>';
	} else {
		$html .= $image;
	}

	$html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';

$html .= $style;
