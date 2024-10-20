<?php
$notification_icon = '';

if ( isset( $args['icon'] ) && '' !== $args['icon'] ) {
	$icon              = ( function_exists( 'fusion_font_awesome_name_handler' ) ) ? fusion_font_awesome_name_handler( $args['icon'] ) : FusionBuilder::font_awesome_name_handler( $args['icon'] );
	$notification_icon = '<span class="elegant-notification-box-icon ' . trim( $icon ) . '"></span>';
}

$html  = '<div ' . FusionBuilder::attributes( 'elegant-notification-box' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-notification-title' ) . '>';

if ( isset( $args['type'] ) && 'classic' === $args['type'] ) {
	$html .= $notification_icon;
}

$html .= $args['title'];

$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-notification-content' ) . '>';

if ( isset( $args['type'] ) && 'modern' === $args['type'] ) {
	$html .= $notification_icon;
}

$html .= do_shortcode( $content );

$html .= '</div>';
$html .= '</div>';
