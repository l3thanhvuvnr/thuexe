<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-image-compare' ) . '>';
$html .= '<figure ' . FusionBuilder::attributes( 'elegant-image-compare-container' ) . '>';
$html .= '<img src="' . $this->args['after_image'] . '" alt="' . basename( $this->args['after_image'] ) . '">';
$html .= '<span ' . FusionBuilder::attributes( 'elegant-image-compare-label-after' ) . '>' . $this->args['after_image_caption'] . '</span>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-image-compare-after-image' ) . '>';
$html .= '<img src="' . $this->args['before_image'] . '" alt="' . basename( $this->args['before_image'] ) . '">';
$html .= '<span ' . FusionBuilder::attributes( 'elegant-image-compare-label-before' ) . '>' . $this->args['before_image_caption'] . '</span>';
$html .= '</div>';
$html .= '<span ' . FusionBuilder::attributes( 'elegant-image-compare-handle' ) . '></span>';
$html .= '</figure>';
$html .= '</div>';
