<?php
$front_content = base64_decode( $this->args['front_content'] ); // @codingStandardsIgnoreLine

// Start cube box.
$html = '<div ' . FusionBuilder::attributes( 'elegant-cube-box' ) . '>';

// Front side content.
$html .= '<div ' . FusionBuilder::attributes( 'elegant-cube-box-front' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-cube-box-front-content' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-cube-box-content' ) . '>';
$html .= do_shortcode( $front_content );
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

// Back side content.
$html .= '<div ' . FusionBuilder::attributes( 'elegant-cube-box-back' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-cube-box-back-content' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-cube-box-content' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';

// Close cube box.
$html .= '</div>';
