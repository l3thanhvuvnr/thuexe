<?php
$html  = '<div ' . FusionBuilder::attributes( 'elegant-advanced-video' ) . '>';
$html .= '<img ' . FusionBuilder::attributes( 'elegant-advanced-video-image' ) . ' />';

// Add play icon.
$html .= '<div class="elegant-advanced-video-play-button">';

if ( 'icon' === $this->args['icon_type'] ) {
	$html .= '<i ' . FusionBuilder::attributes( 'elegant-advanced-video-icon' ) . '></i>';
} elseif ( '' !== $this->args['image_icon'] ) {
	$html .= '<img src=" ' . $this->args['image_icon'] . '" alt="' . basename( $this->args['image_icon'] ) . '"/>';
}
$html .= '</div>';
$html .= '<div class="elegant-advanced-video-overlay" style="background:' . $this->args['image_overlay'] . ';"></div>';
$html .= '</div>';

if ( 'youtube' === $this->args['video_provider'] && 'yes' === $this->args['youtube_subscribe'] ) {
	$channel_data = ( false === strpos( $this->args['youtube_channel'], 'UC' ) ) ? 'channel' : 'channelid';

	$html .= '<div class="elegant-advanced-video-subscription" style="max-width:' . FusionBuilder::validate_shortcode_attr_value( $this->args['width'], 'px' ) . ';">';
	$html .= '<div class="elegant-advanced-video-subscribe-bar" style="background:' . $this->args['subscribe_bar_background'] . '; color: ' . $this->args['subscribe_bar_text_color'] . ';">';
	$html .= wpautop( $this->args['subscribe_text'] );
	$html .= '<script src="https://apis.google.com/js/platform.js"></script>'; // @codingStandardsIgnoreLine
	$html .= '<div class="g-ytsubscribe" data-' . $channel_data . '="' . $this->args['youtube_channel'] . '" data-layout="default" data-count="default"></div>';
	$html .= '</div>';
	$html .= '</div>';
}

$html .= '<div class="fusion-clearfix"></div>';
