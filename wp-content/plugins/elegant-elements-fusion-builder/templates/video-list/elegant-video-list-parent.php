<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-video-list' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-video-list-video-container' ) . '></div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-video-list-items' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';

// Add styling.
$html .= '<style type="text/css" scoped>';
$html .= $this->styles;
$html .= '</style>';
