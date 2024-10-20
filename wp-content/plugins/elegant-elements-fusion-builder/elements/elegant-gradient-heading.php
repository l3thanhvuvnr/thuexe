<?php
if ( fusion_is_element_enabled( 'iee_gradient_heading' ) && ! class_exists( 'IEE_Gradient_Heading' ) ) {
	/**
	 * Element class.
	 *
	 * @package elegant-elements
	 * @since 1.1.0
	 */
	class IEE_Gradient_Heading extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.1.0
		 * @var array
		 */
		protected $args;

		/**
		 * Constructor.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			add_filter( 'fusion_attr_elegant-gradient-heading-wrapper', array( $this, 'wrapper_attr' ) );
			add_filter( 'fusion_attr_elegant-gradient-heading', array( $this, 'heading_attr' ) );

			add_shortcode( 'iee_gradient_heading', array( $this, 'render' ) );
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

			if ( isset( $args['element_typography'] ) && 'default' === $args['element_typography'] ) {
				$args['typography_heading'] = $default_typography['title'];
			}

			if ( ! isset( $args['background_color_1'] ) ) {
				$args['background_color_1'] = '';
			}

			if ( ! isset( $args['background_color_2'] ) ) {
				$args['background_color_2'] = '';
			}

			$this->args = $args;

			$html = '';

			if ( '' !== locate_template( 'templates/gradient-heading/elegant-gradient-heading.php' ) ) {
				include locate_template( 'templates/gradient-heading/elegant-gradient-heading.php', false );
			} else {
				include ELEGANT_ELEMENTS_PLUGIN_DIR . 'templates/gradient-heading/elegant-gradient-heading.php';
			}

			return $html;
		}

		/**
		 * Builds the attributes array.
		 *
		 * @access public
		 * @since 1.1.0
		 * @return array
		 */
		public function wrapper_attr() {
			$attr = array(
				'class' => 'elegant-gradient-heading-wrapper',
				'style' => '',
			);

			$attr['class'] .= ' elegant-align-' . $this->args['alignment'];

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
		 * @since 1.1.0
		 * @return array
		 */
		public function heading_attr() {
			global $fusion_settings;

			$attr = array(
				'class' => 'elegant-gradient-heading',
				'style' => '',
			);

			if ( isset( $this->args['typography_heading'] ) && '' !== $this->args['typography_heading'] ) {
				$typography         = $this->args['typography_heading'];
				$heading_typography = elegant_get_typography_css( $typography );

				$attr['style'] .= $heading_typography;
			}

			if ( isset( $this->args['background_color_1'] ) && '' !== $this->args['background_color_1'] ) {
				$attr['style'] .= 'background: ' . $this->get_gradient_color() . ';';
				$attr['style'] .= 'color:' . $this->args['background_color_1'] . ';';
			}

			if ( '' !== $this->args['background_color_1'] && '' !== $this->args['background_color_2'] ) {
				$attr['style'] .= '-webkit-background-clip: text;';
				$attr['style'] .= '-webkit-text-fill-color: transparent;';
			}

			if ( isset( $this->args['heading_font_size'] ) && '' !== $this->args['heading_font_size'] ) {
				$attr['style'] .= 'font-size:' . FusionBuilder::validate_shortcode_attr_value( $this->args['heading_font_size'], 'px' ) . ';';
				$attr['style'] .= 'line-height:1.2em;';
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

			$gradient .= '-webkit-box-decoration-break: clone; display: inline;';

			return $gradient;
		}
	}

	new IEE_Gradient_Heading();
} // End if().


/**
 * Map shortcode for gradient_heading.
 *
 * @since 1.1.0
 * @return void
 */
function map_elegant_elements_gradient_heading() {
	global $fusion_settings;

	// Get default typography for title and description.
	$default_typography = elegant_get_default_typography();

	fusion_builder_map(
		array(
			'name'                      => esc_attr__( 'Elegant Gradient Heading', 'elegant-elements' ),
			'shortcode'                 => 'iee_gradient_heading',
			'icon'                      => 'icon-heading',
			'preview'                   => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/previews/infi-gradient-heading-preview.php',
			'preview_id'                => 'elegant-elements-module-infi-gradient-heading-preview-template',
			'front-end'                 => ELEGANT_ELEMENTS_PLUGIN_DIR . 'elements/front-end/templates/infi-gradient-heading-preview.php',
			'custom_settings_view_name' => 'ModuleSettingElegantView',
			'inline_editor'             => true,
			'inline_editor_shortcodes'  => true,
			'params'                    => array(
				array(
					'type'        => 'textarea',
					'heading'     => esc_attr__( 'Heading text', 'elegant-elements' ),
					'param_name'  => 'heading',
					'value'       => '',
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
					'max'         => '200',
					'step'        => '1',
					'group'       => esc_attr__( 'Typography', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Heading Gradient Color 1', 'elegant-elements' ),
					'param_name'  => 'background_color_1',
					'value'       => '',
					'description' => esc_attr__( 'Select the first color for the heading background gradient.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
				),
				array(
					'type'        => 'colorpickeralpha',
					'heading'     => esc_attr__( 'Heading Gradient Color 2', 'elegant-elements' ),
					'param_name'  => 'background_color_2',
					'value'       => '',
					'description' => esc_attr__( 'Select the second color for the heading background gradient.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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
					'description' => esc_attr__( 'Controls the gradient color direction for this title.', 'elegant-elements' ),
					'group'       => esc_attr__( 'Design', 'elegant-elements' ),
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

add_action( 'fusion_builder_before_init', 'map_elegant_elements_gradient_heading', 99 );
