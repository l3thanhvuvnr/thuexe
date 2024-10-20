<?php
/**
 * Elegant elements helper functions.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'fusion_builder_add_element_settings' ) ) {
	/**
	 * Insert settings to existing element.
	 *
	 * @since 1.0
	 * @access public
	 * @param string       $element  Shortcode tag of element.
	 * @param string/array $settings Settings to be replaced / updated.
	 * @return void
	 */
	function fusion_builder_add_element_settings( $element, $settings ) {

		global $all_fusion_builder_elements;

		if ( isset( $all_fusion_builder_elements[ $element ]['params'] ) ) {
			$element_settings = $all_fusion_builder_elements[ $element ]['params'];

			if ( 1 < count( $settings ) ) {
				foreach ( $settings as $setting ) {
					$all_fusion_builder_elements[ $element ]['params'][] = $setting;
				}
			} else {
				$all_fusion_builder_elements[ $element ]['params'][] = $settings;
			}
		}
	}
}

/**
 * Read webfonts.json and return Google fonts array.
 *
 * @since 1.0
 * @access public
 * @return $webfonts Google fonts array.
 */
function elegant_elements_get_google_fonts() {
	global $fusion_settings;

	$get_custom_fonts = $fusion_settings->get( 'custom_fonts' );

	$webfonts     = array();
	$google_fonts = array();
	$custom_fonts = array();

	include ELEGANT_ELEMENTS_PLUGIN_DIR . 'inc/webfonts.php';
	$webfonts_json = elegant_elements_get_webfonts_json();

	// An array of all available variants.
	$all_variants = array(
		'100'       => esc_attr__( 'Ultra-Light 100', 'elegant-elements' ),
		'100light'  => esc_attr__( 'Ultra-Light 100', 'elegant-elements' ),
		'100italic' => esc_attr__( 'Ultra-Light 100 Italic', 'elegant-elements' ),
		'200'       => esc_attr__( 'Light 200', 'elegant-elements' ),
		'200italic' => esc_attr__( 'Light 200 Italic', 'elegant-elements' ),
		'300'       => esc_attr__( 'Book 300', 'elegant-elements' ),
		'300italic' => esc_attr__( 'Book 300 Italic', 'elegant-elements' ),
		'400'       => esc_attr__( 'Normal 400', 'elegant-elements' ),
		'regular'   => esc_attr__( 'Normal 400', 'elegant-elements' ),
		'italic'    => esc_attr__( 'Normal 400 Italic', 'elegant-elements' ),
		'500'       => esc_attr__( 'Medium 500', 'elegant-elements' ),
		'500italic' => esc_attr__( 'Medium 500 Italic', 'elegant-elements' ),
		'600'       => esc_attr__( 'Semi-Bold 600', 'elegant-elements' ),
		'600bold'   => esc_attr__( 'Semi-Bold 600', 'elegant-elements' ),
		'600italic' => esc_attr__( 'Semi-Bold 600 Italic', 'elegant-elements' ),
		'700'       => esc_attr__( 'Bold 700', 'elegant-elements' ),
		'700italic' => esc_attr__( 'Bold 700 Italic', 'elegant-elements' ),
		'800'       => esc_attr__( 'Extra-Bold 800', 'elegant-elements' ),
		'800bold'   => esc_attr__( 'Extra-Bold 800', 'elegant-elements' ),
		'800italic' => esc_attr__( 'Extra-Bold 800 Italic', 'elegant-elements' ),
		'900'       => esc_attr__( 'Ultra-Bold 900', 'elegant-elements' ),
		'900bold'   => esc_attr__( 'Ultra-Bold 900', 'elegant-elements' ),
		'900italic' => esc_attr__( 'Ultra-Bold 900 Italic', 'elegant-elements' ),
	);

	// An array of all available variants for system fonts.
	$system_variants = array(
		'400',
		'italic',
		'600',
		'600italic',
		'700',
		'700italic',
		'800',
		'800italic',
		'900',
		'900italic',
	);

	$system_variants            = implode( ',', $system_variants );
	$webfonts['allVariants']    = $all_variants;
	$webfonts['systemVariants'] = $system_variants;

	$system_fonts = elegant_get_system_fonts();

	$fonts_object = $webfonts_json;

	foreach ( $fonts_object->items as $key => $font ) {
		$google_fonts[ $font->family ] = array(
			'family'   => $font->family,
			'variants' => implode( ',', $font->variants ),
		);
	}

	if ( isset( $get_custom_fonts['name'] ) && ! empty( $get_custom_fonts['name'] ) ) {
		foreach ( $get_custom_fonts['name'] as $custom_font ) {
			$custom_fonts[ $custom_font ] = array(
				'family'   => $custom_font,
				'variants' => '',
			);
		}

		$webfonts['Custom Fonts'] = $custom_fonts;
	}

	$webfonts['System Fonts'] = $system_fonts;
	$webfonts['Google Fonts'] = $google_fonts;

	return $webfonts;
}

/**
 * Returns the system fonts array.
 *
 * @since 1.2.1
 * @access public
 * @return array $system_fonts System fonts array.
 */
function elegant_get_system_fonts() {
	// @codingStandardsIgnoreStart
	$system_fonts = array(
		"Arial, Helvetica, sans-serif" => array(
			'family'   => "Arial, Helvetica, sans-serif",
		),
		"Arial Black, Gadget, sans-serif" => array(
			'family'   => "Arial Black, Gadget, sans-serif",
		),
		"Bookman Old Style, serif" => array(
			'family'   => "Bookman Old Style, serif",
		),
		"Comic Sans MS, cursive" => array(
			'family'   => "Comic Sans MS, cursive",
		),
		"Courier, monospace" => array(
			'family'   => "Courier, monospace",
		),
		"Garamond, serif" => array(
			'family'   => "Garamond, serif",
		),
		"Georgia, serif" => array(
			'family'   => "Georgia, serif",
		),
		"Impact, Charcoal, sans-serif" => array(
			'family'   => "Impact, Charcoal, sans-serif",
		),
		"Lucida Console, Monaco, monospace" => array(
			'family'   => "Lucida Console, Monaco, monospace",
		),
		"Lucida Sans Unicode, Lucida Grande, sans-serif" => array(
			'family'   => "Lucida Sans Unicode, Lucida Grande, sans-serif",
		),
		"MS Sans Serif, Geneva, sans-serif" => array(
			'family'   => "MS Sans Serif, Geneva, sans-serif",
		),
		"MS Serif, New York, sans-serif" => array(
			'family'   => "MS Serif, New York, sans-serif",
		),
		"Palatino Linotype, Book Antiqua, Palatino, serif" => array(
			'family'   => "Palatino Linotype, Book Antiqua, Palatino, serif",
		),
		"Tahoma, Geneva, sans-serif" => array(
			'family'   => "Tahoma, Geneva, sans-serif",
		),
		"Times New Roman, Times,serif" => array(
			'family'   => "Times New Roman, Times, serif",
		),
		"Trebuchet MS, Helvetica, sans-serif" => array(
			'family'   => "Trebuchet MS, Helvetica, sans-serif",
		),
		"Verdana, Geneva, sans-serif" => array(
			'family'   => "Verdana, Geneva, sans-serif",
		),
	);
	// @codingStandardsIgnoreEnd

	return $system_fonts;
}
/**
 * Check for typography used in shortcodes and return all font families with their variations required.
 *
 * @since 1.0
 * @access public
 * @param string $content Content to parse shortcode atts and retrive typography.
 * @return string Google Fonts to be enqueued.
 */
