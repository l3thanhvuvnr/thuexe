<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-big-caps' ) . '>';
$html .= esc_attr( $this->args['content'] );
$html .= '</div>';
$html .= '<style type="text/css" scoped="true">';
$html .= $this->generate_style();
$html .= '</style>';
