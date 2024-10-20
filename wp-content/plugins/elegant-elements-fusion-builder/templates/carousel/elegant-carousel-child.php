<?php
$child_html  = '<div ' . FusionBuilder::attributes( 'elegant-carousel-item' ) . '>';
$child_html .= do_shortcode( $content );
$child_html .= '</div>';

$this->carousel_items[] = $child_html;