function elegant_elements_parse_typography( $content ) {
	$fonts      = '';
	$typography = array();
	$shortcode  = array(
		'iee_testimonials',
		'iee_rotating_text',
		'iee_typewriter_text',
		'iee_promo_box',
		'iee_fancy_banner',
		'iee_contact_form7',
		'iee_special_heading',
		'iee_dual_button',
		'iee_modal_dialog',
		'iee_fancy_button',
		'iee_notification_box',
		'iee_cards',
		'iee_profile_panel',
		'iee_gradient_heading',
		'iee_expanding_sections',
		'iee_list_box',
		'iee_skew_heading',
		'iee_image_mask_heading',
		'iee_icon_block',
		'iee_dual_style_heading',
		'iee_content_toggle',
		'iee_video_list',
		'iee_text_path',
	);

	// @codingStandardsIgnoreStart
	$system_fonts = array(
		"Arial, Helvetica, sans-serif",
		"Arial Black, Gadget, sans-serif",
		"Bookman Old Style, serif",
		"Comic Sans MS, cursive",
		"Courier, monospace",
		"Garamond, serif",
		"Georgia, serif",
		"Impact, Charcoal, sans-serif",
		"Lucida Console, Monaco, monospace",
		"Lucida Sans Unicode, Lucida Grande, sans-serif",
		"MS Sans Serif, Geneva, sans-serif",
		"MS Serif, New York, sans-serif",
		"Palatino Linotype, Book Antiqua, Palatino, serif",
		"Tahoma,Geneva, sans-serif",
		"Times New Roman, Times,serif",
		"Trebuchet MS, Helvetica, sans-serif",
		"Verdana, Geneva, sans-serif",
	);
	// @codingStandardsIgnoreEnd

	// Process all shortcodes.
	preg_match_all( '/' . get_shortcode_regex( $shortcode ) . '/s', $content, $matches );

	$atts = ( isset( $matches[3] ) && ! empty( $matches[3] ) ) ? $matches[3] : array();

	if ( ! empty( $atts ) ) {
		foreach ( $atts as $key => $attributes ) {
			$args = shortcode_parse_atts( $attributes );
			foreach ( $args as $key => $value ) {
				$font_family = explode( ':', $value );
				if ( strpos( $key, 'typography_' ) !== false && ( ! in_array( $font_family[0], $system_fonts ) ) && ( ! in_array( $value, $typography ) ) ) {
					$typography[] = $value;
				}
			}
		}
	}

	$fonts = implode( '|', $typography );

	return $fonts;
}

/**
 * Generate css based on typography provided.
 *
 * @since 1.0
 * @access public
 * @param string  $typography Typography from shortcode atts.
 * @param boolean $important  Whether to add !important attribute or not.
 * @return string $typography_css Generated css.
 */
function elegant_get_typography_css( $typography, $important = false ) {
	$typography  = explode( ':', $typography );
	$important   = ( $important ) ? ' !important' : '';
	$font_style  = '';
	$font_weight = '';

	if ( isset( $typography[1] ) ) {
		$font_weight = ( 'regular' === trim( $typography[1] ) ) ? 400 : $typography[1];
		$font_weight = ( ! is_numeric( $font_weight ) ) ? substr( $typography[1], 0, 3 ) : $font_weight;

		$font_style = ( ! is_numeric( $typography[1] ) ) ? substr( $typography[1], 3 ) : '';
		$font_style = ( 'regular' === $typography[1] ) ? '' : $font_style;
	}

	$typography_css  = 'font-family: \'' . $typography[0] . '\' ' . $important . ';';
	$typography_css .= 'font-weight: ' . $font_weight . $important . ';';

	if ( '' !== $font_style ) {
		$typography_css .= 'font-style: ' . $font_style . $important . ';';
	}

	return $typography_css;
}

/**
 * Get contact form 7 forms list.
 *
 * @since 1.0
 * @access public
 * @return $forms Contact form 7 forms list.
 */
function elegant_get_contact_form_list() {
	$args = array(
		'post_type'      => 'wpcf7_contact_form',
		'posts_per_page' => -1,
	);

	$cf7_forms = array();

	// @codingStandardsIgnoreLine
	if ( $data = get_posts( $args ) ) {
		foreach ( $data as $key ) {
			$cf7_forms[ $key->ID ] = $key->post_title;
		}
	} else {
		$cf7_forms['0'] = esc_html__( 'No Contact Form found', 'elegant-elements' );
	}

	return $cf7_forms;
}

/**
 * Return entry animation array.
 *
 * @since 1.0
 * @access public
 * @return $animations Array containig animation groups.
 */
function elegant_get_entry_animations() {
	$animations = array();

	$animations['Attention Seekers'] = array(
		'bounce'     => 'bounce',
		'flash'      => 'flash',
		'pulse'      => 'pulse',
		'rubberBand' => 'rubberBand',
		'shake'      => 'shake',
		'swing'      => 'swing',
		'tada'       => 'tada',
		'wobble'     => 'wobble',
		'jello'      => 'jello',
	);

	$animations['Bouncing Entrances'] = array(
		'bounceIn'      => 'bounceIn',
		'bounceInDown'  => 'bounceInDown',
		'bounceInLeft'  => 'bounceInLeft',
		'bounceInRight' => 'bounceInRight',
		'bounceInUp'    => 'bounceInUp',
	);

	$animations['Fading Entrances'] = array(
		'fadeIn'         => 'fadeIn',
		'fadeInDown'     => 'fadeInDown',
		'fadeInDownBig'  => 'fadeInDownBig',
		'fadeInLeft'     => 'fadeInLeft',
		'fadeInLeftBig'  => 'fadeInLeftBig',
		'fadeInRight'    => 'fadeInRight',
		'fadeInRightBig' => 'fadeInRightBig',
		'fadeInUp'       => 'fadeInUp',
		'fadeInUpBig'    => 'fadeInUpBig',
	);

	$animations['Flippers'] = array(
		'flipInX' => 'flipInX',
		'flipInY' => 'flipInY',
	);

	$animations['Lightspeed'] = array(
		'lightSpeedIn' => 'lightSpeedIn',
	);

	$animations['Rotating Entrances'] = array(
		'rotateIn'          => 'rotateIn',
		'rotateInDownLeft'  => 'rotateInDownLeft',
		'rotateInDownRight' => 'rotateInDownRight',
		'rotateInUpLeft'    => 'rotateInUpLeft',
		'rotateInUpRight'   => 'rotateInUpRight',
	);

	$animations['Sliding Entrances'] = array(
		'slideInUp'    => 'slideInUp',
		'slideInDown'  => 'slideInDown',
		'slideInLeft'  => 'slideInLeft',
		'slideInRight' => 'slideInRight',
	);

	$animations['Zoom Entrances'] = array(
		'zoomIn'      => 'zoomIn',
		'zoomInDown'  => 'zoomInDown',
		'zoomInLeft'  => 'zoomInLeft',
		'zoomInRight' => 'zoomInRight',
		'zoomInUp'    => 'zoomInUp',
	);

	$animations['Specials'] = array(
		'rollIn' => 'rollIn',
	);

	return $animations;
}

/**
 * Return exit animation array.
 *
 * @since 1.0
 * @access public
 * @return $animations Array containig animation groups.
 */
