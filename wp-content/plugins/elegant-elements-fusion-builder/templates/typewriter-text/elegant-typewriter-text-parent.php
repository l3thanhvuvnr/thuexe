<?php

do_shortcode( $content );

$parent_font_family = $child_font_family = '';

if ( isset( $args['typography_parent'] ) && '' !== $args['typography_parent'] ) {
	$typography         = $args['typography_parent'];
	$parent_font_family = elegant_get_typography_css( $typography );
}

if ( isset( $args['typography_child'] ) && '' !== $args['typography_child'] ) {
	$typography        = $args['typography_child'];
	$child_font_family = elegant_get_typography_css( $typography );
}

$html = '<div ' . FusionBuilder::attributes( 'elegant-typewriter-text-container elegant-typewriter-text-container-' . $this->typewriter_text_counter ) . '>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-typewriter-text elegant-align-' . $args['alignment'] ) . ' style="font-size:' . $args['font_size'] . 'px; color:' . $args['title_color'] . ';" data-loop="' . $args['loop'] . '" data-deletedelay="' . $args['delete_delay'] . '" data-counter="elegant-typewriter-' . $this->typewriter_text_counter . '">';

if ( isset( $args['prefix'] ) && '' !== $args['prefix'] ) {
	$html .= '<p ' . FusionBuilder::attributes( 'elegant-typewriter-text-prefix' ) . ' style="' . $parent_font_family . '">';
	$html .= $args['prefix'] . '&nbsp;';
	$html .= '</p>';
}

$html .= '<div id="elegant-typewriter-' . $this->typewriter_text_counter . '" class="typewriter-text"></div>';
$html .= '<p ' . FusionBuilder::attributes( 'elegant-typewriter-text-child' ) . '>';
foreach ( $this->typewriter_text[ $this->typewriter_text_counter ] as $key => $typewriter_text ) {
	$html .= '<span ' . FusionBuilder::attributes( 'elegant-typewriter-text-wrap' ) . ' style="' . $child_font_family . ' color:' . $typewriter_text['title_color'] . ';">' . $typewriter_text['title'] . '</span>';
}
$html .= '</p>';

if ( isset( $args['suffix'] ) && '' !== $args['suffix'] ) {
	$html .= '<p ' . FusionBuilder::attributes( 'elegant-typewriter-text-suffix' ) . ' style="' . $parent_font_family . '">';
	$html .= $args['suffix'];
	$html .= '</p>';
}

$html .= '</div>';
$html .= '</div>';
