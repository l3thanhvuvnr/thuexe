<?php
$html = '<div ' . FusionBuilder::attributes( 'elegant-faq-rich-snippets' ) . '>';

if ( isset( $this->args['title'] ) && '' !== $this->args['title'] ) {
	$html .= '<h2 class="faq-rich-snippets-title">' . $this->args['title'] . '</h2>';
}

if ( 'descriptive' === $this->args['output_type'] ) {
	$html .= do_shortcode( $content );
} else {
	$accordion  = '[fusion_accordion type="" boxed_mode="" border_size="1" border_color="" background_color="" hover_color="" divider_line="" title_font_size="" icon_size="" icon_color="" icon_boxed_mode="" icon_box_color="" icon_alignment="" toggle_hover_accent_color="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id=""]';
	$accordion .= do_shortcode( $content );
	$accordion .= '[/fusion_accordion]';
	$html      .= do_shortcode( $accordion );
}

$html .= '</div>';

// Generate rich snippets JSON for FAQ.
$html .= '<script type="application/ld+json">' . wp_json_encode( $this->seo_faq_data ) . '</script>';