function elegant_get_exit_animations() {
	$animations = array();

	$animations['Bouncing Exits'] = array(
		'bounceOut'      => 'bounceOut',
		'bounceOutDown'  => 'bounceOutDown',
		'bounceOutLeft'  => 'bounceOutLeft',
		'bounceOutRight' => 'bounceOutRight',
		'bounceOutUp'    => 'bounceOutUp',
	);

	$animations['Fading Exits'] = array(
		'fadeOut'         => 'fadeOut',
		'fadeOutDown'     => 'fadeOutDown',
		'fadeOutDownBig'  => 'fadeOutDownBig',
		'fadeOutLeft'     => 'fadeOutLeft',
		'fadeOutLeftBig'  => 'fadeOutLeftBig',
		'fadeOutRight'    => 'fadeOutRight',
		'fadeOutRightBig' => 'fadeOutRightBig',
		'fadeOutUp'       => 'fadeOutUp',
		'fadeOutUpBig'    => 'fadeOutUpBig',
	);

	$animations['Flippers'] = array(
		'flipOutX' => 'flipOutX',
		'flipOutY' => 'flipOutY',
	);

	$animations['Lightspeed'] = array(
		'lightSpeedOut' => 'lightSpeedOut',
	);

	$animations['Rotating Exits'] = array(
		'rotateOut'          => 'rotateOut',
		'rotateOutDownLeft'  => 'rotateOutDownLeft',
		'rotateOutDownRight' => 'rotateOutDownRight',
		'rotateOutUpLeft'    => 'rotateOutUpLeft',
		'rotateOutUpRight'   => 'rotateOutUpRight',
	);

	$animations['Sliding Exits'] = array(
		'slideOutUp'    => 'slideOutUp',
		'slideOutDown'  => 'slideOutDown',
		'slideOutLeft'  => 'slideOutLeft',
		'slideOutRight' => 'slideOutRight',
	);

	$animations['Zoom Exits'] = array(
		'zoomOut'      => 'zoomOut',
		'zoomOutDown'  => 'zoomOutDown',
		'zoomOutLeft'  => 'zoomOutLeft',
		'zoomOutRight' => 'zoomOutRight',
		'zoomOutUp'    => 'zoomOutUp',
	);

	$animations['Specials'] = array(
		'rollOut' => 'rollOut',
	);

	return $animations;
}

/**
 * Insert elegant templates.
 *
 * @since 1.1.0
 * @param array $template Pre-built template.
 * @return array $templates
 */
function elegant_elements_insert_template( $template ) {
	return apply_filters( 'elegant_elements_templates', $template );
}

/**
 * Display template preview.
 *
 * @since 1.1.0
 * @param string $single_template Template file name or uri.
 * @return array
 */
function elegant_elements_preview_template( $single_template ) {
	global $post;

	if ( ( isset( $_GET['template-preview'] ) && isset( $_GET['template_id'] ) ) && is_user_logged_in() ) {
		$single_template = ELEGANT_ELEMENTS_PLUGIN_DIR . '/inc/app/elegant-template-preview.php';
	}

	return $single_template;
}

/**
 * Retrieve and return template data with image replacement.
 *
 * @since 1.1.0
 * @return void
 */
function elegant_get_template_data() {
	// Check security.
	check_ajax_referer( 'elegant_templates_security', 'elegant_templates_security' );

	$template_content = ( isset( $_POST['template_content'] ) ) ? $_POST['template_content'] : '';
	$post_id          = ( isset( $_POST['post_id'] ) ) ? $_POST['post_id'] : '';
	$site_url         = get_site_url();
	$site_url         = str_replace( array( 'http://', 'https://' ), '', $site_url );

	if ( '' === $template_content ) {
		return;
	}

	// Get template content.
	$template_content = stripslashes( $template_content );

	// Retrieve all images from template content.
	$images = elegant_get_all_image_urls( $template_content );

	// Get imported images array.
	$imported_images = get_option( 'elegant_imported_images', array() );

	// Loop through each image url and import if it is not already imported.
	if ( ! empty( $images ) ) {
		foreach ( $images['image'] as $image ) {
			if ( strpos( $image, $site_url ) == false ) {
				if ( isset( $imported_images[ $image ] ) ) {
					// Image is already imported, so just set the url from stored option.
					$imported_image = $imported_images[ $image ];
				} else {
					// Image isn't imported previously, so import it and use the new url and store it in imported option.
					$imported_image            = media_sideload_image( $image, $post_id, '', 'src' );
					$imported_images[ $image ] = $imported_image;
					update_option( 'elegant_imported_images', $imported_images );
				}

				$template_content = str_replace( $image, $imported_image, $template_content );
			}
		}
	}

	$updated_template_data = $template_content;

	echo $updated_template_data; // phpcs:ignore.
	die();
}

add_action( 'wp_ajax_elegant_get_template_data', 'elegant_get_template_data' );

/**
 * Find image urls in content and retrieve urls by array.
 *
 * @since 1.1.0
 * @param string $content Template content.
 * @return array|null
 */
function elegant_get_all_image_urls( $content ) {
	$pattern = '/((http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{1,2}.)(\/\S*.(jpe?g|jpe|gif|png)\b)/i'; // find img tags and retrieve src.
	preg_match_all( $pattern, $content, $urls );

	if ( empty( $urls ) ) {
		return null;
	}

	$image_array = array();

	foreach ( $urls[0] as $index => $url ) {
		$image_array['image'][ $index ] = $urls[0][ $index ];
		$image_array['host'][ $index ]  = $urls[1][ $index ];
	}

	return $image_array;
}

/**
 * Add material design colors to Fusion Builder core button element.
 *
 * @since 1.1.0
 * @return void
 */
function elegant_elements_add_button_styles() {
	global $pagenow;
	if ( ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) || ( function_exists( 'fusion_is_builder_frame' ) && fusion_is_builder_frame() ) ) {
		if ( function_exists( 'elegant_fusion_builder_update_element' ) ) {
			// @codingStandardsIgnoreStart
			$button_styles = array(
				array( 'material-red'         => esc_attr__( 'Material - Red', 'elegant-elements' ) ),
				array( 'material-pink'        => esc_attr__( 'Material - Pink', 'elegant-elements' ) ),
				array( 'material-purple'      => esc_attr__( 'Material - Purple', 'elegant-elements' ) ),
				array( 'material-deep-purple' => esc_attr__( 'Material - Deep Purple', 'elegant-elements' ) ),
				array( 'material-indigo'      => esc_attr__( 'Material - Indigo', 'elegant-elements' ) ),
				array( 'material-blue'        => esc_attr__( 'Material - Blue', 'elegant-elements' ) ),
				array( 'material-light-blue'  => esc_attr__( 'Material - Light Blue', 'elegant-elements' ) ),
				array( 'material-cyan'        => esc_attr__( 'Material - Cyan', 'elegant-elements' ) ),
				array( 'material-teal'        => esc_attr__( 'Material - Teal', 'elegant-elements' ) ),
				array( 'material-green'       => esc_attr__( 'Material - Green', 'elegant-elements' ) ),
				array( 'material-light-green' => esc_attr__( 'Material - Light Green', 'elegant-elements' ) ),
				array( 'material-lime'        => esc_attr__( 'Material - Lime', 'elegant-elements' ) ),
				array( 'material-yellow'      => esc_attr__( 'Material - Yellow', 'elegant-elements' ) ),
				array( 'material-amber'       => esc_attr__( 'Material - Amber', 'elegant-elements' ) ),
				array( 'material-orange'      => esc_attr__( 'Material - Orange', 'elegant-elements' ) ),
				array( 'material-deep-orange' => esc_attr__( 'Material - Deep Orange', 'elegant-elements' ) ),
				array( 'material-brown'       => esc_attr__( 'Material - Brown', 'elegant-elements' ) ),
				array( 'material-grey'        => esc_attr__( 'Material - Grey', 'elegant-elements' ) ),
				array( 'material-blue-grey'   => esc_attr__( 'Material - Blue Grey', 'elegant-elements' ) ),
				array( 'material-black'       => esc_attr__( 'Material - Black', 'elegant-elements' ) ),
			);
			// @codingStandardsIgnoreEnd

			foreach ( $button_styles as $style ) {
				elegant_fusion_builder_update_element( 'fusion_button', 'color', $style );
			}
		}
	}
}
add_action( 'fusion_builder_before_init', 'elegant_elements_add_button_styles', 11 );

/**
 * Change button defaults if material style is selected.
 *
 * @since 2.2.0
 * @param array  $defaults Default values array for the element.
 * @param string $element  Element name.
 * @param array  $args     Shortcode arguments.
 * @return array Updated default options.
 */
function elegant_material_button_styles( $defaults, $element, $args ) {
	if ( 'fusion_button' === $element ) {
		$color = $args['color'];

		if ( false !== strpos( $color, 'material-' ) ) {
			$defaults['color'] = 'default';
			$args['color']     = 'default';
			$defaults['class'] = 'button-' . $color;
		}
	}
	return $defaults;
}
add_filter( 'fusion_builder_default_args', 'elegant_material_button_styles', 10, 3 );

