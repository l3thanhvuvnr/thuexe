<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-image-overlay-button-wrapper' ) . '>';
$html .= '<img ' . FusionBuilder::attributes( 'elegant-image-overlay-button-image' ) . ' />';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-overlay-button-overlay' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-overlay-button' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="fusion-clearfix"></div>';
