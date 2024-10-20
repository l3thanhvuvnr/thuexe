<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-image-hotspot' ) . '>';
$html .= '<img src="' . $this->args['hotspot_image'] . '" alt="' . basename( $this->args['hotspot_image'] ) . '" >';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-hotspot-items' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>';
$html .= '</div>';