/**
 * Retrieve and return the default typography for elements.
 *
 * @since 1.2.0
 * @return array Default typography for title and description.
 */
function elegant_get_default_typography() {
	global $fusion_settings;

	if ( $fusion_settings ) {
		// Title typography defaults.
		$title_typography  = $fusion_settings->get( 'elegant_default_title_typography' );
		$title_font_weight = ( isset( $title_typography['font-weight'] ) ) ? $title_typography['font-weight'] : '400';
		$title_font_family = ( isset( $title_typography['font-family'] ) ) ? $title_typography['font-family'] : 'Open Sans';
		$title_variant     = ( '400' == $title_font_weight ) ? 'regular' : $title_font_weight;
		$title_typography  = $title_font_family . ':' . $title_variant;

		// Description typography defaults.
		$description_typography  = $fusion_settings->get( 'elegant_default_description_typography' );
		$description_font_weight = ( isset( $description_typography['font-weight'] ) ) ? $description_typography['font-weight'] : '300';
		$description_font_family = ( isset( $description_typography['font-family'] ) ) ? $description_typography['font-family'] : 'Open Sans';
		$description_variant     = ( '400' == $description_font_weight ) ? 'regular' : $description_font_weight;
		$description_typography  = $description_font_family . ':' . $description_variant;

		$default_typography = array(
			'title'       => $title_typography,
			'description' => $description_typography,
		);
	} else {
		$default_typography = array(
			'title'       => '',
			'description' => '',
		);
	}

	return $default_typography;
}

if ( ! function_exists( 'elegant_fusion_builder_update_element' ) ) {
	/**
	 * Update single element setting value.
	 *
	 * @since 2.0
	 * @param string       $element    Shortcode tag of element.
	 * @param string       $param_name Param name to be updated.
	 * @param string/array $values     Settings to be replaced / updated.
	 */
	function elegant_fusion_builder_update_element( $element, $param_name, $values ) {

		global $all_fusion_builder_elements;

		$element_settings = $all_fusion_builder_elements[ $element ]['params'];

		$settings = $element_settings[ $param_name ]['value'];

		if ( is_array( $values ) ) {
			$settings = array_merge( $settings, $values );
		} else {
			$settings = $values;
		}

		$all_fusion_builder_elements[ $element ]['params'][ $param_name ]['value'] = $settings;

		// Add on change function to handle gradient preview in frontend editor for columns.
		$all_fusion_builder_elements['fusion_builder_column']['on_change'] = 'updateColumnBackground';

		// Add on change function to handle gradient preview in frontend editor for containers.
		$all_fusion_builder_elements['fusion_builder_container']['on_change'] = 'updateContainerBackground';
	}
}

/**
 * Builds the gradient color.
 *
 * @access public
 * @since 2.1.0
 * @param string $gradient_color_1   Gradient color 1.
 * @param string $gradient_color_2   Gradient color 2.
 * @param string $gradient_direction Gradient direction.
 * @param bool   $force_gradient     Option to use !important to force the gradient color.
 * @return string Generated gradient color.
 */
function elegant_build_gradient_color( $gradient_color_1, $gradient_color_2, $gradient_direction = '0deg', $force_gradient = false ) {

	if ( '' === $gradient_color_1 || '' === $gradient_color_2 ) {
		return 'background-color:' . ( ( '' !== $gradient_color_1 ) ? $gradient_color_1 : $gradient_color_2 );
	}

	$gradient_top_color    = $gradient_color_1;
	$gradient_bottom_color = $gradient_color_2;

	if ( 'top' == $gradient_direction ) {
		// Safari 4-5, Chrome 1-9 support.
		$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
	} else {
		// Safari 4-5, Chrome 1-9 support.
		$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_top_color . '), to(' . $gradient_bottom_color . '))' . $force_gradient . ';';
	}

	// Safari 5.1, Chrome 10+ support.
	$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	// Firefox 3.6+ support.
	$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	// IE 10+ support.
	$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	// Opera 11.10+ support.
	$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_top_color . ', ' . $gradient_bottom_color . ')' . $force_gradient . ';';

	return $gradient;
}

/**
 * Builds the gradient color with all the parameters.
 *
 * @access public
 * @since 2.5
 * @param string $angle          Gradient angle.
 * @param string $color_1        Gradient color 1.
 * @param string $color_2        Gradient color 2.
 * @param string $offset         Gradient offset.
 * @param string $color_1_offset First gradient color offset.
 * @param string $color_2_offset Second gradient color offset.
 * @return string Generated gradient color.
 */
function elegant_gradient_color( $angle = '45', $color_1 = '', $color_2 = '', $offset = '50', $color_1_offset = '0', $color_2_offset = '100' ) {

	if ( '' === $color_1 || '' === $color_2 ) {
		return 'background-color:' . ( ( '' !== $color_1 ) ? $color_1 : $color_2 );
	}

	// General.
	$gradient = 'background: linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// Safari 5.1, Chrome 10+ support.
	$gradient .= 'background: -webkit-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// Firefox 3.6+ support.
	$gradient .= 'background: -moz-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// IE 10+ support.
	$gradient .= 'background: -ms-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	// Opera 11.10+ support.
	$gradient .= 'background: -o-linear-gradient(' . $angle . 'deg, ' . $color_1 . ' ' . $color_1_offset . '%, ' . $offset . '%, ' . $color_2 . ' ' . $color_2_offset . '%);';

	return $gradient;
}

/**
 * Retrieve and return the default typography for elements.
 *
 * @since 2.1.0
 * @return array Default typography for title and description.
 */
function elegant_get_library_collection() {
	// Bail, if page is saving.
	if ( isset( $_POST['action'] ) ) { // @codingStandardsIgnoreLine
		return;
	}

	// Bail, if the builder is not active or is not admin, should not make database call.
	$is_fb_live = ( isset( $_GET['fb-edit'] ) ) ? true : false; // @codingStandardsIgnoreLine
	if ( ! $is_fb_live && ! is_admin() ) {
		return;
	}

	$library_collection = array();

	$args = array(
		'post_type'      => array( 'fusion_template', 'fusion_element' ),
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);

	$library_query = get_posts( $args );

	// Check if there are items available.
	if ( $library_query ) {
		// The loop.
		foreach ( $library_query as $library_item ) :
			setup_postdata( $library_item );
			$element_post_id = $library_item->ID;

			$type          = $library_item->post_title;
			$display_terms = '';
			$global        = '';
			$term_name     = '';

			if ( 'yes' === get_post_meta( $element_post_id, '_fusion_is_global', true ) ) {
				$global = esc_html__( ' ( Global )', 'fusion-builder' );
			}

			if ( 'fusion_template' === $type ) {
				$term_name = esc_html__( 'Template', 'fusion-builder' );

				$library_collection[ $term_name ][ $element_post_id ] = $library_item->post_title . $global;
			} else {
				$template_content = $library_item->post_content;
				if ( 1 === strpos( $template_content, 'fusion_builder_container' ) ) {
					$term_name = esc_html__( 'Container', 'fusion-builder' );
				} elseif ( 1 === strpos( $template_content, 'fusion_builder_column' ) ) {
					$term_name = esc_html__( 'Column', 'fusion-builder' );
				} else {
					$term_name = esc_html__( 'Element', 'fusion-builder' );
				}

				$library_collection[ $term_name ][ $element_post_id ] = $library_item->post_title . $global;
			}

		endforeach;

	}

	// Restore original Post Data.
	wp_reset_postdata();

	return $library_collection;
}

/**
 * Returns the embed url as per the provider.
 *
 * @access public
 * @since 2.3
 * @param string $provider Video provider.
 * @param string $video_id Video ID.
 * @param bool   $autoplay Autoplay video.
 * @return string
 */
