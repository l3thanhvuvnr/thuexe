<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-business-hours' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-business-hours-items' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
