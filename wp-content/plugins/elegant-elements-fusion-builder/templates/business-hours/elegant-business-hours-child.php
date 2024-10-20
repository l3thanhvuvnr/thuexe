<?php
$child_html  = '<div ' . FusionBuilder::attributes( 'elegant-business-hours-item' ) . '>';
$child_html .= '<div ' . FusionBuilder::attributes( 'elegant-business-hours-item-day' ) . '>' . $this->child_args['title'] . '</div>';
$child_html .= '<div ' . FusionBuilder::attributes( 'elegant-business-hours-item-hours' ) . '>' . $this->child_args['hours_text'] . '</div>';
$child_html .= '</div>';

if ( 'none' !== $this->args['separator_type'] && ( $this->business_hours_child_counter !== $this->business_hours_child_count ) ) {
	$child_html .= '<div ' . FusionBuilder::attributes( 'elegant-business-hours-separator' ) . '></div>';
}
