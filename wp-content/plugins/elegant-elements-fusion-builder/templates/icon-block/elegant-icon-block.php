<?php
$icon_display = $this->args['icon_display'];

$icon_html  = '<div ' . FusionBuilder::attributes( 'elegant-icon-block-icon-wrapper' ) . '>';
$icon_html .= '<span ' . FusionBuilder::attributes( 'elegant-icon-block-icon' ) . '>';
$icon_html .= '</span>';
$icon_html .= '</div>';

$html = '<div ' . FusionBuilder::attributes( 'elegant-icon-block' ) . '>';

if ( 'top' === $icon_display ) {
	$html .= $icon_html;
}

$html .= '<div class="elegant-icon-block-title-wrapper">';
$html .= '<h3 ' . FusionBuilder::attributes( 'elegant-icon-block-title' ) . '>' . $this->args['title'] . '</h3>';
$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-icon-block-description' ) . '>';
$html .= '<p>' . $this->args['description'] . '</p>';
$html .= '</div>';

if ( 'top' !== $icon_display ) {
	$html .= $icon_html;
}

if ( isset( $this->args['link'] ) && '' !== $this->args['link'] ) {
	$target = ( isset( $this->args['target'] ) && '' !== $this->args['target'] ) ? ' target="' . $this->args['target'] . '"' : '';
	$html  .= '<a class="icon-block-link" href="' . $this->args['link'] . '"' . $target . '></a>';
}

$html .= '</div>';