function elegant_get_embed_url_by_provider( $provider = 'youtube', $video_id = 'il2ZAZX9KpQ', $autoplay = false ) {
	$embed_url      = '';
	$autoplay_video = ( $autoplay ) ? 'autoplay=1' : '';

	switch ( $provider ) {
		case 'youtube':
			$embed_url = 'https://www.youtube.com/embed/' . $video_id . '?rel=0&start&end&controls=1&mute=0&modestbranding=0&' . $autoplay_video;
			break;
		case 'vimeo':
			$embed_url = 'https://player.vimeo.com/video/' . $video_id . '?' . $autoplay_video;
			break;
		case 'wistia':
			$embed_url = 'https://fast.wistia.net/embed/iframe/' . $video_id . '?dnt=1&videoFoam=true&' . $autoplay_video;
			break;
		case 'hosted':
			$embed_url = $video_id . '?' . $autoplay_video;
			break;
	}

	return $embed_url;
}

/**
 * Generate border styles.
 *
 * @since 2.3
 * @access public
 * @param array $atts Shortcode attributes.
 * @return string Generated border style.
 */
function elegant_get_border_style( $atts ) {
	$style = '';

	$border_color    = ( isset( $atts['border_color'] ) ) ? $atts['border_color'] : '';
	$border_size     = ( isset( $atts['border_size'] ) ) ? FusionBuilder::validate_shortcode_attr_value( $atts['border_size'], 'px' ) : '';
	$border_style    = ( isset( $atts['border_style'] ) ) ? $atts['border_style'] : '';
	$border_position = ( isset( $atts['border_position'] ) ) ? $atts['border_position'] : '';

	$border_radius_top_left     = isset( $atts['border_radius_top_left'] ) ? fusion_library()->sanitize->get_value_with_unit( $atts['border_radius_top_left'] ) : '0px';
	$border_radius_top_right    = isset( $atts['border_radius_top_right'] ) ? fusion_library()->sanitize->get_value_with_unit( $atts['border_radius_top_right'] ) : '0px';
	$border_radius_bottom_right = isset( $atts['border_radius_bottom_right'] ) ? fusion_library()->sanitize->get_value_with_unit( $atts['border_radius_bottom_right'] ) : '0px';
	$border_radius_bottom_left  = isset( $atts['border_radius_bottom_left'] ) ? fusion_library()->sanitize->get_value_with_unit( $atts['border_radius_bottom_left'] ) : '0px';
	$border_radius              = $border_radius_top_left . ' ' . $border_radius_top_right . ' ' . $border_radius_bottom_right . ' ' . $border_radius_bottom_left;
	$border_radius              = ( '0px 0px 0px 0px' === $border_radius ) ? '0px' : $border_radius;

	// Border.
	if ( $border_color && $border_size && $border_style ) {
		$border_direction = ( 'all' !== $border_position ) ? '-' . $border_position : '';
		$style           .= ( 'all' !== $border_position ) ? 'border:none;' : '';
		$style           .= 'border' . $border_direction . ':' . $border_size . ' ' . $border_style . ' ' . $border_color . ';';
	}

	// Border radius.
	if ( $border_radius ) {
		$style .= 'border-radius:' . esc_attr( $border_radius ) . ';';
	}

	return $style;
}

// Add basic oembed support for wistia.
wp_oembed_add_provider(
	'/https?\:\/\/(.+)?(wistia\.com|wi\.st|wistia\.net)\/.*/',
	'https://fast.wistia.com/oembed',
	true
);

/**
 * Returns a big old hunk of JSON from a non-private IG account page.
 * based on https://gist.github.com/cosmocatalano/4544576.
 *
 * @access public
 * @since 2.5
 * @param string $username      Instagram username.
 * @param string $target        Link target.
 * @param int    $limit         Images to display.
 * @param string $size          Image size.
 * @param bool   $show_likes    Show likes.
 * @param bool   $show_comments Show comments.
 * @param string $layout        Gallery layout.
 * @param bool   $fallback      Whether to use to JS fallback or not.
 * @return string|WP_Error
 */
