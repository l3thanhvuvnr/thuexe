<?php
if ( fusion_is_element_enabled( 'iee_expanding_sections' ) && ! class_exists( 'IEE_Expanding_Sections' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class IEE_Expanding_Sections extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Expanding Section counter.
		 *
		 * @since 1.1.0
		 * @access private
		 * @var object
		 */
		private $expanding_section_counter = 1;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-expanding-sections', array( $this, 'attr' ) );
			add_filter( 'fusion_attr_elegant-expanding-section-heading-area', array( $this, 'heading_area_attr' ) );
			add_filter( 'fusion_attr_elegant-expanding-section-title', array( $this, 'title_attr' ) );
			add_filter( 'fusion_attr_elegant-expanding-section-description', array( $this, 'description_attr' ) );
			add_filter( 'fusion_attr_elegant-expanding-section-icon', array( $this, 'icon_attr' ) );
			add_filter( 'fusion_attr_elegant-expanding-section-content-area', array( $this, 'content_attr' ) );

			add_shortcode( 'iee_expanding_sections', array( $this, 'render' ) );
		}

		/**
		 * Render the shortcode.
		 *
		 * @access public
		 * @since 1.1.0
		 * @param  array  $args    Shortcode paramters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			// Get default typography for title and description.
			$default_typography = elegant_get_default_typography();

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'title'                    => '',
					'description'              => '',
					'element_content'          => '',
					'element_typography'       => '',
					'typography_title'         => '',
					'title_font_size'          => '',
					'typography_description'   => '',
					'description_font_size'    => '',
					'heading_color'            => '',
					'background_color_1'       => '',
					'background_color_2'       => '',
					'gradient_direction'       => '',
					'background_color_content' => '',
					'class'                    => '',
					'id'                       => '',
					'hide_on_mobile'           => fusion_builder_default_visibility( 'string' ),
				),
				$args
			);

			$args = $defaults;

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_title']       = $default_typography['title'];
				$args['typography_description'] = $default_typography['description'];
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/expanding-sections/elegant-expanding-sections.php' ) ) {
				include locate_template( 'templates/expanding-sections/elegant-expanding-sections.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/expanding-sections/elegant-expanding-sections.php';
			}

			$this->expanding_section_counter++;

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function attr() {
			$attr = array(
				'class' => 'elegant-expanding-sections',
			);

			$attr['class'] .= ' elegant-expanding-section-' . $this->expanding_section_counter;

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
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function heading_area_attr() {
			$attr = array(
				'class' => 'elegant-expanding-section-heading-area',
				'style' => '',
			);

			if ( ( isset( $this->args['background_color_1'] ) && '' !== $this->args['background_color_1'] ) && ( isset( $this->args['background_color_2'] ) && '' !== $this->args['background_color_2'] ) ) {
				$attr['style'] .= 'background: ' . $this->get_gradient_color() . ';';
			} elseif ( isset( $this->args['background_color_1'] ) && '' !== $this->args['background_color_1'] ) {
				$attr['style'] .= 'background: ' . $this->args['background_color_1'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function title_attr() {
			$attr = array(
				'class' => 'elegant-expanding-section-title',
				'style' => '',
			);

			if ( isset( $this->args['typography_title'] ) && '' !== $this->args['typography_title'] ) {
				$typography       = $this->args['typography_title'];
				$title_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $title_typography;
			}

			if ( isset( $this->args['title_font_size'] ) && '' !== $this->args['title_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['title_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['heading_color'] ) && '' !== $this->args['heading_color'] ) {
				$attr['style'] .= 'color:' . $this->args['heading_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function description_attr() {
			$attr = array(
				'class' => 'elegant-expanding-section-description',
				'style' => '',
			);

			if ( isset( $this->args['typography_description'] ) && '' !== $this->args['typography_description'] ) {
				$typography             = $this->args['typography_description'];
				$description_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $description_typography;
			}

			if ( isset( $this->args['description_font_size'] ) && '' !== $this->args['description_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['description_font_size'], 'px' ) . ';';
			}

			if ( isset( $this->args['heading_color'] ) && '' !== $this->args['heading_color'] ) {
				$attr['style'] .= 'color:' . $this->args['heading_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function icon_attr() {
			$attr = array(
				'class' => 'elegant-expanding-section-icon',
				'style' => '',
			);

			if ( isset( $this->args['heading_color'] ) && '' !== $this->args['heading_color'] ) {
				$attr['style'] .= 'fill:' . $this->args['heading_color'] . ';';
			}

			return $attr;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function content_attr() {
			$attr = array(
				'class' => 'elegant-expanding-section-content-area',
				'style' => '',
			);

			$attr['style'] .= 'display: none;';

			if ( isset( $this->args['background_color_content'] ) && '' !== $this->args['background_color_content'] ) {
				$attr['style'] .= 'background: ' . $this->args['background_color_content'] . ';';
			}

			return $attr;
		}

		/**
		 * Generates and returns the gradient color for heading.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function get_gradient_color() {
			$gradient_color_1   = $this->args['background_color_1'];
			$gradient_color_2   = $this->args['background_color_2'];
			$gradient_direction = $this->args['gradient_direction'];

			if ( 'vertical' == $gradient_direction ) {
				$gradient_direction = 'top';
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(' . $gradient_color_1 . '), to(' . $gradient_color_2 . '));';
			} else {
				// Safari 4-5, Chrome 1-9 support.
				$gradient = 'background: -webkit-gradient(linear, left top, right top, from(' . $gradient_color_1 . '), to(' . $gradient_color_2 . '));';
			}

			// Safari 5.1, Chrome 10+ support.
			$gradient .= 'background: -webkit-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// Firefox 3.6+ support.
			$gradient .= 'background: -moz-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// IE 10+ support.
			$gradient .= 'background: -ms-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			// Opera 11.10+ support.
			$gradient .= 'background: -o-linear-gradient(' . $gradient_direction . ', ' . $gradient_color_1 . ', ' . $gradient_color_2 . ');';

			return $gradient;
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
				'infi-elegant-expanding-sections',
				$elegant_js_folder_url . '/infi-elegant-expanding-sections.min.js',
				$elegant_js_folder_path . '/infi-elegant-expanding-sections.min.js',
				array( 'jquery' ),
				'1',
				true
			);
		}
	}

	new IEE_Expanding_Sections();
} // End if().


/**
 * Map shortcode for expanding_sections.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_expanding_sections() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Expanding Sections', 'elegant-elements' ),
			'shortcode'                 => 'iee_expanding_sections',
			'icon'                      => 'icon-expanding-sections',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-expanding-sections-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-expanding-sections-preview-template',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-expanding-sections-preview.php',
			'allow_generator'           => true,
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'params'                    => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Section Title', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the title for this section.', 'elegant-elements' ),
					'param_name'  => 'title',
					'value'       => esc_attr__( 'Section title goes here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_attr__( 'Section Description', 'elegant-elements' ),
					'description' => esc_attr__( 'Enter the description for this section.', 'elegant-elements' ),
					'param_name'  => 'description',
					'value'       => esc_attr__( 'Section description goes here', 'elegant-elements' ),
					'placeholder' => true,
				),
				array(
					'type'        => 'tinymce',
					'heading'     => esc_attr__( 'Section Content', 'elegant-elements' ),
					'description' => esc_attr__( 'Provide the content for this section.', 'elegant-elements' ),
					'param_name'  => 'element_content',
					'value'       => esc_attr__( 'Section content goes here', 'elegant-elements' ),
					'placeholder' => true,
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
					'heading'     => esc_attr__( 'Title Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the title text.', 'elegant-elements' ),
					'param_name'  => 'typography_title',
					'value'       => '',
					'default'     => $default_typography['title'],
					'group'       => 'Typography',
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
					'description' => esc_attr__( 'Select the font size for title text. ( In Pixel. )', 'elegant-elements' ),
					'param_name'  => 'title_font_size',
					'value'       => '28',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'elegant_typography',
					'heading'     => esc_attr__( 'Description Text Typography', 'elegant-elements' ),
					'description' => esc_attr__( 'Select typography for the description text.', 'elegant-elements' ),
					'param_name'  => 'typography_description',
					'value'       => '',
					'default'     => $default_typography['description'],
					'group'       => 'Typography',
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
					'heading'     => esc_attr__( 'Description Font Size', 'elegant-elements' ),
					'description' => esc_attr__( 'Select the font size for description text. In Pixel (px).', 'elegant-elements' ),
					'param_name'  => 'description_font_size',
					'value'       => '18',
					'min'         => '12',
					'max'         => '100',
					'step'        => '1',
					'group'       => 'Typography',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Heading Area Text Color', 'elegant-elements' ),
					'param_name'  => 'heading_color',
					'value'       => '',
					'description' => esc_attr__( 'Controls the text color for section title, description and the icon.', 'elegant-elements' ),
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Section Background Color 1', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the single background color for this section. If you want gradient color, please choose the second background below.', 'elegant-elements' ),
					'param_name'  => 'background_color_1',
					'value'       => '',
					'group'       => 'Design',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Section Background Color 2', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the second background color for this section to make gradient background.', 'elegant-elements' ),
					'param_name'  => 'background_color_2',
					'value'       => '',
					'group'       => 'Design',
				),
				array(
					'type'        => 'select',
					'heading'     => esc_attr__( 'Gradient Direction', 'elegant-elements' ),
					'param_name'  => 'gradient_direction',
					'default'     => '0deg',
					'value'       => array(
						'vertical' => esc_attr__( 'Vertical From Top to Bottom', 'elegant-elements' ),
						'0deg'     => esc_attr__( 'Gradient From Left to Right', 'elegant-elements' ),
						'45deg'    => esc_attr__( 'Gradient From Bottom - Left Angle', 'elegant-elements' ),
						'-45deg'   => esc_attr__( 'Gradient From Top - Left Angle', 'elegant-elements' ),
					),
					'description' => esc_attr__( 'Controls the gradient color direction for this section.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_attr__( 'Content Background Color', 'elegant-elements' ),
					'description' => esc_attr__( 'Choose the background color for this section content area background.', 'elegant-elements' ),
					'param_name'  => 'background_color_content',
					'value'       => '',
					'group'       => 'Design',
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_expanding_sections', 99 );
