<?php

do_shortcode( $content );

$main_class      = '.elegant-testimonials-container.elegant-testimonial-container-' . $this->testimonials_counter;
$title_class     = $main_class . ' ' . $args['heading_size'] . '.elegant-testimonials-title';
$sub_title_class = $main_class . ' .elegant-testimonials-subtitle';
$content_class   = $main_class . ' .elegant-testimonials-content';

$title_font_family = $sub_title_font_family = $content_font_family = '';

if ( isset( $args['typography_title'] ) && '' !== $args['typography_title'] ) {
	$typography        = $args['typography_title'];
	$title_font_family = elegant_get_typography_css( $typography, true );
}

if ( isset( $args['typography_sub_title'] ) && '' !== $args['typography_sub_title'] ) {
	$typography            = $args['typography_sub_title'];
	$sub_title_font_family = elegant_get_typography_css( $typography, true );
}

if ( isset( $args['typography_content'] ) && '' !== $args['typography_content'] ) {
	$typography          = $args['typography_content'];
	$content_font_family = elegant_get_typography_css( $typography, true );
}

$style = '<style type="text/css">';

$style .= $main_class . '{';
if ( isset( $args['text_color'] ) && '' !== $args['text_color'] ) {
	$style .= 'color: ' . $args['text_color'] . ';';
}
if ( isset( $args['content_font_size'] ) && '' !== $args['content_font_size'] ) {
	$style .= 'font-size: ' . $args['content_font_size'] . 'px;';
}
if ( isset( $args['background_image'] ) && '' !== $args['background_image'] ) {
	$style .= 'background-image: url(' . $args['background_image'] . ');';
	$style .= 'background-position: ' . $args['background_position'] . ';';
}
if ( isset( $args['background_color'] ) && '' !== $args['background_color'] ) {
	$style .= 'background-color: ' . $args['background_color'] . ';';
	$style .= 'background-blend-mode: overlay;';
}
$style .= '}';

$style .= $title_class . '{';
$style .= $title_font_family;

if ( isset( $args['title_font_size'] ) && '' !== $args['title_font_size'] ) {
	$style .= 'font-size: ' . $args['title_font_size'] . 'px !important;';
}

$style .= '}';

$style .= $sub_title_class . '{';
$style .= $sub_title_font_family;

if ( isset( $args['sub_title_font_size'] ) && '' !== $args['sub_title_font_size'] ) {
	$style .= 'font-size: ' . $args['sub_title_font_size'] . 'px;';
}

$style .= '}';

$style .= $content_class . '{';
$style .= $content_font_family;
$style .= '}';

$position = ( isset( $args['description_position'] ) && '' !== $args['description_position'] ) ? $args['description_position'] : 'left';
$html     = '<div ' . FusionBuilder::attributes( 'elegant-testimonials-container elegant-testimonials-position-' . $position . ' elegant-testimonial-container-' . $this->testimonials_counter ) . '>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-testimonials-description-container' ) . '>';

foreach ( $this->testimonials[ $this->testimonials_counter ] as $key => $testimony ) {
	$active_class = ( 0 == $key ) ? 'active-description' : '';

	$html .= '<div ' . FusionBuilder::attributes( 'elegant-testimonials-description elegant-testimonial-' . $key . ' ' . $active_class ) . '>';
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-testimonials-title-container' ) . '>';
	$html .= '<' . $args['heading_size'] . ' class="elegant-testimonials-title" style="color: ' . $testimony['title_color'] . ';">' . $testimony['title'] . '</' . $args['heading_size'] . '>';
	$html .= '<span class="elegant-testimonials-subtitle" style="color: ' . $testimony['sub_title_color'] . ';">' . $testimony['sub_title'] . '</span>';
	$html .= '</div>';
	$html .= '<div class="elegant-testimonials-content">';
	$html .= $testimony['content'];
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '</div>';

$images_background_color = isset( $args['background_color'] ) && '' !== $args['background_color'] ? $args['background_color'] : '';
$html                   .= '<div ' . FusionBuilder::attributes( 'elegant-testimonials-images-container' ) . ' style="background-color:' . $images_background_color . ';">';

foreach ( $this->testimonials[ $this->testimonials_counter ] as $key => $testimony ) {
	$active_class = ( 0 == $key ) ? 'active-testimony' : '';

	$html .= '<div ' . FusionBuilder::attributes( 'elegant-testimonials-image fusion-layout-column fusion-one-fourth ' . $active_class ) . ' data-key="elegant-testimonial-' . $key . '">';
	$html .= '<img src="' . $testimony['image_url'] . '" alt="' . basename( $testimony['image_url'] ) . '"/>';
	$html .= '</div>';
}

$html .= '</div>';
$html .= '</div>';

$style .= '</style>';

$html .= $style;