function elegant_scrape_instagram( $username, $target = '_self', $limit = 9, $size = 'large', $show_likes = false, $show_comments = false, $layout = 'grid', $fallback = true ) {

	$username = trim( strtolower( $username ) );
	$error    = '';

	$api_data = get_option( 'elegant_elements_instagram_api_data', array() );

	if ( ! isset( $api_data['access_token'] ) ) {
		return;
	}

	if ( isset( $api_data['access_token'] ) ) {
		$username = $api_data['username'];

		$instagram = get_transient( 'ee-insta-api-' . sanitize_title_with_dashes( $username ) );
		if ( false === $instagram ) {
			$media_api = 'https://graph.instagram.com/me/media?fields=id,caption,media_url,media_type,permalink,thumbnail_url&access_token=' . $api_data['access_token'];

			$response       = wp_remote_get( $media_api );
			$media_response = wp_remote_retrieve_body( $response );
			$media_response = json_decode( $media_response, true );
			$media_response = $media_response['data'];

			$instagram_media = array();
			foreach ( $media_response as $key => $media ) {
				$type    = $media['media_type'];
				$caption = __( 'Instagram Image', 'elegant-elements' );

				if ( isset( $media['caption'] ) && '' !== $media['caption'] ) {
					$caption = wp_kses( $media['caption'], array() );
				}

				$instagram_media[] = array(
					'description' => $caption,
					'link'        => $media['permalink'],
					'time'        => '',
					'comments'    => '',
					'likes'       => '',
					'thumbnail'   => ( isset( $media['thumbnail_url'] ) ? $media['thumbnail_url'] : $media['media_url'] ),
					'small'       => ( isset( $media['thumbnail_url'] ) ? $media['thumbnail_url'] : $media['media_url'] ),
					'large'       => $media['media_url'],
					'original'    => $media['media_url'],
					'type'        => $type,
				);
			} // End foreach().

			if ( ! empty( $instagram_media ) ) {
				$instagram = base64_encode( serialize( $instagram_media ) ); // @codingStandardsIgnoreLine
				set_transient( 'ee-insta-api-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}

			return $instagram_media;
		} else {
			if ( ! empty( $instagram ) ) {
				return unserialize( base64_decode( $instagram ) ); // @codingStandardsIgnoreLine
			} else {
				return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'elegant-elements' ) );
			}
		}
	} else {
		switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url              = 'https://www.instagram.com/explore/tags/' . str_replace( '#', '', $username );
				$transient_prefix = 'h';
				break;

			default:
				$url              = 'https://www.instagram.com/' . str_replace( '@', '', $username );
				$transient_prefix = 'u';
				break;
		}

		$instagram = get_transient( 'ee-insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) );
		if ( false === $instagram ) {

			$remote = wp_remote_get(
				$url,
				array(
					'headers' => array(
						'referer' => 'facebook.com',
					),
				)
			);

			if ( is_wp_error( $remote ) ) {
				$error  = new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'elegant-elements' ) );
				$remote = array();
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				$error = new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'elegant-elements' ) );
			}

			$shards = explode( 'window._sharedData = ', $remote['body'] );

			if ( isset( $shards[1] ) ) {
				$insta_json  = explode( ';</script>', $shards[1] );
				$insta_array = json_decode( $insta_json[0], true );
			} else {
				$insta_array = false;
			}

			if ( ! $insta_array ) {
				$error = new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'elegant-elements' ) );
			}

			if ( ( isset( $insta_array['entry_data']['LoginAndSignupPage'] ) && true === $fallback ) || '' !== $error ) {
				$url   = $url . '/channel/?__a=1';
				$error = '';

				$remote = wp_remote_get(
					$url,
					array(
						'headers' => array(
							'referer' => 'facebook.com',
						),
					)
				);

				$insta_json  = $remote['body'];
				$insta_array = json_decode( $insta_json, true );

				if ( null === $insta_array ) {
					$insta_array                                     = array();
					$insta_array['entry_data']['LoginAndSignupPage'] = true;
				}

				if ( is_wp_error( $remote ) ) {
					$error  = new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'elegant-elements' ) );
					$remote = array();
				}

				if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
					$error = new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'elegant-elements' ) );
				}
			}

			if ( ( isset( $insta_array['entry_data']['LoginAndSignupPage'] ) && true === $fallback ) || '' !== $error ) {
				$user_id = str_replace( array( '@', '#', '.' ), '', $username );
				$ulclass = apply_filters( 'elegant_instagram_list_class', 'elegant-instagram-pics elegant-instagram-size-' . $size );
				$liclass = apply_filters( 'elegant_instagram_item_class', 'elegant-instagram-pic' );
				$aclass  = apply_filters( 'elegant_instagram_a_class', 'elegant-instagram-pic-link' );
				ob_start();
				?>
				<div <?php echo FusionBuilder::attributes( 'elegant-instagram-gallery' ); ?>>
					<ul class="instagram-pics-with-js-<?php echo esc_attr( $user_id ); ?> <?php echo esc_attr( $ulclass ); ?>">
						<?php echo esc_html__( 'Loading Instagram data.', 'elegant-elements' ); ?>
					</ul>
				</div>
				<script type="text/javascript">
					jQuery.get( '<?php echo esc_attr( $url ); ?>', function( data ) {
						var images = '',
							imagesArray = [],
							target = '<?php echo esc_attr( $target ); ?>',
							size = '<?php echo esc_attr( $size ); ?>',
							imagesData = '',
							likes_comments = '',
							show_likes = '<?php echo $show_likes; ?>',
							show_comments = '<?php echo $show_comments; ?>';

						if ( 'undefined' === typeof data.graphql ) {
							return false;
						}

						if ( 'undefined' !== typeof data.graphql.user ) {
							images = data.graphql.user.edge_owner_to_timeline_media.edges;
						} else if ( 'undefined' !== typeof data.graphql.hashtag ) {
							images = data.graphql.hashtag.edge_hashtag_to_media.edges;
						}

						images = images.slice( 0, parseInt( '<?php echo esc_attr( $limit ); ?>') );

						jQuery.each( images, function( index, item ) {
							var $caption = '<?php echo esc_attr__( 'Instagram Image', 'elegant-elements' ); ?>',
								$instagram = [],
								comments = item.node.edge_media_to_comment.count,
								likes = item.node.edge_liked_by.count,
								likes_comments = '';

							if ( item.node.edge_media_to_caption.edges.length && 'undefined' !== item.node.edge_media_to_caption.edges[0]['node'] ) {
								$caption = item.node.edge_media_to_caption.edges[0]['node']['text'];
							}

							$instagram = {
								description: $caption,
								link: '//www.instagram.com/p/' + item.node.shortcode,
								time: item.node.taken_at_timestamp,
								thumbnail: item.node.thumbnail_resources[0]['src'],
								small: item.node.thumbnail_resources[2]['src'],
								large: item.node.thumbnail_resources[4]['src'],
								original: item.node.display_url,
							};

							if ( 'no' !== show_likes ) {
								likes_comments += '<span class="elegant-instagram-likes fa fa-heart"> ' + likes + '</span>';
							}

							if ( 'no' !== show_comments ) {
								likes_comments += '<span class="elegant-instagram-comments fa fa-comment"> ' + comments + '</span>';
							}

							if ( 'lightbox' !== target ) {
								imagesData += '<li class="<?php echo esc_attr( $liclass ); ?>">';
								imagesData += '<div class="elegant-instagram-pic-wrapper">';
								imagesData += '<a class="<?php echo esc_attr( $aclass ); ?>" href="' + $instagram.link + '" target="' + target + '">';
								imagesData += '<img src="' + $instagram[ size ] + '"  alt="' + $instagram.description + '" title="' + $instagram.description + '"/>';
								imagesData += '</a>';

								if ( '' !== likes_comments ) {
									imagesData += '<div class="elegant-instagram-pic-likes">';
									imagesData += likes_comments;
									imagesData += '</div>';
								}

								imagesData += '</div>';
								imagesData += '</li>';
							} else {
								imagesData += '<li class="<?php echo esc_attr( $liclass ); ?>">';
								imagesData += '<div class="elegant-instagram-pic-wrapper">';
								imagesData += '<a href="' + $instagram.original + '&type=.jpg" class="fusion-lightbox elegant-instagram-pic-link" data-rel="iLightbox[gallery_image_<?php echo $user_id; ?>]">';
								imagesData += '<img src="' + $instagram[ size ] + '">';
								imagesData += '</a>';

								if ( '' !== likes_comments ) {
									imagesData += '<div class="elegant-instagram-pic-likes">';
									imagesData += likes_comments;
									imagesData += '</div>';
								}

								imagesData += '</div>';
								imagesData += '</li>';
							}
						} );

						jQuery( '.instagram-pics-with-js-<?php echo esc_attr( $user_id ); ?>' ).html( imagesData ).promise().done( function() {
							// window.dispatchEvent( new Event( 'load' ) );
							window.avadaLightBoxInitializeLightbox();
							window.avadaLightBox.refresh_lightbox();
							<?php
							if ( 'grid' !== $layout ) {
								?>
								var galleryItems = jQuery( this ).find( 'li' );
								jQuery( this ).find( '.elegant-instagram-pics' ).css( 'opacity', '0' );
								jQuery( document ).trigger( 'instagramGalleryLoaded', { items: galleryItems } );
								<?php
							}
							?>
						} );
					} );
				</script>
				<?php
				$content = ob_get_clean();
				return new WP_Error( 'bad_json_2', $content );
			}

			$user = array();

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user'] ) ) {
				$user = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user'];
			} elseif ( isset( $insta_array['graphql']['user'] ) ) {
				$user = $insta_array['graphql']['user'];
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} elseif ( isset( $insta_array['graphql']['user'] ) ) {
				$images = $insta_array['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'elegant-elements' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'elegant-elements' ) );
			}

			$instagram = array();

			// If user is available, add to the array.
			if ( ! empty( $user ) ) {
				$instagram['user'] = $user;
			}

			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'elegant-elements' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//www.instagram.com/p/' . $image['node']['shortcode'] ),
					'time'        => $image['node']['taken_at_timestamp'],
					'comments'    => $image['node']['edge_media_to_comment']['count'],
					'likes'       => $image['node']['edge_liked_by']['count'],
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][1]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][3]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
					'type'        => $type,
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) ); // @codingStandardsIgnoreLine
				set_transient( 'ee-insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( base64_decode( $instagram ) ); // @codingStandardsIgnoreLine
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'elegant-elements' ) );
		}
	}
}

/**
 * Converts a number into a short version, eg: 1000 -> 1k
 *
 * @since 2.5
 * @access public
 * @param int $number    Integer number.
 * @param int $precision Decimal precision.
 * @return string Number format.
 */
function elegant_number_format_short( $number, $precision = 1 ) {
	if ( $number < 999 ) {
		// 0 - 999
		$number_format = number_format( $number, $precision );
		$suffix        = '';
	} elseif ( $number < 999999 ) {
		// 1k-999k
		$number_format = number_format( $number / 1000, $precision );
		$suffix        = 'K';
	} elseif ( $number < 999999999 ) {
		// 1m-999m
		$number_format = number_format( $number / 1000000, $precision );
		$suffix        = 'M';
	} else {
		// 1b+
		$number_format = number_format( $number / 1000000000, $precision );
		$suffix        = 'B';
	}

	// Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1".
	if ( $precision > 0 ) {
		$dotzero       = '.' . str_repeat( '0', $precision );
		$number_format = str_replace( $dotzero, '', $number_format );
	}

	return $number_format . $suffix;
}

/**
 * Returns a cached query.
 * If the query is not cached then it caches it and returns the result.
 *
 * @since 1.3.1
 * @param string|array $args Same as in WP_Query.
 * @return object
 */
