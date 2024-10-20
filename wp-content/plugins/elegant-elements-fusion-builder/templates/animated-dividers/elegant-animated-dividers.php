<?php
$background_color_top = ( isset( $this->args['background_color_top'] ) && ( '' !== $this->args['background_color_top'] || 'rgba(255,255,255,0)' !== $this->args['background_color_top'] ) ) ? $this->args['background_color_top'] : 'transparent';

$html  = '<div ' . FusionBuilder::attributes( 'elegant-animated-divider' ) . '>';
$html .= '<div style="background: ' . $background_color_top . ';"></div>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-animated-divider-block' ) . '></div>';
$html .= '<div style="background:' . $this->args['background_color'] . '"></div>';
$html .= '</div>';
