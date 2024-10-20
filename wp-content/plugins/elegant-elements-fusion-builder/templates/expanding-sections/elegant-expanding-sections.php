<?php
$html = '<div ' . FusionBuilder::attributes( 'elegant-expanding-sections' ) . '>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-expanding-section-heading-area' ) . '>';

$html .= '<div ' . FusionBuilder::attributes( 'elegant-expanding-section-heading-wrapper' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-expanding-section-title' ) . '>';
$html .= '<h2>' . $this->args['title'] . '</h2>';
$html .= '</div>'; // elegant-expanding-section-title.

$html .= '<div ' . FusionBuilder::attributes( 'elegant-expanding-section-description' ) . '>';
$html .= $this->args['description'];
$html .= '</div>'; // elegant-expanding-section-description.
$html .= '</div>'; // elegant-expanding-section-heading-wrapper.

$html .= '<div ' . FusionBuilder::attributes( 'elegant-expanding-section-icon' ) . '>';
$html .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 32px;" xml:space="preserve">
<g transform="translate(1 1)">
	<g>
		<path d="M489.667,233.667H276.333V20.333C276.333,8.551,266.782-1,255-1s-21.333,9.551-21.333,21.333v213.333H20.333    C8.551,233.667-1,243.218-1,255s9.551,21.333,21.333,21.333h213.333v213.333c0,11.782,9.551,21.333,21.333,21.333    s21.333-9.551,21.333-21.333V276.333h213.333c11.782,0,21.333-9.551,21.333-21.333S501.449,233.667,489.667,233.667z"></path>
	</g>
</g>
</svg>';
$html .= '</div>'; // elegant-expanding-section-icon.
$html .= '</div>'; // elegant-expanding-section-heading-area.

$html .= '<div ' . FusionBuilder::attributes( 'elegant-expanding-section-content-area' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-expanding-section-content' ) . '>';
$html .= do_shortcode( $content );
$html .= '</div>'; // elegant-expanding-section-content.
$html .= '</div>'; // elegant-expanding-section-content-area.

$html .= '</div>'; // elegant-expanding-sections.
