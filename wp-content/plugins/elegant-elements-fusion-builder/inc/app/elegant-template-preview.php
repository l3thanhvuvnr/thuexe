<?php
/**
 * Handles The Template Preview.
 *
 * @package Elegant Elements for Fusion Builder
 * @since 1.1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

// Enqueue all scripts and styles for template preview.
wp_head();

// @codingStandardsIgnoreLine
show_admin_bar( false );

// Display site header to get the actual template preview on site.
get_header();

// Enqueue combined css for template preview.
wp_enqueue_style( 'infi-elegant-combined-css' );
?>
<style type="text/css">
	html { margin: 0 !important; }
</style>
<section id="content" class="full-width">
<?php
$shortcode_content = '';

if ( '' !== $_GET['template_id'] ) { // @codingStandardsIgnoreLine
	$templates    = apply_filters( 'elegant_elements_templates', array() );
	$template_key = $_GET['template_id']; // @codingStandardsIgnoreLine
	$template     = $templates[ $template_key ];

	if ( isset( $template['custom_css'] ) ) {
		?>
		<style type="text/css" id="elegant-custom-css">
		<?php echo $template['custom_css']; ?>
		</style>
		<?php
	}

	// Get template content.
	$template_content = stripslashes( $template['content'] );

	// Run the_content filter to calculate columns and rows.
	$template_content = apply_filters( 'the_content', $template_content );

	// Assign filtered content to shortcode content.
	$shortcode_content = do_shortcode( $template_content );
}

$content  = '<div id="elegant-elements-template-preview">';
$content .= $shortcode_content;
$content .= '</div>';

echo $content;
?>
</section>
<?php
get_footer();
