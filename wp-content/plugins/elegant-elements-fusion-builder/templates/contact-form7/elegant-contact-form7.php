<?php
$html          = '<div ' . FusionBuilder::attributes( 'elegant-contact-form7' ) . '>';
$heading_style = $caption_style = '';

if ( isset( $args['heading_text'] ) && '' !== trim( $args['heading_text'] ) || isset( $args['caption_text'] ) && '' !== trim( $args['caption_text'] ) ) {

	if ( isset( $args['heading_padding'] ) && '' !== $args['heading_padding'] ) {
		$heading_padding = $fusion_library->sanitize->get_value_with_unit( $args['heading_padding'] );
		$heading_style  .= 'padding:' . esc_attr( $heading_padding ) . ';';
	}

	$heading_style .= ( isset( $args['heading_background_color'] ) && '' !== $args['heading_background_color'] ) ? 'background-color:' . $args['heading_background_color'] . ';' : '';

	if ( isset( $args['heading_background_image'] ) && '' !== $args['heading_background_image'] ) {
		$heading_style .= 'background-image: url(' . $args['heading_background_image'] . ');';
		$heading_style .= 'background-position: ' . $args['heading_background_position'] . ';';
		$heading_style .= 'background-repeat: ' . $args['heading_background_repeat'] . ';';
		$heading_style .= 'background-blend-mode: overlay;';
	}

	if ( isset( $args['form_border_size'] ) && '' !== $args['form_border_size'] ) {
		$heading_style .= 'border-width:' . FusionBuilder::validate_shortcode_attr_value( $args['form_border_size'], 'px' ) . ';';
		$heading_style .= 'border-color:' . FusionBuilder::validate_shortcode_attr_value( $args['form_border_color'], 'px' ) . ';';
		$heading_style .= 'border-style:' . FusionBuilder::validate_shortcode_attr_value( $args['form_border_style'], 'px' ) . ';';
		$heading_style .= 'border-bottom: none;';
	}

	if ( isset( $args['form_border_radius'] ) && '' !== $args['form_border_radius'] ) {
		$border_radius  = FusionBuilder::validate_shortcode_attr_value( $args['form_border_radius'], 'px' );
		$heading_style .= 'border-radius:' . $border_radius . ' ' . $border_radius . ' 0 0;';
	}

	$html .= '<div class="elegant-contact-form7-heading-wrapper form-align-' . $args['heading_align'] . '" style="' . $heading_style . '">';

	$heading_style = '';

	if ( $args['heading_text'] ) {
		if ( $args['heading_color'] ) {
			$heading_style = 'color:' . esc_attr( $args['heading_color'] ) . ';';
		}

		if ( $args['heading_font_size'] ) {
			$heading_style .= 'font-size:' . esc_attr( $args['heading_font_size'] ) . 'px;';
		}

		if ( isset( $args['typography_heading'] ) && '' !== $args['typography_heading'] ) {
			$typography         = $args['typography_heading'];
			$heading_typography = elegant_get_typography_css( $typography );

			$heading_style .= $heading_typography;
		}

		$html .= '<' . $args['heading_size'] . ' class="elegant-contact-form7-heading" style="' . $heading_style . '">' . $args['heading_text'] . '</' . $args['heading_size'] . '>';
	}

	if ( $args['caption_text'] ) {
		if ( $args['caption_color'] ) {
			$caption_style = 'color:' . $args['caption_color'] . ';';
		}

		if ( $args['caption_font_size'] ) {
			$caption_style .= 'font-size:' . esc_attr( $args['caption_font_size'] ) . 'px;';
		}

		if ( isset( $args['typography_caption'] ) && '' !== $args['typography_caption'] ) {
			$typography         = $args['typography_caption'];
			$caption_typography = elegant_get_typography_css( $typography );

			$caption_style .= $caption_typography;
		}

		$html .= '<div class="elegant-contact-form7-caption" style="' . $caption_style . '">' . $args['caption_text'] . '</div>';
	}
	$html .= '</div>';
}

$html .= '<div ' . FusionBuilder::attributes( 'elegant-contact-form7-form-wrapper' ) . '>';
$html .= do_shortcode( '[contact-form-7 id="' . $args['form'] . '"]' );
$html .= '</div>';
$html .= '</div>';
