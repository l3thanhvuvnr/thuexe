<?php
// Get first toggle content by template.
$template_content_first = $this->render_library_element( array( 'id' => $this->args['content_first'] ) );

// Get last toggle content by template.
$template_content_last = $this->render_library_element( array( 'id' => $this->args['content_last'] ) );

$html = '<div ' . FusionBuilder::attributes( 'elegant-content-toggle' ) . '>';

$html .= $this->build_styles();

$html .= '<div ' . FusionBuilder::attributes( 'content-toggle-switch' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'content-toggle-switch-first' ) . '>';
$html .= '<span class="content-toggle-title">' . $this->args['title_first'] . '</span>';
$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'content-toggle-switch-button' ) . '>';
$html .= '<label class="content-toggle-switch-label"></label>';
$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'content-toggle-switch-last' ) . '>';
$html .= '<span class="content-toggle-title">' . $this->args['title_last'] . '</span>';
$html .= '</div>';
$html .= '</div>';

$html .= '<div ' . FusionBuilder::attributes( 'content-toggle-first' ) . '>';
$html .= do_shortcode( $template_content_first );
$html .= '</div>';

$html .= '<div ' . FusionBuilder::attributes( 'content-toggle-last' ) . '>';
$html .= do_shortcode( $template_content_last );
$html .= '</div>';
$html .= '</div>';
