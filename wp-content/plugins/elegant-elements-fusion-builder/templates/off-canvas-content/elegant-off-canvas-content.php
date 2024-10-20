<?php
// Output the canvas trigger.
$trigger_content = '';
if ( 'text' === $this->args['trigger_source'] ) {
	$trigger_content = $this->args['trigger_text'];
} elseif ( 'image' === $this->args['trigger_source'] ) {
	$trigger_content = '<img src="' . $this->args['trigger_image'] . '" alt="' . basename( $this->args['trigger_image'] ) . '"/>';
} elseif ( 'icon' === $this->args['trigger_source'] ) {
	$trigger_content = '<span ' . FusionBuilder::attributes( 'elegant-off-canvas-trigger-icon' ) . '></span>';
}

$html  = '<div ' . FusionBuilder::attributes( 'elegant-off-canvas-content' ) . '>';
$html .= '<div ' . FusionBuilder::attributes( 'elegant-off-canvas-content-trigger' ) . '>';

if ( '' !== $trigger_content ) {
	$html .= '<a href="#" class="elegant-off-canvas-trigger"><span data-target="' . $this->args['canvas_id'] . '">' . $trigger_content . '</span></a>';
}

$html .= '</div>';
$html .= '</div>';

// Output the canvas content.
$template_content = '';
if ( 'saved_template' === $this->args['content_source'] ) {
	$template_content = $this->render_library_element( array( 'id' => $this->args['content_template'] ) );
} elseif ( 'custom' === $this->args['content_source'] ) {
	$template_content = $content;
} elseif ( 'sidebar' === $this->args['content_source'] && '' !== $this->args['sidebar'] ) {
	ob_start();
	if ( function_exists( 'dynamic_sidebar' ) ) {
		dynamic_sidebar( $this->args['sidebar'] );
	}
	$template_content = ob_get_clean();
}

$chtml  = '<div ' . FusionBuilder::attributes( 'elegant-off-canvas-content-wrapper' ) . '>';
$chtml .= '<div class="elegant-off-content-header"><a href="#" class="elegant-off-content-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg></a></div>';
$chtml .= '<div ' . FusionBuilder::attributes( 'elegant-off-canvas-content-body' ) . '>';
$chtml .= $template_content;
$chtml .= '</div>';
$chtml .= '</div>';

$this->canvas_contents[] = $chtml;
