<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-image-separator' ) . '>';
$html .= '<img src="' . $this->args['image'] . '" alt="' . basename( $this->args['image'] ) . '" >';
$html .= '</div>';
