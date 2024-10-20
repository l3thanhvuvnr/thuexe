<?php
if ( fusion_is_element_enabled( 'iee_image_mask_heading' ) && ! class_exists( 'IEE_Image_Mask_Heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 2.0
	 */
	class IEE_Image_Mask_Heading extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 2.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 2.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-image-mask-heading-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-image-mask-heading', array( $this, 'heading_attr' ) );

			add_shortcode( 'iee_image_mask_heading', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 2.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_heading'] = $default_typography['title'];
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/image-mask-heading/elegant-image-mask-heading.php' ) ) {
				include locate_template( 'templates/image-mask-heading/elegant-image-mask-heading.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/image-mask-heading/elegant-image-mask-heading.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-image-mask-heading-wrapper',
				'style' => '',
			);

			$attr['class'] .= ' elegant-image-mask-heading-align-' . $this->args['alignment'];

			$attr = fusion_builder_visibility_atts( $this->args['hide_on_mobile'], $attr );

			if ( isset( $this->args['class'] ) ) {
				$attr['class'] .= ' ' . $this->args['class'];
			}

			if ( isset( $this->args['id'] ) ) {
				$attr['id'] = $this->args['id'];
			}

			return $attr;
		}

		/**
		 * Builds the attributes array for heading.
		 *
		 * @access public
		 * @since 2.0
		 * @return array
		 */
		public function heading_attr() {
			global $fusion_settings;

			$attr = array(
				'class' => 'elegant-image-mask-heading',
				'style' => '',
			);

			if ( isset( $this->args['typography_heading'] ) && '' !== $this->args['typography_heading'] ) {
				$typography         = $this->args['typography_heading'];
				$heading_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $heading_typography;
			}

			if ( isset( $this->args['mask_image'] ) && '' !== $this->args['mask_image'] ) {
				$attr['style'] .= 'background-image: url(' . $this->args['mask_image'] . ');';
				$attr['style'] .= 'background-position:' . $this->args['background_position'] . ';';
				$attr['style'] .= 'background-repeat:' . $this->args['background_repeat'] . ';';
				$attr['style'] .= 'background-size:' . $this->args['background_size'] . ';';
				$attr['style'] .= '-webkit-background-clip: text;';
				$attr['style'] .= '-webkit-text-fill-color: transparent;';
			}

			if ( isset( $this->args['heading_font_size'] ) && '' !== $this->args['heading_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' ) . ';';
				$attr['style'] .= 'line-height:1.2em;';
			}

			return $attr;
		}
	}

	new IEE_Image_Mask_Heading();
} // End if().


/**
 * Map shortcode for image_mask_heading.
 *
 * @since 2.0
 * @return void
 */
