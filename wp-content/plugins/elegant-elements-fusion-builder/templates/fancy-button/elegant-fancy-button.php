<?php
global $fusion_library;
$style = '<style type="text/css">';

if ( isset( $args['color'] ) && '' !== $args['color'] ) {
	$hover_color = ( isset( $args['color_hover'] ) && '' !== $args['color_hover'] ) ? '--color:' . $args['color_hover'] . ' !important;' : '';
	$style      .= '.elegant-fancy-button-wrap.elegant-fancy-button-' . $this->button_counter . ' .elegant-fancy-button-link { color:' . $args['color'] . ' !important;' . $hover_color . ' border-color:' . $args['color'] . ';--border-color:' . $args['background'] . '}';
	$style      .= '.elegant-fancy-button-wrap.elegant-fancy-button-' . $this->button_counter . ' .elegant-fancy-button-link:hover { color:' . $args['color_hover'] . ' !important;}';
}

if ( isset( $args['background'] ) && '' !== $args['background'] ) {
	$style_class = '.elegant-fancy-button-wrap.elegant-fancy-button-' . $this->button_counter . ' .elegant-fancy-button-link.elegant-button-' . $args['style'];
	switch ( $args['style'] ) {
		case 'swipe':
			$style .= $style_class . ':before{ background:' . $args['background'] . ';}';
			break;
		case 'diagonal-swipe':
			$style .= $style_class . ':after { border-top-color:' . $args['background'] . ';}';
			break;
		case 'double-swipe':
			$style .= $style_class . ':before { border-left-color:' . $args['background'] . ';}';
			$style .= $style_class . ':after { border-bottom-color:' . $args['background'] . ';}';
			break;
		case 'zoning-in':
			$style .= $style_class . ':before { border-left-color:' . $args['background'] . ';}';
			$style .= $style_class . ':after { border-right-color:' . $args['background'] . ';}';
			$style .= $style_class . ' span:before { border-bottom-color:' . $args['background'] . ';}';
			$style .= $style_class . ' span:after { border-top-color:' . $args['background'] . ';}';
			break;
		case 'diagonal-close':
			$style .= $style_class . ':before { border-left-color:' . $args['background'] . ';}';
			$style .= $style_class . ':after { border-right-color:' . $args['background'] . ';}';
			break;
		case 'corners':
			$style .= $style_class . ':before, ' . $style_class . ':after, ' . $style_class . ' span:before, ' . $style_class . ' span:after { border-color:' . $args['background'] . ';}';
			break;
		case 'alternate':
			$style .= $style_class . ':before, ' . $style_class . ':after, ' . $style_class . ' span:before, ' . $style_class . ' span:after { background-color:' . $args['background'] . ';}';
			break;
		case 'slice':
			$style .= $style_class . ':before{ border-left-color:' . $args['background'] . ';}';
			$style .= $style_class . ':after{ border-right-color:' . $args['background'] . ';}';
			break;
		case 'position-aware':
			$style .= $style_class . ' span{ background-color:' . $args['background'] . ';}';
			break;
		case 'smoosh':
		case 'collision':
			$style .= $style_class . ':before, ' . $style_class . ':after{ background-color:' . $args['background'] . ';}';
			break;
		case 'vertical-overlap':
		case 'horizontal-overlap':
			$color        = Fusion_Color::new_color( $args['background'] );
			$color->alpha = 0.50;
			$color_css    = $color->getNew( 'brightness', 0.50 );
			$color_css    = $fusion_library->sanitize->color( $fusion_library->sanitize->color( $color_css->color ) );
			$style       .= $style_class . ':before, ' . $style_class . ':after{ background-color:' . $color_css . ';}';
			$style       .= $style_class . ' span:before, ' . $style_class . ' span:after{ background-color:' . $color_css . ';}';
			break;
	}
}

$style .= '</style>';

$icon = '';

if ( isset( $args['button_icon'] ) && '' !== $args['button_icon'] ) {
	$icon_class = ( function_exists( 'fusion_font_awesome_name_handler' ) ) ? fusion_font_awesome_name_handler( $args['button_icon'] ) : FusionBuilder::font_awesome_name_handler( $args['button_icon'] );
	$icon       = '<i class="' . $icon_class . '"></i>';
}

if ( isset( $args['button_title'] ) && '' !== $args['button_title'] ) {
	$html  = '<div ' . FusionBuilder::attributes( 'elegant-fancy-button-wrapper' ) . '>';
	$html .= $style;
	$html .= '<div ' . FusionBuilder::attributes( 'elegant-fancy-button' ) . '>';

	$button_title = $args['button_title'];

	if ( '' !== $icon && 'left' === $args['icon_position'] ) {
		$button_title = $icon . $button_title;
	} elseif ( '' !== $icon && 'right' === $args['icon_position'] ) {
		$button_title = $button_title . $icon;
	}

	if ( 'corners' === $args['style'] || 'alternate' === $args['style'] || 'zoning-in' === $args['style'] || 'vertical-overlap' === $args['style'] || 'horizontal-overlap' === $args['style'] ) {
		$button_title = '<span>' . $button_title . '</span>';
	}

	if ( 'position-aware' == $args['style'] ) {
		$button_title = '<span></span>' . $button_title;
	}

	$html .= '<a ' . FusionBuilder::attributes( 'elegant-fancy-button-link' ) . '>' . $button_title . '</a>';
	$html .= '</div>';
	$html .= '</div>';
}
