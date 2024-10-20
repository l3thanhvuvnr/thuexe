<?php
$icon = '<i ' . FusionBuilder::attributes( 'elegant-video-list-icon' ) . '"></i>';

$child_html  = '<div ' . FusionBuilder::attributes( 'elegant-video-list-item' ) . '>';
$child_html .= '<h3 ' . FusionBuilder::attributes( 'elegant-video-list-item-title', $this->child_args ) . '>' . $icon . $this->child_args['title'] . '</h3>';
$child_html .= '</div>';
