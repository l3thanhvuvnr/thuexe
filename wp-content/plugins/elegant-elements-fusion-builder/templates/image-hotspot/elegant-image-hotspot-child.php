<?php
$icon_class = ( function_exists( 'fusion_font_awesome_name_handler' ) ) ? fusion_font_awesome_name_handler( $this->child_args['pointer_icon'] ) : FusionBuilder::font_awesome_name_handler( $this->child_args['pointer_icon'] );
$position   = ( isset( $this->child_args['tooltip_position'] ) && '' !== $this->child_args['tooltip_position'] ) ? $this->child_args['tooltip_position'] : 'top';
$target     = ( isset( $this->child_args['target'] ) && '' !== $this->child_args['target'] ) ? ' target="' . $this->child_args['target'] . '"' : '';
$title      = ( isset( $this->child_args['link_url'] ) && '' !== $this->child_args['link_url'] ) ? '<a href="' . $this->child_args['link_url'] . '"' . $target . '>' . $this->child_args['title'] . '</a>' : $this->child_args['title'];
$pointer    = ( 'icon' === $this->child_args['pointer_type'] ) ? '<i class="' . $icon_class . '"></i>' : $this->image_hotspot_child_counter;

if ( 'text' === $this->child_args['pointer_type'] ) {
	$pointer = ( isset( $args['pointer_custom_text'] ) && '' !== $args['pointer_custom_text'] ) ? base64_decode( $args['pointer_custom_text'] ) : ''; // @codingStandardsIgnoreLine
	$pointer = do_shortcode( $pointer );
}

$pointer = ( isset( $this->child_args['link_url'] ) && '' !== $this->child_args['link_url'] && 'yes' !== $this->child_args['disable_tooltip'] ) ? '<a class="elegant-image-hotspot-pointer elegant-pointer-shape-' . $this->args['pointer_shape'] . '" href="' . $this->child_args['link_url'] . '"' . $target . '>' . $pointer . '</a>' : '<span class="elegant-image-hotspot-pointer elegant-pointer-shape-' . $this->args['pointer_shape'] . '">' . $pointer . '</span>';

$child_html  = '<div ' . FusionBuilder::attributes( 'elegant-image-hotspot-item' ) . '>';
$child_html .= $pointer;

if ( isset( $args['custom_pointer_title'] ) && '' !== $args['custom_pointer_title'] ) {
	$pointer_title  = base64_decode( $args['custom_pointer_title'] ); // @codingStandardsIgnoreLine
	$title_position = isset( $args['pointer_title_position'] ) ? $args['pointer_title_position'] : 'right';
	$child_html    .= '<span class="elegant-image-hotspot-pointer-title position-' . $title_position . '">' . do_shortcode( $pointer_title ) . '</span>';
	$position       = $position . ' title-position-' . $title_position;
}

if ( 'yes' !== $this->child_args['disable_tooltip'] ) {
	$child_html .= '<span class="elegant-image-hotspot-tooltip tooltip-position-' . $position . '"  aria-label="' . $this->child_args['title'] . '" role="tooltip">' . $title . '</span>';
}

$child_html .= '<span class="elegant_' . $this->args['pointer_effect'] . '"></span>';
$child_html .= '</div>';
