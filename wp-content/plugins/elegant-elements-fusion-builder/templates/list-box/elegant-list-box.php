<?php
$defaults = FusionBuilder::set_shortcode_defaults(
	self::get_element_defaults(),
	$args
);

$defaults['size'] = FusionBuilder::validate_shortcode_attr_value( $defaults['size'], 'px' );

$defaults['circle'] = ( 1 == $defaults['circle'] ) ? 'yes' : $defaults['circle'];

// Fallbacks for old size parameter and 'px' check.
if ( 'small' === $defaults['size'] ) {
	$defaults['size'] = '13px';
} elseif ( 'medium' === $defaults['size'] ) {
	$defaults['size'] = '18px';
} elseif ( 'large' === $defaults['size'] ) {
	$defaults['size'] = '40px';
} elseif ( ! strpos( $defaults['size'], 'px' ) ) {
	$defaults['size'] = $defaults['size'] . 'px';
}

// Dertmine line-height and margin from font size.
$font_size                           = str_replace( 'px', '', $defaults['size'] );
$defaults['circle_yes_font_size']    = $font_size * 0.88;
$defaults['line_height']             = $font_size * 1.7;
$defaults['icon_margin']             = $font_size * 0.7;
$defaults['icon_margin_position']    = ( is_rtl() ) ? 'left' : 'right';
$defaults['content_margin']          = $defaults['line_height'] + $defaults['icon_margin'];
$defaults['content_margin_position'] = ( is_rtl() ) ? 'right' : 'left';

extract( $defaults );

$this->parent_args = $defaults;

// Legacy list-box integration.
if ( strpos( $content, '<li>' ) && strpos( $content, '[iee_list_box_item' ) === false ) {
	$content = str_replace( '<ul>', '', $content );
	$content = str_replace( '</ul>', '', $content );
	$content = str_replace( '<li>', '[iee_list_box_item]', $content );
	$content = str_replace( '</li>', '[/iee_list_box_item]', $content );
}

$html  = '<div class="elegant-list-box-container">';
$html .= '<div ' . FusionBuilder::attributes( 'list-box-shortcode-title' ) . '>';
$html .= '<span ' . FusionBuilder::attributes( 'list-box-shortcode-title-span' ) . '>' . $args['title'] . '</span>';
$html .= '</div>';
$html .= '<div ' . FusionBuilder::attributes( 'list-box-shortcode-items' ) . '>';
$html .= '<ul ' . FusionBuilder::attributes( 'list-box-shortcode' ) . '>' . do_shortcode( $content ) . '</ul>';
$html .= '</div>';
$html .= '</div>';

$html = str_replace( '</li><br />', '</li>', $html );
