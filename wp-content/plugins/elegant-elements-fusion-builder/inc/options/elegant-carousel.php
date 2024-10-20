<?php
/**
 * Element Options.
 *
 * @author     InfiWebs
 * @copyright  (c) Copyright by InfiWebs
 * @link       https://infiwebs.com
 * @package    Elegant Elements
 * @subpackage Elegant Carousel
 * @since      1.3.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$fields['elegant_carousel_settings'] = array(
	'label'  => esc_html__( 'Elegant Carousel', 'elegant-elements' ),
	'id'     => 'elegant_carousel_settings',
	'type'   => 'accordion',
	'fields' => array(
		'elegant_carousel_slides_ipad_landscape' => array(
			'label'       => esc_html__( 'Slides to Show on iPad Breakpoint ( 1024px )', 'elegant-elements' ),
			'description' => esc_html__( 'Controls the number of slides to show on ipad landscape breakpoint at 1024px.', 'elegant-elements' ),
			'id'          => 'elegant_carousel_slides_ipad_landscape',
			'default'     => '3',
			'type'        => 'slider',
			'choices'     => array(
				'step' => '1',
				'min'  => '1',
				'max'  => '8',
			),
		),
		'elegant_carousel_slides_ipad'           => array(
			'label'       => esc_html__( 'Slides to Show on iPad Breakpoint ( 768px )', 'elegant-elements' ),
			'description' => esc_html__( 'Controls the number of slides to show on ipad portrait breakpoint at 768px.', 'elegant-elements' ),
			'id'          => 'elegant_carousel_slides_ipad',
			'default'     => '2',
			'type'        => 'slider',
			'choices'     => array(
				'step' => '1',
				'min'  => '1',
				'max'  => '6',
			),
		),
		'elegant_carousel_slides_mobile'         => array(
			'label'       => esc_html__( 'Slides to Show on Mobile Breakpoint ( 480px )', 'elegant-elements' ),
			'description' => esc_html__( 'Controls the number of slides to show on mobile breakpoint at 480px.', 'elegant-elements' ),
			'id'          => 'elegant_carousel_slides_mobile',
			'default'     => '1',
			'type'        => 'slider',
			'choices'     => array(
				'step' => '1',
				'min'  => '1',
				'max'  => '4',
			),
		),
	),
);
