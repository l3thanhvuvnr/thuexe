<?php
$link   = ( '' !== $this->child_args['url'] ) ? $this->child_args['url'] : '#';
$target = ( '' !== $this->child_args['target'] ) ? ' target="' . $this->child_args['target'] . '"' : '';
$alt    = get_post_meta( $this->child_args['image_id'], '_wp_attachment_image_alt', true );

$child_html .= '<li ' . FusionBuilder::attributes( 'elegant-slicebox-image-slider-item' ) . '>';
$child_html .= '<a href="' . $link . '"' . $target . '>';
$child_html .= '<img src="' . $this->child_args['image_url'] . '" alt="' . $alt . '"/>';
$child_html .= '</a>';

if ( '' !== $this->child_args['slide_title'] ) {
	$child_html .= '<div class="elegant-slicebox-image-description eesb-description" style="background:' . $this->child_args['slide_title_background'] . '; color: ' . $this->child_args['slide_title_text_color'] . ';">';
	$child_html .= '<h3 style="font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['slide_title_font_size'], 'px' ) . '">' . $this->child_args['slide_title'] . '</h3>';
	$child_html .= '</div>';
}

$child_html .= '</li>';
