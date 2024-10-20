<?php

do_shortcode( $content );

$elegant_carousel_settings                     = array();
$elegant_carousel_settings['vertical']         = false;
$elegant_carousel_settings['variableWidth']    = ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false;
$elegant_carousel_settings['slidesToShow']     = ( isset( $args['slides_to_show'] ) && ( isset( $args['fade'] ) && 'slide' === $args['fade'] ) ) ? (int) $args['slides_to_show'] : 1;
$elegant_carousel_settings['slidesToScroll']   = ( isset( $args['slides_to_scroll'] ) && ( isset( $args['fade'] ) && 'slide' === $args['fade'] ) ) ? (int) $args['slides_to_scroll'] : 1;
$elegant_carousel_settings['speed']            = ( isset( $args['speed'] ) ) ? $args['speed'] : 300;
$elegant_carousel_settings['fade']             = ( isset( $args['fade'] ) && 'fade' === $args['fade'] ) ? true : false;
$elegant_carousel_settings['infinite']         = ( isset( $args['infinite'] ) && 'yes' === $args['infinite'] ) ? true : false;
$elegant_carousel_settings['centerMode']       = ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false;
$elegant_carousel_settings['centerPadding']    = ( isset( $args['center_padding'] ) && 'yes' === $args['center_padding'] ) ? true : false;
$elegant_carousel_settings['accessibility']    = ( isset( $args['accessibility'] ) && 'yes' === $args['accessibility'] ) ? true : false;
$elegant_carousel_settings['adaptiveHeight']   = ( isset( $args['adaptive_height'] ) && 'yes' === $args['adaptive_height'] ) ? true : false;
$elegant_carousel_settings['arrows']           = ( isset( $args['arrows'] ) && 'yes' === $args['arrows'] ) ? true : false;
$elegant_carousel_settings['dots']             = ( isset( $args['dots'] ) && 'yes' === $args['dots'] ) ? true : false;
$elegant_carousel_settings['autoplay']         = ( isset( $args['autoplay'] ) && 'yes' === $args['autoplay'] ) ? true : false;
$elegant_carousel_settings['autoplaySpeed']    = ( isset( $args['autoplay_speed'] ) ) ? ( $args['autoplay_speed'] * 1000 ) : 3000;
$elegant_carousel_settings['draggable']        = ( isset( $args['draggable'] ) && 'yes' === $args['draggable'] ) ? true : false;
$elegant_carousel_settings['itemPadding']      = ( isset( $args['item_padding'] ) ) ? $args['item_padding'] : '0px 0px 0px 0px';
$elegant_carousel_settings['itemMargin']       = ( isset( $args['item_margin'] ) ) ? $args['item_margin'] : '0px 0px 0px 0px';
$elegant_carousel_settings['nextArrowIcon']    = ( isset( $args['next_arrow_icon'] ) && '' !== $args['next_arrow_icon'] ) ? $args['next_arrow_icon'] : '';
$elegant_carousel_settings['prevArrowIcon']    = ( isset( $args['prev_arrow_icon'] ) && '' !== $args['prev_arrow_icon'] ) ? $args['prev_arrow_icon'] : '';
$elegant_carousel_settings['arrowColor']       = ( isset( $args['arrow_color'] ) && '' !== $args['arrow_color'] ) ? $args['arrow_color'] : '';
$elegant_carousel_settings['arrowFontSize']    = ( isset( $args['arrow_font_size'] ) && '' !== $args['arrow_font_size'] ) ? $args['arrow_font_size'] . 'px' : '24px';
$elegant_carousel_settings['dotsIcon']         = ( isset( $args['dots_icon_class'] ) && '' !== $args['dots_icon_class'] ) ? $args['dots_icon_class'] : '';
$elegant_carousel_settings['dotsColor']        = ( isset( $args['dots_color'] ) && '' !== $args['dots_color'] ) ? $args['dots_color'] : '';
$elegant_carousel_settings['dotsColorActive']  = ( isset( $args['dots_color_active'] ) && '' !== $args['dots_color_active'] ) ? $args['dots_color_active'] : '';
$elegant_carousel_settings['dotsFontSize']     = ( isset( $args['dots_font_size'] ) && '' !== $args['dots_font_size'] ) ? $args['dots_font_size'] . 'px' : '18px';
$elegant_carousel_settings['pauseOnHover']     = ( isset( $args['pause_on_hover'] ) && 'yes' === $args['pause_on_hover'] ) ? true : false;
$elegant_carousel_settings['pauseOnDotsHover'] = ( isset( $args['pause_on_dots_hover'] ) && 'yes' === $args['pause_on_dots_hover'] ) ? true : false;

if ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] && ( isset( $args['infinite'] ) && 'no' === $args['infinite'] ) ) {
	$elegant_carousel_settings['initialSlide'] = 1;
}

if ( isset( $args['responsive'] ) && 'yes' === $args['responsive'] ) {
	$elegant_carousel_settings['responsive'] = array(
		array(
			'breakpoint' => 1024,
			'settings'   => array(
				'slidesToShow'   => (int) $args['ipad_portrait_slides_to_show'],
				'slidesToScroll' => (int) $args['ipad_portrait_slides_to_show'],
				'variableWidth'  => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false,
				'centerMode'     => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false,
				'infinite'       => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? false : $elegant_carousel_settings['infinite'],
			),
		),
		array(
			'breakpoint' => 768,
			'settings'   => array(
				'slidesToShow'   => (int) $args['ipad_portrait_slides_to_show'],
				'slidesToScroll' => (int) $args['ipad_portrait_slides_to_show'],
				'initialSlide'   => 0,
				'variableWidth'  => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false,
				'centerMode'     => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false,
				'infinite'       => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? false : $elegant_carousel_settings['infinite'],
			),
		),
		array(
			'breakpoint' => 480,
			'settings'   => array(
				'slidesToShow'   => $args['mobile_landscape_slides_to_show'],
				'slidesToScroll' => $args['mobile_landscape_slides_to_show'],
				'initialSlide'   => 0,
				'variableWidth'  => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false,
				'centerMode'     => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? true : false,
				'infinite'       => ( isset( $args['variable_width'] ) && 'yes' === $args['variable_width'] ) ? false : $elegant_carousel_settings['infinite'],
			),
		),
	);
}

if ( is_rtl() ) {
	$elegant_carousel_settings['rtl'] = true;
}

$elegant_slick_json = wp_json_encode( $elegant_carousel_settings );

$html = '<div ' . FusionBuilder::attributes( 'elegant-carousel' ) . '>';

if ( '' !== $args['border_size'] || '0' !== $args['border_size'] ) {
	$html .= '<style type="text/css">';
	$html .= '.elegant-carousel-container.elegant-carousel-' . $this->carousel_counter . ' .elegant-carousel-item {';
	$html .= 'border:' . $args['border_size'] . 'px ' . $args['border_style'] . ' ' . $args['border_color'] . ';';

	if ( 'round' === $args['border_radius'] ) {
		$html .= 'border-radius: 3px;';
	}

	$html .= '}';
	$html .= '</style>';
}

if ( 'yes' === $this->args['random_order'] ) {
	shuffle( $this->carousel_items );
}

$carousel_height = ( isset( $this->args['initial_height'] ) && '0' !== $this->args['initial_height'] ) ? 'height: ' . $this->args['initial_height'] . 'px; overflow:hidden;' : '';

$html .= '<script type="text/javascript" class="elegant-slick-settings">var elegantSettings_eac' . $this->carousel_counter . ' = ' . $elegant_slick_json . ';</script>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-carousel elegant-slick' ) . ' data-carousel-id="eac' . $this->carousel_counter . '" style="' . $carousel_height . '">';
$html .= implode( ' ', $this->carousel_items );
$html .= '</div>';
$html .= '</div>';
