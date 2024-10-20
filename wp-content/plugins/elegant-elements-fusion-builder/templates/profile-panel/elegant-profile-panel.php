<?php
$html = '<div ' . FusionBuilder::attributes( 'elegant-profile-panel' ) . '>';

if ( isset( $this->args['header_image'] ) && '' !== $this->args['header_image'] ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-profile-panel-header-image-wrapper' ) . '></div>';
}

if ( isset( $this->args['profile_image'] ) && '' !== $this->args['profile_image'] ) {
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-profile-panel-profile-image-wrapper' ) . '>';
	$html .= '<img ' . FusionBuilder::attributes( 'elegant-profile-panel-profile-image' ) . ' src="' . $this->args['profile_image'] . '" alt="' . basename( $this->args['profile_image'] ) . '"/>';
	$html .= '</div>';
}

$html .= '<div ' . FusionBuilder::attributes( 'elegant-profile-panel-description-wrapper' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<h3 ' . FusionBuilder::attributes( 'elegant-profile-panel-title' ) . '>' . $this->args['title'] . '</h3>';
}

if ( isset( $this->args['description'] ) && '' !== $this->args['description'] ) {
	$html .= '<p ' . FusionBuilder::attributes( 'elegant-profile-panel-description' ) . '>' . $this->args['description'] . '</p>';
}

$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