function map_elegant_elements_image_mask_heading() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Image Mask Heading', 'elegant-elements' ),
			'shortcode'                 => 'iee_image_mask_heading',
			'icon'                      => 'icon-heading',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-image-mask-heading-preview.php',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'inline_editor'             => true,
			'inline_editor_shortcodes'  => true,
			'params'                    => array(
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Heading text', 'elegant-elements' ),
					'param_name'  => 'heading',
					'value'       => esc_attr__( 'Image Mask Heading', 'elegant-elements' ),
					'placeholder' => true,
					'description' => esc_attr__( 'Enter the text for the heading.', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the heading size, H1-H6.', 'elegant-elements' ),
					'param_name'  => 'heading_size',
					'value'       => array(
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
					),
					'default'     => 'h2',
					'dependency'  => array(
						array(
							'element'  => 'heading',
							'value'    => '',
							'operator' => '!=',
						),
					),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Heading Alignment', 'elegant-elements' ),
					'param_name'  => 'alignment',
					'default'     => 'center',
					'value'       => array(
						'left'   => esc_attr__( 'Left', 'elegant-elements' ),
						'center' => esc_attr__( 'Center', 'elegant-elements' ),
						'right'  => esc_attr__( 'Right', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Select the heading alignment.', 'elegant-elements' ),
				),
				array(
					'type'        => 'upload',
					'heading'     => esc_attr__( 'Mask Image', 'elegant-elements' ),
					'param_name'  => 'mask_image',
					'value'       => '',
					'description' => esc_attr__( 'Select the image to be used for masking the background.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Background Image Position', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the postion of the background image.', 'elegant-elements' ),
					'param_name'  => 'background_position',
					'default'     => 'left top',
					'value'       => array(
						'left top'      => esc_attr__( 'Left Top', 'elegant-elements' ),
						'left center'   => esc_attr__( 'Left Center', 'elegant-elements' ),
						'left bottom'   => esc_attr__( 'Left Bottom', 'elegant-elements' ),
						'right top'     => esc_attr__( 'Right Top', 'elegant-elements' ),
						'right center'  => esc_attr__( 'Right Center', 'elegant-elements' ),
						'right bottom'  => esc_attr__( 'Right Bottom', 'elegant-elements' ),
						'center top'    => esc_attr__( 'Center Top', 'elegant-elements' ),
						'center center' => esc_attr__( 'Center Center', 'elegant-elements' ),
						'center bottom' => esc_attr__( 'Center Bottom', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'mask_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Background Repeat', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image repeats.', 'elegant-elements' ),
					'param_name'  => 'background_repeat',
					'default'     => 'no-repeat',
					'value'       => array(
						'no-repeat' => esc_attr__( 'No Repeat', 'elegant-elements' ),
						'repeat'    => esc_attr__( 'Repeat Vertically and Horizontally', 'elegant-elements' ),
						'repeat-x'  => esc_attr__( 'Repeat Horizontally', 'elegant-elements' ),
						'repeat-y'  => esc_attr__( 'Repeat Vertically', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'mask_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Background Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose how the background image show be sized.', 'elegant-elements' ),
					'param_name'  => 'background_size',
					'default'     => 'auto',
					'value'       => array(
						'auto'    => esc_attr__( 'Auto', 'elegant-elements' ),
						'cover'   => esc_attr__( 'Cover', 'elegant-elements' ),
						'contain' => esc_attr__( 'Contain', 'elegant-elements' ),
					),
					'dependency'  => array(
						array(
							'element'  => 'mask_image',
							'value'    => '',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Mask Image', 'elegant-elements' ),
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Element Typography Override', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose if you want to keep the theme options global typography as default for this element or want to set custom. Controls typography options for all typography fields in this element.', 'elegant-elements' ),
					'param_name'  => 'element_typography',
					'default'     => 'custom',
					'value'       => array(
						'default' => esc_attr__( 'Default', 'elegant-elements' ),
						'custom'  => esc_attr__( 'Custom', 'elegant-elements' ),
					),
					'group'       => 'Typography',
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Heading Typography', 'elegant-elements' ),
					'param_name'  => 'typography_heading',
					'value'       => '',
					'default'     => $default_typography['title'],
					'description' => esc_attr__( 'Select the font for the heading.', 'elegant-elements' ),
					'dependency'  => array(
						array(
							'element'  => 'element_typography',
							'value'    => 'default',
							'operator' => '!=',
						),
					),
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'range',
					'heading'     => esc_attr__( 'Heading Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for heading text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'heading_font_size',
					'value'       => '28',
					'min'         => '12',
					'max'         => '300',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'checkbox_button_set',
					'heading'     => esc_attr__( 'Element Visibility', 'elegant-elements' ),
					'param_name'  => 'hide_on_mobile',
					'value'       => fusion_builder_visibility_options( 'full' ),
					'default'     => fusion_builder_default_visibility( 'array' ),
					'description' => esc_attr__( 'Choose to show or hide the element on small, medium or large screens. You can choose more than one at a time.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS Class', 'elegant-elements' ),
					'param_name'  => 'class',
					'value'       => '',
					'description' => esc_attr__( 'Add a class to the wrapping HTML element.', 'elegant-elements' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'CSS ID', 'elegant-elements' ),
					'param_name'  => 'id',
					'value'       => '',
					'description' => esc_attr__( 'Add an ID to the wrapping HTML element.', 'elegant-elements' ),
				),
			),
		)
	);
}

add_action( 'fusion_builder_before_init', 'map_elegant_elements_image_mask_heading', 99 );