function elegant_fb_cached_query( $args ) {
	$query_id = md5( maybe_serialize( $args ) );
	$query    = wp_cache_get( $query_id, 'eefb_library_templates' );

	if ( false === $query ) {
		$query = new WP_Query( $args );
		wp_cache_set( $query_id, $query, 'eefb_library_templates' );
	}

	return $query;
}

/**
 * Get Registered Sidebars.
 *
 * @access public
 * @since 3.2.0
 * @return array
 */
function elegant_get_sidebars() {
	global $wp_registered_sidebars;

	$sidebars = array();

	foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
		$name                    = $sidebar['name'];
		$sidebars[ $sidebar_id ] = $name;
	}

	return $sidebars;
}

/**
 * Get image URL from image ID.
 *
 * @access public
 * @since 3.3.2
 */
function elegant_get_image_url() {

	check_ajax_referer( 'elegant_load_nonce', 'elegant_load_nonce' );

	if ( ! isset( $_POST['elegant_image_ids'] ) && '' === $_POST['elegant_image_ids'] ) {
		die( 'No images' );
	}

	$data      = array();
	$image_ids = wp_unslash( $_POST['elegant_image_ids'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	foreach ( $image_ids as $image_id ) {
		if ( '' !== $image_id ) {
			$image_url        = wp_get_attachment_url( $image_id, 'thumbnail' );
			$image_html       = '<div class="fusion-multi-image" data-image-id="' . $image_id . '">';
			$image_html      .= '<img src="' . $image_url . '"/>';
			$image_html      .= '<span class="fusion-multi-image-remove dashicons dashicons-no-alt"></span>';
			$image_html      .= '</div>';
			$data['images'][] = $image_html;
		}
	}
	$json_data = wp_json_encode( $data );

	die( $json_data ); // phpcs:ignore WordPress.Security.EscapeOutput
}

add_action( 'wp_ajax_elegant_elements_get_image_url', 'elegant_get_image_url' );
add_action( 'wp_ajax_nopriv_elegant_elements_get_image_url', 'elegant_get_image_url' );

/**
 * Load ACF fields from JSON.
 *
 * @access public
 * @since 3.3.5
 * @return String|WP_Post_Type An array of post type names or objects.
 */
function elegant_get_post_types() {
	$args = array(
		'public' => true,
	);

	return get_post_types( $args, 'names' );
}

/**
 * Refresh access token interval: 50 days.
 *
 * @since 3.3.6
 * @access public
 * @param array $schedules WP Cron schedules.
 * @return array
 */
function elegant_add_cron_interval( $schedules ) {
	$schedules['fifty_days'] = array(
		'interval' => 4320000,
		'display'  => esc_html__( 'Every 50 Days' ),
	);

	return $schedules;
}
add_filter( 'cron_schedules', 'elegant_add_cron_interval' );

/**
 * Refresh access token for Instagram every 50 days.
 *
 * @since 3.3.6
 * @access public
 * @return void
 */
function elegant_instagram_refresh_token() {
	$api_data = get_option( 'elegant_elements_instagram_api_data', array() );
	$args     = array(
		'grant_type'   => 'ig_refresh_token',
		'access_token' => $api_data['access_token'],
	);

	$post_data = http_build_query( $args );

	$response              = wp_remote_get( 'https://graph.instagram.com/refresh_access_token?' . $post_data );
	$response_access_token = wp_remote_retrieve_body( $response );
	$response_access_token = json_decode( $response_access_token, true );

	if ( isset( $response_access_token['access_token'] ) ) {
		$api_data['access_token'] = $response_access_token['access_token'];
		update_option( 'elegant_elements_instagram_api_data', $api_data );
	}

	// Unschedule the previous refresh token event.
	$timestamp = wp_next_scheduled( 'elegant_instagram_refresh_token' );
	wp_unschedule_event( $timestamp, 'elegant_instagram_refresh_token' );

	// Schedule refresh token event.
	if ( ! wp_next_scheduled( 'elegant_instagram_refresh_token' ) ) {
		wp_schedule_event( time(), 'fifty_days', 'elegant_instagram_refresh_token' );
	}
}
add_action( 'elegant_instagram_refresh_token', 'elegant_instagram_refresh_token' );

/**
 * Return the SVG with text path.
 *
 * @since 3.5.0
 * @access public
 * @param array $args Element attributes.
 * @return string
 */
function elegant_get_text_path_svg( $args ) {
	$shape = $args['shape'];
	$text  = $args['text'];
	$id    = wp_rand();
	$svg   = '';

	if ( isset( $args['link'] ) && '' !== $args['link'] ) {
		$text = '<a href="' . $args['link'] . '">' . $text . '</a>';
	}

	$text_node = '<text><textPath id="ee-text-path-' . $id . '" href="#ee-path-' . $id . '" startOffset="0%">' . $text . '</textPath></text>';

	switch ( $shape ) {
		case 'wave':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="42.4994" viewBox="0 0 250 42.4994"><path id="ee-path-' . $id . '" d="M0,42.2494C62.5,42.2494,62.5.25,125,.25s62.5,41.9994,125,41.9994"/><path d="M-41.6693,49.25"/><path d="M-208.3307-6.75"/>' . $text_node . '</svg>';
			break;
		case 'arc-top':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.25" viewBox="0 0 250.5 125.25"><path id="ee-path-' . $id . '" d="M.25,125.25a125,125,0,0,1,250,0"/>' . $text_node . '</svg>';
			break;
		case 'arc-bottom':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.25" viewBox="0 0 250.5 125.25"><path id="ee-path-' . $id . '" d="M 0 0 C 0 180 250 180 250 0"/>' . $text_node . '</svg>';
			break;
		case 'circle':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="250.5" viewBox="0 0 250.5 250.5"><path id="ee-path-' . $id . '" d="M.25,125.25a125,125,0,1,1,125,125,125,125,0,0,1-125-125"/>' . $text_node . '</svg>';
			break;
		case 'line-top':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="22" viewBox="0 0 250 22"><path id="ee-path-' . $id . '" d="M 0 27 l 250 -22"/>' . $text_node . '</svg>';
			break;
		case 'line-bottom':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250" height="22" viewBox="0 0 250 22"><path id="ee-path-' . $id . '" d="M 0 27 l 250 22"/>' . $text_node . '</svg>';
			break;
		case 'oval':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.5" height="125.75" viewBox="0 0 250.5 125.75"><path id="ee-path-' . $id . '" class="b473dc75-7459-43a5-8a1c-89caf910da53" d="M.25,62.875C.25,28.2882,56.2144.25,125.25.25s125,28.0382,125,62.625-55.9644,62.625-125,62.625S.25,97.4619.25,62.875"/>' . $text_node . '</svg>';
			break;
		case 'spiral':
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="250.4348" height="239.4454" viewBox="0 0 250.4348 239.4454"><path id="ee-path-' . $id . '" d="M.1848,49.0219a149.3489,149.3489,0,0,1,210.9824-9.8266,119.479,119.479,0,0,1,7.8613,168.786A95.5831,95.5831,0,0,1,84,214.27a76.4666,76.4666,0,0,1-5.0312-108.023"/>' . $text_node . '</svg>';
			break;
	}

	return $svg;
}

/**
 * Map shortcode for gradient_backgrounds.
 *
 * @since 3.5.1
 * @return void
 */
function map_elegant_backgrounds() {
	global $fusion_settings;

	if ( function_exists( 'fusion_builder_add_element_settings' ) ) {
		// Add settings to container and column.
		$gradient_options = array(
			array(
				'type'             => 'subgroup',
				'heading'          => esc_attr__( 'Elegant Backgrounds Types', 'fusion-builder' ),
				'description'      => esc_attr__( 'Use filters to see specific type of content.', 'fusion-builder' ),
				'param_name'       => 'elegant_backgrounds',
				'default'          => 'gradient',
				'group'            => esc_attr__( 'Elegant Backgrounds', 'fusion-builder' ),
				'remove_from_atts' => true,
				'value'            => array(
					'gradient' => esc_attr__( 'Gradient', 'fusion-builder' ),
					'slider'   => esc_attr__( 'Slider', 'fusion-builder' ),
					'lottie'   => esc_attr__( 'Lottie', 'fusion-builder' ),
				),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Gradient Top Color', 'elegant-elements' ),
				'param_name'  => 'gradient_top_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the top color of the background gradient.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'gradient',
				),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Gradient Bottom Color', 'elegant-elements' ),
				'param_name'  => 'gradient_bottom_color',
				'value'       => '',
				'description' => esc_attr__( 'Controls the bottom color of the background gradient.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'gradient',
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Gradient Type', 'elegant-elements' ),
				'description' => esc_attr__( 'Select how you want the gradient to be applied.', 'elegant-elements' ),
				'param_name'  => 'ee_gradient_type',
				'default'     => 'vertical',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'value'       => array(
					'vertical'   => esc_attr__( 'Vertical', 'elegant-elements' ),
					'horizontal' => esc_attr__( 'Horizontal', 'elegant-elements' ),
				),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'gradient',
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Horizontal Gradient Direction', 'elegant-elements' ),
				'description' => esc_attr__( 'Controls the gradient color direction for horizontal gradient.', 'elegant-elements' ),
				'param_name'  => 'gradient_direction',
				'default'     => '0deg',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'value'       => array(
					'0deg'   => esc_attr__( 'Left to Right', 'elegant-elements' ),
					'45deg'  => esc_attr__( 'Bottom - Left Angle', 'elegant-elements' ),
					'-45deg' => esc_attr__( 'Top - Left Angle', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'ee_gradient_type',
						'value'    => 'vertical',
						'operator' => '!=',
					),
				),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'gradient',
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Force Gradient Apply', 'elegant-elements' ),
				'description' => __( 'Would you like to force gradient background over your background color and image settings? <br/>This will use only gradient background for this element.<br/>If set to "No", css will be generated, but might not applied if background image or background color is set.', 'elegant-elements' ),
				'param_name'  => 'gradient_force',
				'default'     => 'yes',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'gradient',
				),
			),
		);

		// Add settings to container and column.
		$slider_options = array(
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Enable Background Image Slider', 'elegant-elements' ),
				'param_name'  => 'enable_background_slider',
				'default'     => 'no',
				'value'       => array(
					'yes' => esc_attr__( 'Yes', 'elegant-elements' ),
					'no'  => esc_attr__( 'No', 'elegant-elements' ),
				),
				'description' => esc_attr__( 'Add stunning image slider as your column or container background.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'slider',
				),
			),
			array(
				'type'        => 'elegant_upload_images',
				'heading'     => esc_attr__( 'Upload Background Images for Slider', 'elegant-elements' ),
				'param_name'  => 'image_ids',
				'value'       => '',
				'dependency'  => array(
					array(
						'element'  => 'enable_background_slider',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Upload images for background slider. Only first 3 images will be displayed in the slider while editing in live editor.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'slider',
				),
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Background Image Slider Effect', 'elegant-elements' ),
				'param_name'  => 'elegant_transition_effect',
				'default'     => 'fade',
				'value'       => array(
					'random'      => esc_attr__( 'Random Effects', 'elegant-elements' ),
					'fade'        => esc_attr__( 'Fade', 'elegant-elements' ),
					'fade_in_out' => esc_attr__( 'Fade-in-Out', 'elegant-elements' ),
					'push_left'   => esc_attr__( 'Push Left', 'elegant-elements' ),
					'push_right'  => esc_attr__( 'Push Right', 'elegant-elements' ),
					'push_up'     => esc_attr__( 'Push Up', 'elegant-elements' ),
					'push_down'   => esc_attr__( 'Push Down', 'elegant-elements' ),
					'cover_left'  => esc_attr__( 'Cover Left', 'elegant-elements' ),
					'cover_right' => esc_attr__( 'Cover Right', 'elegant-elements' ),
					'cover_up'    => esc_attr__( 'Cover Up', 'elegant-elements' ),
					'cover_down'  => esc_attr__( 'Cover Down', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'enable_background_slider',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Select the slider effect. Random will use all effects with random order for each slide.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'slider',
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Background Image Scale', 'elegant-elements' ),
				'param_name'  => 'elegant_background_scale',
				'default'     => 'cover',
				'value'       => array(
					'cover' => esc_attr__( 'Cover', 'elegant-elements' ),
					'fit'   => esc_attr__( 'Fit', 'elegant-elements' ),
					'fill'  => esc_attr__( 'Fill', 'elegant-elements' ),
				),
				'dependency'  => array(
					array(
						'element'  => 'enable_background_slider',
						'value'    => 'yes',
						'operator' => '==',
					),
				),
				'description' => esc_attr__( 'Controls the scaling mode.', 'elegant-elements' ),
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'slider',
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Transition Delay', 'elegant-elements' ),
				'description' => esc_attr__( 'Select delay between image transitions. ( In Seconds. )', 'elegant-elements' ),
				'param_name'  => 'elegant_transition_delay',
				'value'       => '3',
				'min'         => '1',
				'max'         => '10',
				'step'        => '1',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'slider',
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Transition Duration', 'elegant-elements' ),
				'description' => esc_attr__( 'Select transition duration between image transitions. ( In Miliseconds. )', 'elegant-elements' ),
				'param_name'  => 'elegant_transition_duration',
				'value'       => '750',
				'min'         => '100',
				'max'         => '5000',
				'step'        => '1',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'elegant-elements' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'slider',
				),
			),
		);

		// Add settings for lottie animtion background.
		$lottie_options = array(
			array(
				'type'        => 'uploadfile',
				'heading'     => esc_attr__( 'Lottie Animation JSON File.', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload the Lottie JSON file or enter the Lottie image animation url from https://lottiefiles.com.', 'elegant-elements' ),
				'param_name'  => 'lottie_json_url',
				'value'       => '',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'fusion-builder' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'lottie',
				),
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Play Mode', 'elegant-elements' ),
				'description' => esc_attr__( 'Normal mode will play animation in one direction and the bounce mode will play animation in revese after the normal animation.', 'elegant-elements' ),
				'param_name'  => 'animation_mode',
				'value'       => array(
					'normal' => 'Normal',
					'bounce' => 'Bounce',
				),
				'default'     => 'normal',
				'group'       => esc_attr__( 'Elegant Backgrounds', 'fusion-builder' ),
				'subgroup'    => array(
					'name' => 'elegant_backgrounds',
					'tab'  => 'lottie',
				),
			),
		);

		$container_gradient_options = $gradient_options;
		unset( $container_gradient_options[0]['value']['lottie'] );

		if ( empty( $settings ) || ( isset( $settings['remove_gradient_backgrounds'] ) && 1 !== absint( $settings['remove_gradient_backgrounds'] ) ) ) {
			fusion_builder_add_element_settings( 'fusion_builder_container', $container_gradient_options );
			fusion_builder_add_element_settings( 'fusion_builder_column', $gradient_options );
		}

		if ( empty( $settings ) || ( isset( $settings['remove_lottie_backgrounds'] ) && 1 !== absint( $settings['remove_lottie_backgrounds'] ) ) ) {
			fusion_builder_add_element_settings( 'fusion_builder_column', $lottie_options );
		}

		if ( empty( $settings ) || ( isset( $settings['remove_background_sliders'] ) && 1 !== absint( $settings['remove_background_sliders'] ) ) ) {
			fusion_builder_add_element_settings( 'fusion_builder_container', $slider_options );
			fusion_builder_add_element_settings( 'fusion_builder_column', $slider_options );
		}
	}
}

add_action( 'fusion_builder_load_templates', 'map_elegant_backgrounds', 99 );
add_action( 'wp_loaded', 'map_elegant_backgrounds', 99 );
