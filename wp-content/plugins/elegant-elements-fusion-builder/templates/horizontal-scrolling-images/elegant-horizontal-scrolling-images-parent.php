<?php
$elegant_carousel_settings                   = array();
$elegant_carousel_settings['slidesToShow']   = ( isset( $this->args['images_to_show'] ) ) ? (int) $this->args['images_to_show'] : 1;
$elegant_carousel_settings['slidesToScroll'] = ( isset( $this->args['images_to_scroll'] ) ) ? (int) $this->args['images_to_scroll'] : 1;
$elegant_carousel_settings['itemPadding']    = ( isset( $this->args['image_padding'] ) ) ? $this->args['image_padding'] : '0px 0px 0px 0px';
$elegant_carousel_settings['speed']          = ( isset( $this->args['speed'] ) ) ? $this->args['speed'] : 300;
$elegant_carousel_settings['autoplay']       = ( isset( $this->args['autoplay'] ) && 'yes' === $this->args['autoplay'] ) ? true : false;
$elegant_carousel_settings['autoplaySpeed']  = ( isset( $this->args['autoplay_speed'] ) ) ? ( $this->args['autoplay_speed'] * 1000 ) : 3000;
$elegant_carousel_settings['itemMargin']     = '0px 0px 0px 0px';
$elegant_carousel_settings['adaptiveHeight'] = true;
$elegant_carousel_settings['vertical']       = false;
$elegant_carousel_settings['arrows']         = false;
$elegant_carousel_settings['dots']           = false;
$elegant_carousel_settings['draggable']      = true;
$elegant_carousel_settings['initialSlide']   = 1;
$elegant_carousel_settings['centerMode']     = true;
$elegant_carousel_settings['infinite']       = true;

if ( isset( $this->args['force_visible_images'] ) && 'yes' === $this->args['force_visible_images'] ) {
	$elegant_carousel_settings['centerPadding'] = '120px';
	$elegant_carousel_settings['variableWidth'] = false;
} else {
	$elegant_carousel_settings['centerPadding'] = false;
	$elegant_carousel_settings['variableWidth'] = true;
}

if ( isset( $this->args['responsive'] ) && 'yes' === $this->args['responsive'] ) {
	$elegant_carousel_settings['responsive'] = array(
		array(
			'breakpoint' => 1024,
			'settings'   => array(
				'slidesToShow'   => $this->args['ipad_landscape_slides_to_show'],
				'slidesToScroll' => $this->args['ipad_landscape_slides_to_show'],
				'infinite'       => false,
			),
		),
		array(
			'breakpoint' => 768,
			'settings'   => array(
				'slidesToShow'   => $this->args['ipad_portrait_slides_to_show'],
				'slidesToScroll' => $this->args['ipad_portrait_slides_to_show'],
				'initialSlide'   => 0,
				'infinite'       => false,
			),
		),
		array(
			'breakpoint' => 480,
			'settings'   => array(
				'slidesToShow'   => $this->args['mobile_landscape_slides_to_show'],
				'slidesToScroll' => $this->args['mobile_landscape_slides_to_show'],
				'initialSlide'   => 0,
				'variableWidth'  => true,
				'centerMode'     => true,
				'infinite'       => false,
			),
		),
	);
}

if ( is_rtl() ) {
	$elegant_carousel_settings['rtl'] = true;
}

$elegant_slick_json = wp_json_encode( $elegant_carousel_settings );

$html  = '<div ' . FusionBuilder::attributes( 'elegant-horizontal-scrolling-images' ) . '>';
$html .= '<script type="text/javascript" class="elegant-slick-settings">var elegantSettings_ehc' . $this->horizontal_scrolling_images_counter . ' = ' . $elegant_slick_json . ';</script>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-horizontal-scrolling-images-items' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '<style type="text/css">' . $this->add_style() . '</style>';
$html .= '</div>';
