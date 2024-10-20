<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-text-path-wrapper' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-text-path' ) . '>';
$html .= elegant_get_text_path_svg( $this->args );
$html .= '</div>';
$html .= '</div>';
