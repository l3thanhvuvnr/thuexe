<?php
$content = wpautop( do_shortcode( $content ) );

if ( 'descriptive' === $this->args['output_type'] ) {
	$child_html  = '<div ' . FusionBuilder::attributes( 'elegant-faq-rich-snippet-item' ) . '>';
	$child_html .= '<h3 class="faq-rich-snippet-item-question">' . $this->child_args['question'] . '</h3>';
	$child_html .= '<div class="faq-rich-snippet-item-answer">' . $content . '</div>';
	$child_html .= '</div>';
} else {
	$accordion   = '[fusion_toggle title="' . $this->child_args['question'] . '" open="no"]' . $content . '[/fusion_toggle]';
	$child_html .= $accordion;
}

$this->seo_faq_data['mainEntity'][] = array(
	'@type'          => 'Question',
	'name'           => $this->child_args['question'],
	'acceptedAnswer' => array(
		'@type' => 'Answer',
		'text'  => $content,
	),
);
