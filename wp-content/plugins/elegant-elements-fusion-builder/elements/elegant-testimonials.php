<?php
if ( fusion_is_element_enabled( 'iee_testimonials' ) && ! class_exists( 'IEE_Testimonials' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.0
	 */
	class IEE_Testimonials extends Fusion_Element {

		/**
		 * Testimonials counter.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $testimonials_counter = 1;

		/**
		 * Testimonials.
		 *
		 * @since 1.0
		 * @access private
		 * @var object
		 */
		private $testimonials = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_shortcode( 'iee_testimonials', array( $this, 'render_parent' ) );
			add_shortcode( 'iee_testimonial', array( $this, 'render_child' ) );
		}

		/**
		 * Render the parent shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_parent( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_title']     = $default_typography['title'];
				$args['typography_sub_title'] = $default_typography['title'];
				$args['typography_content']   = $default_typography['description'];
			}

			$html = '';

			if ( '' !== locate_template( 'templates/testimonials/elegant-testimonials-parent.php' ) ) {
				include locate_template( 'templates/testimonials/elegant-testimonials-parent.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/testimonials/elegant-testimonials-parent.php';
			}

			$this->testimonials_counter++;

			return $html;
		}

		/**
		 * Render the child shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render_child( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$child_html = '';

			if ( '' !== locate_template( 'templates/testimonials/elegant-testimonials-child.php' ) ) {
				include locate_template( 'templates/testimonials/elegant-testimonials-child.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/testimonials/elegant-testimonials-child.php';
			}

			return $child_html;
		}

		/**
		 * Returns equivalent global information for FB param.
		 *
		 * @since 1.0
		 * @return array|bool Element option data.
		 */
		public function iee_testimonials_map_descriptions() {
			$shortcode_option_map = array();

			$shortcode_option_map['background_color']['iee_testimonials'] = array(
				'theme-option' => 'iee_testimonials_background_color',
				'reset'        => true,
			);

			return $shortcode_option_map;
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return void
		 */
		public function add_scripts() {
			global $elegant_js_folder_url, $elegant_js_folder_path, $elegant_css_folder_url, $elegant_css_folder_path;

			Fusion_Dynamic_JS::enqueue_script(
				'infi-elegant-testimonials',
				$elegant_js_folder_url . '/infi-elegant-testimonials.min.js',
				$elegant_js_folder_path . '/infi-elegant-testimonials.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Testimonials();
} // End if().


/**
 * Map shortcode for testimonials.
 *
 * @since 1.0
 * @return void
 */
function map_elegant_elements_testimonials() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	$parent_args = array(
		'name'                      => esc_attr__( 'Elegant Testimonials', 'elegant-elements' ),
		'shortcode'                 => 'iee_testimonials',
		'icon'                      => 'icon-testimonials',
		'multi'                     => 'multi_element_parent',
		'element_child'             => 'iee_testimonial',
		'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-testimonials-preview.php',
		'preview_id'                => 'elegant-elements-module-infi-testimonials-preview-template',
		'custom_settings_view_name' => 'ModuleSettingElegantView',
		'params'                    => array(
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Content', 'elegant-elements' ),
				'description' => esc_attr__( 'Child testimonials.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => '[iee_testimonial title="' . esc_attr__( 'Title Goes Here', 'elegant-elements' ) . '" sub_title="' . esc_attr__( 'Sub Title Here', 'elegant-elements' ) . '"]' . esc_attr__( 'Your testimonial description text goes here.', 'elegant-elements' ) . '[/iee_testimonial]',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Heading Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the testimonials title size, H1-H6.', 'elegant-elements' ),
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
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_attr__( 'Text Color', 'elegant-elements' ),
				'param_name'  => 'text_color',
				'value'       => '',
				'description' => esc_attr__( 'Select text color for testimonial. Individual element text colors can be changed from child testimonial.', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Background Color', 'elegant-elements' ),
				'param_name'  => 'background_color',
				'value'       => '',
				'description' => esc_attr__( 'Select background color for the testimonials container.', 'elegant-elements' ),
			),
			array(
				'type'        => 'upload',
				'heading'     => esc_attr__( 'Background Image', 'elegant-elements' ),
				'param_name'  => 'background_image',
				'value'       => '',
				'description' => esc_attr__( 'Select background image for the testimonials container.', 'elegant-elements' ),
			),
			array(
				'type'        => 'select',
				'heading'     => esc_attr__( 'Background Image Position', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the postion of the background image for testimonials background image.', 'elegant-elements' ),
				'param_name'  => 'background_position',
				'default'     => 'left top',
				'dependency'  => array(
					array(
						'element'  => 'background_image',
						'value'    => '',
						'operator' => '!=',
					),
				),
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
			),
			array(
				'type'        => 'elegant_typography',
				'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the testimonial title.', 'elegant-elements' ),
				'param_name'  => 'typography_title',
				'value'       => '',
				'default'     => $default_typography['title'],
				'dependency'  => array(
					array(
						'element'  => 'element_typography',
						'value'    => 'default',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'elegant_typography',
				'heading'     => esc_attr__( 'Sub-title Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the testimonial sub-title.', 'elegant-elements' ),
				'param_name'  => 'typography_sub_title',
				'value'       => '',
				'default'     => $default_typography['title'],
				'dependency'  => array(
					array(
						'element'  => 'element_typography',
						'value'    => 'default',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'elegant_typography',
				'heading'     => esc_attr__( 'Content Typography', 'elegant-elements' ),
				'description' => esc_attr__( 'Select typography for the testimonial content.', 'elegant-elements' ),
				'param_name'  => 'typography_content',
				'value'       => '',
				'default'     => $default_typography['description'],
				'dependency'  => array(
					array(
						'element'  => 'element_typography',
						'value'    => 'default',
						'operator' => '!=',
					),
				),
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for title. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'title_font_size',
				'value'       => '28',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Sub Title Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for title. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'sub_title_font_size',
				'value'       => '18',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'range',
				'heading'     => esc_attr__( 'Content Font Size', 'elegant-elements' ),
				'description' => esc_attr__( 'Select the font size for testimonial content. ( In Pixel. )', 'elegant-elements' ),
				'param_name'  => 'content_font_size',
				'value'       => '16',
				'min'         => '12',
				'max'         => '100',
				'step'        => '1',
			),
			array(
				'type'        => 'radio_button_set',
				'heading'     => esc_attr__( 'Description Position', 'elegant-elements' ),
				'description' => esc_attr__( 'Choose the postion of the testimonial content.', 'elegant-elements' ),
				'param_name'  => 'description_position',
				'default'     => 'left',
				'value'       => array(
					'left'  => esc_attr__( 'Left', 'elegant-elements' ),
					'right' => esc_attr__( 'Right', 'elegant-elements' ),
				),
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
	);

	$child_args = array(
		'name'              => esc_attr__( 'Testimonial', 'elegant-elements' ),
		'shortcode'         => 'iee_testimonial',
		'hide_from_builder' => true,
		'allow_generator'   => true,
		'on_change'         => 'elegantTestimonialsShortcodeFilter',
		'params'            => array(
			array(
				'type'        => 'upload',
				'heading'     => esc_attr__( 'Image', 'elegant-elements' ),
				'description' => esc_attr__( 'Upload an image to display in the frame.', 'elegant-elements' ),
				'param_name'  => 'image_url',
				'value'       => '',
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter title to be displayed for this testimonial.', 'elegant-elements' ),
				'param_name'  => 'title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Your Title Goes Here', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Title Color', 'elegant-elements' ),
				'param_name'  => 'title_color',
				'value'       => '',
				'description' => esc_attr__( 'Select text color for this testimonial title.', 'elegant-elements' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_attr__( 'Sub Title', 'elegant-elements' ),
				'description' => esc_attr__( 'Enter sub-title to be displayed for this testimonial.', 'elegant-elements' ),
				'param_name'  => 'sub_title',
				'placeholder' => true,
				'value'       => esc_attr__( 'Sub Title Here', 'elegant-elements' ),
			),
			array(
				'type'        => 'colorpickeralpha',
				'heading'     => esc_attr__( 'Sub Title Color', 'elegant-elements' ),
				'param_name'  => 'sub_title_color',
				'value'       => '',
				'description' => esc_attr__( 'Select text color for this testimonial sub title.', 'elegant-elements' ),
			),
			array(
				'type'        => 'tinymce',
				'heading'     => esc_attr__( 'Description', 'elegant-elements' ),
				'description' => esc_attr__( 'Provide testimonial description.', 'elegant-elements' ),
				'param_name'  => 'element_content',
				'value'       => esc_attr__( 'Your testimonial description text goes here.', 'elegant-elements' ),
				'placeholder' => true,
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
	);

	if ( function_exists( 'fusion_builder_frontend_data' ) ) {
		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Testimonials',
				$parent_args,
				'parent'
			)
		);

		fusion_builder_map(
			fusion_builder_frontend_data(
				'IEE_Testimonials',
				$child_args,
				'child'
			)
		);
	} else {
		fusion_builder_map(
			$parent_args
		);

		fusion_builder_map(
			$child_args
		);
	}
}

add_action( 'fusion_builder_before_init', 'map_elegant_elements_testimonials', 99 );
