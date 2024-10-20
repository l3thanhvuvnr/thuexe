<?php
$defaults = shortcode_atts(
	array(
		'circle'      => '',
		'circlecolor' => '',
		'icon'        => '',
		'iconcolor'   => '',
	),
	$args
);

extract( $defaults );

$this->child_args = $defaults;

$html  = '<li ' . FusionBuilder::attributes( 'list-box-shortcode-li-item' ) . '>';
$html .= '<span ' . FusionBuilder::attributes( 'list-box-shortcode-span' ) . '>';
$html .= '<i ' . FusionBuilder::attributes( 'list-box-shortcode-icon' ) . '></i>';
$html .= '</span>';
$html .= '<div ' . FusionBuilder::attributes( 'list-box-shortcode-item-content' ) . '>' . do_shortcode( $content ) . '</div>';
$html .= '</li>';

$this->circle_class = 'circle-no';
